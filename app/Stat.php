<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Stat extends Model
{

	protected $filable = [
		'statable_id','statable_type','deliveries','spam_complaints','clicks','opens'
	];

	private $type_matches = [
		'entry' => 'App\Entry',
		'message' => 'App\Message',
		'list' => 'App\MailList'

	];
    public function statable()
    {
    	return $this->morphTo();
    }

    /**
     * Scope method to determine the relationships to query
     * If null - return the query.
     * @param  Eloquent $query The eloquent query
     * @param  string $type  The typoe to query against
     * @return Eloquent        The query
     */
    public function scopeFromType($query, $type) {
    	if(is_null($type))
    		return $query;

    	return $query->where('statable_type','=',$this->type_matches[$type]);
    }

    public function scopeFromDateRange($query, $start = null, $end = null)
    {
        if(is_null($start))
            return $query;

    	$end = (is_null($end)) ? Carbon::now()->toDateTimeString() : Carbon::parse($end)->toDateTimeString();
    	$start = Carbon::parse($start)->toDateTimeString();

    	return $query->whereBetween('created_at', [$start, $end]);

    }

    public function scopeForGraphData($query)
    {
    	return $query->get()->sortBy('created_at')->groupBy(function($date) {
    		return Carbon::parse($date->created_at)->format('Y-M-d H:00');
    	})->map(function($col) {
    		$output = new \stdClass;
    		$output->deliveries = 0;
    		$output->clicks = 0;
    		$output->spam_complaints = 0;
    		$output->opens = 0;
    		foreach($col as $element)
    		{
    			$output->deliveries = $output->deliveries + $element->deliveries;
    			$output->clicks = $output->clicks + $element->clicks;
    			$output->spam_complaints = $output->spam_complaints + $element->spam_complaints;
    			$output->opens = $output->opens + $element->opens;
    		}

    		return collect($output);
    	});
    }



}
