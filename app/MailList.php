<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Entry;
use Carbon\Carbon;
use App\Exceptions\NoMessagesException;
use App\MailQueue;
use App\Jobs\ImportEntries;
use App\Jobs\StartCampaign;
use App\Jobs\PauseCampaign;
use App\Jobs\StopCampaign;
use App\MailWebhookTrait;
use App\Jobs\ResumeCampaign;

class MailList extends Model
{
  use MailWebhookTrait;

   protected $fillable = [
   		'title','description', 'campaign_start','status'
   ];

   private $status_choices = [
      1 => 'Pending',
      2 => 'Active',
      3 => 'Complete',
      4 => 'Cancelled',
      5 => 'Paused',
   ];

   public function stats()
    {

        return $this->morphMany('App\Stat', 'statable');
    }

    public function getStats()
    {
        return $this->stats()->first();
    }


   protected $table = 'lists';


   public function exportQueue()
    {
        $queues = $this->queues()->get();

      $csvExporter = new \Laracsv\Export();
      $csvExporter->beforeEach(function ($queue) {
        $queue->entry = $queue->entry->email;
        $queue->message = $queue->message->name;
      });
      $csvExporter->build($queues, [
        'status',
        'report'
    ]);

      return $csvExporter;
    }

   /**
    * Accessor for human friendly representation of status column
    * @return string
    */
   public function getStatus()
   {
      $stat = $this->attributes['status'];

      return $this->status_choices[$stat];

   }

   /**
    * This is the function to use if you need to pause all future messages for the list
    * This will call necessary methods on child and parent relationships
    */
   public function pause()
   {
      if(! $this->isActive() ) {

        return false;
      }


      dispatch(new PauseCampaign($this));

      return true;
   }

   /**
    * Determine if $this is paused
    * @return boolean [description]
    */
   public function isPaused()
   {
    return $this->attributes['status'] == 5;
   }

   /**
    * Resume a paused campaign.
    * Do whatever else is needed to resume future child and parent models.
    */
   public function resumeCampaign()
   {

      if(! $this->isPaused())
        return false;

      dispatch(new ResumeCampaign($this));



      return true;


   }

    /**
    * Get a sum of all of the opens for $this->messages
    * @return int
    */
    public function getListOpens()
    {

    return $this->stats()->sum('opens');
    }

   /**
    * Get a sum of all of the clicks for $this->messages
    * @return int
    */
   public function getListClicks()
   {
    return $this->stats()->sum('clicks');
   }

   /**
    * Get a sum of all of the successful deliveries for $this->messages
    * @return int
    */
   public function getListDeliveries()
   {
    return $this->stats()->sum('deliveries');
   }

   /**
    * Eloquent relationship for child entry models
    * @return Collection Collection of \App\Entry
    */
   public function entries()
   {
   	return $this->hasMany('App\Entry');
   }

   /**
    * Eloquent relationship for child Messages
    * @return Collection A bunch of \App\Message
    */
   public function messages()
   {
      return $this->hasMany('App\Message');
   }

   /**
    * Relationship from list -> queue through the entry table
    * @return App\MailQueue
    */
   public function queues()
   {
      return $this->hasManyThrough('App\MailQueue', 'App\Entry');
   }

   /**
    * [stopCampaign description]
    * @return [type] [description]
    */
   public function stopCampaign()
   {
      $this->attributes['status'] = 4;
      $this->save();

      return $this;
   }
   /**
    * Determine if a list has any messages
    * @return boolean
    */
   public function hasMessages()
   {
      return $this->messages->count() > 0;

   }

   public function hasNewMessages()
   {

      return $this->queues()->whereStatus(1)->count() > 0;

   }

   /**
    * Save entries that were inported via application.
    * This should be passed to the method from \App\ListResponse
    * But since this data is shared between a couple requests
    * There is no DRY way I can think of implementing this code
    * @param  array  $entries [description]
    * @return [type]          [description]
    */
   public function saveEntries(array $entries)
   {

      $chunk = array_chunk($entries, 200);
      foreach($chunk as $chu) {
          dispatch(new ImportEntries($this, $chu));
      }


      return $entries;
   }

   /**
    * Export the list entries that are still "subscribed" to our marketing attempts
    * @return string  CSV Magic
    */
   public function exportEntries()
   {

      $entries = $this->entries()->isSubscribed()->get();

      $csvExporter = new \Laracsv\Export();
      $csvExporter->beforeEach(function ($entry) {
          $entry->opens = $entry->stats()->sum('opens');
          $entry->spam_complaints = $entry->Stats()->sum('spam_complaints');
          $entry->clicks = $entry->stats()->sum('clicks');
          $entry->deliveries = $entry->stats()->sum('deliveries');
      });
      $csvExporter->build($entries, [
        'first_name',
        'last_name',
        'email',
        'segment',
        'company',
        'phone',
        'city',
        'state',
        'zip',
        'opens',
        'deliveries',
        'clicks',
        'spam_complaints']);

      return $csvExporter;
      // return (string) $csv; // To get the CSV as string
   }
   /**
    * Remove all entries from the list
    * @return boolean
    */
   public function clearList()
   {
      foreach($this->entries as $entry)
         $entry->delete();

      return true;
   }

  /**
   * Mutator for the campaign start
   * Since this requires just a date stamp, carbon parse that shit and return a datestamp
   * @param self
   */
   public function setCampaignStartAttribute($value)
   {
      $val = Carbon::parse($value)->toDateString();

      $this->attributes['campaign_start'] = $val;

      $this->save();

      return $this;
   }

   /**
    * Determine if the list has any entries
    * @return boolean
    */
   public function hasEntries()
   {

    return $this->entries->count() > 0;
   }

   /**
    * Determine if the campaign start date for the list has been changed
    * Generally, code like this really only works on Event::updating broadcasts
    * @return boolean [description]
    */
   public function hasCampaignStartChanged()
   {
      return $this->campaign_start == $this->getOriginal('campaign_start');
   }

   /**
    * Start the campaign!
    */
   public function startCampaign()
   {
      if(! $this->hasMessages() )
         throw new NoMessagesException('This list does not have any messages');

      if(! $this->hasEntries() )
          throw new NoEntryException('You must have entries in this list before starting the campaign');


      dispatch(new StartCampaign($this));

      return true;
   }

   /**
    * Indicate that the list has been completed and perform necessary cleanup methods
    * to model and its children
    * @return [type] [description]
    */
   public function markAsCompleted()
   {
      $this->attributes['status'] = 3;

      $this->save();
   }
   /**
    * Determine if the campaign is active
    * @return boolean
    */
   public function isActive()
   {
      return $this->getOriginal('status') == 2;
   }

   /**
    * Determine if the campaign is pending
    * @return boolean
    */
   public function canUse()
   {

    return $this->getOriginal('status') == 1;
   }


   /**
    * Queue list messages for sending
    * Depending on the application environment, this could be done immediatly or on a queue depending on queue driver
    * Read about queue drivers at L5.4 docs
    * @param  Message $message The message instance
    */
   public function queueMessages(Message $message)
   {
      if(! $this->isActive() )
         throw new QueueListException('A list must be active before queueing messages');

      foreach($this->entries as $entry)
      {
            $queued = new MailQueue;
            $queued->message_id = $message->id;
            $queued->status = 1;
            $entry->mailQueue()->save($queued);
      }


   }
}

