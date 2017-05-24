<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\MailQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\Message;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mailqueue, $email, $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailQueue $mailqueue)
    {
        $this->mailqueue = $mailqueue;
        $this->email = $mailqueue->entry->email;
        $this->name = $mailqueue->entry->name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            Mail::to($this->email, $this->name)->send(new Message($this->mailqueue));
        } catch (\Exception $e) {
            $this->mailqueue->processingError($e);
        }
    }
}
