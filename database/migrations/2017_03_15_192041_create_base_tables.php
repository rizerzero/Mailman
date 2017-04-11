<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('lists', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->mediumText('description')->nullable();
            $table->date('campaign_start')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('entries', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->index();
            $table->boolean('clicked_unsubscribe')->default(0);
            $table->integer('mail_list_id');
            $table->integer('deliveries')->default(0);
            $table->integer('spam_complaints')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('opens')->default(0);
            $table->timestamps();
        });

        Schema::create('messages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('content');
            $table->string('subject');
            $table->integer('position')->default(1);
            $table->integer('day_offset')->nullable();
            $table->time('message_time');
            $table->timestamp('send_date')->nullable();
            $table->integer('deliveries')->default(0);
            $table->integer('spam_complaints')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('opens')->default(0);
            $table->integer('mail_list_id');
            $table->timestamps();
        });

        // Schema::create('series', function(Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('mail_list_id');
        //     $table->integer('position')->default(1);
        //     $table->integer('message_id');
        //     $table->timestamps();
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lists');
        Schema::dropIfExists('entries');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('series');
    }
}
