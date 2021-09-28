<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmployeeAssetsMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee_assets_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('asset_id')->comment('Pk of Asset Master');
            $table->unSignedSmallInteger('user_id')->comment('PK of user master');
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
        Schema::dropIfExists('tbl_employee_assets_masters');
    }
}
