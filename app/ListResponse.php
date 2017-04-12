<?php namespace App;


class ListResponse {

   public $input_data;

   private $output_data = [];

   function __construct($data) {
      $this->input_data = trim($data);
      $this->output_data = $this->parseCSVString($this->input_data);
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
            $obj->name = trim($split[0]);
            $obj->email = trim($split[1]);

            $output[] = $obj;
        }

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