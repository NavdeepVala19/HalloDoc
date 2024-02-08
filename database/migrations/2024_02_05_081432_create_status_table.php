<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *  1-Unassigned,
     * 2-Accepted,
     * 3-Cancelled,
     * 4-Reserving,
     * 5-MDEnRoute,
     * 6-MDOnSite,
     * 7-FollowUp,
     * 8-Closed,
     * 9-Locked,
     * 10-Declined,
     * 11-Consult,
     * 12-Clear,
     * 13-CancelledByProvider,
     * 14-CCUploadedByClient,
     * 15-CCApprovedByAdmin
     */
    public function up(): void
    {
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('status_type');
            $table->timestamps();
            // $table->softDeletes();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
