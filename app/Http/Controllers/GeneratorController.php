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

    				return $c->name . ',' . $c->email;
    			})->implode("\r\n");
    			break;

    		default:
    			$data = null;

    			break;
    	}

    	return view('generators')->withData($data)->withAction($action);
    }
}
