<?php namespace App\FileParse;

use App\Exceptions\FileParseException;

class CSVReader
{

	/**
	 * The location of the file
	 * @var string
	 */
	private $file_location;

	/**
	 * The Data located in the CSV file
	 * @var array
	 */
	private $csv_data;

	/**
	 * The headers for the CSV file
	 * @var array
	 */
	public $headers;

	function __construct($file_location)
	{
		$this->file_location = $file_location;
	}

	private function utf8Only(array $row)
	{
		foreach($row as $key => &$value)
		{
			$value = $this->utf8Regex($value);
		}

		return $row;
	}

	private function utf8Regex($string)
	{
		return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);
	}

	private function removeSlashes($string)
	{
		return strip_slashes($string);
	}
	/**
	 * Converts the CSV data to an array
	 * @param  boolean $headers Adds the CSV headers to the object to be called later
	 * @return self           Returns the instance
	 */
	public function toArray($headers = false)
	{
// 		foreach($res as $raw){
//     $arr[] = preg_replace('/\s+/', ' ', trim($raw)); // Remove newlines from str
// }

// 		$csv = array_map('str_getcsv', file($this->file_location));
// 	    array_walk($csv, function(&$a) use ($csv) {
// 	      $a = array_combine($csv[0], $a);
// 	    });
// 	    array_shift($csv); # remove column header


// 		$data = array_map('str_getcsv', file($this->file_location));

// 		dd($data);

// 		if(! is_array($data)) {
// 			throw new \Exception('CSV Data muast be an array');
// 		} else {
// 			$this->csv_data = $data;
// 		}

// 		if($headers) {
// 			$this->headers = $this->csv_data[0];
// 			unset($this->csv_data[0]);
// 		}

		return $this;
	}

	public function parseCSV($headers = true, $buffer = 1024, $delimiter = ',', $enclosure = '"') {
	    $csv_data = array();
	    $file = $this->file_location;

	    $csvFile = file($file);
	    $csv_data = [];
	    foreach ($csvFile as $line) {

	    	$value = stripslashes($line);

	    	$value = $this->utf8Regex($value);
	        $csv_data[] = str_getcsv($value);
	    }


	    // if (file_exists($file) && is_readable($file)) {
	    //     if (($handle = fopen($file, 'r')) !== FALSE) {
	    //         while (($data = fgetcsv($handle, $buffer, $delimiter)) !== FALSE) {

	    //             $csv_data[] = $data;
	    //         }
	    //     }
	    // }


	    if($headers) {

	    	$header_cols = $csv_data[0];


	    	unset($csv_data[0]);
	    	$csv_data = array_values($csv_data);



		    foreach($csv_data as $key =>  &$data)
		    {

		    	try {

		    		$data = array_combine($header_cols, $data);
		    	} catch (\Exception $e) {
		    		unset($csv_data[$key]);
		    		continue;
		    		// $message =  __METHOD__ . ' ' . $e->getMessage() . ' on ' . $file;
		    		// throw new FileParseException($message);

		    	}

		    }
		}


	    return $csv_data;
	}

	/**
	 * Joins the headers of the file to its' data in an associative array
	 * @param  array $headers The headers to join in case they haven't been specified yet
	 * @return instance       Daisy chaining is awesome
	 */
	public function joinHeaders($headers = null)
	{

		/**
		 * If custom headers are provided, use those instead
		 */
		if($headers && is_array($headers))
			$this->headers = $headers;

		$i = 0;

		foreach($this->csv_data as &$data)
		{

			if(!is_array($data))
				throw new \Exception('Not correct format');


			try {
				$data = array_combine($this->headers, $data);
			} catch (\ErrorException $e) {
				$nu_i = 0;
				foreach($this->headers as $header)
				{

					if(!array_key_exists($header, $data)) {
						$data[$i][$header] = null;
					} else {
						$data[$i][$header] = $data[$i][$nu_i];
					}

					$nu_i++;

				}


				// continue;

			}

			$i++;

		}


		return $this;

	}

	/**
	 * Returns the CSV data in array format
	 * @return array The data from the CSV in an array
	 */
	public function get()
	{
		return array_values($this->csv_data);
	}

}