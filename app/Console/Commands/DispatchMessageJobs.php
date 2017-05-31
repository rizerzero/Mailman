<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MailQueue;
use App\Message;
use Log;

class DispatchMessageJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pushes models from the queue table to the actual job queue';

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
        $messages = Message::readyToSend()->orderBy('send_date', 'ASC')->get(); // get all messages that are ready to be sent


        foreach($messages as $message) { //iterate over every individual message


            $models = $message->mailQueues()->getNew()->get(); //push the queue models attached to "ready to send messages" to queue driver


            foreach($models as $model)
            {
                if( $model->mailList()->isActive() ) {
                    $model->processingStart();
                    $model->push(false);
                }
            }


        }

        die();
    }
}
