<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\MailList;
use App\Entry;

class ImportEntries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $list;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailList $list, array $data)
    {
        $this->list = $list;
        $this->data = $data;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $save = [];
      foreach($this->data as $entry)
      {
         if(is_null($this->list->entries()->whereEmail($entry->email)->first())) {
            $listentry = new Entry;

            if(\App::environment() == 'testing' || $listentry->MGValidate($entry->email)) {
                $listentry->first_name = $entry->first_name;
                $listentry->last_name = $entry->last_name;
                $listentry->email = $entry->email;
                $listentry->segment = $entry->segment;
                $listentry->company_name = $entry->company_name;
                $listentry->phone = $entry->phone;
                $listentry->city = $entry->city;
                $listentry->state = $entry->state;
                $listentry->zip = $entry->zip;
                $save[] = $listentry;

            } else {

               Log::critical('MG Validation failed');
               continue;
            }
         } else {
            Log::critical('Entry already exists')
         }
      }


      $this->list->entries()->saveMany($save);
    }
}
