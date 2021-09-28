<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAssetsMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_assets_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('type')->comment('PK of Asset Type Masters');
            $table->string('description')->comment('Description of Asset');
            $table->string('company')->comment('Company of the asset');
            $table->string('specifications')->comment('Asset Tech Specifications');
            $table->string('serial_number')->comment('Serial Number of Asset');
            $table->string('issue_date')->comment('When was the asset issued');
            $table->string('warrenty_period')->comment('Warenty period of the asset');
            $table->unsignedSmallInteger('quantity')->comment('Quantity of the Asset');
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
        Schema::dropIfExists('tbl_assets_masters');
    }
}
