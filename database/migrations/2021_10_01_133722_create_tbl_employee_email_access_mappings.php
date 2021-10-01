<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmployeeEmailAccessMappings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_email_access_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('employee_id')->comment('Pk of employee masters');
            $table->unSignedSmallInteger('provider_id')->comment('Pk of Email Provider Masters');
            $table->string('exclusion_type')->comment('Exceptions like email can be sent outside org. Default/E1/E2/E3');
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
        Schema::dropIfExists('tbl_employee_email_access_mappings');
    }
}
