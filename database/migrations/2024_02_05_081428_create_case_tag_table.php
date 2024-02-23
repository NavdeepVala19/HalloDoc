<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *  used for cancellation reason
     * 
     * Select option values (prefilled)
     * 1. Cost Issue
     * 2. Inappropriate for service
     * 3. Provider not available
     * 4. Location problem
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
