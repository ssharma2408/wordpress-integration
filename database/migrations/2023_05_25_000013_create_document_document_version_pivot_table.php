<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentDocumentVersionPivotTable extends Migration
{
    public function up()
    {
        Schema::create('document_document_version', function (Blueprint $table) {
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id', 'document_id_fk_8530193')->references('id')->on('documents')->onDelete('cascade');
            $table->unsignedBigInteger('document_version_id');
            $table->foreign('document_version_id', 'document_version_id_fk_8530193')->references('id')->on('document_versions')->onDelete('cascade');
        });
    }
}
