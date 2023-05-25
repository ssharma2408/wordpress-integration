<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order');
            $table->string('status');
            $table->string('total');
            $table->string('customer')->nullable();
            $table->datetime('date_created');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
