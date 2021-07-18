<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeTxReservation;

class OrderAPIController extends Controller
{
    public function get()
    {
        try {
            $currentYear = date('Y');
            $limit      = request('limit') ? request('limit') : 100;
            $search     = request('search') ? request('search') : '';
            $location   = request('location') ? request('location') : '';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $page       = request('page') ? request('page') : 1;
            $pagination = request('pagination') ? request('pagination') : 12;

            $orderBy    = request('order_by') ? request('order_by') : 'row_last_modified';
            $sort       = request('sort') ? request('sort') : 'desc';

            $ORDER = FeTxReservation::leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_id', '=', 'fe_tx_reservation.reservation_id')
                                    ->leftJoin('cms_tm_room as r', 'r.room_id', '=', 'rd.room_id')
                                    ->leftJoin('cms_tm_room_unit as un', 'rd.room_unit_id', '=', 'un.room_unit_id')
                                    ->leftJoin('cms_tm_place as p', 'p.place_id', '=', 'r.place_id')
                                    ->leftJoin('cms_tm_voucher as v', 'v.voucher_no', '=', 'fe_tx_reservation.voucher_id')
                                    ->leftJoin('fe_tx_cart as c', 'c.cart_id', '=', 'fe_tx_reservation.cart_id')
                                    ->leftJoin('fe_tm_user as u', 'u.user_id', '=', 'fe_tx_reservation.user_id')
                                    ->leftJoin('sys_type as t', 't.type_id', '=', 'fe_tx_reservation._status')
                                    ->leftJoin('sys_type as tp', 'tp.type_id', '=', 'fe_tx_reservation.payment_method_id')
                                    ->leftJoin('fe_tm_reservation_schedule as rs', 'rd.reservation_schedule_id', '=', 'rs.reservation_schedule_id')
                                    ->leftJoin('fe_tm_payment_channel as pc', 'pc.payment_channel_id', '=', 'fe_tx_reservation.payment_channel_id')
                                    ->leftJoin('cms_tm_asset as a', 'a.asset_id', '=', 'pc.payment_channel_logo_id')
                                    ->leftJoin('tm_province as pv', 'pv.id', '=', 'p.province_id')
                                    ->where(function($query) use ($search){
                                        $query->where('fe_tx_reservation.order_no', 'like', '%'.$search.'%')
                                            ->orWhere('u._fullname', 'like', '%'.$search.'%')
                                            ->orWhere('u.email', 'like', '%'.$search.'%');
                                    })
                                    ->where('r.place_id', $location)
                                    ->whereBetween('fe_tx_reservation.row_last_modified', [$startDate, $endDate])
                                    ->select('fe_tx_reservation.*', 'fe_tx_reservation.create_date as order_created',
                                            'c.cart_no',
                                            't._name as reservation_status', 'tp._name as payment_method',
                                            'p._name as place_name', 'r.place_id',
                                            'r.room_id', 'r._name as room_name',
                                            'un._name as unit_name',
                                            'rd._qty as qty',
                                            'pv.name as province',
                                            'rs._name as duration_name', 'rs.duration as duration',
                                            'v._name as voucher_name', 'v._code as voucher_code', 'v._operator as voucher_operator', 'v._value as voucher_value',
                                            // 'rd._current_price', // 'rd._current_sale_status', 'rd._current_sale_price', 'rd._fixed_price',
                                            'u.email', 'u._fullname', 'u._dob', 'u.gender_id', 'u._mobile_phone',
                                            'pc._name as payment_channel_name', 'a._url as payment_channel_logo'
                                            // 'p._name as place', 'p._address as place_address'
                                            )
                                    ->orderBy('fe_tx_reservation.'.$orderBy, $sort)
                                    ->paginate($pagination);

            $orders = $ORDER->makeHidden([
                'payment_gateway_trx_ref_no',
                'payment_gateway_trx_bank_account_no',
                'payment_gateway_trx_token',
                'payment_gateway_callback_status',
                'payment_gateway_callback_raw',
                'payment_gateway_callback_date',
            ]);

            $ORDER->data = $orders;

            $data = [
                'orders'    => $ORDER,
                's'         => $startDate,
                'e'         => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switch($reservationId, $status)
    {
        try {
            DB::beginTransaction();

            $ORDER = FeTxReservation::find($reservationId);
            if ($status == false || $status == 'false') {
                $ORDER->_active = '0';
            } else {
                $ORDER->_active = '1';
            }
            $ORDER->save();
            DB::commit();

            $ORDER = CmsTmRoom::leftJoin('cms_tm_place as p', 'p.place_id', '=', 'cms_tm_room.place_id')
                                ->where('room_id', $reservationId)
                                ->select('cms_tm_room.*', 'p._name as location')
                                ->first();
            return response()->json($ORDER);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
