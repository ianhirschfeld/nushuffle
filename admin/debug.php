<?php
include_once '../inc/helper.functions.php';

session_start();

if(isset($_REQUEST['debugmode']) && !empty($_REQUEST['debugmode'])){
	if($_REQUEST['debugmode'] == 'on')
		$_SESSION['debug'] = true;
	elseif($_REQUEST['debugmode'] == 'off')
		$_SESSION['debug'] = false;
	$_SESSION['debug_options'] = array(
		'showquery' => false,
		'showsession' => false,
		'showuser' => false,
		'showobject' => false
	);
}

if($_SESSION['debug']){
	if(isset($_REQUEST['option']) && !empty($_REQUEST['option'])){
		$option = $_REQUEST['option'];
		switch($option){
			case 'showquery':
				$_SESSION['debug_options']['showquery'] = !$_SESSION['debug_options']['showquery'];
				break;
				
			case 'showsession':
				$_SESSION['debug_options']['showsession'] = !$_SESSION['debug_options']['showsession'];
				break;
				
			case 'showuser':
				$_SESSION['debug_options']['showuser'] = !$_SESSION['debug_options']['showuser'];
				break;
				
			case 'showobject':
				$_SESSION['debug_options']['showobject'] = !$_SESSION['debug_options']['showobject'];
				break;
		}
	}
}

if($_SESSION['debug']){
	d('Debug Mode: On');
	foreach($_SESSION['debug_options'] as $key => $val){
		if($val)
			$mode = 'on';
		else
			$mode = 'off';
		
		d($key.': '.$mode);
	}
}else{
	d('Debug Mode: Off');
}
?>