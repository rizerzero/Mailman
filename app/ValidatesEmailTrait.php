<?php namespace App;

use Mailgun\Mailgun;
trait ValidatesEmailTrait {

	/**
	 * Validates an email against Mailguns validation API, this is to reduce spam and nefarious login attempts.
	 * @param string $input The email to validate against
	 */
	public function MGValidate($input)
	{
		$mgClient = new Mailgun(env('MAILGUN_PUBLIC_KEY'));
		# Issue the call to the client.
		$result = $mgClient->get("address/validate", array('address' => $input));
		# is_valid is 0 or 1
		return (bool) $result->http_response_body->is_valid;
	}
}