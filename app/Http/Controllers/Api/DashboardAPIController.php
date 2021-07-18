<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmPlace;
use App\Models\CmsTmRoom;
use App\Models\CmsTmRoomUnit;
use App\Models\FeTxReservation;
use App\Models\FeTxReservationRating;

class DashboardAPIController extends Controller
{
    public function statistik()
    {
        try {
            $currentYear = date('Y');
            $location   = request('location') ? request('location') : '%%';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $orderBy    = request('order_by') ? request('order_by') : 'last_update';
            $sort       = request('sort') ? request('sort') : 'desc';

            $HOTEL_ON   = CmsTmPlace::where('_active', '=', '1')
                                    ->select(DB::raw("count(place_id) as total_hotel"))
                                    ->first();

            $HOTEL_OFF  = CmsTmPlace::where('_active', '=', '0')
                                    ->select(DB::raw("count(place_id) as total_hotel"))
                                    ->first();

            if ($location != '%%') {
                $TOTAL_UNIT = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', '=', 'cms_tm_room_unit.room_id')
                                        ->where('r.place_id', $location)
                                        ->select(DB::raw('count(cms_tm_room_unit.room_unit_id) as total_unit'))
                                        ->pluck('total_unit');
            } else {
                $TOTAL_UNIT = 0;
            }

            $REVIEW     = FeTxReservationRating::whereBetween('create_date', [$startDate, $endDate])
                                                ->where('place_id', 'like', $location)
                                                ->select(DB::raw("count(reservation_rating_id) as total_review"))
                                                ->first();
            
            $BOOKING    = FeTxReservation::leftJoin('sys_type as t', 't.type_id', '=', 'fe_tx_reservation._status')
                                ->leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_id', '=', 'fe_tx_reservation.reservation_id')
                                ->leftJoin('cms_tm_room as r', 'r.room_id', '=', 'rd.room_id')
                                ->where(function($query){
                                    $query->where('fe_tx_reservation._status', '=', 'PAYMENTSTATUS03');
                                        // ->orWhere('fe_tx_reservation._status', '=', 'PAYMENTSTATUS03');
                                })
                                ->whereBetween('fe_tx_reservation.row_last_modified', [$startDate, $endDate])
                                ->where('fe_tx_reservation._active', '1')
                                ->where('r.place_id', 'like', $location)
                                ->select(DB::raw("count(fe_tx_reservation.order_no) as total_booking"))
                                ->first();

            $REVENUE    = FeTxReservation::leftJoin('sys_type as t', 't.type_id', '=', 'fe_tx_reservation._status')
                                ->leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_id', '=', 'fe_tx_reservation.reservation_id')
                                ->leftJoin('cms_tm_room as r', 'r.room_id', '=', 'rd.room_id')
                                ->where(function($query){
                                    $query->where('fe_tx_reservation._status', '=', 'PAYMENTSTATUS03');
                                        // ->orWhere('fe_tx_reservation._status', '=', 'PAYMENTSTATUS03');
                                })
                                ->whereBetween('fe_tx_reservation.row_last_modified', [$startDate, $endDate])
                                ->where('fe_tx_reservation._active', '1')
                                ->where('r.place_id', 'like', $location)
                                ->select(DB::raw('SUM(_grand_total) as total_revenue'))
                                ->first();
            $data = [
                'total_unit'       => $TOTAL_UNIT,
                'total_hotel_on'   => $HOTEL_ON->total_hotel,
                'total_hotel_off'  => $HOTEL_OFF->total_hotel,
                'total_booking' => $BOOKING->total_booking,
                'total_review'  => $REVIEW->total_review,
                'total_revenue' => "Rp " . number_format($REVENUE->total_revenue,2,',','.'),
                's'     => $startDate,
                'e'     => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function booking()
    {
        try {
            $currentYear = date('Y');
            $location   = request('location') ? request('location') : '%%';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $BOOKING    = FeTxReservation::leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_id', '=', 'fe_tx_reservation.reservation_id')
                                ->leftJoin('cms_tm_room as r', 'r.room_id', '=', 'rd.room_id')
                                ->leftJoin('cms_tm_place as p', 'p.place_id', '=', 'r.place_id')
                                ->where(function($query){
                                    $query->where('fe_tx_reservation._status', '=', 'PAYMENTSTATUS03');
                                        // ->orWhere('fe_tx_reservation._status', '=', 'PAYMENTSTATUS03');
                                })
                                ->whereBetween('fe_tx_reservation.row_last_modified', [$startDate, $endDate])
                                ->where('fe_tx_reservation._active', '1')
                                ->where('r.place_id', 'like', $location)
                                ->select('p._name as placename', DB::raw("count(fe_tx_reservation.order_no) as total_booking"))
                                ->groupBy('p.place_id')
                                ->orderBy('total_booking', 'desc')
                                ->get();

            $data = [
                'bookings'   => $BOOKING,
                's'     => $startDate,
                'e'     => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function booked()
    {
        try {
            $currentYear = date('Y');
            $limit      = request('limit') ? request('limit') : 25;
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $BOOKINGS    = FeTxReservation::leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_id', '=', 'fe_tx_reservation.reservation_id')
                                ->leftJoin('sys_type as t', 't.type_id', '=', 'fe_tx_reservation._status')
                                ->leftJoin('cms_tm_room as r', 'r.room_id', '=', 'rd.room_id')
                                ->leftJoin('cms_tm_place as p', 'p.place_id', '=', 'r.place_id')
                                ->whereBetween('fe_tx_reservation.create_date', [$startDate, $endDate])
                                ->where('fe_tx_reservation._active', '1')
                                ->select('fe_tx_reservation.order_no', 
                                        'fe_tx_reservation.create_date as booking_date',
                                        'p._name as placename',
                                        'r._name as roomname',
                                        'fe_tx_reservation._grand_total as total_price',
                                        't._name as status')
                                ->orderBy('fe_tx_reservation.create_date', 'desc')
                                ->take($limit)
                                ->get();

            $data = [
                'bookings'   => $BOOKINGS,
                's'     => $startDate,
                'e'     => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function revenue()
    {
        try {
            $currentYear = date('Y');
            $location   = request('location') ? request('location') : '%%';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $REVENUE    = FeTxReservation::leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_detail_id', 'fe_tx_reservation.reservation_id')
                                ->leftJoin('cms_tm_room as rm', 'rm.room_id', 'rd.room_id')
                                ->whereBetween('fe_tx_reservation.create_date', [$startDate, $endDate])
                                ->where('fe_tx_reservation._proceed', '1')
                                ->where('fe_tx_reservation._status', 'PAYMENTSTATUS03')
                                ->where('rm.place_id', 'like', $location)
                                ->select(DB::raw('sum(fe_tx_reservation._grand_total) as sum'), 
                                        DB::raw('DATE(fe_tx_reservation.create_date) as date'))
                                ->groupBy('date')
                                ->get();

            $data = [
                'location'  => $location,
                'revenues'  => $REVENUE,
                's'         => $startDate,
                'e'         => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function room_sold()
    {
        try {
            $currentYear = date('Y');
            $location   = request('location') ? request('location') : '%%';
            $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $ROOM_SOLD  = FeTxReservation::leftJoin('fe_tx_reservation_detail as rd', 'rd.reservation_detail_id', 'fe_tx_reservation.reservation_id')
                            ->leftJoin('cms_tm_room as rm', 'rm.room_id', 'rd.room_id')
                            ->whereBetween('fe_tx_reservation.create_date', [$startDate, $endDate])
                            ->where('fe_tx_reservation._proceed', '1')
                            ->where('fe_tx_reservation._status', 'PAYMENTSTATUS03')
                            ->where('rm.place_id', 'like', $location)
                            ->select(DB::raw('count(fe_tx_reservation.reservation_id) as total_sold'), DB::raw('DATE(fe_tx_reservation.create_date) as date'))
                            ->groupBy('date')
                            ->get();

            $data = [
                'room_sold' => $ROOM_SOLD,
                's'         => $startDate,
                'e'         => $endDate,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
