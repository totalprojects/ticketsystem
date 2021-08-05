<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDistributionChannelMasters extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_distribution_channel_masters', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('distribution_channel_code')->comment('The Code of this Distribution');
            $table->string('distribution_channel_description')->comment('Description of this Distributor Channel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_distribution_channel_masters');
    }
}
