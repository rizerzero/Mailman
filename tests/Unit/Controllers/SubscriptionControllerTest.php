<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Helpers;
use App\Entry;

class SubscriptionControllerTest extends TestCase
{
	use DatabaseMigrations, DatabaseTransactions;

	/** @test */
	public function it_can_unsubscribe_a_user()
	{


		$entry = factory(Entry::class)->create([
			'mail_list_id' => 1
		]);
		$this->get(action('SubscriptionController@unsubscribe', Helpers::urlSafeHashMake($entry->email)));

		$this->assertSame(1, Entry::first()->clicked_unsubscribe);
	}

}
