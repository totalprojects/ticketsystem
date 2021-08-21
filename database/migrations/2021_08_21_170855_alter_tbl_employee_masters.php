<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTblEmployeeMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
        Schema::table('tbl_employee_masters', function (Blueprint $table) {

            $table->string('designation_id')->default(0)->nullable()->after('department_id')->comment('Pk of Desingation masters');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }
}
