<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../facebook/facebook.config.php';
include_once '../oauth/twitteroauth/twitteroauth.php';
include_once '../classes/User.class.php';
include_once '../classes/Achievement.class.php';
include_once '../classes/Notification.class.php';

session_start();

if(isset($_SESSION['post_data']) && !empty($_SESSION['post_data'])){ // Post data found

	$post = $_SESSION['post_data'];
	unset($_SESSION['post_data']);

}else{ // No post data found

	header("Location: {$config['baseurl']}/home");
	exit();

}

if(!empty($post)){ // Post data still found

	$badgesWon = array();
	$_SESSION['notifications'] = array();
	
	// Check post count total
	if($_SESSION['user']->post_counts['total'] == 80){ $badgesWon[] = 41; } // number of posts = 80 on fire
	elseif($_SESSION['user']->post_counts['total'] == 55){ $badgesWon[] = 26; } // = 55 spades
	elseif($_SESSION['user']->post_counts['total'] == 30){ $badgesWon[] = 17; } // = 30 hearts
	elseif($_SESSION['user']->post_counts['total'] == 15){ $badgesWon[] = 13; } // = 15 clubs
	elseif($_SESSION['user']->post_counts['total'] == 5){ $badgesWon[] = 15; } // = 5 diamonds
	
	// Check department iss
	foreach($post['dids'] as $did){
		if($did == 23){ $badgesWon[] = 5; } // campus recreation
		elseif($did == 59){ $badgesWon[] = 25; } // cba
		elseif($did == 78){ $badgesWon[] = 6; } // nu athletics
		elseif($did == 82){ $badgesWon[] = 21; } // osccr
		elseif($did == 87){ $badgesWon[] = 19; } // library
		elseif($did == 134){ $badgesWon[] = 29; } // mbta
		elseif($did == 136){ $badgesWon[] = 16; } // dodge hall
		elseif($did == 150){ $badgesWon[] = 38; } // snowpocalypse 2011
		elseif($did == 152){ $badgesWon[] = 39; } // nu shuffle.com
	}
	
	if($post['hiddenRating'] >= 4){ // Postive post (4 or 5 stars)
	
		$positive = $_SESSION['user']->post_counts['five_stars'] + $_SESSION['user']->post_counts['four_stars'];
		
		if($positive == 30){ $badgesWon[] = 9; } // brown noser
		elseif($positive == 15){ $badgesWon[] = 12; } // chronic liker
		elseif($positive == 5){ $badgesWon[] = 27; } // suckup
		
		foreach($post['dids'] as $did){
			if($did == 132){ $badgesWon[] = 34; } // NUPD
			elseif($did == 135){ $badgesWon[] = 3; } // afterhours
			elseif($did == 137){ $badgesWon[] = 28; } // I love IV
		}
		
	}elseif($post['hiddenRating'] <= 2){ // Negative post (1 or 2 stars)
	
		$negative = $_SESSION['user']->post_counts['two_stars'] + $_SESSION['user']->post_counts['one_star'];
		
		if($negative == 30){ $badgesWon[] = 23; } // screwed
		elseif($negative == 15){ $badgesWon[] = 11; } // chronic disliker
		
		foreach($post['dids'] as $did){
			if($did == 138){ $badgesWon[] = 7; } // blue plate special
			elseif($did == 128){ $badgesWon[] = 22; } // el presidente
			elseif($did == 139){ $badgesWon[] = 30; } // gift horse (taco bell)
		}
		
		if($post['hiddenType'] == 'Phone Call'){
			if($_SESSION['user']->post_counts['phone_call'] == 10){ $badgesWon[] = 10; } // chatterbox
		}elseif($post['hiddenType'] == 'Website'){
			if($_SESSION['user']->post_counts['website'] == 10){ $badgesWon[] = 18; } // internetz
		}
		
		if(count($post['dids']) >= 4){ $badgesWon[] = 33; } // well traveled
		
		foreach($post['dids'] as $did){ // If first negative post for a department with only at least five positive
			$result = $config['database']->query("
				SELECT id
				FROM nuratings
				WHERE dept_id = $did
					AND (score = 2 OR score = 1)
					AND enabled = 1
			");
			
			if($result->num_rows == 1){ // First negative post
				$result = $config['database']->query("
					SELECT id
					FROM nuratings
					WHERE dept_id = $did
						AND (score = 5 OR score = 4)
						AND enabled = 1
				");
				
				if($result->num_rows >= 5){ // At least five positive posts
					$badgesWon[] = 14; // devil's advocate
				}
			}
		}
		
	}
	
	if(!empty($badgesWon)){ // User achieved at least one badge
		foreach($badgesWon as $bid){
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
		
		if($_SESSION['user']->options['social_badge_default']){ // Publish first badgewin to social networks
			$a = new Achievement(array('id' => $badgesWon[0]));
			
			if($_SESSION['user']->fbid && $_SESSION['user']->fb_can_publish){ // Post to facebook
				$options = array(
					'name' => 'Start Playing',
					'link' => $config['baseurl']
				);
				$statusUpdate = $fb->api('me/feed', 'post', array(
					'picture' => $config['badgesurl'].'/'.$a->image_path,
					'link' => $config['baseurl'],
					'name' => 'New Badge: '.$a->name,
					'caption' => $a->description,
					'description' => "I won a badge for rating Northeastern's service and performance! Earn your own badges, points, and free stuff on Northeastern Shuffle!",
					'actions' => json_encode($options)
				));
			}
			
			if($_SESSION['user']->options['twitter']){ // Post to twitter
				$ct = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['user']->options['twitter']['oauth_token'], $_SESSION['user']->options['twitter']['oauth_token_secret']);
				$ct->post('statuses/update', array('status' => 'I just won the '.$a->name.' badge on #NUshuffle at http://northeasternshuffle.com'));
			}
		}
	}
	
	$notif_args = array(
		'type' => 'success',
		'message' => 'Rating submitted successfully!',
		'points' => $post['points_earned']
	);
	$n = new Notification($notif_args);
	array_unshift($_SESSION['notifications'], $n);
	header("Location: {$config['baseurl']}{$post['hiddenReturn']}");
	exit();
	
}else{ // Post data lost for some strange reason

	header("Location: {$config['baseurl']}/home");
	exit();

}
?>