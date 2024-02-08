<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * AspNetUsers Table
     * Stores Credentials of Users
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password_hash')->nullable();
            $table->string('security_stamp')->nullable();
            $table->string('email')->unique()->nullable();
            $table->boolean('email_confirmed');
            $table->string('phone_number')->nullable();
            $table->boolean('phone_number_confirmed');
            $table->boolean('two_factor_enabled');
            $table->date('LockoutEndDateUtc')->nullable();
            $table->boolean("lockout_enabled");
            $table->integer("access_failed_count");
            $table->string('core_password_hash')->nullable();
            $table->integer('hash_version')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
