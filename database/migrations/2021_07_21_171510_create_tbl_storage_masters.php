<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStorageMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_storage_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('plant_code')->comment('Pk of Plant Master');
            $table->string('storage_code')->comment('Code of Storage');
            $table->string('storage_description')->comment('Storage Description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_storage_masters');
    }
}
