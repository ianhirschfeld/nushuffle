<?php
// Script for converting nu ratings from -1,0,1 to 5 star rating
include_once '../../inc/config.php';
include_once '../../inc/helper.functions.php';

$db = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

$ratings = $db->query("
	SELECT *
	FROM nuratings
");

while($rating = $ratings->fetch_assoc()){
	d('Working on Rating id: '.$rating['id']);

	$curr = $rating['score'];
	if($curr == 1)
		$score = 4;
	elseif($curr == -1)
		$score = 2;
	
	$update = $db->query("
		UPDATE nuratings
		SET score = $score
		WHERE id = {$rating['id']}
	");
}
?>