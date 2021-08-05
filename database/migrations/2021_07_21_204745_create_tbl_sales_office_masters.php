<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSalesOfficeMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sales_office_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('sales_office_code')->comment('The Code of this Sales Office');
            $table->string('sales_office_name')->comment('Name of this sales office');
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
        Schema::dropIfExists('tbl_sales_office_masters');
    }
}
