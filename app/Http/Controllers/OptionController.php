<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;
use Config;

class OptionController extends Controller
{
    public function index(Option $options)
    {
    	return view('options.index')->withOptions($options->retreive());
    }

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
