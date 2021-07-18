<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TokenAPIController extends Controller
{
    public function loginAPI()
    {
        try {
            $apiUrl     = env('API_URL');
            $username   = env('API_USERNAME');
            $key        = env('API_KEY');
            $secret     = env('API_SECRET');
            $endpoint   = 'sys/v1/login/api';

            $params     = [
                'username' => $username,
                'key' => $key,
                'secret' => $secret,
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
