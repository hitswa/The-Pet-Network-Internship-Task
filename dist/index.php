<?php

session_start();

?><!DOCTYPE HTML>
<html>
<head>
	<title>The pet network</title>

	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<style type="text/css">
		.image {
			border:5px solid #ccc;
			height:128px;width:128px;
		}
		.communication {
			color:#666;
		}
		.post {
			text-align: justify;
			border:1px solid black;
			padding:10px;
			background: white;
			height: 142px; width: 545px;
		}
		.loader {
			width: 100px;
			height: 100px;
			position: absolute;
			top:20px;
			bottom: 0;
			left: 0;
			right: 0;
			margin: auto;
		}
	</style>

</head>
<body>


	<header class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-2">
				<h1>THE PET NETWORK</h1>
			</div>
		</div><br>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				
				<div class="row  well">
					<div class="col-md-3">
						<image>
							<img class="image" src="<?php echo isset($_SESSION['image']) ? $_SESSION['image'] : 'assets/img/empty-user-photo.png' ?>">
						</image>
					</div>
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-12">
								<h2><i class="fa fa-user"></i> <span class="name"><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : '' ?></span> <small><span class="gender"><?php

								if(isset($_SESSION['gender'])) {
									if($_SESSION['gender']=='male') {
										echo '<i class="fa fa-mars"></i>';
									} else {
										echo '<i class="fa fa-venus"></i>';
									}
								}

								?></span></small></h2>
							</div>
						</div>
						<div class="row communication">
							<div class="col-md-12">
								<i class="fa fa-envelope"></i> <span class="email"><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : '' ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class="fa fa-phone"></i> <span class="phone"><?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : '' ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class="fa fa-mobile"></i> <span class="cell"><?php echo isset($_SESSION['cell']) ? $_SESSION['cell'] : '' ?></span>
							</div>
						</div>
						<div class="row" style="color:#666">
							<div class="col-md-12">
								<i class="fa fa-map-marker"></i> <span class="address"><?php echo isset($_SESSION['address']) ? $_SESSION['address'] : '' ?></span>
							</div>
						</div>
						<div class="row" style="color:#666">
							<div class="col-md-12">
								<i class="fa fa-birthday-cake"></i> <span class="dob"><?php echo isset($_SESSION['dob']) ? $_SESSION['dob'] : '' ?></span>
							</div>
						</div>
					</div>

				</div>

			</div>
			<div class="col-md-2"></div>			
		</div>
	</header><br>

	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 well">
				<p class="post">
					<?php echo isset($_SESSION['joke']) ? $_SESSION['joke'] : '<div class="loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>' ?>
				</p>
				<div class="row">
					<div class="col-md-12" style="color:#777">
						<b>Total Likes:</b> <span class="likes_count"><?php echo isset($_SESSION['likes_count']) ? $_SESSION['likes_count'] : '0' ?></span>
						<?php

						if( isset($_SESSION['status']) && ( $_SESSION['status']=='unliked' || $_SESSION['status'] == 'disliked' ) ) {
							$uid = $_SESSION['user_id'];
							echo "<button class='btn pull-right likeButton btn-info' data-id='$uid'><i class='fa fa-thumbs-up'></i> Like</button>";
						} else if( isset($_SESSION['status']) && $_SESSION['status']=='liked' ) {
							$uid = $_SESSION['user_id'];
							echo "<button class='btn pull-right likeButton btn-danger' data-id='$uid'><i class='fa fa-thumbs-down'></i> Unlike</button>";
						} else {
							echo "<button class='btn pull-right likeButton btn-info' data-id=''><i class='fa fa-thumbs-up'></i> Like</button>";
						}

						?>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/all.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-notify.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){

			var user_id;



			<?php if(!isset($_SESSION['user_id'])) : ?>

				$.notify({ message: 'Welcome' },{ type: 'info' });
				console.log('welcome');

				$('.likeButton').addClass('btn-disabled');

				$.post('php/api.php?action=new_user', function(res){

					// console.log(res);

					if(res.success) {
						user_id = res.data.user_id;
						$('.likeButton').data('id',user_id);
						$('.image').attr('src',res.data.image);
						$('.name').html(res.data.name);
						$('.email').html(res.data.email);
						$('.cell').html(res.data.cell);
						$('.phone').html(res.data.phone);
						$('.address').html(res.data.address);
						$('.dob').html(res.data.dob);
						$('.loader').css('display','none');
						$('.post').html(res.data.joke1);
						$('.likes_count').html(res.data.likes_count);
						$('.likeButton').removeClass('btn-disabled');	
						$.notify({ message: 'New user created' },{ type: 'success' });
						// console.log('New user created');



					} else {
						$.notify({ message: 'Fails to create a new user, try again' },{ type: 'danger' });
					}	

				});

			<?php else : ?>

				$.notify({ message: 'Welcome back' },{ type: 'info' });
				console.log('welcome back');

			<?php endif; ?>

			var state = "<?php if( isset($_SESSION['status']) && ( $_SESSION['status']=='unliked' || $_SESSION['status'] == 'disliked' ) ) {
							echo 'unliked';
						} else if( isset($_SESSION['status']) && $_SESSION['status']=='liked' ) {
							echo 'liked';
						} else {
							echo 'unliked';
						}?>";
			
			$('.likeButton').click(function(){
				if(state=="unliked") {
					var likeUrl = 'php/api.php?action=like&user_id=' + $('.likeButton').data('id');
					console.log(likeUrl);
					$.post(likeUrl, function(res){

						console.log(res);
						
						if(res.success) {
							$('.post').html(res.data.joke);
							$('.likes_count').html(res.data.likes_count);
							$('.likeButton').html('<i class="fa fa-thumbs-down" aria-hidden="true"></i> Unlike');
							$('.likeButton').removeClass( 'btn-info' );
							$('.likeButton').addClass( 'btn-danger' );
							$.notify({ message: 'Post liked' },{ type: 'success' });
							console.log('liked')
							state = 'liked';
						} else {
							$.notify({ message: 'Try again' },{ type: 'danger' });
						}

					});
					
				} else if(state=="liked") {
					var dislikeUrl = 'php/api.php?action=dislike&user_id=' + $('.likeButton').data('id');
					console.log(dislikeUrl);
					$.post(dislikeUrl, function(res){

						console.log(res);
						
						if(res.success) {
							$('.post').html(res.data.joke);
							$('.likes_count').html(res.data.likes_count);
							$('.likeButton').html('<i class="fa fa-thumbs-up" aria-hidden="true"></i> Like');
							$('.likeButton').removeClass( 'btn-danger' );
							$('.likeButton').addClass( 'btn-info' );

							$.notify({ message: 'Post disliked' },{ type: 'success' });
							console.log('disliked')
							state = 'unliked';
						} else {
							$.notify({ message: 'Try again' },{ type: 'danger' });
						}

					});
				}
			});
		});
	</script>
	
</body>
</html>