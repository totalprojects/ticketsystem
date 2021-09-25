<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSapToTblEmployeeMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_employee_masters', function (Blueprint $table) {
            //
            $table->unSignedSmallInteger('sap_code')->comment('SAP code of employee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_employee_masters', function (Blueprint $table) {
            //
        });
    }
}
