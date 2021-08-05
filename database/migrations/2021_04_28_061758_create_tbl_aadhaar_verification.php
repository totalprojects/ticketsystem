<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAadhaarVerification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_aadhaar_verification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_id')->comments('Pk of crm id')->nullable();
            $table->string('verification_id')->comment('Pan Verification Id')->nullable()->default(0);
            $table->string('customer_name')->comment('Customer Name')->nullable()->default('N/A');
            $table->date('dob')->comment('Customer DOB')->nullable();
            $table->string('fathers_name')->comment('Customer Father Name')->nullable();
            $table->string('address')->comment('Customer Residential Address')->nullable();
            $table->string('image')->comment('Aadhaar Image')->nullable();
            $table->unsignedBigInteger('phone')->comment('Customer Phone Number')->nullable();
            $table->unsignedSmallInteger('status')->comment('0 -> Not Verified 1 - Verified')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_aadhaar_verification');
    }
}
