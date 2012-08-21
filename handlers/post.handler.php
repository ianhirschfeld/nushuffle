<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../facebook/facebook.config.php';
include_once '../oauth/twitteroauth/twitteroauth.php';
include_once '../classes/User.class.php';
include_once '../classes/Department.class.php';
include_once '../classes/Rating.class.php';
include_once '../classes/Achievement.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['hiddenSubmit'] == 'true'){ // Data submitted

	$r = new Rating();

	$type = $_POST['hiddenType'];
	$deptNames = $_POST['departments'];
	$comment = $_POST['comment'];
	$rating = $_POST['hiddenRating'];
	if($_SESSION['user']->options['anonymous'])
		$enabled = 0;
	else
		$enabled = 1;
	$depts = array();
	$dids = array();
	
	$socialDepts = '';
	$socialImg = '';
	
	foreach($deptNames as $deptName){ // Gather filled in departments
		if($deptName != '' && $deptName != 'start typing a college, department, or office')
			$depts[] = $deptName;
	}
	
	if(count($depts) < 1){ // No department filled out
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Please type in at least one college, department, or office.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}{$_POST['hiddenReturn']}");
		exit();
	}
	
	if($comment == '' || $comment == 'comments'){ // No comment filled out
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Please type in your comments.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}{$_POST['hiddenReturn']}");
		exit();
	}
	
	if(!$rating){
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Please select your rating.'
		);
		$_SESSION['notifications'] = array(new Notification($notif_args));
		header("Location: {$config['baseurl']}{$_POST['hiddenReturn']}");
		exit();
	}
	
	foreach($depts as $dept){ // Get department ids from name
		if(!get_magic_quotes_gpc())
			$dName = $config['database']->real_escape_string($dept);
		else
			$dName = $dept;
			
		$result = $config['database']->query("
			SELECT id
			FROM nudepartments
			WHERE name = '$dName'
			LIMIT 1
		");
		
		if($result->num_rows){ // Department found
			$row = $result->fetch_assoc();
			$dids[] = $row['id'];
		}else{ // New department
			$d = new Department();
			$create = $d->create(array('name' => $dName));
			if($create)
				$dids[] = $d->last_id;
		}
	}
	
	$shuffle = json_encode($dids);
	$insert = $r->insert(array(
		'table' => 'nushuffles',
		'data' => array(
			'user_id' => $_SESSION['user']->id,
			'dept_ids' => $shuffle,
			'enabled' => $enabled
		)
	));
	foreach($dids as $did){
		$create = $r->create(array(
			'dept_id' => $did,
			'user_id' => $_SESSION['user']->id,
			'shuffle_id' => $r->last_id,
			'type' => $type,
			'score' => $rating,
			'comment' => $comment,
			'enabled' => $enabled
		));
		
		$temp_d = new Department(array('id' => $did));
		if($dids[count($dids)-1] == $did){
			$socialDepts .= $temp_d->name;
		}else{
			$socialDepts .= $temp_d->name.', ';
		}
	}

	if($create && $insert){ // Shuffle and rating correctly entered into database
		$count = count($dids);
		$curPoints = $_SESSION['user']->level_pts;
		$points = $_SESSION['user']->level_pts;
		switch($count){
			case 1:
				$socialImg = $config['imagesurl'].'/'.'social_one_star.jpg';
				$points += RATE_DEPT_1;
				break;
			case 2:
				$socialImg = $config['imagesurl'].'/'.'social_two_stars.jpg';
				$points += RATE_DEPT_2;
				break;
			case 3:
				$socialImg = $config['imagesurl'].'/'.'social_three_stars.jpg';
				$points += RATE_DEPT_3;
				break;
			case 4:
				$socialImg = $config['imagesurl'].'/'.'social_four_stars.jpg';
				$points += RATE_DEPT_4;
				break;
			default:
				$socialImg = $config['imagesurl'].'/'.'social_five_stars.jpg';
				$points += RATE_DEPT_CAP;
				break;
		}
		
		switch($rating){
			case 1:
				$socialImg = $config['imagesurl'].'/'.'social_one_star.jpg';
				break;
			case 2:
				$socialImg = $config['imagesurl'].'/'.'social_two_stars.jpg';
				break;
			case 3:
				$socialImg = $config['imagesurl'].'/'.'social_three_stars.jpg';
				break;
			case 4:
				$socialImg = $config['imagesurl'].'/'.'social_four_stars.jpg';
				break;
			default:
				$socialImg = $config['imagesurl'].'/'.'social_five_stars.jpg';
				break;
		}
		
		if($_POST['shareFb'] == 'true'){ // Post to facebook
			if($_SESSION['user']->fbid && $_SESSION['user']->fb_can_publish){
				$options = array(
					'name' => 'Start Playing',
					'link' => $config['baseurl']
				);
				$statusUpdate = $fb->api('me/feed', 'post', array(
					'message' => $comment,
					'picture' => $socialImg,
					'link' => $config['baseurl'].'/department/'.$dids[0],
					'name' => 'I rated '.$depts[0],
					'caption' => "I just rated $socialDepts at Northeastern for badges, points, and free stuff on Northeastern Shuffle!",
					'actions' => json_encode($options)
				));
			}
			$points += FACEBOOK_POST;
		}
		
		if($_POST['shareTw'] == 'true'){ // Post to twitter
			if($_SESSION['user']->options['twitter']){
				$ct = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['user']->options['twitter']['oauth_token'], $_SESSION['user']->options['twitter']['oauth_token_secret']);
				$tail = ' http://northeasternshuffle.com #Northeastern #NUshuffle';
				if(strlen($comment.$tail) <= 140){
					$ct->post('statuses/update', array('status' => $comment.$tail));
				}else{
					$shortenlen = 140-strlen($tail)-3;
					$shortcomment = substr($comment,0,$shortenlen).'...';
					$ct->post('statuses/update', array('status' => $shortcomment.$tail));
				}
			}
			$points += TWITTER_POST;
		}
		
		$update = $_SESSION['user']->update(array('level_pts' => $points));
		if(!$_SESSION['user']->options['anonymous'])
			$_SESSION['user']->update(array('prize_pts' => $points));
		
		if($update){ // Point update successful
			switch($rating){
				case 1:
					$_SESSION['user']->post_counts['one_star']++;
					break;
				case 2:
					$_SESSION['user']->post_counts['two_stars']++;
					break;
				case 3:
					$_SESSION['user']->post_counts['three_stars']++;
					break;
				case 4:
					$_SESSION['user']->post_counts['four_stars']++;
					break;
				case 5:
					$_SESSION['user']->post_counts['five_stars']++;
					break;
			}
			$_SESSION['user']->post_counts['total']++;
			
			$_SESSION['post_data'] = $_POST;
			$_SESSION['post_data']['dids'] = $dids;
			$_SESSION['post_data']['points_earned'] = $points-$curPoints;
			
			header("Location: {$config['baseurl']}/handlers/badge.check.handler.php");
			exit();
		}else{ // Point update failed
			$notif_args = array(
				'type' => 'error',
				'message' => 'Whoops! Something went wrong updating your account.'
			);
			$_SESSION['notifications'] = array(new Notification($notif_args));
			header("Location: {$config['baseurl']}{$_POST['hiddenReturn']}");
			exit();
		}
	}else{ // Error submitting to database
		$notif_args = array(
			'type' => 'error',
			'message' => 'Whoops! Something went wrong submitting your rating. Please try again.'
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