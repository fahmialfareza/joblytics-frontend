<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CmsTmFacility;
use App\Models\CmsTmAsset;

class FacilityAPIController extends Controller
{
    public function get()
    {
        try {

            $FACILITY = CmsTmFacility::leftJoin('cms_tm_asset as a', 'a.asset_id', 'cms_tm_facility.asset_id')
                                        ->select('cms_tm_facility.*',
                                                'a.asset_type_id',
                                                'a._url',
                                                'a._real_name')
                                        ->orderBy('last_update', 'desc')
                                        ->get();

            $data = [
                'facilities' => $FACILITY
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
