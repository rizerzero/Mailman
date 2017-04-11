<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MailList;
use App\Message;
use Carbon\Carbon;
use App\Entry;

class MessageController extends Controller
{

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
            $last_position = $list->messages->count() + 1;

	    	$message->content = $request->get('body');
	    	$message->name = $request->get('name');
	    	$message->subject = $request->get('subject');
            $message->position = $last_position;
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
    public function edit($list_id, $message_id)
    {
    	try {
    		return view('messages.create')
		    	->withMessage(Message::whereId($message_id)->firstOrFail())
		    	->withList(MailList::whereId($list_id)->firstOrFail());
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
            'body' => 'required',
            'name' => 'required',
            'subject' => 'required',
            'start_time' => 'required',
            'day_offset' => 'required'
        ]);



        $body = $request->get('body');
        $list = MailList::whereId($list)->firstOrFail();
        $message = Message::whereId($message)->firstOrFail();
        $name = $request->get('name');
        $subject = $request->get('subject');
        $position = $request->get('position');


        $message->content = $body;
        $message->name = $name;
        $message->subject = $subject;
        $message->position = $position;
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

            return view('emails.message')->with([
                'mailmessage' => $message,
                'entry' => $entry
            ]);

        } catch (\Exception $e) {

        }

    }
}
