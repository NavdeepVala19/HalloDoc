<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Request of new case
     */
    public function up(): void
    {
        Schema::create('request', function (Blueprint $table) {
            $table->id();
            // will store who created the request like (patient/family/buisness partner/Conceirge)
            $table->unsignedBigInteger('request_type_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('physician_id')->nullable();
            $table->string('confirmation_no')->nullable();
            $table->string('declined_by')->nullable();
            $table->boolean('is_urgent_email_sent');
            $table->date('last_wellness_date')->nullable();
            $table->boolean('is_mobile');
            $table->enum('call_type', ['house_call', 'consult'])->nullable();
            $table->boolean('completed_by_physician')->nullable();
            $table->date('last_reservation_date')->nullable();
            $table->date('accepted_date')->nullable();
            $table->string('relation_name')->nullable();
            $table->string('case_number')->nullable();
            $table->unsignedBigInteger('case_tag')->nullable();
            $table->string('case_tag_physician');
            $table->string('patient_account_id');
            $table->integer('created_user_id');

            $table->foreign('request_type_id')->references('id')->on('request_type');
            $table->foreign('status')->references('id')->on('status');
            $table->foreign('physician_id')->references('id')->on('provider');
            $table->foreign('case_tag')->references('id')->on('case_tag');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request');
    }
};
