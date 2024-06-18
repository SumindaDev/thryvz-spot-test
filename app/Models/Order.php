<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_value','customer_name','order_discount','order_status','process_id','order_id'];

     //Returns the order status in string version
     public static function getOrderStatus($status){

        if($status == 0){

            return "Pending";

        }else if($status == 1){

            return "In progress";

        }else if($status == 2){

            return "Completed";
        }else{

            return "Invalid status";
        }
    }
}
