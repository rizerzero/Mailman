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
            $table->string('first_name');
            $table->string('email')->index();
            $table->string('last_name')->nullable();
            $table->string('segment')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('clicked_unsubscribe')->default(0);
            $table->boolean('excessive_bounces')->default(0);
            $table->integer('mail_list_id')->index();
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
            $table->integer('mail_list_id')->index();
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
        Schema::dropIfExists('lists');
        Schema::dropIfExists('entries');
        Schema::dropIfExists('messages');
    }
}
