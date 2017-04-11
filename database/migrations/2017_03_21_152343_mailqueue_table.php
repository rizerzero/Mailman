<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailqueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailqueues', function($table) {
            $table->increments('id');
            $table->integer('entry_id');
            $table->integer('message_id');
            $table->integer('status');
            $table->integer('deliveries')->default(0);
            $table->integer('spam_complaints')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('opens')->default(0);
            $table->longText('report')->nullable();
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
        Schema::dropIfExists('mailqueues');
    }
}
