<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblModuleHeadMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_module_head_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('department_id')->comment('PK of Department Masters');
            $table->unSignedSmallInteger('user_id')->comment('PK of user masters');
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
        Schema::dropIfExists('tbl_module_head_masters');
    }
}
