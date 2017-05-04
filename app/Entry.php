<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MailWebhookTrait;
use App\Searchable;
use App\ValidatesEmailTrait;

class Entry extends Model
{
    use MailWebhookTrait, Searchable, ValidatesEmailTrait;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'segment',
        'company_name',
        'city',
        'state',
        'zip',
        'phone',
        'mail_list_id',
        'clicked_unsubscribe',
        'excessive_bounces',
        'dropped',
    ];

    protected $table = 'entries';

    /**
     * Eloquent relationship for parent Lists
     * @return App\MailList
     */
    public function mailList()
    {
    	return $this->belongsTo('App\MailList');
    }

    public function stats()
    {
        return $this->morphMany('App\Stat', 'statable');
    }

    public function getStats()
    {
        return $this->stats()->first();
    }

    public function hasComplained()
    {
        return $this->clicked_unsubscribe == 1 || $this->excessive_bounces == 1;
    }


    public function address()
    {
        return $this->city . ', ' . $this->state . ' ' . $this->zip;
    }

    /**
     * Eloquent relationship for children queue models
     * @return App\MailQueue
     */
    public function mailQueue()
    {
    	return $this->hasMany('App\MailQueue');
    }

    /**
     * Unsubscribe customer from mailing list
     * @return self
     */
    public function unsubscribe()
    {
        $this->attributes['clicked_unsubscribe'] = 1;

        $this->save();

        $this->mailList->incrementComplaints();
        return $this;

    }

    public function hardBounceAction()
    {
        $this->attributes['clicked_unsubscribe'] = 1;
        $this->attributes['dropped'] = 1;
        $this->save();

        return $this;
    }
    public function scopeIsSubscribed($query)
    {
        return $query->where('clicked_unsubscribe', '=', 0)->where('excessive_bounces','=', 0);
    }

    public function subscribed()
    {
        return $this->attributes['clicked_unsubscribe'] == 0 && $this->attributes['excessive_bounces'] == 0;
    }

    /**
     * Generate a link for the customer to remove themselves from the mailing list
     * This will create a hashed string that is able to be "decoded" by the server when they hit the url this method returns
     * @return string aforementioned URL
     */
    public function unsubscribeLink()
    {
        return action(config('mail.signature.unsubscribe'), Helpers::urlSafeHashMake($this->email));
    }
}
