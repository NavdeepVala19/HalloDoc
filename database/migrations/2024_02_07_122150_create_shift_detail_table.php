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
            // $table->unsignedBigInteger('shift_id');
            // $table->foreign('shift_id')->references('id')->on('shift');
            $table->integer('shift_id')->nullable();
            $table->date('shift_date');
            // $table->unsignedBigInteger('region_id')->nullable();
            // $table->foreign('region_id')->references('id')->on('shift_detail_region');
            $table->integer('region_id')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['pending', 'approved']);

            //check here
            $table->string('modified_by')->nullable();
            $table->dateTime('last_running_date')->nullable();
            $table->string('event_id')->nullable();
            $table->boolean('is_sync')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
