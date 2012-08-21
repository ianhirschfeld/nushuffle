<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';

if(isset($_REQUEST['query'])){ // Data submitted

	$q = '%'.$_REQUEST['query'].'%';
	
	$stmt = $config['database']->prepare("
		SELECT name
		FROM nudepartments
		WHERE name LIKE ?
	");
	$stmt->bind_param('s', $q);
	$stmt->bind_result($name);
	$stmt->execute();
	
	$depts = array();
	while($stmt->fetch()){
		$depts[] = $name;
	}
	
	$data = array(
		'query' => $_REQUEST['query'],
		'suggestions' => $depts
	);
	$data = json_encode($data);
	
	echo $data;
	
}else{ // No post data found
	
	header("Location: {$config['baseurl']}/home");
	exit();

}
?>