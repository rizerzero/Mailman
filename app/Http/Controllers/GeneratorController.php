<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entry;

class GeneratorController extends Controller
{
    /**
     * Return Faker data that pertains to request vars
     * @param  Request $request What data to provide and how much
     * @param  string  $action  The type of data to produce
     * @return view             Http response
     */
    public function generate(Request $request, $action = null) {
    	$quantity = !is_null($request->get('amount')) ? intval($request->get('amount')) : 1;

    	switch ($action) {
    		case 'entry-csv':
    			$data = factory(Entry::class, $quantity)->make()->map(function($c) {

    				return implode(',', [$c->first_name, $c->last_name, $c->email, $c->segment, $c->company_name, $c->phone, $c->address]);
    			})->implode("\r\n");
    			break;

    		default:
    			$data = 'Invalid Arguments';
    			break;
    	}

    	return view('generators')->withData($data)->withAction($action);
    }
}
