<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
require_once '../inc/helper.functions.php';
require_once '../inc/config.php';
require_once '../classes/User.class.php';
require_once '../classes/Achievement.class.php';
require_once '../classes/Notification.class.php';
require_once 'twitteroauth/twitteroauth.php';

session_start();

$_SESSION['notifications'] = array();

if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) { // If the oauth_token is old redirect to the connect page.
	$_SESSION['oauth_status'] = 'oldtoken';
	$notif_args = array(
		'type' => 'error',
		'message' => "Whoops! Looks like that didn't work. Please try again later."
	);
	$_SESSION['notifications'][] = new Notification($notif_args);
	header("Location: {$config['baseurl']}/account");
}

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']); // Create TwitteroAuth object with app key/secret and token key/secret from default phase
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']); // Request access tokens from twitter

if(isset($_REQUEST['t']) && $_REQUEST['t'] == 'del'){ // Remove credentails from db
	$options = $_SESSION['user']->options;
	$options['twitter'] = null;
	$deleted = $_SESSION['user']->update(array('options' => $options));
}else{ // Save credentails to db
	$options = $_SESSION['user']->options;
	$options['twitter'] = $access_token;
	$update = $_SESSION['user']->update(array('options' => $options));

	$bid = 32; // Twitter badge
	if(!$_SESSION['user']->hasAchievement($bid)){ // Badge win
		$insert = $_SESSION['user']->insert(array(
			'table' => 'nuuserachievements',
			'data' => array('achievement_id' => $bid)
		));
		if($insert){
			$a = new Achievement(array('id' => $bid));
			$notif_args = array(
				'type' => 'badge',
				'message' => "New Badge: <strong>{$a->name}</strong>",
				'submessage' => $a->description,
				'image' => $config['badgesurl'].'/'.$a->image_path,
				'image_size' => 50,
				'image_title' => $a->name,
				'image_mirror' => true
			);
			$_SESSION['notifications'][] = new Notification($notif_args);
		}
	}
}

// Remove no longer needed request tokens
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

if (200 == $connection->http_code) { // If HTTP response is 200 continue otherwise send to connect page to retry
	if($update){ // The user has been verified and the access tokens can be saved for future use
		$notif_args = array(
			'type' => 'success',
			'message' => 'Twitter linked successfully!'
		);
		$_SESSION['notifications'][] = new Notification($notif_args);
		header("Location: {$config['baseurl']}/account");
		exit();
	}else{
		$notif_args = array(
			'type' => 'error',
			'message' => "Whoops! Looks like that didn't work. Please try again later."
		);
		$_SESSION['notifications'][] = new Notification($notif_args);
		header("Location: {$config['baseurl']}/account");
		exit();
	}
}elseif($deleted){ // User unlinked twitter
	$notif_args = array(
		'type' => 'success',
		'message' => 'Twitter unlinked successfully!'
	);
	$_SESSION['notifications'][] = new Notification($notif_args);
	header("Location: {$config['baseurl']}/account");
	exit();
}else{ // Save HTTP status for error dialog on connnect page.
	$notif_args = array(
		'type' => 'error',
		'message' => "Whoops! Looks like that didn't work. Please try again later."
	);
	$_SESSION['notifications'][] = new Notification($notif_args);
	header("Location: {$config['baseurl']}/account");
	exit();
}
