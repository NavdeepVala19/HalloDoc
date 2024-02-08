<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

      /**
     * this table show types of account
     * 1.admin
     * 2.physician
     * 3.patient
     */
    public function up(): void
    {
        Schema::create('account_type', function (Blueprint $table) {
            $table->id();
            $table->string('account_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_type');
    }
};
