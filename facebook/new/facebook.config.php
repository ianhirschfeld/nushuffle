<?php
/* ----- Include Facebook PHP API ----- */
try{
	include_once 'facebook.php';
}
catch(Exception $o){
	//d($o);
}

/* ----- Create Instance of Facebook ----- */
$fb = new Facebook(array(
	'appId'  => $config['fbappid'],
	'secret' => $config['fbsecret']
));

$fbuser = $fb->getUser();

if($fbuser) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		$fbme = $fb->api('/me');
	} catch (FacebookApiException $e) {
		//error_log($e);
		$fbuser = null;
	}
}
?>