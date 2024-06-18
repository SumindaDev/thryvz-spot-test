<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('order_value',10,2)->default(0);
            $table->string('customer_name');
            $table->decimal('order_discount',10,2)->default(0);
            $table->integer('order_status')->default(0)->comment("0=>pending, 1=>in progress, 2=>completed");
            $table->string('order_id')->nullable();
            $table->integer('process_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
