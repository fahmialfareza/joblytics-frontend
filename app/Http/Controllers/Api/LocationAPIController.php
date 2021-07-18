<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmPlace;
use App\Models\CmsTmRoom;
use App\Models\SysTmActivityLog;

class LocationAPIController extends Controller
{
    public function get()
    {
        try {
            $currentYear = date('Y');
            $limit      = request('limit') ? request('limit') : 100;
            $search     = request('search') ? request('search') : '';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $page       = request('page') ? request('page') : 1;
            $pagination = request('pagination') ? request('pagination') : 12;

            $orderBy    = request('order_by') ? request('order_by') : 'last_update';
            $sort       = request('sort') ? request('sort') : 'desc';

            $PLACE = CmsTmPlace::leftJoin('tm_province as p', 'p.id', '=', 'province_id')
                                ->where(function($query) use ($search){
                                    $query->where('_name', 'like', '%'.$search.'%')
                                        ->orWhere('p.name', 'like', '%'.$search.'%');
                                })
                                ->whereBetween('last_update', [$startDate, $endDate])
                                ->select('cms_tm_place.*',
                                        'p.name as province')
                                ->orderBy('cms_tm_place.'.$orderBy, $sort)
                                ->paginate($pagination);
            $data = [
                'places'    => $PLACE,
                's'         => $startDate,
                'e'         => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switch($placeId, $status)
    {
        try {
            DB::beginTransaction();
            $CHECK_ROOM = CmsTmRoom::where('place_id', $placeId)->get();

            if (sizeof($CHECK_ROOM) == 0) {
                return response()->json(null);
            } else {
                $PLACE = CmsTmPlace::find($placeId);
                if ($status == false || $status == 'false') {
                    $PLACE->_active = '0';
                    $DEACTIVATE_ROOM = CmsTmRoom::where('place_id', $placeId)->update(['_active' => '0']);
                    $this->logbook($placeId, 16, $PLACE->_name);
                } else {
                    $PLACE->_active = '1';
                    $this->logbook($placeId, 15, $PLACE->_name);
                }
                $PLACE->save();
                DB::commit();

                $RETURNPLACE = CmsTmPlace::leftJoin('tm_province as p', 'p.id', '=', 'province_id')
                                        ->where('place_id', $placeId)
                                        ->select('*', 'p.name as province')
                                        ->first();
                return response()->json($RETURNPLACE);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function logbook($ref_id, $act_id, $ref_name)
    {
        try {
            DB::beginTransaction();

            $ip             = $_SERVER['REMOTE_ADDR'];
            $userId         = request('user_id');
            $permissionId   = 3;

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
