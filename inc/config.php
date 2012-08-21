<?php
/* ----- Database Settings ----- */
$url=parse_url(getenv("mysql://b08245b6fa7b90:ac2cb9ed@us-cdbr-east.cleardb.com/heroku_8142c261b5aaae0?reconnect=true
"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"],1);

$config['database'] = mysql_select_db($db);

if($config['database']->connect_errno){ // Check successful connection to database
	echo 'Error connecting to database';
	exit();
}

/* ----- URL Config Settings ----- */
$config['baseurl'] = 'http://www.northeasternshuffle.com';
$config['imagesurl'] = 'http://www.northeasternshuffle.com/images';
$config['badgesurl'] = 'http://www.northeasternshuffle.com/images/achievements';
$config['jsurl'] = 'http://www.northeasternshuffle.com/js';
$config['cssurl'] = 'http://www.northeasternshuffle.com/css';

/* ----- Facbook Settings ----- */
$config['fbappid'] = '179862732034548';
$config['fbapi'] = 'd89518afa12909f3d09eea3df057d20a';
$config['fbsecret'] = 'e953aec0929d6bcf4223d44cfa30e007';

/* ----- Twitter Settings ----- */
define('CONSUMER_KEY', 'lW8Jk2kehDytn7lPF9KHXA');
define('CONSUMER_SECRET', 'eZ89yoFBx68ktttZpUmQooQpqB0PIYWOJ54zjaVLik');
define('OAUTH_CALLBACK', $config['baseurl'].'/oauth/callback.php');

/* ----- Point Settings ----- */
define('RATE_DEPT_1', 150);
define('RATE_DEPT_2', 200);
define('RATE_DEPT_3', 250);
define('RATE_DEPT_4', 350);
define('RATE_DEPT_CAP', 500);
//define('POST_TIP', 200);
//define('POLL_VOTE', 200);
define('FACEBOOK_POST', 10);
define('TWITTER_POST', 10);
?>