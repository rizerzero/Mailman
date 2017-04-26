<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	protected $baseUrl = 'http://mailer.wholehousetransmitter.com';
    use CreatesApplication;
}
