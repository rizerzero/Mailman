<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MailList;

class PageController extends Controller
{
    public function index()
    {
    	return view('index')->with([
    		'lists' => MailList::all(),
    	]);
    }
}
