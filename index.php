<?php
// Include necessary files
require 'inc/helper.functions.php';
require 'inc/config.php';
require 'facebook/facebook.config.php';
require 'classes/Department.class.php';
require 'classes/Rating.class.php';
require 'classes/Achievement.class.php';
require 'classes/Prize.class.php';
require 'classes/User.class.php';
require 'classes/Notification.class.php';

session_start();

/* ---------- User Login ---------- */
if($fbme){ // Check for FB login
	if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
		//$theuser = new User(array('fbid' => $fbme['id']));
		//$theuser->login(true);
		
		//$_SESSION['user'] = $theuser->getData();
		
		$_SESSION['user'] = new User(array('fbid' => $fbme['id']));
		$_SESSION['user']->login(true);
	}
}

if(!isset($_SESSION['user'])){ // If user not logged in, set session user to null
	$_SESSION['user'] = null;
}

/* ---------- Page Actions ---------- */
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){ // Action called
	$action = $_REQUEST['action'];
	switch($action){ // Logout
		case 'logout':
			$_SESSION['user'] = null;
			$_SESSION['depts_most_rated'] = null;
			$_SESSION['total_users'] = null;
			$_SESSION['prizes'] = null;
			header("Location: {$config['baseurl']}/home");
			exit();
			break;
	}
}

/* ---------- Stored Data ---------- */
if(!isset($_SESSION['depts_most_rated']) || empty($_SESSION['depts_most_rated'])){ // Departments Most Rated (Report Card)
	$temp_dept = new Department();
	$_SESSION['depts_most_rated'] = $temp_dept->loadMostRated();
}
if(!isset($_SESSION['total_users']) || empty($_SESSION['total_users'])){ // Total # of Users
	$result = $config['database']->query("
		SELECT COUNT(*) AS user_count
		FROM nuusers
	");
	$row = $result->fetch_assoc();
	$_SESSION['total_users'] = $row['user_count'];
}
if(!isset($_SESSION['prizes']) || empty($_SESSION['prizes'])){ // Prizes
	$_SESSION['prizes'] = array();
	$temp_prize = new Prize();
	$pids = $temp_prize->loadPrizeIds();
	foreach($pids as $pid){
		$_SESSION['prizes'][] = new Prize(array('id' => $pid));
	}
}

/* ---------- Page Information ---------- */
if(isset($_REQUEST['page']) && !empty($_REQUEST['page'])){ // Page requested
	$page = $_REQUEST['page'];
	$pageFile = $_REQUEST['page'].'.php';
}else{ // Default page
	$page = 'home';
	$pageFile = 'home.php';
}

if(isset($_REQUEST['oid']) && !empty($_REQUEST['oid'])){ // Specific object requested
	if(!isset($_SESSION['object']) || (isset($_SESSION['object']) && $_SESSION['object']->id != $_REQUEST['oid'])){
		switch($page){
			case 'department':
				$_SESSION['object'] = new Department(array('id' => $_REQUEST['oid']));
				break;
			case 'prize':
				$_SESSION['object'] = new Prize(array('id' => $_REQUEST['oid']));
				break;
			case 'user':
				$_SESSION['object'] = new User(array('id' => $_REQUEST['oid']));
				break;
		}
	}
}

switch($page){ // Set page title
	case 'home':
		$pageTitle = '';
		break;
	case 'department':
	case 'prize':
		if($_SESSION['object']->id){
			$pageTitle = $_SESSION['object']->name.' | '.ucwords($page);
			break;
		}
	case 'user':
		if($_SESSION['object']->id){
			$pageTitle = $_SESSION['object']->first_name.' '.$_SESSION['object']->last_name.' | '.ucwords($page);
			break;
		}
	default:
		$pageTitle = ucwords($page);
		break;
}

switch($page){ // Set page stylesheet
	case '404':
	case 'join':
	case 'login':
	case 'terms':
	case 'verify':
	case 'verified':
		$stylesheet = 'style.misc.css';
		break;
	default:
		$stylesheet = 'style.main.css';
		break;
}

//d($_SESSION['user']);
//unset($_SESSION);

include_once 'admin/debugger.php';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE]><![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraph.org/schema/" dir="ltr" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="copyright" content="Copyright <?php echo date('Y'); ?> Sleepybits, All Rights Reserved." />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title><?php if(!empty($pageTitle)) echo $pageTitle.' | '; ?>Northeastern Shuffle</title>
	<link href="<?php echo $config['baseurl']; ?>/favicon.ico" rel="shortcut icon" />
	<link href="<?php echo $config['cssurl']; ?>/style.reset.css" rel="stylesheet" />
	<link href="<?php echo $config['cssurl'].'/'.$stylesheet; ?>" rel="stylesheet" />
	<!--[if IE 7]><link href="<?php echo $config['cssurl']; ?>/style.ie.css" rel="stylesheet" /><![endif]-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo $config['jsurl']; ?>/tabs.min.js" type="text/javascript"></script>
	<script src="<?php echo $config['jsurl']; ?>/jquery.tools.min.js" type="text/javascript"></script>
	<script src="<?php echo $config['jsurl']; ?>/jquery.autocomplete-min.js" type="text/javascript"></script>
	<script src="<?php echo $config['jsurl']; ?>/jquery.functions.js" type="text/javascript"></script>
	<script src="<?php echo $config['jsurl']; ?>/google.analytics.js" type="text/javascript"></script>
	<?php include_once 'template/google.analytics.php'; ?>
</head>
<body>
<div id="globalWrapper" class="<?php if($page == '404') echo ' notfound'; ?>">

<?php include_once $pageFile; ?>

</div><!-- globalWrapper -->

<img class="hide" src="<?php echo $config['imagesurl']; ?>/ajax-loader.gif" />

<?php include_once 'facebook/facebook.js.php'; ?>

</body>
</html>