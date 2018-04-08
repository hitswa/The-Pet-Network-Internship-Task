<?php

require_once 'classes/class.functions.php';
require_once 'classes/class.utilities.php';
require_once 'classes/randomuser.php';
require_once 'classes/jokeapi.php';

session_start();

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if(!empty($action)) {

	switch ($action) {
		case 'new_user':

			// generate a random user
			$randomUser = new Randomuser;
			$user = $randomUser->generateNewUser();

			// generate random likes
			$utility = new Utility;
			$likes = $utility->generateRandomString('numaric',3);

			// generate two jokes
			$joke = new Jokeapi;
			$joke1 = $joke->generateNewJoke();
			$joke2 = $joke->generateNewJoke();

			// enter the value of user in user_master tabel and get user_id
			$fields = array(
				'id' 			=> 'null', 
				'name' 			=> '\''.$user['name'].'\'',
				'gender' 		=> '\''.$user['gender'].'\'',
				'email' 		=> '\''.$user['email'].'\'',
				'cell' 			=> '\''.$user['cell'].'\'',
				'phone' 		=> '\''.$user['phone'].'\'',
				'image' 		=> '\''.$user['image'].'\'',
				'dob' 			=> '\''.$user['dob'].'\'',
				'address' 		=> '\''.$user['address'].'\'',
				'likes_count'	=> '\''.$likes.'\'',
				);
				
			$inserted= MySql::insertData('user_master',$fields);

			if($inserted['success']) {

				$user_id = $inserted['id'];

				$fields = array(
							'id' 			=> 'null', 
							'user_id' 		=> '\''.$user_id.'\'',
							'joke_id' 		=> '\'1\'',
							'joke' 			=> '\''.$joke1.'\'',
							);

				$joke1Inserted= MySql::insertData('jokes',$fields);

				$fields = array(
							'id' 			=> 'null', 
							'user_id' 		=> '\''.$user_id.'\'',
							'joke_id' 		=> '\'2\'',
							'joke' 			=> '\''.$joke2.'\'',
							);

				$joke2Inserted= MySql::insertData('jokes',$fields);


				if( $joke1Inserted['success'] && $joke2Inserted['success'] ) {

					// setting up session keys
					$_SESSION['user_id']	= $inserted['id'];
					$_SESSION['name'] 		= $user['name'];
					$_SESSION['gender'] 	= $user['gender'];
					$_SESSION['email'] 		= $user['email'];
					$_SESSION['cell'] 		= $user['cell'];
					$_SESSION['phone'] 		= $user['phone'];
					$_SESSION['image'] 		= $user['image'];
					$_SESSION['dob'] 		= $user['dob'];
					$_SESSION['address']	= $user['address'];
					$_SESSION['image']		= $user['image'];
					$_SESSION['status']		= 'unliked';
					$_SESSION['likes_count']= $likes;
					$_SESSION['joke']		= $joke1;

					$data = array(
							'user_id' 		=> $user_id, 
							'name' 			=> $user['name'],
							'gender' 		=> $user['gender'],
							'email' 		=> $user['email'],
							'cell' 			=> $user['cell'],
							'phone' 		=> $user['phone'],
							'image' 		=> $user['image'],
							'dob' 			=> $user['dob'],
							'address' 		=> $user['address'],
							'likes_count'	=> $likes,
							'joke1'			=> $joke1,
							'joke2'			=> $joke2,
							);

					$arr = array(
							'success'	=>	1,
							'data'		=>	$data,
							'error'		=>	null,
							'message'	=>	'success',
							'code'		=>	'001',
							);

					header('Content-Type: application/json');
					echo json_encode($arr);
					exit();

				} else {

					$arr = array(
							'success'	=>	0,
							'data'		=>	null,
							'error'		=>	null,
							'message'	=>	'not able to insert jokes in database',
							'code'		=>	'002',
							);
					header('Content-Type: application/json');
					echo json_encode($arr);
					exit();
				}
			} else {

				$arr = array(
						'success'	=>	0,
						'data'		=>	null,
						'error'		=>	$inserted['error'],
						'message'	=>	'not able to insert new user in database',
						'code'		=>	'003',
						);
				header('Content-Type: application/json');
				echo json_encode($arr);
				exit();	
			}

			break;
		
		case 'like':
			$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';

			if( !empty($user_id) ) {

				// increment the like value
				$fields = array(
							'likes_count' 	=> '`likes_count` + 1',
							);
				$condition = array(
							'id' 	=> '\''.$user_id.'\'',
							);
				$updated = MySql::updateData('user_master',$fields,$condition);

				if($updated['success']) {

					$qry = "SELECT um.`likes_count`,j.`joke`
							FROM `user_master` AS um
							LEFT JOIN `jokes` AS j
							ON um.`id`=j.`user_id`
							WHERE um.`id`='".$user_id."'
							AND j.`joke_id`='1';";
					$result = MySql::fetchRow($qry);

					if($result['success']) {

						// setting latest values in session
						$_SESSION['likes_count']= $result['data']['likes_count'];
						$_SESSION['joke']		= $result['data']['joke'];
						$_SESSION['status']		= 'liked';

						$data = array(
								'likes_count' 	=> $result['data']['likes_count'],
								'joke' 			=> $result['data']['joke'],
								);
						$arr = array(
								'success'	=>	1,
								'data'		=>	$data,
								'error'		=>	null,
								'message'	=>	'success',
								'code'		=>	'004',
								);
						header('Content-Type: application/json');
						echo json_encode($arr);
						exit();

					} else {
						$arr = array(
								'success'	=>	0,
								'data'		=>	null,
								'error'		=>	$result['error'],
								'message'	=>	'error in fetching data',
								'code'		=>	'005',
								);
						header('Content-Type: application/json');
						echo json_encode($arr);
						exit();
					}

				} else {
					$arr = array(
							'success'	=>	0,
							'data'		=>	null,
							'error'		=>	null,
							'message'	=>	'error in incrementing likes',
							'code'		=>	'006',
							);
					header('Content-Type: application/json');
					echo json_encode($arr);
					exit();
				}

			} else {
				$arr = array(
						'success'	=>	0,
						'data'		=>	null,
						'error'		=>	null,
						'message'	=>	'required data missing',
						'code'		=>	'007',
						);
				header('Content-Type: application/json');
				echo json_encode($arr);
				exit();
			}

			break;

		case 'dislike':
			$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';

			if( !empty($user_id) ) {

				// decrement the like value
				$fields = array(
							'likes_count' 	=> '`likes_count` - 1',
							);
				$condition = array(
							'id' 	=> '\''.$user_id.'\'',
							);
				$updated = MySql::updateData('user_master',$fields,$condition);

				if($updated['success']) {

					$qry = "SELECT um.`likes_count`,j.`joke`
							FROM `user_master` AS um
							LEFT JOIN `jokes` AS j
							ON um.`id`=j.`user_id`
							WHERE um.`id`='".$user_id."'
							AND j.`joke_id`='2';";
					$result = MySql::fetchRow($qry);

					if($result['success']) {

						// setting latest values in session
						$_SESSION['likes_count']= $result['data']['likes_count'];
						$_SESSION['joke']		= $result['data']['joke'];
						$_SESSION['status']		= 'disliked';

						$data = array(
								'likes_count' 	=> $result['data']['likes_count'],
								'joke' 			=> $result['data']['joke'],
								);
						$arr = array(
								'success'	=>	1,
								'data'		=>	$data,
								'error'		=>	null,
								'message'	=>	'success',
								'code'		=>	'008',
								);
						header('Content-Type: application/json');
						echo json_encode($arr);
						exit();

					} else {
						$arr = array(
								'success'	=>	0,
								'data'		=>	null,
								'error'		=>	$result['error'],
								'message'	=>	'error in fetching data',
								'code'		=>	'009',
								);
						header('Content-Type: application/json');
						echo json_encode($arr);
						exit();
					}

				} else {
					$arr = array(
							'success'	=>	0,
							'data'		=>	null,
							'error'		=>	null,
							'message'	=>	'error in decrementing likes',
							'code'		=>	'010',
							);
					header('Content-Type: application/json');
					echo json_encode($arr);
					exit();
				}

			} else {
				$arr = array(
						'success'	=>	0,
						'data'		=>	null,
						'error'		=>	null,
						'message'	=>	'required data missing',
						'code'		=>	'011',
						);
				header('Content-Type: application/json');
				echo json_encode($arr);
				exit();
			}
			break;

		default:
			$arr = array(
					'success'	=>	0,
					'data'		=>	null,
					'error'		=>	null,
					'message'	=>	'no such api found',
					'code'		=>	'012',
					);
			header('Content-Type: application/json');
			echo json_encode($arr);
			exit();
			break;
	}

} else {
	$arr = array(
			'success'	=>	0,
			'data'		=>	null,
			'error'		=>	null,
			'message'	=>	'require data missing',
			'code'		=>	'013',
			);
	header('Content-Type: application/json');
	echo json_encode($arr);
	exit();
}