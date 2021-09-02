<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblModeratorMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_moderator_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('employee_id')->comment('Pk of employee master');
            $table->unSignedSmallInteger('module_id')->nullable()->comment('Pk of permission master');
            $table->unSignedSmallInteger('type_id')->comment('Type of Moderator 1 -> SAP Lead 2 -> Directors 3 -> IT Head 4 -> Basis (Final Approval) ');
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
        Schema::dropIfExists('tbl_moderator_masters');
    }
}
