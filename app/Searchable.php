<?php namespace App;

trait Searchable {

	/**
	 * Search for a model based off of the "fillable" property of the model
	 * @param  type $query The query, no need to insert this into method as L typehints it
	 * @param  string $term  The search term
	 * @return type        The modified query
	 */
	public function scopeSearchFor($query, $term = null)
	{

		if(is_null($term) || empty($term))
			return $query;
		$fields = $this->fillable;
		$first = $fields[0]; #get the first var of the models searchable fields

		$term = trim($term);


		if(count($fields) > 1) {


			$query = $query->where($first, 'like', '%'.$term.'%');

			unset($fields[0]); #unset it so we can start the filtering, and then iterate the columns

			foreach($fields as $field)
				$query = $query->orWhere($field, 'like', '%'.$term.'%');


		} else {
			$query =  $query->where($first, 'like', '%'.$term.'%');
		}

		return $query;

	}
}