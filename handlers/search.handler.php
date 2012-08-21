<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/Department.class.php';

session_start();

if(isset($_POST['submit']) && $_POST['hiddenSubmit'] == 'true'){ // Data submitted
	
	$s = $_POST['searchDept'];
	
	$stmt = $config['database']->prepare("
		SELECT id
		FROM nudepartments
		WHERE name = ?
	");
	$stmt->bind_param('s', $s);
	$stmt->bind_result($id);
	$stmt->execute();
	
	if($stmt->fetch()){ // Department found
		
		header("Location: {$config['baseurl']}/department/$id");
		exit();
	
	}else{ // Department not found

		$s = '%'.$s.'%';
		
		$stmt = $config['database']->prepare("
			SELECT id
			FROM nudepartments
			WHERE name LIKE ?
		");
		$stmt->bind_param('s', $s);
		$stmt->bind_result($id);
		$stmt->execute();
		
		$depts = array();		
		while($stmt->fetch()){
			$depts[] = new Department(array('id' => $id));
		}
		
		$_SESSION['search_term'] = $s;
		$_SESSION['search_results'] = $depts;

		header("Location: {$config['baseurl']}/search");
		exit();
		
	}
	
}else{ // No post data found

	header("Location: {$config['baseurl']}/home");
	exit();

}
?>