<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MailQueue;
use App\MailList;

class QueueController extends Controller
{

    function __construct()
    {
        $this->middleware('has-queues');
    }
	/**
	 * Display queue elements
	 * @param  Request $request Includes GET Params filtering the data
	 */
    public function index(Request $request)
    {
    	$queues = MailQueue::fromStatus($request->get('status'))->join('messages', 'messages.id', '=', 'mailqueues.message_id')->orderBy('messages.send_date', 'asc')->paginate(50);

    	$filter_data = MailQueue::getFilterOptions();

    	$lists = MailList::all();
    	return view('queues.index')->withQueues($queues)->withData($filter_data)->withLists($lists);
    }

    public function test()
    {
        $queue = MailQueue::first();


        $queue->incrementDeliveries();
    }


    public function export($list)
    {
        return MailList::whereId($list)->firstOrFail()->exportQueue()->download();

    }
}
