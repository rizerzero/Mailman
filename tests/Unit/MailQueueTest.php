<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Entry;
use App\Message;
use App\MailList;
use App\MailQueue;

class MailQueueTest extends TestCase
{
	use DatabaseMigrations, DatabaseTransactions;

	private $list;
	public function setUp()
	{
		parent::setUp();

		factory(MailList::class, 1)->create()->each(function($l) {
			$l->entries()->save(factory(Entry::class)->make());
			$l->messages()->save(factory(Message::class)->make());
		});


		$this->list = MailList::first();

	}

	private function _startCampaign($list)
	{
		$this->assertNull(MailQueue::first());
		$ret =  $list->startCampaign();

		$this->assertTrue($ret);

		return $ret;
	}
	/** @test */
	public function it_can_get_filter_options()
	{
		$options = MailQueue::getFilterOptions();

		$this->assertNotNull($options);

	}
	/** @test */
	public function it_can_pause_a_queue()
	{
		$start_res = $this->_startCampaign($this->list);

		$queue = MailQueue::first();

		$this->assertNotNull($queue);

		$this->assertNotNull($queue->message->send_date);

		$this->assertTrue($queue->pause());

		$this->assertTrue($queue->isPaused());

		$this->assertSame(1, MailQueue::getPaused()->count());

	}
	/** @test */
	public function it_can_resume_a_queue()
	{

		 $this->_startCampaign($this->list);
		$queue = MailQueue::first();
		$this->assertTrue($queue->pause());
		$this->assertTrue($queue->isPaused());
		$this->assertTrue($queue->resume());
		$this->assertTrue($queue->isNew());
		$this->assertCount(1, MailQueue::getNew()->get());
	}
	/** @test */
	public function it_can_indicate_delivery()
	{

		$this->_startCampaign($this->list);


		$queue = MailQueue::first();

		$this->assertTrue($queue->hasBeenDelivered());

		$this->assertTrue($queue->isDelivered());

		$this->assertSame($queue->getStats()->deliveries, 1);
		$this->assertSame($queue->entry->getStats()->deliveries, 1);
		$this->assertSame($queue->message->getStats()->deliveries, 1);
	}
	/** @test */
	public function it_can_indicate_complaint()
	{
		$this->_startCampaign($this->list);
		$queue = MailQueue::first();
		$this->assertFalse($queue->entry->hasComplained());
		$this->assertTrue($queue->entry->subscribed());
		$this->assertTrue($queue->complained());

		$this->assertFalse($queue->entry->subscribed());

	}
	/** @test */
	public function it_can_indicate_clicked()
	{
		$this->_startCampaign($this->list);
		$queue = MailQueue::first();

		$this->assertTrue($queue->clickedLink());

		$this->assertSame($queue->getStats()->clicks, 1);
		$this->assertSame($queue->entry->getStats()->clicks, 1);
		$this->assertSame($queue->message->getStats()->clicks, 1);
	}
	/** @test */
	public function it_can_indicate_opened()
	{
		$this->_startCampaign($this->list);
		$queue = MailQueue::first();

		$this->assertTrue($queue->hasBeenOpened());

		$this->assertSame($queue->entry->getStats()->opens, 1);
		$this->assertSame($queue->getStats()->opens, 1);
		$this->assertSame($queue->message->getStats()->opens, 1);

	}


	/** @test */
	public function it_can_indicate_processing_started()
	{
		$this->_startCampaign($this->list);
		$queue = MailQueue::first();

		$queue->processingStart();

		$this->assertSame($queue->getOriginal('status'), 3);
		$this->assertSame($queue->report, 'Begin Processing');
	}
	/** @test */
	public function it_can_indicate_processing_error()
	{
		$message = 'message';

		try {
			$this->_startCampaign($this->list);
			$queue = MailQueue::first();
			throw new \Exception($message);
		} catch (\Exception $e) {
			$queue->processingError($e);
		}

		$this->assertSame($queue->report, $message);
		$this->assertSame($queue->getOriginal('status'), 4);

	}
	/** @test */
	public function it_can_indicate_processing_completed()
	{
		$this->_startCampaign($this->list);
			$queue = MailQueue::first();
		$queue->processingCompleted();

		$this->assertSame($queue->report, 'Message sent to Queue');
		$this->assertSame($queue->getOriginal('status'), 2);
	}
	/** @test */
	public function it_uses_status_accessor_correctly()
	{
		$this->_startCampaign($this->list);
		$queue = MailQueue::first();

		$this->assertSame($queue->status, 'New');
	}
}
