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
        Schema::create('email_log', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');

            $table->unsignedBigInteger('request_id')->nullable();
            $table->foreign('request_id')->references('id')->on('request');

            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admin');

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('provider');

            $table->string('recipient_name')->nullable();
            $table->string('email_template');
            $table->string('subject_name');
            $table->string('email');
            $table->string('confirmation_number')->nullable();
            $table->string('file_path')->nullable();

            $table->date('create_date')->nullable();
            $table->date('sent_date')->nullable();
            $table->boolean('is_email_sent')->nullable();
            $table->integer('sent_tries')->nullable();
            $table->enum('action', ['Link to create Request', 'Notification to Provider', 'Provider Edit Profile Request', 'Send Agreement to Patient'])->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_log');
    }
};
