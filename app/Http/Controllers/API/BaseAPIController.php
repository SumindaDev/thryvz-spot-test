<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseAPIController extends Controller
{

    //send the common API response
    public function sendResponse($status, $code, $message, $payload){

        try{

            return response()->json([
                'status' => $status,
                'code' => $code,
                'message' => $message,
                'payload' => $payload
            ]);

        }catch(\Exception $exception){

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => 'Internal server error',
                'payload' => null
            ]);


        }
    }
}
