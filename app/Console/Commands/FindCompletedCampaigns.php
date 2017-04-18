<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MailList;

class FindCompletedCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finish-campaigns';

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
        $lists = MailList::all();

        foreach($lists as $list)
        {
            if(! $list->hasNewMessages() && $list->isActive()) {
                $list->markAsCompleted();
            }
        }
    }
}
