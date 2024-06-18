<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessApiRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderAPIRequest;

    /**
     * Create a new job instance.
     */
    public function __construct($orderAPIRequest)
    {
        $this->orderAPIRequest = $orderAPIRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderResponse = $this->createOrder($this->orderAPIRequest);
    }

    //Creates order received via http request
    public function createOrder($orderAPIRequest){

        try{
            
            Log::channel('orders_api_log')->info("[Create order] ==> order create request received - ".json_encode($orderAPIRequest));
          
            $order = new Order;

            $order->order_value = $orderAPIRequest['order_value'];
            $order->customer_name = $orderAPIRequest['customer_name'];
            $order->order_discount = $orderAPIRequest['order_discount'];
            $order->order_status = 0; //pending status
            $order->process_id = rand(1,10);

            $savedOrder = Order::create($order->toArray());

            //updating order id
            $savedOrder->order_id = 'ORD'.$savedOrder->id;
            $savedOrder->save();

            Log::channel('orders_api_log')->info("[Create order] ==> new order created with the id - ".$savedOrder->id);

            //sending created order to 3rd party API
            $sendAPIResponse = $this->sendOrder($savedOrder->id);

            Log::channel('orders_api_log')->info("[Create order] ==> ". $sendAPIResponse['message']." - ".$savedOrder->id);

            return array(
                'status' => true,
                'message' => $sendAPIResponse['message']
            );            

        }catch(\Exception $exception){

            Log::channel('orders_api_log')->info("[Create order] ==> error occured while creating order - ".$exception->getMessage());

            return array(
                'status' => false,
                'message' => 'Order process storing failed'
            );   

        }
    }

    //Send order to external api
    public function sendOrder($orderId){

        try{

            Log::channel('orders_api_log')->info("[Sending order] ==> sending order - ".$orderId);

            $order = Order::where('id',$orderId)->get()->first();
    
            $client = new \GuzzleHttp\Client();
    
            //Request url
            $url = "https://wibip.free.beeceptor.com/order";
    
            //Request body feeding
            $data['Order_ID'] = $order->order_id;
            $data['Customer_Name'] = $order->customer_name;
            $data['Order_Value'] = $order->order_value;
            $data['Order_Date'] = $order->order_date;
            $data['Order_Status'] = $order->order_status;
            $data['Process_ID'] = $order->process_id;
    
            Log::channel('orders_api_log')->info("[Sending order] ==> url - ".$url);
            Log::channel('orders_api_log')->info("[Sending order] ==> request body - ".json_encode($data));
    
            $request = $client->post($url, ['body' => json_encode($data)]);
            $response = $request->send();
    
            Log::channel('orders_api_log')->info("[Sending order] ==> response received - ".json_encode($response->body()));
    
            return array(
                'status' => true,
                'message' => 'Order created successfully !',
                'payload' => $response
            );


        }catch(\Exception $exception){

            Log::channel('orders_api_log')->info("[Sending order] ==> order saved, but sending failed due to - ".$exception->getMessage());

            return array(
                'status' => false,
                'message' => "order saved. sending failed due to - ".$exception->getMessage(),
                'payload' => null
            );
        }      

    }
}
