<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Entry;
use App\MailList;
use App\Helpers;

class EntryTest extends TestCase
{
	use DatabaseMigrations, DatabaseTransactions;


	public $entry;

	public function setUp()
	{
		parent::setUp();

		$this->list = factory(MailList::class)->create();


		$this->entry = $this->list->entries()->save(factory(Entry::class)->make());
	}
	/** @test */
	public function it_can_unsubscribe_entry()
	{

		$this->assertSame($this->entry->clicked_unsubscribe, 0);
		$this->entry->unsubscribe();
		$this->assertSame($this->entry->clicked_unsubscribe, 1);;
	}

	/** @test */
	public function it_can_scope_subscribed_entries()
	{

		$this->list->entries()->saveMany(factory(Entry::class, 20)->make([
			'clicked_unsubscribe' => 1
		]));

		$this->assertSame($this->list->entries()->count(), 21);
		$this->assertSame($this->list->entries()->isSubscribed()->count(), 1);

	}

	/** @test */
	public function it_can_generate_an_unsubscribe_link()
	{

		$entry = Entry::first();

		$this->assertNotNull(config('mail.signature.unsubscribe'));

		$action = action(config('mail.signature.unsubscribe'), Helpers::urlSafeHashMake($entry->email));

		$this->assertSame($action, $entry->unsubscribeLink());
	}
}
