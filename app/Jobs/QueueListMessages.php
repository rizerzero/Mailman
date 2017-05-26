<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\MailList;
use App\Message;

class QueueListMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $list, $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailList $list, Message $message)
    {
        $this->list = $list;
        $this->message = $message;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $this->list->queueMessages($this->message);
    }
}
