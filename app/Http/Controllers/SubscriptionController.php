<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers;
use App\Entry;

class SubscriptionController extends Controller
{
    public function unsubscribe($email)
    {

    	$unhashed = Helpers::urlSafeHashDecode($email);

    	try {
    		$entry = Entry::whereEmail($unhashed)->firstOrFail();

    		$entry->unsubscribe();

    		return 'You have been removed from our marketing list. We apologize for any inconvenience this may have caused you.';
    	} catch (\Exception $e) {
    		return 'An error occured while trying to locate your entry, please contact us at ' . config('mail.from.address') . ' to be removed from our mailing list.';
    	}

    }
}
