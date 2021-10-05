<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDevRequestsChangeLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_dev_requests_change_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('dev_req_id')->comment('Pk of Dev Request');
            $table->unsignedSmallInteger('from_stage')->comment('Pk of Dev stages masters');
            $table->unSignedSmallInteger('to_stage')->comment('Pk of Dev Stage masters');
            $table->string('remarks')->comment('Any Remarks on the stage shift');
            $table->unSignedSmallInteger('created_by')->comment('Creator Pk of Employee Master');
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
        Schema::dropIfExists('tbl_dev_requests_change_logs');
    }
}
