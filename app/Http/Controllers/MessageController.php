<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MailList;
use App\Message;
use Carbon\Carbon;
use App\Entry;
use Blade;
use App\MailQueue;
use Mail;
use App\Mail\TestMessage;

class MessageController extends Controller
{


    public function sendTestMessage(Request $request, $message_id)
    {
        try {
            $to = $request->get('email');
            $message = Message::whereId($message_id)->firstOrFail();


            $entry = factory(Entry::class)->make([
                'email' => $to
            ]);

            Mail::to($entry)->send(new TestMessage($message, $entry));
            return redirect()->back()->withSuccess('Test message sent to queue');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());

        }
    }
    /**
     * Display the messages whose parent is the provided list
     * @param  integer $list The ID of the list to pull messages from
     */
	public function index($list) {
        try {
            $list = MailList::whereId($list)->firstOrFail();

            return view('messages.index')->withList($list);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

	}

    /**
     * Create a message for the provided list
     * @param  integer $id THe id of the list to create messages for
     */
    public function create($id)
    {
    	try {
    		$list = MailList::whereId($id)->firstOrFail();

    		return view('messages.create')->withList($list);
    	} catch (\Exception $e) {
    		return redirect()->back()->withError($e->getMessage());
    	}

    }

    /**
     * Save the message for the list
     * @param  Request $request Http request
     */
    public function save(Request $request)
    {
    	try {
            $this->validate($request, [
                'body' => 'required',
                'name' => 'required',
                'subject' => 'required',
                'start_time' => 'required',
                'day_offset' => 'required'
            ]);


	    	$list = MailList::whereId($request->get('list_id'))->firstOrFail();
            $message = new Message;

	    	$message->content = html_entity_decode($request->get('body'));
	    	$message->name = $request->get('name');
	    	$message->subject = $request->get('subject');
            $message->day_offset = $request->get('day_offset');
            $message->message_time = $request->get('start_time');
            $list->messages()->save($message);

	    	return redirect()->action('MessageController@edit', ['list' => $list->id, 'message' => $message->id])->withSuccess($message->name . ' successfully created!');
    	} catch (\Exception $e) {

    		return redirect()->back()->withError($e->getMessage());
    	}

    }

    /**
     * Edit the specified message
     * @param  integer $list_id    The ID of the list that the message belongs to
     * @param  integer $message_id The ID of the message to edit
     */
    public function edit(Request $request, $list_id, $message_id)
    {
    	try {
            $r = $request->only(['date_start','date_end','type']);

            $model = Message::whereId($message_id)->firstOrFail();
            $list = MailList::whereId($list_id)->firstOrFail();
            $stats = $model->stats()->fromDateRange($r['date_start'], $r['date_end'])->get();

    		return view('messages.create')
		    	->withMessage($model)
		    	->withList($list)
                ->withStats($stats);
    	} catch (\Exception $e) {
    		return redirect()->back()->withError('Message not found');
    	}


    }

    /**
     * Update the provided message
     * @param  Request $request Http request
     * @param  integer  $list    The ID of the list that the message belongs to
     * @param  integer  $message The ID of the message to be updated
     */
    public function update(Request $request, $list, $message)
    {
         $this->validate($request, [
            'message_body' => 'required',
            'name' => 'required',
            'subject' => 'required',
            'start_time' => 'required',
            'day_offset' => 'required'
        ]);



        $body = $request->get('message_body');
        $list = MailList::whereId($list)->firstOrFail();
        $message = Message::whereId($message)->firstOrFail();
        $name = $request->get('name');
        $subject = $request->get('subject');

        if(!is_null($request->get('text_only'))) {
            $message->text_only = 1;
            $message->content = $request->get('message_body');
        } else {
            $message->content = html_entity_decode($body);
            $message->text_only = 0;
        }


        $message->name = $name;
        $message->subject = $subject;
        $message->day_offset = $request->get('day_offset');
        $message->message_time  = Carbon::parse($request->get('start_time'))->toTimeString();
        $message->save();


        return redirect()->back()->withSuccess('Message Updated!');
    }

    /**
     * Display a preview of the message
     * @param  integer $message The ID of the message to be displayed
     */
    public function render($message)
    {
        try {
            $message = Message::whereId($message)->firstOrFail();
            $entry = factory(Entry::class)->make();

            $view = ($message->text_only) ? 'emails.text' : 'emails.message';
            // dd( Blade::compileString($message->content));
            return view($view)->with([
                'mailmessage' => $message,
                'entry' => $entry,
            ]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}
