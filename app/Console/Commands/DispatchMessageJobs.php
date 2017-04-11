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
        $messages = Message::readyToSend()->get();

        foreach($messages as $message) {
            $models = $message->mailQueues()->getNew()->get();



            foreach($models as $model)
            {

                if( $model->message->readyToSend() && $model->mailList()->isActive() ) {
                    Log::info("Queueing $model->id");
                    $model->push();
                }
            }
        }
    }
}
