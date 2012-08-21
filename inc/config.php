<?php
/* ----- Database Settings ----- */
$config['dbhost'] = 'localhost';
$config['dbname'] = 'hippo_nushuff_shuffle';
$config['dbuser'] = 'hippo_nushuff';
$config['dbpass'] = 'QxPHR2jYI9E8';
$config['database'] = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

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