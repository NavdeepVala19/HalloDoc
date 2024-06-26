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
        Schema::create('request_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');


            $table->string('str_month')->nullable();
            $table->integer('int_year')->nullable();
            $table->integer('int_date')->nullable();

            // $table->unsignedBigInteger('phy_notes')->nullable();
            // $table->foreign('phy_notes')->references('id')->on('notes');
            // $table->unsignedBigInteger('admin_notes')->nullable();
            // $table->foreign('admin_notes')->references('id')->on('notes');

            $table->text('physician_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();

            // Check These
            $table->string('AdministrativeNotes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_notes');
    }
};
