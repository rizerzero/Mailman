<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\Entry;
use Auth;

class SubscriptionController extends Controller
{
    /**
     * Remove the customer from the mailing list
     * @param  string $email This is a URL Safe Hashed string
     */
    public function unsubscribe($email)
    {

    	$unhashed = Helpers::urlSafeHashDecode($email);

    	try {

    		$entries = Entry::whereEmail($unhashed)->get();


            foreach($entries as $entry)
    		  $entry->unsubscribe();


            return view('unsubscribe')
                    ->withMessage('You have been removed from all of our marketing lists. We apologize for any inconvenience this may have caused you.')
                    ->withEmail($unhashed)->withUnsub('true');

    	} catch (\Exception $e) {
    		return view('unsubscribe')
                    ->withMessage('An error occured, please contact us at ' . config('mail.from.address') . '.')
                    ->withEmail($unhashed)
                    ->withUnsub('false');
    	}

    }
}
