<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SysTmActivity;
use App\Models\SysTmActivityLog;

class HistoryAPIController extends Controller
{
    public function get()
    {
        try {
            $currentYear = date('Y');
            $limit      = request('limit') ? request('limit') : 100;
            $search     = request('search') ? request('search') : '';
            // $location   = request('location') ? request('location') : '';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $page       = request('page') ? request('page') : 1;
            $pagination = request('pagination') ? request('pagination') : 100;

            $orderBy    = request('order_by') ? request('order_by') : 'create_date';
            $sort       = request('sort') ? request('sort') : 'desc';

            $HISTORY = SysTmActivityLog::leftJoin('sys_tm_permission as per', 'per.permission_id', '=', 'sys_tm_activity_log.permission_id')
                                    ->leftJoin('cms_tm_user as u', 'u.cms_user_id', '=', 'sys_tm_activity_log.cms_user_id')
                                    ->leftJoin('sys_tm_activity as a', 'a.activity_id', '=', 'sys_tm_activity_log.activity_id')
                                    ->leftJoin('cms_tm_place as p', 'p.place_id', '=', 'u.place_id')
                                    ->leftJoin('sys_tm_role as r', 'r.role_id', '=', 'u.user_role_id')
                                    ->where(function($query) use ($search){
                                        $query->where('u._full_name', 'like', '%'.$search.'%')
                                            ->orWhere('u._email', 'like', '%'.$search.'%')
                                            ->orWhere('sys_tm_activity_log._ip', 'like', '%'.$search.'%')
                                            ->orWhere('sys_tm_activity_log.referred_id', 'like', '%'.$search.'%')
                                            ->orWhere('a._name', 'like', '%'.$search.'%');
                                    })
                                    ->whereBetween('sys_tm_activity_log.create_date', [$startDate, $endDate])
                                    ->select('sys_tm_activity_log.*',
                                            'a._name as activity_name',
                                            'u._email', 'u._full_name', 'u.user_role_id', 'u.place_id',
                                            'p._name as place_name',
                                            'r._name as role_name',
                                            )
                                    ->orderBy('sys_tm_activity_log.'.$orderBy, $sort)
                                    ->paginate($pagination);

            $data = [
                'histories' => $HISTORY,
                's'         => $startDate,
                'e'         => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
