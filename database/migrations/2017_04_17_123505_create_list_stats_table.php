<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('statable_id');
            $table->string('statable_type');
            $table->integer('deliveries')->default(0);
            $table->integer('spam_complaints')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('opens')->default(0);
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
        Schema::dropIfExists('stats');
    }
}
