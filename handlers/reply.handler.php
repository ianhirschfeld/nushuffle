<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Department.class.php';
include_once '../classes/Rating.class.php';
include_once '../classes/Achievement.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['hiddenSubmit'] == 'true'){ // Data submitted

	$r = new Rating(array('id' => $_POST['hiddenPostId']));
	
	$comment = $_POST['comment'];
	if($_SESSION['user']->options['anonymous'])
		$enabled = 0;
	else
		$enabled = 1;
	
	if($comment == '' || $comment == 'comments'){ // No comment filled out
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Please type in your comments.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}{$_POST['hiddenReturn']}");
		exit();
	}
	
	$insert = $r->insert(array(
		'table' => 'nureplies',
		'data' => array(
			'user_id' => $_SESSION['user']->id,
			'rating_id' => $r->id,
			'comment' => $comment,
			'enabled' => $enabled
		)
	));

	if($insert){ // Reply correctly entered into database
		$notif_args = array(
			'type' => 'success',
			'message' => 'Reply submitted successfully!'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}{$_POST['hiddenReturn']}");
		exit();
	}else{ // Error submitting to database
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Something went wrong submitting your reply. Please try again.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}{$_POST['hiddenReturn']}");
		exit();
	}
	
}else{ // No post data found

	header("Location: {$config['baseurl']}/home");
	exit();

}
?>