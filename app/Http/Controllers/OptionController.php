<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;
use Config;

class OptionController extends Controller
{
    /**
     * Display the options that a user is able to configure for the website
     * @param  Option $options I think this is provided via DI - can't remember ... sorry.

     */
    public function index(Option $options)
    {
    	return view('options.index')->withOptions($options->retreive());
    }

    /**
     * Update the database as well as the config file with the provided blocks
     * @param  Request $request Http Request
     */
    public function update(Request $request)
    {


    	$req = array_dot($request->except('_token'));

    	$options = [];

    	foreach($req as $k => $v) {

    		if(!empty($v) && !is_null($v)) {
                if(! is_null($option = Option::where('key', '=', $k)->first())) {
                   $option->value = $v;
                   $option->category = explode('.', $k)[0];
                   $option->save();
                } else {
                    $option = Option::create([
                        'key' => $k,
                        'value' => $v,
                        'category' => explode('.', $k)[0],
                    ]);
                }
    			//
    		}

    	}

    	return redirect()->action('OptionController@index')->withSuccess('Options Updated Successfully!');
    }
}
