<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JobDetailAPIController extends Controller
{
    public function city(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'jobtrendbycity';

            $params     = [
                'job_id'        => $request->job_id,
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

    public function jobPosting(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'companyjobdemand';

            $params     = [
                'job_id'        => $request->job_id,
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

    public function experience(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'datascienceexperience';

            $response = Http::post($apiUrl.$endpoint);

            $result   = json_decode($response->getBody()->getContents());

            $data = [
                'result' => $result
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function role(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'datasciencerole';

            $response = Http::post($apiUrl.$endpoint);

            $result   = json_decode($response->getBody()->getContents());

            $data = [
                'result' => $result
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function topSkill(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'datasciencetopskill';

            $response = Http::post($apiUrl.$endpoint);

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
