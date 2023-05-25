<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDocumentPivotTable extends Migration
{
    public function up()
    {
        Schema::create('client_document', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id', 'client_id_fk_8530218')->references('id')->on('clients')->onDelete('cascade');
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id', 'document_id_fk_8530218')->references('id')->on('documents')->onDelete('cascade');
        });
    }
}
