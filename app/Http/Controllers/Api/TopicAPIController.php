<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TopicAPIController extends Controller
{
    public function list($topic)
    {
        try {
            $apiUrl     = env('API_URL');
            $endpoint   = $topic;

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
