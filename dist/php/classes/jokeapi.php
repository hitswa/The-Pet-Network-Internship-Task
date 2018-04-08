<?php

class Jokeapi {
	function generateNewJoke() {
		// fetch joke
		$jokeAPI = 'http://api.icndb.com/jokes/random';
		$data = array();

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'GET',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		
		$result1 = file_get_contents($jokeAPI, false, $context);
		if ($result1 === FALSE) { /* Handle error */ }
		$joke = json_decode($result1,true);

		if( count($joke['value']['categories']) != 0 ) {
			return '<b>'.strtoupper($joke['value']['categories'][0]).' JOKE:</b> ' . $joke['value']['joke'];
		} else {
			return '<b>JOKE:</b> ' . $joke['value']['joke'];
		}


	}
}