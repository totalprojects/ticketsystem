<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTcodeMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_tcode_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('permission_id')->comment('PK of permission masters');
            $table->string('t_code')->comment('Transaction Code');
            $table->string('description')->comment('Descript    ion of T Code');
            $table->json('actions')->comment('PK of Action master');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_tcode_masters');
    }
}
