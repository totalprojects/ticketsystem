<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRoleWiseModuleTcodeAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_role_wise_module_tcode_access', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('role_id')->comment('Pk of role Master');
            $table->unSignedSmallInteger('module_id')->comment('Pk of permission Master');
            $table->unSignedSmallInteger('tcode_id')->comment('Pk of standard tcode Master');
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
        Schema::dropIfExists('tbl_role_wise_module_tcode_access');
    }
}
