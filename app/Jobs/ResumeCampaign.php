<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\MailList;

class ResumeCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $list;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailList $list)
    {
        $this->list = $list;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $this->list->status = 2;
      $this->list->save();

      foreach($this->list->queues()->getPaused()->get() as $q) {
        $q->resume();
      }

      foreach($this->list->messages()->whereNull('send_date')->get() as $message)
      {
        $message->resumeQueuedMessages();
      }
    }
}
