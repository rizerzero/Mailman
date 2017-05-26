<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MailList;
use Mail;
use App\Mail\RunawayList;

class CatchRunawayLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runaways';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and stop lists that have excessive queue elements in regard to the entries and message product.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lists = MailList::getActive()->get();


        foreach($lists as $list)
        {

            $max_queues_for_list = $list->entries()->count() * $list->messages()->count();

            $total_list_queues = $list->queues()->count();


            if($total_list_queues > $max_queues_for_list) {
                foreach($list->queues()->getNew()->get() as $queue)
                    $queue->pause();
                $list->pause();
                Mail::to('tomfordweb@gmail.com', 'TF')->send(new RunawayList());
            }


        }

    }
}
