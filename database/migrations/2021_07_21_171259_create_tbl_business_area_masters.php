<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBusinessAreaMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_business_area_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('business_code')->comment('Code of the business');
            $table->string('business_name')->comment('Name of the business');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_business_area_masters');
    }
}
