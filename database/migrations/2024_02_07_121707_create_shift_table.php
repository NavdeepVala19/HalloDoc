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
        Schema::create('shift', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('physician_id');
            $table->foreign('physician_id')->references('id')->on('provider');
            $table->date('start_date');
            $table->boolean('is_repeat');
            $table->char('week_days')->nullable();
            $table->integer('repeat_upto')->nullable();

            // check
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('roles');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift');
    }
};
