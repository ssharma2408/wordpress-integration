<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerRolesTable extends Migration
{
    public function up()
    {
        Schema::create('customer_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->float('discount_percentage', 5, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
