<?php
// Script for creating a shuffle for nuratings with shuffle_id  = 0
include_once '../../inc/config.php';
include_once '../../inc/helper.functions.php';

$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

$ratings = $db->query("
	SELECT *
	FROM nuratings
");

while($rating = $ratings->fetch_assoc()){
	d('Working on Rating id: '.$rating['id']);

	if(empty($rating['shuffle_id'])){
		$shuffle = array($rating['dept_id']);
		$shuffle = json_encode($shuffle);
		
		$insert = $db->query("
			INSERT INTO nushuffles (user_id, dept_ids, date_submitted)
			VALUES ({$rating['user_id']}, '$shuffle', '{$rating['date_submitted']}')
		");
		
		if($insert){
			d('Shuffle inserted');
			$update = $db->query("
				UPDATE nuratings
				SET shuffle_id = {$db->insert_id}
				WHERE id = {$rating['id']}
			");
		}
	}
}
?>