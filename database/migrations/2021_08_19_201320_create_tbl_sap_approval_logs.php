<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSapApprovalLogs extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_sap_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('request_id')->comment('Request Id of SAP Request Table');
            $table->unsignedInteger('approval_stage')->comment('Stages of Approval');
            $table->unSignedSmallInteger('created_by')->comment('Approved By');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_sap_approval_logs');
    }
}
