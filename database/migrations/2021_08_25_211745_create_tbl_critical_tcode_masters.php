<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCriticalTcodeMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_critical_tcode_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('tcode_id')->comment('PK of tcode masters');
            $table->unSignedSmallInteger('status')->comment('Status of Critical Tcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_critical_tcode_masters');
    }
}
