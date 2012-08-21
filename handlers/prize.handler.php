<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Achievement.class.php';
include_once '../classes/Prize.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['hiddenSubmit'] == 'true'){ // Data submitted

	$p = new Prize(array('id' => $_POST['hiddenPrize']));
	
	if($_SESSION['user']->prize_pts >= $p->points_req){ // User has enough points
	
		if((!empty($_POST['addr1']) && $_POST['addr1'] != 'Address') &&
			(!empty($_POST['city']) && $_POST['city'] != 'City') &&
			(!empty($_POST['state']) && $_POST['state'] != 'State') &&
			(!empty($_POST['zip']) && $_POST['zip'] != 'Zip')){ // All fields entered
			$insert = $_SESSION['user']->insert(array(
				'table' => 'nuuserprizes',
				'data' => array(
					'prize_id' => $p->id,
					'points_used' => $p->points_req
				)
			));
			$update = $_SESSION['user']->update(array('prize_pts' => $_SESSION['user']->prize_pts - $p->points_req));
			
			$to = 'support@northeasternshuffle.com';
			$from = 'redeem@northeasternshuffle.com';
			$subject = 'Prize Redeemed '.date('Y-m-d H:i:s').': '.$p->name;
			
			$body = 'Timestamp: '.date('Y-m-d H:i:s')."\n\n";
			$body .= 'Prize ID/Name: '.$p->id.' - '.$p->name."\n";
			$body .= 'User ID: '.$_SESSION['user']->id."\n\n";
			$body .= 'User Name/Address:'."\n\n";
			$body .= $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name."\n";
			$body .= $_POST['addr1'].' '.$$_POST['addr2']."\n";
			$body .= $_POST['city'].', '.$_POST['state'].' '.$_POST['zip']."\n\n";
			$body .= 'Sincerely,'."\n";
			$body .= 'The Automated Email System';
			
			$headers = "From: $from\r\n";
			$headers .= "Reply-To: $from\r\n";
			$headers .= "X-Mailer: PHP/" . phpversion();
			
			if($insert && update)
				$send = mail($to, $subject, $body, $headers);
			
			if($send){ // Everything worked out
				/*if($prize->__get('ID') == 1)
					$_SESSION['prize_boloco'] = true;*/
				$notif_args = array(
					'type' => 'success',
					'message' => 'Prize redeemed successfully! Please allow 1-2 weeks for delivery.'
				);
				$_SESSION['notifications'] = array(new Notification($notif_args));
				header("Location: {$config['baseurl']}/home");
				exit();
			}else{ // Something went wrong
				$notif_args = array(
					'type' => 'error',
					'message' => 'Whoops! An error submitting your prize request has occured.'
				);
				$_SESSION['notifications'] = array(new Notification($notif_args));
				header("Location: {$config['baseurl']}/prize/{$p->id}");
				exit();
			}
		}else{ // Missing fields
			$notif_args = array(
				'type' => 'error',
				'message' => 'Whoops! Please fill out Address, City, State, and Zip.'
			);
			$_SESSION['notifications'] = array(new Notification($notif_args));
			header("Location: {$config['baseurl']}/prize/{$p->id}");
			exit();
		}
		
	}else{ // User does not have enough points
	
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! You do not have enough points to redeem this prize.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}/prize/{$p->id}");
		exit();
		
	}
	
}else{ // No post data found

	header("Location: {$config['baseurl']}/home");
	exit();

}
?>