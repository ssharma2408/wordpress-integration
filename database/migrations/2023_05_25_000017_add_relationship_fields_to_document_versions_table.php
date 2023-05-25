<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocumentVersionsTable extends Migration
{
    public function up()
    {
        Schema::table('document_versions', function (Blueprint $table) {
            $table->unsignedBigInteger('document_id')->nullable();
            $table->foreign('document_id', 'document_fk_8530177')->references('id')->on('documents');
        });
    }
}
