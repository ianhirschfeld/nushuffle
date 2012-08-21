<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Achievement.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['hiddenSubmit'] == 'true'){ // Data submitted

	$_SESSION['notifications'] = array();
	
	if($_POST['addr1'] == 'Address') $addr1 = '';
	else $addr1 = $_POST['addr1'];
	
	if($_POST['addr2'] == 'Apt or Suite') $addr2 = '';
	else $addr2 = $_POST['addr2'];
	
	if($_POST['city'] == 'City') $city = '';
	else $city = $_POST['city'];
	
	if($_POST['state'] == 'State') $state = '';
	else $state = $_POST['state'];
	
	if($_POST['zip'] == 'Zip') $zip = '';
	else $zip = $_POST['zip'];
	
	if($_POST['socialPostDefault'] == 'true') $socialPost = 1;
	else $socialPost = 0;
	
	if($_POST['socialBadgeDefault'] == 'true'){
		$socialBadge = 1;
	}else{
		$socialBadge = 0;
		$bid = 43; // Spam badge
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
	
	if($_POST['makeAnon'] == 'true'){
		$anon = 1;
		$bid = 37; // Anonymous badge
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
	}else{
		$anon = 0;
	}
	
	$options = $_SESSION['user']->options;
	$options['anonymous'] = $anon;
	$options['social_post_default'] = $socialPost;
	$options['social_badge_default'] = $socialBadge;
	
	$update = $_SESSION['user']->update(array(
		'addr1' => $addr1,
		'addr2' => $addr2,
		'city' => $city,
		'state' => $state,
		'zip' => $zip,
		'options' => $options
	));
	
	if($update){
		$notif_args = array(
			'type' => 'success',
			'message' => 'Settings updated successfully!'
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
	
}else{ // No post data found

	header("Location: {$config['baseurl']}/home");
	exit();

}
?>