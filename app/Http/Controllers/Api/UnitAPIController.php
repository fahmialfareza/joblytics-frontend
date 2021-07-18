<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmRoomUnit;

class UnitAPIController extends Controller
{
    public function get($roomId)
    {
        try {
            $search = request('search') ? request('search') : '';

            $UNIT   = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('sys_type as t', 't.type_id', 'cms_tm_room_unit._status')
                                    ->where(function($query) use ($search){
                                        $query->where('r._name', 'like', '%'.$search.'%')
                                            ->orWhere('cms_tm_room_unit._name', 'like', '%'.$search.'%');
                                    })
                                    ->where('cms_tm_room_unit.room_id', $roomId)
                                    ->select('cms_tm_room_unit.*', 'r.place_id',
                                            'r._name as room_name', 'r._max_capacity_a_day as room_max_capacity',
                                            't._name as status_name')
                                    ->orderBy('room_unit_id', 'asc')
                                    ->get();

            $data = [
                'units' => $UNIT
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

    public function switchStatus($unitId, $status)
    {
        try {
            DB::beginTransaction();
            $UNITDONE = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('sys_type as t', 't.type_id', 'cms_tm_room_unit._status')
                                    ->where('cms_tm_room_unit.room_unit_id', $unitId)
                                    ->select('cms_tm_room_unit.*', 'r.place_id',
                                            'r._name as room_name', 'r._max_capacity_a_day as room_max_capacity',
                                            't._name as status_name')
                                    ->first();

            $UNITDONE['status_initial'] = initial($UNITDONE->status_name);

            DB::commit();

            return response()->json($UNITDONE);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateUnit($unitId)
    {
        try {
            if (!$this->_isRoomUnitNameAvailable(request('unitname'), $unitId)) {
                return json_encode(null);
            }

            DB::beginTransaction();
            
            $name = request('unitname');
            // $desc = request('desc');

            $UNIT = CmsTmRoomUnit::find($unitId);
            $UNIT->_name = $name;
            // $UNIT->_desc = $desc;
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

    private function _isRoomUnitNameAvailable($unitName, $unitId) {
        $LOCATION_ID = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('cms_tm_place as p', 'p.place_id', 'r.place_id')
                                    ->where('cms_tm_room_unit.room_unit_id', $unitId)
                                    ->select('p.place_id as location_id')
                                    ->pluck('location_id')
                                    ->first();

        $UNIT_NAMES = CmsTmRoomUnit::leftJoin('cms_tm_room as r', 'r.room_id', 'cms_tm_room_unit.room_id')
                                    ->leftJoin('cms_tm_place as p', 'p.place_id', 'r.place_id')
                                    ->where('cms_tm_room_unit._name', $unitName)
                                    ->where('p.place_id', $LOCATION_ID)
                                    ->select('cms_tm_room_unit._name as unit_name')
                                    ->orderBy('cms_tm_room_unit._name', 'desc')
                                    ->pluck('unit_name')
                                    ->first();

        if ($UNIT_NAMES != null) {
            return false;
        } else {
            return true;
        }
    }
}
