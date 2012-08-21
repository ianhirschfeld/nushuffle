<?php
// Script for creating and populating a user's options column
include_once '../../inc/config.php';
include_once '../../inc/helper.functions.php';

$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

$users = $db->query("
	SELECT *
	FROM nuusers
");

while($user = $users->fetch_assoc()){
	d('Working on User id: '.$user['id']);
	
	$twitter = $user['twitter_access_token'];
	$twitter = json_decode($twitter);
	
	$options = array(
		'twitter' => $twitter,
		'anonymous' => $user['anonymous'],
		'social_post_default' => $user['social_post_default'],
		'social_badge_default' => $user['social_badge_default']
	);
	
	$options = json_encode($options);
	
	$update = $db->query("
		UPDATE nuusers
		SET options = '$options'
		WHERE id = {$user['id']}
	");
	
	if($update)
		d("options populated");
	
	d('=======================================');
}
?>