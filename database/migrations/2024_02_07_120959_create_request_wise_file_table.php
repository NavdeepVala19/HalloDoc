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
        Schema::create('request_wise_file', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admin');
            $table->unsignedBigInteger('physician_id');
            $table->foreign('physician_id')->references('id')->on('provider');

            $table->string('file_name');
            $table->enum('doc_type',['test_one','medical_report','cost_receipt']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_wise_file');
    }
};
