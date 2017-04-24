<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stat;

class StatController extends Controller
{
    public function view(Request $request)
    {
    	try {
    		$r = $request->only(['date_start','date_end','type']);

	    	$stats = Stat::fromType($r['type'])->fromDateRange($r['date_start'], $r['date_end'])->take(500)->forGraphData();
	    	return view('stats.index')->withStats($stats);
    	} catch (\Exception $e) {
    		dd($e);
    	}

    }
}
