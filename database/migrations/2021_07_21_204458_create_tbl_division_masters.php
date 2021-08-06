<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDivisionMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_division_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('division_code')->comment('The Code of this Division');
            $table->string('division_description')->comment('Description of this division');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_division_masters');
    }
}
