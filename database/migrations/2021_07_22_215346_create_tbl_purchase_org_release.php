<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPurchaseOrgRelease extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_purchase_org_release', function (Blueprint $table) {
            $table->id();
            $table->string('rel_code')->comment('PO Release Code');
            $table->string('rel_description')->comment('PO Description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_purchase_org_release');
    }
}
