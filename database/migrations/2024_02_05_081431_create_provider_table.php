<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Provider/Physician detail
     */
    public function up(): void
    {
        Schema::create('provider', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->integer('medical_license')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->integer('zip')->nullable();
            $table->enum('status', ['pending', 'active', 'inactive'])->nullable();
            $table->unsignedBigInteger('regions_id')->nullable();
            $table->foreign('regions_id')->references('id')->on('regions');

            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('role');
            
            $table->string('photo')->nullable();
            $table->string('npi_number')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('alt_phone')->nullable();

            $table->string('business_name')->nullable();
            $table->string('business_website')->nullable();

            // $table->text('regions_id')->nullable();

           // $table->string('created_by')->nullable();
            // $table->string('modified_by')->nullable();

            //add these 
           

            $table->boolean('IsAgreementDoc')->nullable();
            $table->boolean('IsBackgroundDoc')->nullable();
            $table->boolean('IsTrainingDoc')->nullable();
            $table->boolean('IsNonDisclosureDoc')->nullable();
          
            $table->boolean('IsLicenseDoc')->nullable();
            $table->string('signature')->nullable();
            $table->boolean('IsCredentialDoc')->nullable();
            $table->boolean('IsTokenGenerate')->nullable();

            $table->string('syncEmailAddress')->nullable();
            $table->boolean('is_notifications')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider');
    }
};
