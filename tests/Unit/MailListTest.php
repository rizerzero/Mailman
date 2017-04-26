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
		$test = MailList::whereId($this->active_w_timestamps->id)->first();
		$this->assertFalse($test->isActive());
		$this->assertTrue($test->isPaused());

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
		$this->assertSame(MailList::whereId($this->active->id)->first()->queues()->count(), 2);
		$this->assertTrue($this->active->pause());
		$this->assertSame(MailList::whereId($this->active->id)->first()->queues()->getNew()->count(), 0);
		$this->assertTrue(MailList::whereId($this->active->id)->first()->isPaused());
		$this->assertTrue(MailList::whereId($this->active->id)->first()->resumeCampaign());
		$this->assertTrue(MailList::whereId($this->active->id)->first()->isActive());
		$this->assertSame(MailList::whereId($this->active->id)->first()->queues()->getNew()->count(), 2);
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
		$csv = new ListResponse("Meredith,Rolfson,maybell63@example.net,asdf,Kiehn Group,+14764641032,2269 Laisha Forest Suite 624 Port Liam  GA 53569
Melisa,Berge,brycen88@example.org,asdf,Windler  Donnelly and Harvey,1-413-383-0679 x169,5645 Quigley Viaduct Apt. 739 Denesiktown  HI 40194
Jerod,Strosin,cecelia72@example.net,asdf,Batz PLC,(543) 982-7117 x836,5337 Swift Stravenue Suite 976 East Waino  LA 13547
Candelario,Hane,tnader@example.net,asdf,Pollich  Crona and Legros,(378) 456-4224 x64844,6283 Hamill Via North Susieside  NJ 63794
Roberto,Nader,katlynn30@example.org,asdf,Gleason  Terry and Keeling,1-714-417-2330 x130,66598 Kamryn Alley Lewton  CT 34161-0219
Marcel,Padberg,flatley.ramiro@example.net,asdf,Kertzmann-Crooks,(701) 978-9077 x97734,420 Nola Cape Suite 038 Brionnaburgh  SD 31737
Marisol,Torphy,arden55@example.com,asdf,Davis LLC,+1.446.998.6217,94381 Maggio Squares Suite 424 Leopoldotown  VA 57651-9559
Tamara,Johnston,dominic87@example.net,asdf,Denesik  Lowe and Gerhold,(694) 679-2616 x314,460 Crawford Stream Hilpertside  WV 40818
Marlee,Prohaska,norwood63@example.net,asdf,Grant  Kertzmann and Hodkiewicz,+17395846259,266 Abshire Crossroad New Timmyside  HI 76841-9256
Ahmad,Schmitt,herzog.jaquelin@example.org,asdf,Parker  Goodwin and Zemlak,+1-737-514-9075,82549 Grady Valleys New Websterport  NY 51021-7512
Rahul,Cartwright,cassin.prudence@example.net,asdf,Bechtelar-Waelchi,749.750.6956 x4781,888 Jany Plaza Suite 341 East Keeleyberg  HI 39377
Yasmine,Goyette,lang.winston@example.net,asdf,Homenick-Wintheiser,346.476.7806 x4140,2242 Kellen Coves Suite 834 Alainashire  WA 35704
Gerry,Hodkiewicz,hamill.thora@example.net,asdf,Littel Group,502.430.7148 x151,94488 Quigley Land Kerlukeborough  AZ 51510-7798
Arno,Rosenbaum,emohr@example.com,asdf,Pagac-Bayer,1-960-340-0857,166 Schinner Rapids Port Myrnaton  SD 06313
Susie,Lemke,bayer.dee@example.com,asdf,Paucek  Mohr and McClure,(896) 318-6142,413 Pfeffer Wall Kleinport  VT 19906
Carli,Greenfelder,qchristiansen@example.com,asdf,Klocko  Brakus and Sauer,961-332-4685 x720,73407 Edgar Bypass Romastad  TX 37828-0446
Monte,Ratke,dejon.medhurst@example.org,asdf,Langosh-Schuster,(927) 676-8538 x305,4171 Adolph Village West Samantha  PA 41746
Ricardo,Lakin,gherman@example.org,asdf,Rutherford Group,562-219-9248 x3196,33752 Brianne Station Lake Newton  AZ 48200
Palma,Haley,vernie.baumbach@example.com,asdf,Cole Ltd,(847) 253-9265 x4751,16748 Hayes Lights Grahamfurt  MI 81426
Rowan,Rice,ibreitenberg@example.org,asdf,Koss Ltd,(338) 700-5992,94052 Jayda Prairie Davisbury  MD 99619-3800");

		$this->assertCount(20, $csv->output());
		$this->assertSame($this->active_w_timestamps->entries()->count(), 10);
		$this->active_w_timestamps->saveEntries($csv->output());
		$this->assertSame($this->active_w_timestamps->entries()->count(), 30);
	}



	/** @test */
	public function it_can_determine_if_list_has_entries()
	{
		$this->assertTrue($this->active_w_timestamps->hasEntries());
	}



}
