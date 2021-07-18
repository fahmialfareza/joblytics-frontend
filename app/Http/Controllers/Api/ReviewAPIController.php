<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeTxReservationRating;

class ReviewAPIController extends Controller
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

            $orderBy    = request('order_by') ? request('order_by') : 'row_last_modified';
            $sort       = request('sort') ? request('sort') : 'desc';

            $REVIEW = FeTxReservationRating::leftJoin('fe_tx_reservation as r', 'r.reservation_id', '=', 'fe_tx_reservation_rating.reservation_id')
                                    ->leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_id', '=', 'r.reservation_id')                        
                                    ->leftJoin('cms_tm_place as p', 'p.place_id', '=', 'fe_tx_reservation_rating.place_id')                        
                                    ->leftJoin('cms_tm_room as rm', 'rm.room_id', '=', 'fe_tx_reservation_rating.room_id')
                                    ->leftJoin('cms_tm_room_unit as ru', 'ru.room_unit_id', '=', 'rd.room_unit_id')
                                    ->leftJoin('cms_tm_voucher as v', 'v.voucher_no', '=', 'r.voucher_id')
                                    ->leftJoin('fe_tm_user as u', 'u.user_id', '=', 'fe_tx_reservation_rating.user_id')
                                    ->leftJoin('sys_type as t', 't.type_id', '=', 'r._status')
                                    ->leftJoin('sys_type as tp', 'tp.type_id', '=', 'r.payment_method_id')
                                    ->where(function($query) use ($search){
                                        $query->where('fe_tx_reservation_rating._review', 'like', '%'.$search.'%')
                                            ->orWhere('fe_tx_reservation_rating._rate', 'like', '%'.$search.'%')
                                            ->orWhere('r.order_no', 'like', '%'.$search.'%')
                                            ->orWhere('u._fullname', 'like', '%'.$search.'%')
                                            ->orWhere('u.email', 'like', '%'.$search.'%');
                                    })
                                    ->whereBetween('fe_tx_reservation_rating.create_date', [$startDate, $endDate])
                                    ->select('fe_tx_reservation_rating.*',
                                            'r.*',
                                            't._name as reservation_status', 'tp._name as payment_method',
                                            'p._name as place_name',
                                            'rm.room_id', 'rm._name as room_name',
                                            'ru._name as unit_name',
                                            'rd._qty as qty', 
                                            'v._name as voucher_name', 'v._code as voucher_code', 'v._operator as voucher_operator', 'v._value as voucher_value',
                                            'u.email', 'u._fullname', 'u._dob', 'u.gender_id', 'u._mobile_phone'
                                            )
                                    ->orderBy('fe_tx_reservation_rating.'.$orderBy, $sort)
                                    ->paginate($pagination);

            $reviews = $REVIEW->makeHidden([
                'payment_gateway_trx_ref_no',
                'payment_gateway_trx_bank_account_no',
                'payment_gateway_trx_token',
                'payment_gateway_callback_status',
                'payment_gateway_callback_raw',
                'payment_gateway_callback_date',
            ]);

            $REVIEW->data = $reviews;

            $data = [
                'reviews'    => $REVIEW,
                's'         => $startDate,
                'e'         => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function print()
    {
        $currentYear    = date('Y');
        $limit          = request('limit') ? request('limit') : 100;
        $location       = request('location') ? request('location') : '';
        $startDate      = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
        $endDate        = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

        $orderBy        = request('order_by') ? request('order_by') : 'row_last_modified';
        $sort           = request('sort') ? request('sort') : 'desc';

        $REVIEW = FeTxReservationRating::leftJoin('fe_tx_reservation as r', 'r.reservation_id', '=', 'fe_tx_reservation_rating.reservation_id')
                                ->leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_id', '=', 'r.reservation_id')                        
                                ->leftJoin('cms_tm_place as p', 'p.place_id', '=', 'fe_tx_reservation_rating.place_id')                        
                                ->leftJoin('cms_tm_room as rm', 'rm.room_id', '=', 'fe_tx_reservation_rating.room_id')
                                ->leftJoin('cms_tm_voucher as v', 'v.voucher_no', '=', 'r.voucher_id')
                                ->leftJoin('fe_tm_user as u', 'u.user_id', '=', 'fe_tx_reservation_rating.user_id')
                                ->leftJoin('sys_type as t', 't.type_id', '=', 'r._status')
                                ->leftJoin('sys_type as tp', 'tp.type_id', '=', 'r.payment_method_id')
                                // ->where(function($query) use ($search){
                                //     $query->where('fe_tx_reservation_rating._review', 'like', '%'.$search.'%')
                                //         ->orWhere('fe_tx_reservation_rating._rate', 'like', '%'.$search.'%')
                                //         ->orWhere('r.order_no', 'like', '%'.$search.'%')
                                //         ->orWhere('u._fullname', 'like', '%'.$search.'%')
                                //         ->orWhere('u.email', 'like', '%'.$search.'%');
                                // })
                                ->whereBetween('fe_tx_reservation_rating.create_date', [$startDate, $endDate])
                                ->select('fe_tx_reservation_rating.*',
                                        'r.*',
                                        't._name as reservation_status', 'tp._name as payment_method',
                                        'p._name as place_name',
                                        'rm.room_id', 'rm._name as room_name',
                                        'rd._qty as qty', 
                                        'v._name as voucher_name', 'v._code as voucher_code', 'v._operator as voucher_operator', 'v._value as voucher_value',
                                        'u.email', 'u._fullname', 'u._dob', 'u.gender_id', 'u._mobile_phone'
                                        )
                                ->orderBy('fe_tx_reservation_rating.'.$orderBy, $sort)
                                ->take($limit)
                                ->get();
                                // ->paginate($pagination);

        $reviews = $REVIEW->makeHidden([
            'payment_gateway_trx_ref_no',
            'payment_gateway_trx_bank_account_no',
            'payment_gateway_trx_token',
            'payment_gateway_callback_status',
            'payment_gateway_callback_raw',
            'payment_gateway_callback_date',
        ]);

        $data = [
            'reviews'   => $REVIEW,
            's'         => $startDate,
            'e'         => $endDate,
        ];
        return response()->json($data);
    }
}
