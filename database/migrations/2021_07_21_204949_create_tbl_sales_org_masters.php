<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSalesOrgMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('tbl_sales_org_masters', function (Blueprint $table) {

            $table->id();
            $table->unSignedBigInteger('sales_org_code')->comment('The Code of this Sales Org');
            $table->string('sales_org_description')->comment('Description of sales org');
            $table->unSignedBigInteger('distribution_channel_code')->comment('PK of distribution channel masters');
            $table->unSignedBigInteger('division_code')->comment('PK of division masters');
            $table->unSignedBigInteger('sales_office_code')->comment('PK of sales office masters');
            $table->unSignedBigInteger('plant_code')->comment('PK of plant masters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_sales_org_masters');
    }
}
