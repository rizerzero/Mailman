<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\Entry;

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
    		$entry = Entry::whereEmail($unhashed)->get();

            foreach($entries as $entry)
    		  $entry->unsubscribe();

    		return 'You have been removed from all of our marketing lists. We apologize for any inconvenience this may have caused you.';
    	} catch (\Exception $e) {
    		return 'An error occured while trying to locate your entry, please contact us at ' . config('mail.from.address') . ' to be removed from our mailing list.';
    	}

    }
}
