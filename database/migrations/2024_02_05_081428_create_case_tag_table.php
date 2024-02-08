<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     *  All Types of Cases:
     * 1.New
     * 2.Pending
     * 3.Active
     * 4.Conclude
     * 5.ToClose
     * 6.UnPaid
     */
    public function up(): void
    {
        Schema::create('case_tag', function (Blueprint $table) {
            $table->id();
            $table->string('case_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_tag');
    }
};
