<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblModuleApprovalStages extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_module_approval_stages', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('module_id')->comment('Pk of permission masters');
            $table->unSignedSmallInteger('approval_matrix_id')->comment('Pk of approval matrix masters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_module_approval_stages');
    }
}
