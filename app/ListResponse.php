<?php namespace App;

use League\Csv\Reader;
class ListResponse {

   public $input_data;

   private $output_data = [];

   function __construct($data) {
      $this->input_data = trim($data);
      $this->output_data = $this->leagueParse($this->input_data);
      // $this->output_data = $this->parseCSVString($this->input_data);
   }


   function leagueParse($data) {

      $reader = Reader::createFromString($data);

      $results = $reader->fetch();

      foreach ($results as $row) {
      dd($row);
}
      dd($results);

      dd($reader);


   }
   /**
    * Parse the input CSV string and create junk objects that can be used to verify the input information
    * @param  string $data CSV Data, comma seperated columns, newline for rows
    * @return array       An array of objects containing the data
    */
   function parseCSVString($data) {

   		$array = explode("\r\n", $data);

        $output = [];
        foreach($array as &$pair) {
            $split = explode(",", $pair);

            $obj = new \stdClass;
            $obj->first_name = trim($split[0]);
            $obj->last_name = trim($split[1]);
            $obj->email = trim($split[2]);
            $obj->segment = trim($split[3]);
            $obj->company_name = trim($split[4]);
            $obj->phone = trim($split[5]);
            $obj->address = trim($split[6]);

            if($obj->first_name == 'Mack')
              dd($split);
            $output[] = $obj;
        }

        dd($output);
       	return $output;
   }

   /**
    * Return the parsed output as JSON
    * @return JSON
    */
   function getJson()
   {
   		return collect($this->output_data)->toJson();

   }

   /**
    * Return the parsed output as intended
    * @return array An array of objects
    */
   function output() {
   	return $this->output_data;

   }
}