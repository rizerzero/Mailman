<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MailList;
use App\ListResponse;
use App\Entry;
use App\Stat;
use App\Jobs\ResumeCampaign;
use App\Helpers;

class ListController extends Controller
{

    function __construct() {


        $this->middleware('has-messages', ['except' => [
            'import',
            'importEntries',
            'saveEntries',
            'exportListEntries',
            'create',
            'deleteList',
            'store',
            'index',
            'viewQueue',
        ]]);

        $this->middleware('has-entries', ['only' => [
            'exportListEntries',
            'clearListEntries',
            'startCampaign',
            'stopCampaign',
            'viewQueue',
        ]]);
    }

    /**
     * Pause a campaign
     * @param  int $id The ID of the campaign to be paused
     */
    public function pauseCampaign($id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $list->pause();


            return redirect()
                    ->back()
                    ->withSuccess($list->name . ' paused! No more messages will be sent! When resuming the list the send date will be recalculated');
        } catch (\Exception $e) {
            return redirect()
                    ->back()
                    ->withError($e->getMessage());
        }
    }

    /**
     * Resume a paused campaign
     * @param  int $id The ID of the campaign to resume
     */
    public function resumeCampaign($id) {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            dispatch(new ResumeCampaign($list));

            return redirect()
                    ->back()
                    ->withSuccess('Campaign successfully resumed!');
        } catch (\Exception $e) {
            return redirect()
                    ->back()
                    ->withError($e->getMessage());
        }
    }

    public function singleStats(Request $request, $id)
    {

        try {
             $model = MailList::whereId($id)->firstOrFail();

            $r = $request->only(['date_start','date_end','type']);

            $stats = $model->messages->stats->fromDateRange($r['date_start'], $r['date_end'])->get();

            return view('lists.stats')->withList($model)->withStats($stats);
        } catch (\Exception $e) {
            return redirect()->action('ListController@index')->withError($e->getMessage());
        }


    }

    /**
     * Show all lists
     */
	public function index()
	{

		return view('lists.index')->with([
    		'lists' => MailList::all(),
    	]);
	}

    /**
     * Show a single list resource
     * @param  Request $request The HTTP request
     * @param  int  $id      The id of the list to display
     */
    public function single(Request $request, $id)
    {
    	try {
            $search_string = $request->get('find_entry');
    		$model = MailList::whereId($id)->firstOrFail();

            $entries = $model->entries();
            if(is_null($search_string)) {
                $entries = $entries->paginate(50);
            } else {
               $entries =  $entries->searchFor($search_string)->paginate(50);
            }

            $r = $request->only(['date_start','date_end','type']);

            $stats = $model->stats()->forGraphData();


    		return view('lists.single')
                    ->withList($model)
                    ->withEntries($entries)
                    ->withSearch($search_string)
                    ->withStats($stats);

    	} catch (\Exception $e) {
    		return redirect()->back()->withError($e->getMessage());
    	}

    }

    /**
     * Display the page used to import entries into a list
     * @param  int $id The ID of the list to import values into
     */
    public function import($id) {

        try {
            $model = MailList::whereId($id)->firstOrFail();

            $helpers = new Helpers;

            $data = factory(Entry::class, 20)->make()->map(function($c) use($helpers) {

                return $helpers->generateFactoryCSVString($c);
            })->implode("\r\n");


            return view('lists.import')->withList($model)->withDummy($data);
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withError($e->getMessage());
        }
    }

    /**
     * Import entries into the active mail list
     * @param  Request $request THe HTTP request, this includes the list ID And CSV data
     * @return view           Return a confirmation modal before actually importing the list
     */
    public function importEntries(Request $request) {

        try {

            $this->validate($request, [
                'csv_data' => 'required'
            ]);

             $data = trim($request->get('csv_data'));

            $list = MailList::whereId($request->get('list_id'))->firstOrFail();

            return view('partials.lists.confirm-import')->with([
                'data' => new ListResponse($data),
                'list' => $list
            ]);

        } catch (\Exception $e) {

            return response()->json($e->getMessage());
        }


    }

    /**
     * After confirming the import of list entries, actually import them to the DB
     * @param  Request $request HTTP Request
     * @return redirect         Redirect with a status message
     */
    public function saveEntries(Request $request)
    {

        try {
             $this->validate($request, [
                'csv_json' => 'required'
            ]);

            $json = json_decode($request->get('csv_json'));

            $list = MailList::whereId($request->get('list_id'))->firstOrFail();

            $new_entry_count = count($list->saveEntries($json));

            $message = 'Success! ' . $new_entry_count . ' records added to ' . $list->title . '. I will not store any invalid emails, so this number may be incorrect';

            return redirect()->action('ListController@single', $list->id)->withSuccess($message);
        } catch (\Exception $e) {

            return redirect()->back()->withError($e->getMessage());
        }

    }

    /**
     * Export the entries for a list - same format it came to us in
     * @param  int $id The ID of the list to export entries from
     */
    public function exportListEntries($id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();


            return $list->exportEntries()->download();
        } catch (\Exception $e) {
            // dd($e);
            return 'ERROR: ' . $e->getMessage();
        }
    }

    /**
     * Clear all entries from a list
     * @param  int $id The ID of the list to wipe
     */
    public function clearListEntries($id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $list->clearList();

            return redirect()->back()->withSuccess($list->title . ' has had all of its entries removed!');
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    /**
     * Update the provided list (editing the list model)
     * @param  Request $request Http Request
     * @param  integer  $id      The ID of the model
     */
    public function update(Request $request, $id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $this->validate($request, [
                'title' => 'required|unique:lists,title,'. $list->id,
                'description' => 'required',
                'from_name' => 'required',
                'from_email' => 'required|email'
            ]);


            $list->title = $request->get('title');
            $list->description = $request->get('description');
            $list->from_name = $request->get('from_name');
            $list->from_email = $request->get('from_email');
            $list->campaign_start = $request->get('campaign_start');

            $list->save();

            return redirect()->action('ListController@single', $list->id)->withSuccess($list->title. ' updated!');
        } catch (\Exception $e) {

            return redirect()->back()->withError($e->getMessage());
        }
    }

    /**
     * Delete a list by ID
     * @param  integer $id The ID of the list
     */
    public function deleteList($id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $list->clearList();
            $list->clearQueues();
            $list->clearMessages();


            $list->delete();

            return redirect()->action('ListController@index')->withSuccess($list->title . ' has been deleted!');
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    /**
     * Create a new list
     */
    public function create()
    {
        return view('lists.create');
    }

    /**
     * Store a newly created list
     * @param  Request $request Illuminate HTTP request
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        try {

            $this->validate($request, [
                'title' => 'required|unique:lists',
                'description' => 'required',
                'from_name' => 'required',
                'from_email' => 'required|email'
            ]);

            $list = new MailList;

            $list->title = $request->get('title');
            $list->description = $request->get('description');
            $list->from_name = $request->get('from_name');
            $list->from_email = $request->get('from_email');
            $list->save();

            return redirect()->action('ListController@single', $list->id)->withSuccess($list->title. ' was successfully created! You may now import entries');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }


    }

    /**
     * Start the provided list
     * @param  integer $list The ID of the list to start
     */
    public function startCampaign($list)
    {
        try {
            $list = MailList::whereId($list)->firstOrFail();

            $list->startCampaign();


            return redirect()->back()->withSuccess('Start signal sent. Please give the application a few moments to create queue elements and set the send date for the message.');
        } catch (\Exception $e) {

            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function stopCampaign($list)
    {
        try {
            $list = MailList::whereId($list)->firstOrFail();

            $list->stopCampaign();


            return redirect()->back()->withSuccess('Campaign Started.');
        } catch (\Exception $e) {

            return redirect()->back()->withError($e->getMessage());
        }

    }
    public function viewQueue($list)
    {
        try {
            $list = MailList::whereId($list)->firstOrFail();

            return view('lists.queue')->withQueues($list->queues()->paginate(50))->withList($list);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}
