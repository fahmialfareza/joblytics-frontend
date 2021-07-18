<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SysTmNotification;
use App\Models\SysTmNotificationSent;
use App\Models\CmsTmVoucher;

class NotificationAPIController extends Controller
{
    public function get()
    {
        try {
            $currentYear = date('Y');
            $isTemplate = request('template') == 'true' ? '1' : '0';
            $search     = request('search') ? request('search') : '';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $orderBy    = request('order_by') ? request('order_by') : 'create_date';
            $sort       = request('sort') ? request('sort') : 'desc';

            $NOTIFICATION = SysTmNotification::leftJoin('sys_type as t', 't.type_id', '=', 'notification_type_id')
                                    ->leftJoin('sys_type as t2', 't2.type_id', '=', 'sent_event_id')
                                    ->leftJoin('cms_tm_user as u', 'u.cms_user_id', '=', '_sent_by')
                                    ->leftJoin('sys_tm_role as r', 'r.role_id', '=', 'u.user_role_id')
                                    ->where(function($query) use ($search){
                                        $query->where('sys_tm_notification._title', 'like', '%'.$search.'%')
                                            ->orWhere('sys_tm_notification._content', 'like', '%'.$search.'%')
                                            ->orWhere('t._name', 'like', '%'.$search.'%')
                                            ->orWhere('u._full_name', 'like', '%'.$search.'%');
                                    })
                                    ->whereBetween('sys_tm_notification.create_date', [$startDate, $endDate])
                                    ->where('_is_template', $isTemplate)
                                    ->select('sys_tm_notification.*', 
                                            'u._full_name as sender_name', 'u._email as sender_mail', 'r._name as sender_role',
                                            't._name as type_name', 't._desc as type_desc',
                                            't2._name as sent_name', 't2._desc as sent_desc')
                                    ->orderBy('sys_tm_notification.'.$orderBy, $sort)
                                    ->get();

            $temp_notifications = [];
            foreach ($NOTIFICATION as $n) {
                $VOUCHER            = CmsTmVoucher::find($n->voucher_id);
                $NOTIFICATION_SENT  = SysTmNotificationSent::where('notification_id', $n->notification_id)
                                                            ->select(DB::raw('count(notification_sent_id) as total_sent'))
                                                            ->first();
                $n['voucher']           = $VOUCHER;
                $n['notification_sent'] = $NOTIFICATION_SENT;
                array_push($temp_notifications, $n);
            }

            $data = [
                'notifications' => $temp_notifications,
                's'             => $startDate,
                'e'             => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
