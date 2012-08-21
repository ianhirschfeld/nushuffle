<?php
// Script for populating column id in the given tables based on a user's fbid
include_once '../../inc/config.php';
include_once '../../inc/helper.functions.php';

$tables = array(
	'nuratings',
	'nushuffles',
	'nuuserachievements',
	'nuuserprizes'
);

$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

$users = $db->query("
	SELECT id, fbid
	FROM nuusers
");

while($user = $users->fetch_assoc()){
	d('Working on User id: '.$user['id'].' with fbid: '.$user['fbid']);
	foreach($tables as $table){
		d('Table: '.$table);
		$db->query("
			UPDATE $table
			SET user_id = {$user['id']}
			WHERE user_fbid = {$user['fbid']}
		");
	}
	d('=======================================');
}
?>