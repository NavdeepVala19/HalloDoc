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
        Schema::create('shift_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
            $table->foreign('shift_id')->references('id')->on('shift');
            $table->date('shift_date');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('status');
            $table->foreign('status')->references('id')->on('status');

            //check here
            $table->string('modified_by')->nullable();
            $table->dateTime('last_running_date')->nullable();
            $table->string('event_id')->nullable();
            $table->boolean('is_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_detail');
    }
};
