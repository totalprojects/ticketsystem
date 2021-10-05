<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDevRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_dev_requests', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('employee_id')->comment('Pk of employee masters');
            $table->unSignedSmallInteger('module_id')->comment('PK of permission masters');
            $table->unSignedSmallInteger('tcode_id')->comment('PK of tcode masters');
            $table->string('description')->comment('Detailed description of the changes');
            $table->unSignedSmallInteger('current_stage')->comment('Pk of Dev Stages');
            $table->unSignedSmallInteger('status')->comment('0 -> Not Approved 1 -> Approved');
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
        Schema::dropIfExists('tbl_dev_requests');
    }
}
