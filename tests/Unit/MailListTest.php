<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\MailList;
use App\Message;
use App\Entry;
use Carbon\Carbon;
use App\ListResponse;

class MailListTest extends TestCase
{
	use DatabaseMigrations, DatabaseTransactions;

	protected $pending, $active, $complete, $cancelled, $paused, $active_w_timestamps;

	public function setUp()
	{
		parent::setUp();

		$this->pending = factory(MailList::class)->create([
			'status' => 1
		]);

		$this->active = factory(MailList::class)->create([
			'status' => 2
		]);

		$this->active_w_timestamps = factory(MailList::class)->create([
			'status' => 2
		]);


		$this->complete = factory(MailList::class)->create([
			'status' => 3
		]);

		$this->cancelled = factory(MailList::class)->create([
			'status' => 4
		]);

		$this->paused = factory(MailList::class)->create([
			'status' => 5
		]);


		$this->active_w_timestamps->entries()->saveMany(factory(Entry::class, 5)->make());

		for ($i=1; $i < 6; $i++) {

			$timestamp = Carbon::now()->subMinutes(1)->toTimeString();
			$datestamp = Carbon::now()->subDays(1)->toDateString();

			$message = factory(Message::class)->make([
				'position' => 0,
				'message_time' => $timestamp,
				'send_date' => $datestamp,
				'opens' => 1,
				'clicks' => 5,
				'deliveries' => 5,

			]);

			$entry = factory(Entry::class)->make();

			$this->pending->messages()->save($message);
			$this->pending->entries()->save($entry);
			$this->paused->messages()->save($message);
			$this->paused->entries()->save($entry);
			$this->active_w_timestamps->messages()->save($message);
			$this->active_w_timestamps->entries()->save($entry);
		}

	}


	/** @test */
	public function it_can_get_the_status()
	{
		$this->assertTrue($this->pending->canUse());
		$this->assertFalse($this->active->canUse());
		$this->assertFalse($this->pending->isActive());
		$this->assertTrue($this->active->isActive());

		$this->assertSame($this->pending->getStatus(), 'Pending');
		$this->assertSame($this->active->getStatus(), 'Active');
		$this->assertSame($this->complete->getStatus(), 'Complete');
		$this->assertSame($this->cancelled->getStatus(), 'Cancelled');
		$this->assertSame($this->paused->getStatus(), 'Paused');
	}

	/** @test */
	public function it_can_pause_a_list()
	{


		foreach($this->active_w_timestamps->messages as $message)
			$this->assertNotNull($message->send_date);

		$this->assertTrue($this->active_w_timestamps->isActive());

		$this->active_w_timestamps->pause();
		$this->assertFalse($this->active_w_timestamps->isActive());
		$this->assertTrue($this->active_w_timestamps->isPaused());

		foreach($this->active->messages as $message)
			$this->assertNull($message->send_date);
	}

	/** @test */
	public function it_can_resume_a_campaign()
	{

		$this->assertSame($this->active->queues->count(), 0);
		$this->active->messages()->saveMany(factory(Message::class, 2)->make([
			'message_time' =>  Carbon::now()->subMinutes(1)->toTimeString(),
			'send_date' =>  Carbon::now()->subDays(1)->toDateString(),
		]));
		$this->active->entries()->save(factory(Entry::class)->make());

		$this->assertTrue($this->active->startCampaign());
		$this->assertSame($this->active->queues()->count(), 2);
		$this->assertTrue($this->active->pause());
		$this->assertSame($this->active->queues()->getNew()->count(), 0);
		$this->assertTrue($this->active->isPaused());
		$this->assertTrue($this->active->resumeCampaign());
		$this->assertTrue($this->active->isActive());
		$this->assertSame($this->active->queues()->getNew()->count(), 2);
	}

	/** @test */
	public function it_can_get_list_opens()
	{
		$this->assertSame($this->active_w_timestamps->getListOpens(), 5);


	}

	/** @test */
	public function it_can_get_list_clicks()
	{
		$this->assertSame($this->active_w_timestamps->getListClicks(), 25);
	}

	/** @test */
	public function it_can_get_list_deliveries()
	{
		$this->assertSame($this->active_w_timestamps->getListDeliveries(), 25);
	}

	/** @test */
	public function it_can_stop_a_campaign()
	{
		$this->assertTrue($this->active_w_timestamps->isActive());
		$this->active_w_timestamps->stopCampaign();
		$this->assertFalse($this->active_w_timestamps->isActive());
	}

	/** @test */
	public function it_can_determine_if_campaign_has_messages()
	{
		$this->assertTrue($this->active_w_timestamps->hasMessages());
	}

	/** @test */
	public function it_can_save_imported_entries()
	{
		$csv = new ListResponse( "qui doloremque velit veniam delectus,pkassulke@example.org \r\n porro nihil sequi cumque consequuntur,adams.giovanny@example.net \r\n laudantium ipsa voluptatem voluptatibus,citlalli46@example.com");

		$this->assertCount(3, $csv->output());
		$this->assertSame($this->active_w_timestamps->entries()->count(), 10);
		$this->active_w_timestamps->saveEntries($csv->output());
		$this->assertSame($this->active_w_timestamps->entries()->count(), 13);
	}



	/** @test */
	public function it_can_determine_if_list_has_entries()
	{
		$this->assertTrue($this->active_w_timestamps->hasEntries());
	}



}
