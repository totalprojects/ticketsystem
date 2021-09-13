<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblMailVariableMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mail_variable_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('template_id')->comment('Pk of template id');
            $table->string('variable_name')->comment('Name of variable');
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
        Schema::dropIfExists('tbl_mail_variable_masters');
    }
}
