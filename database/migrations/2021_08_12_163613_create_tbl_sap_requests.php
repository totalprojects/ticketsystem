<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSapRequests extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_sap_requests', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('user_id')->comment('Pk of users table');
            $table->unSignedBigInteger('module_id')->comment('Pk of permission table');
            $table->unSignedBigInteger('tcode_id')->comment('Pk of tcode masters');
            $table->json('actions')->comment('PKs of Action masters');
            $table->smallInteger('status')->comment('Status of approval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_sap_requests');
    }
}
