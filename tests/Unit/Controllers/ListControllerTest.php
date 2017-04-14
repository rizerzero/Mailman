<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\MailList;
use App\User;
use App\Entry;
use App\Message;

class ListControllerTest extends TestCase
{
	use DatabaseMigrations, DatabaseTransactions;

	protected $list;

	public function setUp()
	{
		parent::setUp();

		$this->be(factory(User::class)->create());
		$this->list = factory(MailList::class)->create([
			'status' => 1
		]);

		$this->list->entries()->save(factory(Entry::class)->make());
		$this->list->messages()->saveMany(factory(Message::class, 2)->make());
	}

	/** @test */
	public function it_can_pause_campaign()
	{
		$response = $this->get(action('ListController@pauseCampaign', $this->list->id));

		$response->assertSessionHas('success');

		$list = MailList::first();

		$this->assertTrue($list->isPaused());

	}

	/** @test */
	public function it_can_resume_campaign()
	{

		$this->list->pause();

		$response = $this->get(action('ListController@resumeCampaign', $this->list->id));

		$response->assertSessionHas('success');

		$list = MailList::first();

		$this->assertTrue($list->isActive());
	}

	/** @test */
	public function it_can_display_index()
	{
		$response = $this->get(action('ListController@index'));

		$response->assertSeeText($this->list->title);

	}


	/** @test */
	public function it_can_clear_list_entries()
	{
		$response = $this->get(action('ListController@clearListEntries', $this->list->id));


		$response->assertSessionHas('success');


		$this->assertFalse($this->list->hasEntries());
	}


	/** @test */
	public function it_can_delete_a_list()
	{
		$list_id = $this->list->id;

		$response = $this->get(action('ListController@deleteList', $list_id));

		$response->assertSessionHas('success');

		$this->assertNull(MailList::whereId($list_id)->first());
	}


	/** @test */
	public function it_can_start_and_stop_the_campaign()
	{
		$response = $this->get(action('ListController@startCampaign', $this->list->id));

		$response->assertSessionHas('success');

		$this->assertTrue(MailList::whereId($this->list->id)->first()->isActive());

		$response = $this->get(action('ListController@stopCampaign', $this->list->id));

		$response->assertSessionHas('success');

		$this->assertSame(MailList::whereId($this->list->id)->first()->getOriginal('status'), 4);
	}

	public function it_can_view_the_queue()
	{
		// no need to test this
	}

	public function it_can_display_a_single_list_and_update_it()
	{
		// i cant get dusk to work
	}


	public function it_can_import_entries()
	{
		// need to get dusk to work
		//do all 3 steps if possible
	}

	public function it_can_export_list_entries()
	{
		// go ahead and write code for this if you want...i'm not.
	}

	public function it_can_display_the_create_page_and_store_the_list()
	{
		// need to get dusk to work
	}

}
