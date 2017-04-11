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



            for ($i=1; $i < 7; $i++) {
                 $l->messages()->save(factory(App\Message::class)->make([
                    'position' => 1,
                    'day_offset' => (6 - $i),
                    'subject' => 'This is the ' . (6 - $i) . ' message.',
                    'name' => (6 - $i) . 'th message'
                ]));
            }

	    });



    }
}
