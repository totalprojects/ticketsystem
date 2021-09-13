<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblMailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mail_templates', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('approval_matrix_id')->comment('Pk of Approval Matrix');
            $table->string('html_template')->comment('HTML Templete to be used for sending mail');
            $table->json('variables')->comment('PK of variable masters');
            $table->unSignedSmallInteger('status')->comment('Status of the temeplate');
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
        Schema::dropIfExists('tbl_mail_templates');
    }
}
