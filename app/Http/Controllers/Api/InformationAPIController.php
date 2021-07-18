<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmBlog;
use App\Models\SysTmActivityLog;

class InformationAPIController extends Controller
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

            $INFORMATION = CmsTmBlog::where('_title', 'like', '%'.$search.'%')
                                ->whereBetween('last_update', [$startDate, $endDate])
                                ->select('*')
                                ->orderBy(''.$orderBy, $sort)
                                ->paginate($pagination);
            $data = [
                'informations' => $INFORMATION,
                's'     => $startDate,
                'e'     => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switch($blogId, $status)
    {
        try {
            DB::beginTransaction();

            $INFORMATION = CmsTmBlog::find($blogId);
            if ($status == false || $status == 'false') {
                $INFORMATION->_active = '0';
                $this->logbook($blogId, 45, $INFORMATION->_title);
            } else {
                $INFORMATION->_active = '1';
                $this->logbook($blogId, 44, $INFORMATION->_title);
            }
            $INFORMATION->save();
            DB::commit();

            $RETURNINFORMATION = CmsTmBlog::where('blog_id', $blogId)
                                ->select('*')
                                ->first();
            return response()->json($RETURNINFORMATION);
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
            $permissionId   = 8;

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
