<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Helpers;

class HelperTest extends TestCase
{

	/** @test */
	public function it_can_read_a_url_safe_hash_string()
	{

		$string = 'administrator@website.com';

		$hashed = Helpers::urlSafeHashMake($string);

		$unhashed = Helpers::urlSafeHashDecode($hashed);

		$this->assertSame($string, $unhashed);
		$this->assertFalse($string == $hashed);
	}
}
