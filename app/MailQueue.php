<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\SendMessage;
use App\MailWebhookTrait;

class MailQueue extends Model
{
    use MailWebhookTrait;

	protected $table = 'mailqueues';

    protected $fillable = [
 		'entry_id',
 		'message_id',
 		'status',
 		'report',
        'deliveries','spam_complaints','clicks','opens'
    ];

    protected static $status_choices = [
    	1 => 'New',
    	2 => 'Sent',
    	3 => 'Processing',
    	4 => 'Error',
        5 => 'Campaign Stopped',
        6 => 'Delivered',
        7 => 'Paused',
    ];

    public static function getFilterOptions()
    {
        $o =  new \stdClass;
        $o->status = [0 => 'All'] + self::$status_choices;

        return $o;
    }
    /**
     * Pause the queue element
     * Models that are paused are not picked up by \App\Console\Commands\DispatchMessageJobs
     * They are essentially ignored by the rest of the application until the campaign has been resumed
     * Triggering the necessary methods
     */
    public function pause()
    {

        $this->attributes['status'] = 7;
        $this->save();

    }

    /**
     * Resume the paused queue models
     */
    public function resume()
    {
        $this->attributes['status'] = 1;
        $this->save();

    }

    /**
     * Eloquent relationship for App\Message
     */
    public function message()
    {
    	return $this->belongsTo('App\Message');
    }

    /**
     * Return the parent Mail List for the model
     * @return App\MailList blah
     */
    public function mailList()
    {
        return $this->message->mailList;
    }

    /**
     * To be used with webhooks or where applicable
     * Increments the deliveries on all relationships
     */
    public function hasBeenDelivered()
    {
        $this->attributes['status'] = 6;
        $this->save();
        $this->incrementDeliveries();
        $this->entry->incrementDeliveries();
        $this->message->incrementDeliveries();

    }

    /**
     * To be used with webhooks when moron customers complain our messages are
     * spam even though they double opted in to be sent marketing emails
     */
    public function complained()
    {
        $this->entry->unsubscribe();
        $this->entry->incrementComplaints();
        $this->message->incrementComplaints();
    }

    /**
     * To be used with webhooks when a link is clicked
     * FYI - this webhook will fire even when they click the unsubscribe link. - Nothing I can do about this
     */
    public function clickedLink()
    {
        $this->incrementClicks();
        $this->entry->incrementClicks();
        $this->message->incrementClicks();
    }

    /**
     * To be used with webhooks when an email has been opened
     */
    public function hasBeenOpened()
    {
        $this->entry->incrementOpens();
        $this->incrementOpens();
        $this->message->incrementOpens();
    }

    /**
     * Eloquent relationship for Entry model
     * @return App\Entry
     */
    public function entry()
    {
    	return $this->belongsTo('App\Entry');
    }

    /**
     * Scope method to find only messages that haven't been dealt with yet (new)
     * @param  Builder $query Query builder
     * @return Builder        Modified query builder instance
     */
    public function scopeGetNew($query)
    {
    	return $query->whereStatus(1);
    }

    public function scopeFromList($query, $id)
    {
        if($id == 0)
            return $query;
        return $query->where('list_id','=', $id);
    }
    public function scopeFromStatus($query, $id)
    {

        if($id == 0)
            return $query;

        return $query->whereStatus($id);
    }
    /**
     * Scope method to find only paused queue models
     * @param  Builder $query Query builder
     * @return Builder        Modified query builder instance
     */
    public function scopeGetPaused($query)
    {
        return $query->where('status','=', 7);
    }

    /**
     * Push the queue model to the configured mail handler.
     * Depending on app configuration this will most likely start sending messages.
     * @return [type] [description]
     */
    public function push()
    {
    	dispatch(new SendMessage($this));
    }

    /**
     * Change the steatus of the model to indicate that it is being sent out to mail provider
     * @return self
     */
    public function processingStart()
    {
    	$this->attributes['status'] = 3;
        $this->report['Begin Processing'];
    	$this->save();
    	return $this;
    }

    /**
     * Change the stratus of the model to indicate there was an error processing.
     * This should be logged.
     * @param  \Exception $e uhh
     * @return self        Daisy chaining son.
     */
    public function processingError(\Exception $e)
    {
    	$this->attributes['status'] = 4;
    	$this->attributes['report'] = $e->getMessage();
    	$this->save();
    	return $this;
    }

    /**
     * Change the status of the model to indicate sending of the message was completed
     * @return self
     */
    public function processingCompleted()
    {
    	$this->attributes['status'] = 2;
        $this->attributes['report'] = 'Message sent to Queue';
    	$this->save();

    	return $this;
    }

    /**
     * Mutator for the status attribute of the row.
     * @return [type] [description]
     */
    public function getStatusAttribute()
    {
    	return $this::$status_choices[$this->attributes['status']];
    }



}
