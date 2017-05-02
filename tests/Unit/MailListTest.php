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
		$csv = new ListResponse("Icie,Padberg,lee.bartell@example.net,asdf,Heidenreich  Corkery and Kreiger,968-287-7130,Margaretville,Hawaii,62786-3650
Alfonso,McKenzie,dwill@example.com,asdf,Fritsch-Renner,(683) 857-4101 x512,Dustinside,Montana,87729-5644
Yesenia,Huels,alivia.fisher@example.com,asdf,Dickens Group,+1-867-623-9812,South Jonatanton,Massachusetts,18529
Fred,Wisoky,krajcik.joelle@example.net,asdf,Donnelly LLC,1-378-329-1073 x72230,Murazikland,Tennessee,68226-7805
Antonette,Considine,boyle.geoffrey@example.com,asdf,Schimmel  Kub and Stanton,897-979-8890,Pearliebury,Michigan,07309-4506
Zoey,Kutch,jayne.prohaska@example.org,asdf,Lesch  Considine and Ankunding,(870) 724-3935,North Jeromystad,Georgia,13603
Sallie,Raynor,craig.von@example.net,asdf,Brakus Inc,1-512-771-4321 x7441,Coraport,Wisconsin,50915-0939
Lia,Graham,tgrimes@example.org,asdf,Skiles  Corkery and Rath,1-734-260-5839 x9609,Pollichton,New Mexico,62846
Kendall,Maggio,marques76@example.org,asdf,Parisian  Koch and Kunde,645.238.3651 x3791,Maiamouth,New Mexico,52018
Margarette,Boyle,rohan.keely@example.net,asdf,Harris Ltd,(949) 536-8613,Ryanhaven,Nevada,68326-0846
Ernestina,Bosco,shana.hegmann@example.com,asdf,Walter-Simonis,1-695-978-1647 x7086,East Stanleyborough,Kansas,00753-0700
Bessie,Koelpin,cturcotte@example.net,asdf,Turner  Gleichner and Mueller,(475) 782-6192,Koelpinland,New Jersey,61682-0773
Lamont,Hessel,jeanette13@example.com,asdf,Rogahn-Carroll,668-416-4050 x839,Kleinfurt,Kentucky,41195-8819
Lon,Boyer,vernice.beer@example.com,asdf,Mosciski  Stokes and Sauer,775-537-8958 x166,Marcelluston,Maryland,87648
Annabel,Wilderman,ernser.dane@example.net,asdf,Krajcik  Schroeder and Hagenes,259-880-9327 x4969,Danielberg,Nebraska,57369
Delphia,Rutherford,polly28@example.org,asdf,Larson Inc,361-831-3444,West Estefania,Maine,65645-5720
Zula,Hermann,lionel.monahan@example.org,asdf,Smith  Weber and Konopelski,218.329.5318 x04909,Aftonside,North Carolina,14093-1459
Maudie,Nienow,altenwerth.brianne@example.org,asdf,Collier LLC,+1.976.467.7961,Kamronport,Wyoming,36537
Alisha,Zboncak,waters.hershel@example.net,asdf,Grant-Powlowski,591-832-7155 x7495,North Connertown,Maine,08272-3424
Wilfrid,Kautzer,jsenger@example.com,asdf,Maggio-Little,(675) 693-1613 x947,Hoytburgh,Ohio,53926");

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
