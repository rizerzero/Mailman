<?php namespace App\Http\Webhooks;

use App\Entry;
use Log;
use App\MailQueue;
use App\Message;

class MailGunRequest extends WebhookRequest {

	public $recipient, $entry_model, $mailqueue_model, $mailmessage_model, $action, $timestamp, $r, $header_vars;

	function __construct($request)
	{
		parent::__construct($request);

		$this->r = $this->request;

		$this->recipient = $this->request['recipient'];
		$this->action = $this->request['event'];
		$this->timestamp = $this->request['timestamp'];
		Log::debug('Incoming Webhook from Mailgun for ' . $this->action . ' Request Body: ' . json_encode($this->r));
		$this->mailqueue_model = $this->setMailQueueModel();
		$this->mailmessage_model = $this->setMailMessageModel();
		$this->entry_model = $this->setEntryModel();

	}

	/**
	 * Process the webhook event
	 * Update models where necessary
	 * @return string A response message in JSON for logging purposes
	 */
	public function process()
	{

		if(is_null($this->mailqueue_model)) {
			Log::info($this->header_vars . ' did not find mailqueue model');
			return 'Mailqueue Model not found in Database';
		}

		try {
		switch ($this->action) {
			case 'delivered':
				$this->mailqueue_model->hasBeenDelivered();

				break;
			case 'opened':

				$this->mailqueue_model->hasBeenOpened();
				Log::info('made it here');
				break;

			case 'complained':
				$this->mailqueue_model->complained();
				break;

			case 'clicked':
				$this->mailqueue_model->clickedLink();
				break;
			case 'bounced';
				$this->mailqueue_model->hardBounce();

			default:
				# code...
				break;
		}

			return true;
		} catch (\Exception $e) {
			Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
			return false;
    	}
	}

	/**
	 * Search the input array for the MailGun Header Variables
	 * @param  string $search The string to search the array for
	 * @param  array $array  The input array
	 * @return int         The key from the input array that contains the desired values
	 */
	private function _recusiveSearchForMailgunVars($search, $array)
	{
		foreach($array as $k => $ar)
		{
			foreach($ar as $val) {
				if($val == $search)
					return $k;
			}
		}

		throw new \Exception('Did not find search key');
	}

	/**
	 * Since mailgun sends webhooks in different formats depending on the URL action
	 * This will determine where to grab the data and return a json decoded object
	 * containing the variables needed to tie the request back to the models
	 * @return obj A stdClass object containing necessary properties.
	 */
	private function getMailgunHeaderVars()
	{

			switch ($this->action) {
				case 'delivered':

					$string = json_decode($this->r['message-headers']);

					$position = $this->_recusiveSearchForMailgunVars('X-Mailgun-Variables', $string);
					$return =  end($string[$position]);
					$this->header_vars = $return;
					break;

				case 'bounced':
					$string = json_decode($this->r['message-headers']);
					$position = $this->_recusiveSearchForMailgunVars('X-Mailgun-Variables', $string);

					$return =  end($string[$position]);
					$this->header_vars = $return;
					break;

				default:
					$headers = $this->r;
					$obj = new \stdClass;
					$obj->mailqueue = $headers['mailqueue'];
					$obj->mailmessage = $headers['mailmessage'];
					$obj->entry = $headers['entry'];
					$return = json_encode($obj);
					$this->header_vars = $return;
					break;
			}

			return json_decode($return);

	}

	/**
	 * Sets the model for the mailqueue passed via header vars
	 */
	private function setMailQueueModel()
	{
		$mod = $this->getMailgunHeaderVars();
		return MailQueue::whereId(intval($mod->mailqueue))->first();
	}

	/**
	 * Set the model for Message passed via header vars
	 */
	private function setMailMessageModel()
	{
		try {
			$mod = $this->getMailgunHeaderVars();
			return Message::whereId(intval($mod->mailmessage))->first();
		} catch (\Exception $e) {
			return null;
		}
	}

	/**
	 * Set the model for Entry passed via header vars
	 */
	private function setEntryModel()
	{
		try {
			$mod = $this->getMailgunHeaderVars();
			return Entry::whereId(intval($mod->entry))->first();
		} catch (\Exception $e) {
			return null;
		}

	}

}