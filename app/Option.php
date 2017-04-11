<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Config;
use Artisan;

class Option extends Model
{
    protected $fillable =  ['key','value','category'];

    public $timestamps = false;

    /**
     * Retreive the websites options in an array capable of being served and written as config values
     * @param  Builder $query The eloquent query
     * @return Builder
     */
   public function scopeRetreive($query) {
	    $newArray = array();
	    $array = $query->get()->mapWithKeys(function($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

	    	foreach($array as $key => $value) {
		        $dots = explode(".", $key);
		        try {
		        	 if(count($dots) > 1) {
			            $last = &$newArray[ $dots[0] ];
			            foreach($dots as $k => $dot) {
			                if($k == 0) continue;
			                $last = &$last[$dot];
			            }
			            $last = $value;
			        } else {
			            $newArray[$key] = $value;
			        }
		        } catch (\Exception $e) {

		        }

		    }


	    return $newArray;
	}

	/**
	 * Return the value for a dot seperated "config" value
	 * @param  [type] $index [description]
	 * @return [type]        [description]
	 */
	public function findByIndex($index)
	{
		return array_get($this->retreive(), $index);
	}

	/**
	 * Returns the data for a specific category (mail, app, storage, etc)
	 * @return array The configuration values for the category block
	 */
	private function getCategoryData()
	{
		return $this->findByIndex($this->category);
	}

	/**
	 * Get the parent item for the configuration block
	 * @return array
	 */
	private function getParent()
	{
		return  substr($this->key, 0, strrpos( $this->key, '.') );
	}

	/**
	 * Get the key for a specific option
	 * ex: mail.log = daily will return as mail.log
	 * @return [type] [description]
	 */
	private function getOptionKey()
	{
		return last(explode('.', $this->key));

	}

	/**
	 * Push changes to the configuration files
	 */
	public function propagateChange()
	{

			Config::write($this->category, $this->getCategoryData());


	}
}
