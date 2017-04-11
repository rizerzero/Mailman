<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
	protected $table = 'series';

    protected $fillable = [
    	'list_id','position','message_id'
    ];

    public function list()
    {
    	return $this->belongsTo('App\MailList');
    }

    public function message()
    {
    	return $this->hasOne('App\Message');
    }
}
