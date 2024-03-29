<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GeneratorControllerTest extends TestCase
{

	use DatabaseMigrations, DatabaseTransactions;

 	/** @test */
 	public function it_can_generate()
 	{

 			$this->be(factory(\App\User::class)->create());

 		$response = $this->get(action('GeneratorController@generate', 'entry-csv'));


 		$response->assertSeeText('@example.');
 		$response->assertDontSee('Invalid Arguments');

 	}
}
