<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmployeeMappings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_employee_mappings', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('employee_id')->comment('PK of employee masters');
            $table->unSignedSmallInteger('report_to')->comment('Member of User table');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_employee_mappings');
    }
}
