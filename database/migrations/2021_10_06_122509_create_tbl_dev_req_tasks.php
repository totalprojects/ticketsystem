<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDevReqTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_dev_req_tasks', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('assigned_to')->comment('Pk of employee id');
            $table->unSignedSmallInteger('request_id')->comment('Pk of SAP Dev Request');
            $table->string('description')->comment('Detailed description of the task to be done');
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
        Schema::dropIfExists('tbl_dev_req_tasks');
    }
}
