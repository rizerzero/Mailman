<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\MailList;

class PauseCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $list;
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
      $this->list->status = 5;
      $this->list->save();

      foreach($this->list->messages as $message) {
          $message->cancelQueuedMessage();
      }
    }
}
