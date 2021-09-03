<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTblSapApprovalLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_sap_approval_logs', function (Blueprint $table) {

            $table->unSignedSmallInteger('status')->nullable()->after('approval_stage')->comment('1 -> Approved 0 -> Rejected');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
