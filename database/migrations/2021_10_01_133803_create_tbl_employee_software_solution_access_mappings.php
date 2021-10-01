<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmployeeSoftwareSolutionAccessMappings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_software_solution_access_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('employee_id')->comment('Pk of employee masters');
            $table->unSignedSmallInteger('software_id')->comment('Pk of software solution masters');
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
        Schema::dropIfExists('tbl_employee_software_solution_access_mappings');
    }
}
