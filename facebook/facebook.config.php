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
	'secret' => $config['fbsecret'],
	'cookie' => true
));

$session = $fb->getSession(); // Check for session
$fbme = null; // Create Facebook user variable

// Initial Graph API call
// If session is found, populate user variables
// Else throw exception
if($session){
	try {
		$uid = $fb->getUser();
		$fbme = $fb->api('/me');
	}catch(FacebookApiException $e){
		//d($e);
	}
}
?>