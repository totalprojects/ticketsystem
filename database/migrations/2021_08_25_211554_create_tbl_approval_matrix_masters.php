<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblApprovalMatrixMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_approval_matrix_masters', function (Blueprint $table) {
            $table->id();
            $table->string('aproval_type')->comment('Type of Approval');
            $table->unSignedSmallInteger('status')->comment('Status of Approval Matrix');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_approval_matrix_masters');
    }
}
