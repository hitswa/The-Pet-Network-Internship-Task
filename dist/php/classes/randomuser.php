<?php

class Randomuser {
	function generateNewUser() {
		// fetch the details of new user
			$randomuserAPI = 'https://randomuser.me/api/';
			$data = array();

			$options = array(
			    'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'GET',
			        'content' => http_build_query($data)
			    )
			);
			$context  = stream_context_create($options);
			$result = @file_get_contents($randomuserAPI, false, $context);
			if ($result === FALSE) { /* Handle error */ }
			
			$data = json_decode($result,true);
			$data=$data['results'][0];

			$_name	= strtoupper($data['name']['title'] . '. ' . $data['name']['first'] . ' ' . $data['name']['last']);
			$_gender = $data['gender'];
			$_email 	= $data['email'];
			$_cell 	= $data['cell'];
			$_phone 	= $data['phone'];
			$_image 	= $data['picture']['large'];

			$dob = $data['dob'];
			$dateArray = explode( "-", explode(" ",$dob)[0] );
			$months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
			$_dob = $dateArray[2] . ', ' . $months[intval($dateArray[1])-1] . ' ' . $dateArray[0];

			$location = $data['location'];

			$_address = $location['street'] . ', ' . $location['city'] . ', ' . $location['state'] . ', ' . $location['postcode'];

			$arr = array(
					"name"		=>	$_name,
					"gender"	=>	$_gender,
					"image"		=>	$_image,
					"email"		=>	$_email,
					"cell"		=>	$_cell,
					"phone"		=>	$_phone,
					"dob"		=>	$_dob,
					"address"	=>	$_address,
					);
			return $arr;
	}
}