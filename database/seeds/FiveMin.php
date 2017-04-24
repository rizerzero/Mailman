<?php

use Illuminate\Database\Seeder;

use App\MailList;
use App\Entry;
use App\Message;
use Carbon\Carbon;

class FiveMin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $lists = factory(MailList::class, 1)->create()->each(function($list) {
        	$list->entries()->save(factory(Entry::class)->make(['email' => 'tomfordweb@gmail.com']));
        	$list->messages()->saveMany(factory(Message::class, 5)->make());
        });

        $list = $lists->first();

        $add_minutes = 1;
        foreach($list->messages as $message) {

        	$message->day_offset = 0;
        	$message->message_time = Carbon::now()->addMinutes($add_minutes)->toTimeString();
        	$message->save();

        	$add_minutes++;
        }

    }
}
