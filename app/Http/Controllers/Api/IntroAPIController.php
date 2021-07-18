<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmFeIntro;
use App\Models\SysTmActivityLog;

class IntroAPIController extends Controller
{
    public function get()
    {
        try {
            $currentYear = date('Y');

            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-01-28 00:00:00': request('end_date').' 23:59:59';

            $INTRO = CmsTmFeIntro::whereBetween('last_update', [$startDate, $endDate])
                                    ->orderBy('_position', 'asc')
                                    ->get();

            $data = [
                'intros' => $INTRO
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switch($introId, $status)
    {
        try {
            DB::beginTransaction();
            $INTRO = CmsTmFeIntro::find($introId);
            if ($status == false || $status == 'false') {
                $INTRO->_active = '0';
                $this->logbook($introId, 24, $INTRO->_title);
            } else {
                $ACTIVE_COUNT = CmsTmFeIntro::where('_active', '1')->get();
                if (sizeof($ACTIVE_COUNT) >= 5) {
                    return response()->json(null);
                } else {
                    $INTRO->_active = '1';
                    $this->logbook($introId, 23, $INTRO->_title);
                }
            }
            $INTRO->save();
            DB::commit();

            return response()->json($INTRO);
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json($th->getMessage());

        }
    }

    private function logbook($ref_id, $act_id, $ref_name)
    {
        try {
            DB::beginTransaction();

            $ip             = $_SERVER['REMOTE_ADDR'];
            $userId         = request('user_id');
            $permissionId   = 5;

            $LOG = new SysTmActivityLog();
            $LOG->_ip = $ip;
            $LOG->permission_id = $permissionId;
            $LOG->cms_user_id = $userId;
            $LOG->activity_id = $act_id;
            $LOG->referred_id = $ref_id;
            $LOG->referred_name = $ref_name;
            $LOG->create_date = date('Y-m-d H:m:s');
            $LOG->save();

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
        }
    }
}
