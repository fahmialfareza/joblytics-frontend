<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmRoomUnit;
use App\Models\FeTxReservationDetail;

use DateTime;
use DateInterval;

class UnitStatusAPIController extends Controller
{
    public function get()
    {
        try {
            $placeId = request('place_id') ? request('place_id') : '';

            $UNIT   = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('sys_type as t', 't.type_id', 'cms_tm_room_unit._status')
                                    ->leftJoin('sys_type as tem', 'tem.type_id', 'cms_tm_room_unit._embark_status')
                                    ->where('r.place_id', $placeId)
                                    ->whereNull('r.deleted_at')
                                    ->select('cms_tm_room_unit.*', 
                                            'r._name as room_name', 'r._max_capacity_a_day as room_max_capacity',
                                            't._name as status_name','r.place_id',
                                            'tem._name as status_embark')
                                    ->orderBy('room_unit_id', 'asc')
                                    ->get();

            $guestOrderDetail = [];

            foreach ($UNIT as $i => $unit) {
                $UNIT[$i]['status_initial'] = initial($unit->status_name);
                $UNIT[$i]['status_embark_initial'] = $unit->status_embark == '' ? '' : initial($unit->status_embark);

                $restrictedStatusName = ['VACANT CLEAN', 'VACANT DIRTY', 'OUT OF ORDER'];

                // if (!in_array($unit->status_name, $restrictedStatusName)) {
                if ($unit->status_embark || $unit->status_name == 'CHECKED IN') {
                    $GUEST_ORDER = FeTxReservationDetail::leftJoin('fe_tx_reservation as r', 'r.reservation_id', 'fe_tx_reservation_detail.reservation_id')
                                        ->leftJoin('fe_tm_reservation_schedule as rs', 'rs.reservation_schedule_id', 'fe_tx_reservation_detail.reservation_schedule_id')
                                        ->leftJoin('fe_tm_reservation_schedule_start as rss', 'rss.reservation_schedule_start_id', 'fe_tx_reservation_detail.reservation_schedule_start_id')
                                        ->leftJoin('sys_type as s', 's.type_id', 'rs.reservation_type_id')
                                        ->where('fe_tx_reservation_detail.room_unit_id', $unit->room_unit_id)
                                        ->select('r.reservation_id',
                                                'r.order_no', 
                                                'r._check_in_date as checkin', 'r._check_out_date as checkout', 
                                                'r._actual_check_in_date as actual_checkin', 'r._actual_check_out_date as actual_checkout', 
                                                'r._current_contact_name as guest_name', 
                                                'r._current_contact_email as guest_email',
                                                'r._current_phone as guest_phone',
                                                'r.user_id as guest_id',
                                                'rs._name as duration_name', 'rs.duration', 'rs.reservation_type_id', 
                                                's._name as duration_type',
                                                'fe_tx_reservation_detail.room_unit_id',
                                                'fe_tx_reservation_detail.reservation_schedule_start_id',
                                                'fe_tx_reservation_detail.reservation_schedule_id')
                                        ->latest('fe_tx_reservation_detail.reservation_id')
                                        ->first();
                    // Nights
                    if ($GUEST_ORDER->duration_type == 'DAILY') {
                        // If guest already checked In
                        if ($GUEST_ORDER->actual_checkin) {
                            $staySchedule = $this->dailyDateTimeParser($GUEST_ORDER->actual_checkin, $GUEST_ORDER->checkout);
                        }
                        // If guest not check in yet 
                        else {
                            $staySchedule = $this->dailyDateTimeParser($GUEST_ORDER->checkin, $GUEST_ORDER->checkout);
                        }
                        $GUEST_ORDER['stay_schedule'] = $staySchedule;
                    }
                    // Hours
                    else {
                        // If guest already checked In
                        if ($GUEST_ORDER->actual_checkin) {
                            $staySchedule = $this->hourlyDateTimeParser($GUEST_ORDER->actual_checkin, $GUEST_ORDER->duration, true);
                        }
                        // If guest not check in yet 
                        else {
                            $staySchedule = $this->hourlyDateTimeParser($GUEST_ORDER->checkin, $GUEST_ORDER->duration, false);
                        }
                        $GUEST_ORDER['stay_schedule'] = $staySchedule;
                    }

                    $guestOrderDetail[$unit->room_unit_id] = $GUEST_ORDER;
                }
            }

            $ROOM   = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->where('r.place_id', $placeId)
                                    ->whereNull('r.deleted_at')
                                    ->select('cms_tm_room_unit.room_id as room_id')
                                    ->groupBy('room_id')
                                    ->pluck('room_id');


            $data = [
                'units' => $UNIT,
                'rooms' => $ROOM,
                'guests' => $guestOrderDetail
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switch($unitId, $status)
    {
        try {
            DB::beginTransaction();
            $UNIT = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('sys_type as t', 't.type_id', 'cms_tm_room_unit._status')
                                    ->where('cms_tm_room_unit.room_unit_id', $unitId)
                                    ->select('cms_tm_room_unit.*', 'r.place_id',
                                            'r._name as room_name', 'r._max_capacity_a_day as room_max_capacity',
                                            't._name as status_name')
                                    ->first();

            $UNIT['status_initial'] = initial($UNIT->status_name);

            if ($status == false || $status == 'false') {
                $UNIT->_active = '0';
            } else {
                $UNIT->_active = '1';
            }
            $UNIT->save();
            DB::commit();

            return response()->json($UNIT);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchStatus($unitId, $statusId)
    {
        try {
            DB::beginTransaction();
            $manual = request('manual');

            if ($manual == 'true') {
                $UNIT = CmsTmRoomUnit::find($unitId);
                $UNIT->_status = $statusId;
                $UNIT->save();
            }

            $UNITDONE = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('sys_type as t', 't.type_id', 'cms_tm_room_unit._status')
                                    ->leftJoin('sys_type as tem', 'tem.type_id', 'cms_tm_room_unit._embark_status')
                                    ->where('cms_tm_room_unit.room_unit_id', $unitId)
                                    ->select('cms_tm_room_unit.*', 'r.place_id',
                                            'r._name as room_name', 'r._max_capacity_a_day as room_max_capacity',
                                            't._name as status_name',
                                            'tem._name as status_embark')
                                    ->first();

            $UNITDONE['status_initial'] = initial($UNITDONE->status_name);
            $UNITDONE['status_embark_initial'] = $UNITDONE->status_embark == '' ? '' : initial($UNITDONE->status_embark);
            
            $GUEST_ORDER = null;
            $restrictedStatusName = ['VACANT CLEAN', 'VACANT DIRTY', 'OUT OF ORDER'];
            
            // if (!in_array($UNITDONE->status_name, $restrictedStatusName)) {
            if ($UNITDONE->status_embark || $UNITDONE->status_name == 'CHECKED IN') {
                $GUEST_ORDER = FeTxReservationDetail::leftJoin('fe_tx_reservation as r', 'r.reservation_id', 'fe_tx_reservation_detail.reservation_id')
                                    ->leftJoin('fe_tm_reservation_schedule as rs', 'rs.reservation_schedule_id', 'fe_tx_reservation_detail.reservation_schedule_id')
                                    ->leftJoin('fe_tm_reservation_schedule_start as rss', 'rss.reservation_schedule_start_id', 'fe_tx_reservation_detail.reservation_schedule_start_id')
                                    ->leftJoin('sys_type as s', 's.type_id', 'rs.reservation_type_id')
                                    ->where('fe_tx_reservation_detail.room_unit_id', $UNITDONE->room_unit_id)
                                    ->select('r.reservation_id',
                                            'r.order_no', 
                                            'r._check_in_date as checkin', 'r._check_out_date as checkout', 
                                            'r._actual_check_in_date as actual_checkin', 'r._actual_check_out_date as actual_checkout', 
                                            'r._current_contact_name as guest_name', 
                                            'r._current_contact_email as guest_email',
                                            'r._current_phone as guest_phone',
                                            'r.user_id as guest_id',
                                            'rs._name as duration_name', 'rs.duration', 'rs.reservation_type_id', 
                                            's._name as duration_type',
                                            'fe_tx_reservation_detail.room_unit_id',
                                            'fe_tx_reservation_detail.reservation_schedule_start_id',
                                            'fe_tx_reservation_detail.reservation_schedule_id')
                                    ->latest('fe_tx_reservation_detail.reservation_id')
                                    ->first();
                // Nights
                if ($GUEST_ORDER->duration_type == 'DAILY') {
                    // If guest already checked In
                    if ($GUEST_ORDER->actual_checkin) {
                        $staySchedule = $this->dailyDateTimeParser($GUEST_ORDER->actual_checkin, $GUEST_ORDER->checkout);
                    }
                    // If guest not check in yet 
                    else {
                        $staySchedule = $this->dailyDateTimeParser($GUEST_ORDER->checkin, $GUEST_ORDER->checkout);
                    }
                    $GUEST_ORDER['stay_schedule'] = $staySchedule;
                }
                // Hours
                else {
                    // If guest already checked In
                    if ($GUEST_ORDER->actual_checkin) {
                        $staySchedule = $this->hourlyDateTimeParser($GUEST_ORDER->actual_checkin, $GUEST_ORDER->duration, true);
                    }
                    // If guest not check in yet 
                    else {
                        $staySchedule = $this->hourlyDateTimeParser($GUEST_ORDER->checkin, $GUEST_ORDER->duration, false);
                    }
                    $GUEST_ORDER['stay_schedule'] = $staySchedule;
                }
            }

            $UNITDONE['guest_order'] = $GUEST_ORDER;

            DB::commit();

            return response()->json($UNITDONE);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateUnit($unitId)
    {
        try {
            DB::beginTransaction();
            
            $name = request('unitname');
            $desc = request('desc');

            $UNIT = CmsTmRoomUnit::find($unitId);
            $UNIT->_name = $name;
            $UNIT->_desc = $desc;
            $UNIT->save();

            $UNITDONE = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('sys_type as t', 't.type_id', 'cms_tm_room_unit._status')
                                    ->where('cms_tm_room_unit.room_unit_id', $unitId)
                                    ->select('cms_tm_room_unit.*', 'r.place_id',
                                            'r._name as room_name', 'r._max_capacity_a_day as room_max_capacity',
                                            't._name as status_name')
                                    ->first();
                                    
            DB::commit();

            return response()->json($UNITDONE);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function dailyDateTimeParser($checkIn, $checkOut) {
        $checkInDate = new DateTime($checkIn);
        $checkInFormatted = $checkInDate->format('d F Y, H:i');

        $checkOutDate = new DateTime($checkOut);
        $checkOutFormatted = $checkOutDate->format('d F Y, H:i');

        $result = [
            'checkIn' => $checkInFormatted,
            'checkOut' => $checkOutFormatted
        ];
        return $result;
    }

    private function hourlyDateTimeParser($checkIn, $duration, $checkedIn = false) {
        
        if (!$checkedIn) {
            $checkInDate = new DateTime($checkIn);
            // Save original check in date as string
            $checkInFormatted = $checkInDate->format('d F Y, H:i');
            // original check in date add +$duration Hours
            $checkInDateTimeInterval = $checkInDate->add(new DateInterval('PT'.$duration.'H'));
            $checkInFormatted.=' - '.$checkInDateTimeInterval->format('H:i');

            // Set checkout date from inverval of check in date
            $checkOutFormatted = $checkInDateTimeInterval->format('d F Y, H:i');
            // Check out date add +$duration Hours
            $checkOutDateInterval = $checkInDateTimeInterval->add(new DateInterval('PT'.$duration.'H'));
            $checkOutFormatted.=' - '.$checkOutDateInterval->format('H:i');
        } else {
            $checkInDate = new DateTime($checkIn);
            $checkInFormatted = $checkInDate->format('d F Y, H:i');

            $checkInDateTimeInterval = $checkInDate->add(new DateInterval('PT'.$duration.'H'));
            $checkOutFormatted = $checkInDateTimeInterval->format('d F Y, H:i');
        }
        
        $result = [
            'checkIn' => $checkInFormatted,
            'checkOut' => $checkOutFormatted
        ];

        return $result;
    }
}