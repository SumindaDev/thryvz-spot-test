<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\OrderAPIRequest;
use App\Jobs\ProcessApiRequestJob;
use Illuminate\Support\Facades\Log;

class OrderAPIController extends BaseAPIController
{
     //Should be authenticated
     public function __construct(){

        $this->middleware('auth:sanctum');
    }

    //Creates order received via http request
    public function createOrder(OrderAPIRequest $orderAPIRequest){

        try{            

            Log::channel('orders_api_log')->info("[Create order] ==> order create request received - ".json_encode($orderAPIRequest->all()));
          
            ProcessApiRequestJob::dispatch($orderAPIRequest->all());

            Log::channel('orders_api_log')->info("[Create order] ==> new order creation queued");

            //process queued        
            return $this->sendResponse(true, 200, 'Process queued', array('info' => 'Order process queued'));
            

        }catch(\Exception $exception){

            Log::channel('orders_api_log')->info("[Create order] ==> error occured while creating order - ".$exception->getMessage());

            return $this->sendResponse(false,500,$exception->getMessage(),null);

        }
    }

}
