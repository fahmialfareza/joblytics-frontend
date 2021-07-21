<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TrendsAPIController extends Controller
{
    public function topic($topic)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = $topic;

            $params     = [
                'year_start'    => $request->year_start,
                'year_end'      => $request->year_end,
                'job_id'        => $request->job_ids,
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

    public function job(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'jobtrend';

            $params     = [
                'year_start'    => $request->year_start,
                'year_end'      => $request->year_end,
                'job_id'        => $request->job_ids,
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

    public function skill(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'skilltrend';

            $params     = [
                'year_start'    => $request->year_start,
                'year_end'      => $request->year_end,
                'skill_id'      => $request->skill_ids,
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

    public function industry(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'industrytrend';

            $params     = [
                'year_start'    => $request->year_start,
                'year_end'      => $request->year_end,
                'industry_id'      => $request->industry_ids,
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
            $endpoint   = 'bootcamptrendbyyear';
            $params     = [
                'year_start'    => $request->year_start,
                'year_end'      => $request->year_end,
                'bootcamp_id'   => $request->bootcamp_ids,
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
