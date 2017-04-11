<?php namespace App\Http\Webhooks;

class WebhookRequest {

	protected $request;

	function __construct($request)
	{
		$this->request = $request;
	}
}