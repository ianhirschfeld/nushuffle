<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['submit'] == 'Join'){ // Post correctly submitted
	
	/* ---------- Collect Data ---------- */
	$fname = $_POST['first_name'];
	$lname = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['password_confirm'];
	if(	!empty($_POST['birthday_month']) &&
		!empty($_POST['birthday_day']) &&
		!empty($_POST['birthday_year']))
		$bday = $_POST['birthday_year'].'-'.$_POST['birthday_month'].'-'.$_POST['birthday_day'];
	$gender = $_POST['gender'];
	
	$_SESSION['submit'] = $_POST;
	
	/* ---------- Validate Data ---------- */
	if( empty($fname) || $fname == 'First Name' ||
		empty($lname) || $lname == 'Last Name' ||
		empty($email) || $email == 'Email' ||
		empty($password) || $password == 'Password' ||
		empty($passwordConfirm) || $passwordConfirm == 'Confirm Password'){ // Required fields empty
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Please fill out First Name, Last Name, Email, Password, and Confirm Password.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/join");
		exit();
	}
	
	if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Email format valid
		$regex = "/^(.+)@(husky\.)*(neu\.edu)/";
		if(!preg_match($regex, $email)){ // Email not Northeastern (ends in @husky.neu.edu or @neu.edu)
			$notif_args = array(
				'type' => 'error',
				'message' => 'Whoops! You must have a valid Northeastern email address.'
			);
			$_SESSION['notifications'] = array(new Notification($notif_args));
			header("Location: {$config['baseurl']}/join");
			exit();
		}else{ // Check if email is already in use
			$result = $config['database']->query("
				SELECT id
				FROM nuusers
				WHERE email = '$email'
				LIMIT 1
			");
			if($result->num_rows){ // Email already registered
				$notif_args = array(
					'type' => 'error',
					'message' => 'Whoops! Your email address is already registered.'
				);
				$_SESSION['notifications'] = array(new Notification($notif_args));
				header("Location: {$config['baseurl']}/join");
				exit();
			}
		}
	}else{ // Email format invalid
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Invalid email address.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/join");
		exit();
	}
	
	if(strlen($password) < 4){ // Password too short
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Password must be four (4) characters or longer.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/join");
		exit();
	}
	
	if($password != $passwordConfirm){ // Password and Confirm Password do not match
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Password and Confirm Password did not match.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/join");
		exit();
	}
	
	/* ---------- Create User ---------- */
	$create_args = array(
		'first_name' => $fname,
		'last_name' => $lname,
		'email' => $email,
		'password' => $password,
		'birthday' => $bday,
		'gender' => $gender
	);
	
	foreach($create_args as $key => $val){
		if(empty($val))
			unset($create_args[$key]);
	}
	
	$user = new User();
	$create = $user->create($create_args, true);
	
	if($create){ // Successfully created user
		unset($_SESSION['submit']);
		header("Location: {$config['baseurl']}/verify");
		exit();
	}else{ // Error creating user
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! An error has occurred while creating your account, please try again.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/join");
		exit();
	}
	
}else{ // No post data found
	
	header("Location: {$config['baseurl']}/join");
	exit();
	
}
?>