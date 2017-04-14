<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\MailList;
use App\Entry;
use App\Message;

class MessageControllerTest extends TestCase
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
	public function it_can_display_a_lists_messages()
	{
		$response = $this->get(action('MessageController@index', $this->list->id));

		$first = $this->list->messages->first();
		$response->assertSeeText($first->name);
		$response->assertSeeText('Preview');
		$response->assertSeeText('Messages for ' . $this->list->title);
	}

	public function it_can_display_a_create_page_and_save_entry()
	{
		// need dusk to be working
	}

	public function it_can_edit_and_update_a_message()
	{
		// need dusk to be working
	}
	/** @test */
	public function it_can_render_the_message()
	{

		$message = $this->list->messages->first();

		$response = $this->get(action('MessageController@render', $message->id));

		$response->assertSeeText($message->subject);

	}
}
