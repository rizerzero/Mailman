<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        factory(App\MailList::class, 1)->create()->each(function ($l) {

	        $l->entries()->saveMany(factory(App\Entry::class, 500)->make());

            foreach($l->entries as $entry)
            {
                $entry->stats()->save(factory(App\Stat::class)->make());
            }
            $l->stats()->saveMany(factory(App\Stat::class, rand(1,15))->make());


            for ($i=1; $i < 7; $i++) {
                 $l->messages()->save(factory(App\Message::class)->make([
                    'position' => 1,
                    'day_offset' => (6 - $i),
                    'subject' => 'This is the ' . (6 - $i) . ' message.',
                    'name' => (6 - $i) . 'th message'
                ]));
            }


            foreach($l->messages as $message)
            {
                $message->stats()->saveMany(factory(App\Stat::class, rand(2,10))->make());
            }

	    });

        factory(App\MailList::class, 1)->create()->each(function($l) {
            $l->entries()->save(factory(App\Entry::class)->make([
                'email' => 'tomfordweb@gmail.com',
            ]));
            $l->messages()->save(factory(App\Message::class)->make([
                    'position' => 1,
                    'day_offset' => 1,
                    'subject' => 'This is the 1st message.',
                    'name' => '1st message'
                ]));
        });

    }
}
