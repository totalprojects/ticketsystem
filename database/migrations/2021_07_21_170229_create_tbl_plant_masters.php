<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPlantMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_plant_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_code')->comment('Company Code');
            $table->unsignedBigInteger('plant_code')->comment('Plant Code of the Company');
            $table->string('plant_name')->comment('Name of the plant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_plant_masters');
    }
}
