<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Message;
use App\MailList;

class Push2Queue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push2queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $lists = MailList::whereStatus(2)->get(); // get all messages that are ready to be sent

        foreach($lists as $list)
        {
            $messages = $list->messages()->readyToQueue()->get();

            foreach($messages as $message) {
                $message->mailList->queueMessages($message);
                $message->hasBeenQueued();
                $message->markAsReady();
            }

        }

    }
}
