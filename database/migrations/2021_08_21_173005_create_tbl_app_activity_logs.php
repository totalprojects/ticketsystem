<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAppActivityLogs extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_app_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unSignedSmallInteger('user_id')->comment('Pk of user master');
            $table->string('activity_type')->comment('Type of Activity');
            $table->json('description_meta')->comment('Description of the activity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_app_activity_logs');
    }
}
