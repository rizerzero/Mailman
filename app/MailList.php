<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Entry;
use Carbon\Carbon;
use App\Exceptions\NoMessagesException;
use App\MailQueue;

class MailList extends Model
{
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
   protected $table = 'lists';


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
      $this->attributes['status'] = 5;
      $this->save();

      foreach($this->messages as $message) {
          $message->cancelQueuedMessage();
      }

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
      $this->attributes['status'] = 2;
      $this->save();

      foreach($this->queues()->getPaused()->get() as $q) {
        $q->resume();
      }

      foreach($this->messages as $message)
      {
        $message->resumeQueuedMessages();
      }

      return true;


   }

    /**
    * Get a sum of all of the opens for $this->messages
    * @return int
    */
    public function getListOpens()
    {

    return $this->messages->sum('opens');
    }

   /**
    * Get a sum of all of the clicks for $this->messages
    * @return int
    */
   public function getListClicks()
   {
    return $this->messages->sum('clicks');
   }

   /**
    * Get a sum of all of the successful deliveries for $this->messages
    * @return int
    */
   public function getListDeliveries()
   {
    return $this->messages->sum('deliveries');
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
      $save = [];
      foreach($entries as $entry)
      {
         if(is_null($this->entries()->whereEmail($entry->email)->first())) {
            $listentry = new Entry;
            $listentry->name = $entry->name;
            $listentry->email = $entry->email;

            $save[] = $listentry;
         }
      }


      $this->entries()->saveMany($save);

      return $save;
   }

   /**
    * Export the list entries that are still "subscribed" to our marketing attempts
    * @return string  CSV Magic
    */
   public function exportEntries()
   {

      $entries = $this->entries()->isSubscribed()->get()->pluck('name','email')->toArray();

      $out = '';
      foreach($entries as $key => $val) {
          $out .= implode(",", [$key, $val]) . "\r\n";

      }


     return $out;
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

      $this->attributes['status'] = 2;
      $this->attributes['campaign_start'] = Carbon::now()->toDateString();
      $this->save();

       foreach($this->messages as $message) {
          $message->createSendDate();
          $this->queueMessages($message);
       }
       return true;
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
   private function queueMessages(Message $message)
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

