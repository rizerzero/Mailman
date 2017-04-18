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
            $listentry->name = $entry->name;
            $listentry->email = $entry->email;

            $save[] = $listentry;
         }
      }


      $this->list->entries()->saveMany($save);
    }
}
