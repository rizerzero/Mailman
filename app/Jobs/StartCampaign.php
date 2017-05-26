<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\MailList;
use Carbon\Carbon;
use Log;

class StartCampaign implements ShouldQueue
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
      Log::info('Starting Campaign: ' . $this->list->title);




      try {
          $this->list->status = 2;
          $this->list->campaign_start = Carbon::now()->toDateString();
          $this->list->save();


          foreach($this->list->messages as $message) {
            if(! $message->been_queued) {

                $message->createSendDate();
                dispatch(new QueueListMessages($this->list, $message));

            }

         }
      } catch (\Exception $e) {

          $this->list->status = 1;
          $this->list->campaign_start = null;
          $this->list->save();

          // return the exception so it can be handled by logging, frontend, etc.
          // How this is displayed depends on queue driver.
          throw $e;

      }

    }
}
