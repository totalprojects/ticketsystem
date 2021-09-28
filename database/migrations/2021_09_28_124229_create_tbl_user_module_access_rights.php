<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserModuleAccessRights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_module_access_rights', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('user_id')->comment('Pk of user masters');
            $table->unSignedSmallInteger('sys_mod_id')->comment('PK of Sys Modules');
            $table->unSignedSmallInteger('status')->comment('0 -> Inactive 1 -> Active');
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
        Schema::dropIfExists('tbl_user_module_access_rights');
    }
}
