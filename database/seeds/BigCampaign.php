<?php

use Illuminate\Database\Seeder;

use App\MailList;
use App\Entry;
use App\Message;
use Carbon\Carbon;
class BigCampaign extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = factory(MailList::class, 1)->create()->each(function($list) {
        	$list->entries()->saveMany(factory(Entry::class, 20000)->make());
        	$list->messages()->saveMany(factory(Message::class, 5)->make([
        		'text_only' => 1,
        		'content' => 'this is the content',
        	]));
        });

        $list = $lists->first();

        $add_minutes = 1;
        foreach($list->messages as $message) {

        	$message->day_offset = 0;
        	$message->message_time = Carbon::now()->addMinutes($add_minutes)->toTimeString();
        	$message->save();

        	$add_minutes = $add_minutes + 5;
        }
    }
}
