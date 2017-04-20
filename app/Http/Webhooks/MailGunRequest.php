<?php namespace App\Http\Webhooks;

use App\Entry;
use Log;
use App\MailQueue;
use App\Message;

class MailGunRequest extends WebhookRequest {

	public $recipient, $entry_model, $mailqueue_model, $mailmessage_model, $action, $timestamp, $r;

	function __construct($request)
	{
		parent::__construct($request);

		$this->r = $this->request;
		$this->recipient = $this->request['recipient'];
		$this->action = $this->request['event'];
		$this->timestamp = $this->request['timestamp'];
		// $this->mailqueue_model = $this->setMailQueueModel();
		// $this->mailmessage_model = $this->setMailMessageModel();
		// $this->entry_model = $this->setEntryModel();

	}
	public function process()
	{


		try {
		switch ($this->action) {
			case 'delivered':
				$this->mailqueue_model->hasBeenDelivered();

				break;
			case 'opened':
				$this->mailqueue_model->hasBeenOpened();

				break;

			case 'complained':
				$this->mailqueue_model->complained();
				break;

			case 'clicked':
				$this->mailqueue_model->clickedLink();
				break;
			case 'bounced';
				return 'hai';
				$this->mailqueue_model->hardBounce();

			default:
				# code...
				break;
		}
		} catch (\Exception $e) {
			return $e->getMessage();
		}

	}

	private function setEntryModel()
	{
		$entry_id = $this->getMailgunHeaderVars()->entry;

		return Entry::whereId($entry_id)->firstOrFail();
	}

	private function _recusiveSearchForMailgunVars($search, $array)
	{
		foreach($array as $k => $ar)
		{
			foreach($ar as $val) {
				if($val == $search)
					return $k;
			}
		}
	}
	private function getMailgunHeaderVars()
	{

		switch ($this->action) {
			case 'delivered':
				$string = json_decode($this->r['message-headers']);
				$position = $this->_recusiveSearchForMailgunVars('X-Mailgun-Variables', $string);
				$return =  end($string[$position]);
				break;

			case 'bounced':
				$string = json_decode($this->r['message-headers']);
				$position = $this->_recusiveSearchForMailgunVars('X-Mailgun-Variables', $string);
				$return =  end($string[$position]);
				break;

			default:
				$headers = $this->r;
				$obj = new \stdClass;
				$obj->mailqueue = $headers['mailqueue'];
				$obj->mailmessage = $headers['mailmessage'];
				$obj->entry = $headers['entry'];

				$return = json_encode($obj);
				break;
		}


		return json_decode($return);


	}

	private function setMailQueueModel()
	{
		$queue_id = $this->getMailgunHeaderVars()->mailqueue;

		return MailQueue::whereId($queue_id)->firstOrFail();
	}

	private function setMailMessageModel()
	{
		$message_id = $this->getMailgunHeaderVars()->mailmessage;

		return Message::whereId($message_id)->firstOrFail();
	}


}