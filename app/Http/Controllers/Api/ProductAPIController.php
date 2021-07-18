<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmRoom;
use App\Models\SysTmActivityLog;

class ProductAPIController extends Controller
{
    public function get()
    {
        try {
            // $currentYear = date('Y');
            $limit      = request('limit') ? request('limit') : 100;
            $search     = request('search') ? request('search') : '';
            $location   = request('location') ? request('location') : '';
            // $startDate  = request('start_date') == null ? $currentYear.'-01-01 00:00:00' : request('start_date').' 00:00:00';
            // $endDate    = request('end_date') == null ? $currentYear.'-12-31 00:00:00': request('end_date').' 23:59:59';

            $page       = request('page') ? request('page') : 1;
            $pagination = request('pagination') ? request('pagination') : 12;

            $orderBy    = request('order_by') ? request('order_by') : 'last_update';
            $sort       = request('sort') ? request('sort') : 'desc';

            $PRODUCT = CmsTmRoom::leftJoin('cms_tm_place as p', 'p.place_id', '=', 'cms_tm_room.place_id')
                                ->leftJoin('cms_tm_room_unit as u', 'u.room_id', '=', 'cms_tm_room.room_id')
                                ->where(function($query) use ($search){
                                    $query->where('cms_tm_room._name', 'like', '%'.$search.'%')
                                        ->orWhere('p._name', 'like', '%'.$search.'%');
                                })
                                // ->whereBetween('cms_tm_room.last_update', [$startDate, $endDate])
                                ->where('p.place_id', $location)
                                ->whereNull('u.deleted_at')
                                ->select('cms_tm_room.*',
                                        DB::raw("count(u.room_unit_id) as total_room"),
                                        'p._name as location')
                                ->orderBy('cms_tm_room.'.$orderBy, $sort)
                                ->groupBy('cms_tm_room.room_id')
                                ->paginate($pagination);
            $data = [
                'products'  => $PRODUCT
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switch($roomId, $status)
    {
        try {
            DB::beginTransaction();

            $PRODUCT = CmsTmRoom::find($roomId);
            if ($status == false || $status == 'false') {
                $PRODUCT->_active = '0';
                $this->logbook($roomId, 29, $PRODUCT->_name);
            } else {
                $PRODUCT->_active = '1';
                $this->logbook($roomId, 28, $PRODUCT->_name);
            }
            $PRODUCT->save();

            $RETURNPRODUCT = CmsTmRoom::leftJoin('cms_tm_place as p', 'p.place_id', '=', 'cms_tm_room.place_id')
                                        ->leftJoin('cms_tm_room_unit as u', 'u.room_id', '=', 'cms_tm_room.room_id')
                                        ->where('cms_tm_room.room_id', $roomId)
                                        ->select('cms_tm_room.*', 
                                                DB::raw("count(u.room_unit_id) as total_room"),
                                                'p._name as location')
                                        ->first();
            DB::commit();

            return response()->json($RETURNPRODUCT);
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
            $permissionId   = 6;

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
