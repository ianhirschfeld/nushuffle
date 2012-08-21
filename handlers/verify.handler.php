<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_REQUEST['vid']) && !empty($_REQUEST['vid'])){ // Verify code URL
	$vid = $_REQUEST['vid'];
}elseif(isset($_POST['submit']) && $_POST['submit'] == 'Verify'){ // Verify code submitted
	$vid = $_POST['verify'];
}else{ // No verify code
	$vid = null;
}

if($vid){ // Verify code found
	
	$result = $config['database']->query("
		SELECT user_id
		FROM nuuserverify
		WHERE hash = '$vid'
		LIMIT 1
	");
	
	if($result->num_rows){ // Hash found
		$row = $result->fetch_assoc();
		
		$update = $config['database']->query("
			UPDATE nuusers
			SET date_joined = NOW()
			WHERE id = {$row['user_id']}
			LIMIT 1
		");
		
		if($update){
			$config['database']->query("
				DELETE FROM nuuserverify
				WHERE hash = '$vid'
				LIMIT 1
			");
		}
		
		$_SESSION['user'] = new User(array('id' => $row['user_id']));
		$_SESSION['user']->login();
		
		header("Location: {$config['baseurl']}/verified");
		exit();
	}else{ // Hash not found
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Invalid verification code entered.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/verify");
		exit();
	}
}else{ // No verify code

	header("Location: {$config['baseurl']}/verify");
	exit();
	
}
?>