<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDesignationMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_designation_masters', function (Blueprint $table) {
            $table->id();
            $table->string('designation_name')->comment('Name of Designation');
            $table->unSignedSmallInteger('role_id')->comment('Pk of role master');
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
        Schema::dropIfExists('tbl_designation_masters');
    }
}
