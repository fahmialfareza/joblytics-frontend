<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FutureJobAPIController extends Controller
{
    public function list(Request $request)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = 'futureofwork';

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
