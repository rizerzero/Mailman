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
    	$queues = MailQueue::fromStatus($request->get('status'))
                        ->join('messages', 'messages.id', '=', 'mailqueues.message_id')
                        ->orderBy('messages.send_date', 'asc')
                        ->paginate(50);

    	return view('queues.index')
                ->withQueues($queues)
                ->withData(MailQueue::getFilterOptions())
                ->withLists(MailList::all());
    }

    public function export($list)
    {
        return MailList::whereId($list)
                ->firstOrFail()
                ->exportQueue()
                ->download();

    }
}
