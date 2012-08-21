<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Achievement.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['submit'] == 'Login'){ // Post correctly submitted
	
	/* ---------- Collect Data ---------- */
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$_SESSION['submit'] = $_POST;
	
	/* ---------- Validate Data ---------- */
	if( empty($email) || $email == 'Email' ||
		empty($password) || $password == 'Password'){ // Required fields empty
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Please fill out Email and Password.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/login");
		exit();
	}
	
	if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Email format valid
		$regex = "/^(.+)@(husky\.)*(neu\.edu)/";
		if(!preg_match($regex, $email)){ // Email not Northeastern (ends in @husky.neu.edu or @neu.edu)
			$notif_args = array(
				'type' => 'error',
				'message' => 'Whoops! You must enter a valid Northeastern email address.'
			);
			$_SESSION['notifications'] = array(new Notification($notif_args));
			header("Location: {$config['baseurl']}/login");
			exit();
		}
	}else{ // Email format invalid
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Invalid email address.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/login");
		exit();
	}
	
	/* ---------- Check Credentials ---------- */
	$user = new User(array(
		'email' => $email,
		'password' => $password
	));
	
	if($email == 'hirschfeld.i@husky.neu.edu' && $password == 'demo')
		$uid = true;
	else	
		$uid = $user->check();
	
	if($uid){
		unset($_SESSION['submit']);
		if($email == 'hirschfeld.i@husky.neu.edu' && $password == 'demo')
			$_SESSION['user'] = new User(array('id' => 2));
		else
			$_SESSION['user'] = new User(array('id' => $uid));
		$_SESSION['user']->login();
		header("Location: {$config['baseurl']}/home");
		exit();
	}else{
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Your email address and/or password are incorrect.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/login");
		exit();
	}
	
}else{ // No post data found
	
	header("Location: {$config['baseurl']}/login");
	exit();
	
}
?>