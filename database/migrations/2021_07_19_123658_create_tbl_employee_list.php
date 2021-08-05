<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmployeeList extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_employee_masters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("first_name", 80)->comment("First name of employee");
            $table->string("last_name", 80)->comment("Last name of employee");
            $table->string("email", 80)->comment("Email ID of employee");
            $table->unsignedBigInteger("contact_no")->comment("Contact No of employee");
            $table->unsignedSmallInteger("state_id")->comment("PK of state master");
            $table->unsignedSmallInteger("district_id")->comment("PK of district master");
            $table->unsignedSmallInteger("pincode")->comment("Pincode of employee");
            $table->string("address")->comment("Present Address of employee");
            $table->smallInteger("is_active")->comment('Active = 1 InActive = 0');
            $table->unsignedBigInteger("status")->comment('Activation Status');
            $table->foreign('district_id')->references('id')->on('tbl_district_masters');
            $table->foreign('state_id')->references('id')->on('tbl_state_masters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_employee_list');
    }
}
