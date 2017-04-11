<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Config;
use Artisan;

class Option extends Model
{
    protected $fillable =  ['key','value','category'];

    public $timestamps = false;


    function scopeRetreive($query) {
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

	public function findByIndex($index)
	{
		return array_get($this->retreive(), $index);
	}


	private function getCategoryData()
	{
		return $this->findByIndex($this->category);
	}
	private function getParent()
	{
		return  substr($this->key, 0, strrpos( $this->key, '.') );
	}

	private function getOptionKey()
	{
		return last(explode('.', $this->key));

	}
	public function propagateChange()
	{

			Config::write($this->category, $this->getCategoryData());


	}
}
