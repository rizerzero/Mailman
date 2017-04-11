<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MailList;
use App\ListResponse;
use App\Entry;

class ListController extends Controller
{

    public function pauseCampaign($id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $list->pause();

            return redirect()->back()->withSuccess($list->name . ' paused! No more messages will be sent! When resuming the list the send date will be recalculated');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function resumeCampaign($id) {
        try {
            $list = MailList::whereId($id)->firstOrFail();
            $list->resumeCampaign();

            return redirect()->back()->withSuccess('Campaign successfully resumed!');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

	public function index()
	{
		return view('lists.index')->with([
    		'lists' => MailList::all(),
    	]);
	}
    public function single(Request $request, $id)
    {
    	try {
            $search_string = $request->get('find_entry');

    		$model = MailList::whereId($id)->firstOrFail();
            if(is_null($search_string)) {
                $entries = $model->entries()->paginate(50);
            } else {
               $entries =  $model->entries()->searchFor($search_string)->paginate(50);
            }

    		return view('lists.single')->withList($model)->withEntries($entries)->withSearch($search_string);
    	} catch (\Exception $e) {
    		return redirect()->back()->withError($e->getMessage());
    	}

    }

    public function import($id) {

        try {
            $model = MailList::whereId($id)->firstOrFail();

            $data = factory(Entry::class, 20)->make()->map(function($c) {

                return $c->name . ',' . $c->email;
            })->implode("\r\n");


            return view('lists.import')->withList($model)->withDummy($data);
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function importEntries(Request $request) {

        try {

             $data = trim($request->get('csv_data'));

            $list = MailList::whereId($request->get('list_id'))->firstOrFail();



            return view('partials.lists.confirm-import')->with([
                'data' => new ListResponse($data),
                'list' => $list
            ]);

        } catch (\Exception $e) {

            return response()->json()->withError($e->getMessage());
        }


    }

    public function saveEntries(Request $request)
    {

        try {
            $json = json_decode($request->get('csv_json'));

            $list = MailList::whereId($request->get('list_id'))->firstOrFail();

            $new_entry_count = count($list->saveEntries($json));

            $message = 'Success! ' . $new_entry_count . ' records added to ' . $list->title;

            return redirect()->action('ListController@single', $list->id)->withSuccess($message);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withError($e->getMessage());
        }

    }

    public function exportListEntries($id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $entries = $list->exportEntries();

            return $list->exportEntries();
        } catch (\Exception $e) {

            return 'ERROR: ' . $e->getMessage();
        }
    }

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

    public function update(Request $request, $id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $this->validate($request, [
                'title' => 'required|unique:lists,title,'. $list->id,
                'description' => 'required',
            ]);


            $list->title = $request->get('title');
            $list->description = $request->get('description');
            $list->campaign_start = $request->get('campaign_start');

            $list->save();

            return redirect()->action('ListController@single', $list->id)->withSuccess($list->title. ' updated!');
        } catch (\Exception $e) {

            return redirect()->back()->withError($e->getMessage());
        }
    }
    public function deleteList($id)
    {
        try {
            $list = MailList::whereId($id)->firstOrFail();

            $list->clearList();

            $list->delete();

            return redirect()->action('ListController@index')->withSuccess($list->title . ' has been deleted!');
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function create()
    {
        return view('lists.create');
    }

    public function store(Request $request)
    {
        try {

            $this->validate($request, [
                'title' => 'required|unique:lists',
                'description' => 'required'
            ]);

            $list = new MailList;

            $list->title = $request->get('title');
            $list->description = $request->get('description');

            $list->save();

            return redirect()->action('ListController@single', $list->id)->withSuccess($list->title. ' was successfully created! You may now import entries');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }


    }

    public function startCampaign($list)
    {
        try {
            $list = MailList::whereId($list)->firstOrFail();

            $list->startCampaign();


            return redirect()->back()->withSuccess('Campaign Started.');
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

            return view('partials.queues.list')->withQueues($list->queues);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}
