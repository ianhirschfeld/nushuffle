<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Achievement.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['hiddenDeleteSubmit'] == 'true'){ // Data submitted
	
	header("Location: {$config['baseurl']}/home");
	exit();
	
}else{ // No post data found

	header("Location: {$config['baseurl']}/home");
	exit();

}
?>