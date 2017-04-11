<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Webhooks\MailGunRequest;

class WebhookController extends Controller
{
    /**
     * Process Webhooks sent to the server
     * @param  Request $request Http request
     * @param  string  $service The service that it was posted from - generally this is defined in the webhooks control panel for the mail provider
     */
    public function processWebhook(Request $request, $service)
    {
    	// try {

    		switch ($service) {
	    		case 'mailgun':
	    			$handler = new MailGunRequest($request->all());
	    			break;

	    		default:
	    			# code...
	    			break;
	    	}

	    	return response()->json($handler->process());
    	// } catch (\Exception $e) {
    	// 	return response()->json($e->getMessage());
    	// }

    	return 'done';
    }
}
