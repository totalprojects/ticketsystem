<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPurchaseGroupMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_purchase_group_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('pg_code')->comment('Purchase Group Code');
            $table->string('pg_description')->comment('PG Description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_purchase_group_masters');
    }
}
