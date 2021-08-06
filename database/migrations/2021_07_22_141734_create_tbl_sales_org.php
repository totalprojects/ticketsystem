<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSalesOrg extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_sales_org', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('so_code')->comment('Sales Org Code');
            $table->string('so_description')->comment('Detail about SO');
            $table->unSignedBigInteger('company_code')->comment('Belongs to Company Master PK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_sales_org');
    }
}
