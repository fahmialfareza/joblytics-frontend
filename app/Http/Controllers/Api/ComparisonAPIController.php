<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ComparisonAPIController extends Controller
{
    public function graduate(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'industryneed';

            $params     = [
                'year_start'    => $request->year_start,
                'year_end'      => $request->year_end,
                'industry_id'   => $request->industry_ids,
            ];

            $response = Http::post($apiUrl.$endpoint, $params);

            $result   = json_decode($response->getBody()->getContents());

            $data = [
                'result' => $result
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function bootcamp(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'jobvsbootcamp';

            $params     = [
                'year_start'    => $request->year_start,
                'year_end'      => $request->year_end,
                'job_id'        => $request->job_id
            ];

            $response = Http::post($apiUrl.$endpoint, $params);

            $result   = json_decode($response->getBody()->getContents());

            $data = [
                'result' => $result
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
