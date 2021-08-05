<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserMenuMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_menu_mapping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id')->comment('Pk of menu master');
            $table->unsignedBigInteger('sub_menu_id')->nullable()->comment('Pk of sub_menu master');
            $table->unsignedBigInteger('user_id')->comment('Pk of users');
            $table->unsignedTinyInteger('status')->comment('0 -> Hold 1 -> Approved');
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
        Schema::dropIfExists('tbl_user_menu_mapping');
    }
}
