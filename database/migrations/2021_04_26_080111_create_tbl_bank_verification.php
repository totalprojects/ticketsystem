<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBankVerification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bank_verification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_id')->comments('Pk of crm id')->nullable();
            $table->string('verification_id')->comment('Bank Verification Id')->nullable()->default(0);
            $table->string('customer_name')->comment('Customer Name')->nullable()->default('N/A');
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
        Schema::dropIfExists('tbl_bank_verification');
    }
}
