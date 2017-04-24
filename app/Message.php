<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\MailWebhookTrait;
use App\Mailqueue;

class Message extends Model
{
    use MailWebhookTrait;

	protected $table = 'messages';

    protected $fillable = [
    	'name','content','subject','mail_list_id','day_offset','message_time','send_date','deliveries','spam_complaints','clicks','opens','position'
    ];

    public function stats()
    {
        return $this->morphMany('App\Stat', 'statable');
    }

    public function getSendTime()
    {
        $offset = $this->attributes['day_offset'];
        $time = $this->attributes['message_time'];

        return '+ ' . $offset . ' days at ' . $time;
    }
    public function getStats()
    {
        return $this->stats()->first();
    }

    /**
     * Eloquent child relationship for Campaign List
     * @return App\MailList
     */
    public function mailList()
    {
    	return $this->belongsTo('App\MailList');
    }

    /**
     * Eloquent parent relationship for queue elements
     * @return Collection Collection of App\MailQueue
     */
    public function mailQueues()
    {
        return $this->hasMany('App\MailQueue');
    }


    /**
     * Scope messages where the send date is less than the current timestamp
     * @param  Builder $query Eloquent query builder
     * @return Builder        Modified eloquent query
     */
    public function scopeReadyToSend($query)
    {
        return $query->where('send_date', '<', Carbon::now()->toDateTimeString());
    }

    /**
     * Iterate over the lists messages and set the p based on its siblings.
     * For example: You have messages 1,2,3,4,5 and you set a new message to have a position of 3.
     * Well the old original messages (3,4,5) will need to have their position recalculated to (4,5,6) since (3) is now taken.
     * @return bool
     */
    // public function propagatePositions()
    // {

    // 	$value = $this->attributes['position'];

    // 	$list_messages = $this->mailList->messages();

    // 	$models_after_val = $list_messages->orderBy('position','asc')->where('position', '>=', $value)->where('id', '!=', $this->attributes['id'])->get();

    // 	foreach($models_after_val as $model) {
    // 		$newval = $model->position + 1;
    // 		$model->position = $newval;
    // 		$model->save();
    // 	}

    // 	$i = 1;

    // 	$other_models = $this->mailList->messages()->orderBy('position','asc')->get();

    // 	foreach($other_models as $model) {
    // 		$model->position = $i;
    // 		$model->save();

    // 		$i++;
    // 	}

    // 	return true;

    // }

    /**
     * Cancel all new queues for this message
     * This is used when stopping or cancelling the list
     */
    public function cancelQueuedMessage()
    {
        // If the message is "in the future" nullify the send date
        if(Carbon::parse($this->send_date)->gt(Carbon::now())) {
           $this->attributes['send_date'] = null;
           $this->save();
        }

        foreach($this->mailQueues()->getNew()->get() as $queue)
        {
            $queue->pause();
        }

        return true;
    }

    /**
     * Resume messages that have been paused or cancelled.
     * Note: This will generate a new send date that may have different results than expected.
     * Off the top of my head I think it is: Current timestamp + day offset
     * @return [type] [description]
     */
    public function resumeQueuedMessages()
    {
        $this->createSendDate(Carbon::now()->toDateTimeString());
    }

    /**
     * Generate the send date for the model
     * @param  string $timestamp If a custom start date (not the one supplied at creation) is needed, you can pass it in as an arg
     */
    public function createSendDate($timestamp = null)
    {
        if(is_null($timestamp)) {
            $list_start = Carbon::parse($this->mailList->campaign_start);
        } else {
            $list_start = Carbon::parse($timestamp);
        }

        $time_object = Carbon::parse($this->message_time);

        // $add_days = ($this->position == 1) ? 0 : $this->day_offset;

        $intended_first_message_time = Carbon::parse($this->mailList->campaign_start)->addDays($this->day_offset)->setTime($time_object->hour, $time_object->minute);



        if($intended_first_message_time->lt(Carbon::now())) {

           $save = $list_start->addDays(1)->setTime($time_object->hour, $time_object->minute);
        } else {

            $save = $intended_first_message_time;
        }

        $this->attributes['send_date'] = $save->toDateTimeString();

        $this->save();

    }

    /**
     * Mutator for the start_time attribute for a message
     * We only need a timestamp and since you can't trust people or jQuery plugins, have Carbon take care of this for us
     * @param [type] $value [description]
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::parse($value)->toTimeString();

    }


    /**
     * Scope method to find messages from a certian position
     * @param  Builder $query    The eloquent builder
     * @param  int $position Position to query by
     * @return Builder           The modified query
     */
    public function scopeFromPosition($query, $position)
    {
        return $query->where('position','=', $position);
    }

    /**
     * Scope method to order messages by their position
     * @param  Builder $query     The eloquent builder
     * @param  string $direction Ascending or descending
     * @return Builder            The modified query
     */
    public function scopeByPosition($query, $direction = 'asc')
    {
    	return $query->orderBy('position', $direction);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = html_entity_decode($value);
    }
}

