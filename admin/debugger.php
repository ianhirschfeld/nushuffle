<?php
if(isset($_SESSION['debug']) && $_SESSION['debug']){
	foreach($_SESSION['debug_options'] as $key => $val){
		if($key == 'showquery' && $val){
			d('========== QUERY STRING ==========');
			d($_SERVER['QUERY_STRING']);
		}elseif($key == 'showsession' && $val){
			d('========== SESSION ==========');
			d($_SESSION);
		}elseif($key == 'showuser' && $val){
			d('========== USER ==========');
			if(!empty($_SESSION['user']))
				d($_SESSION['user']->getData());
		}elseif($key == 'showobject' && $val){
			d('========== OBJECT ==========');
			if(!empty($_SESSION['object']))
				d($_SESSION['object']->getData());
		}
	}

	if(isset($_REQUEST['command']) && !empty($_REQUEST['command'])){
		$command = $_REQUEST['command'];
		switch($command){
			case 'query':
				d('========== QUERY STRING ==========');
				d($_SERVER['QUERY_STRING']);
				break;
				
			case 'session':
				d('========== SESSION ==========');
				d($_SESSION);
				break;
				
			case 'unset':
				if(isset($_REQUEST['key']) && !empty($_REQUEST['key']))
					unset($_SESSION[$_REQUEST['key']]);
				break;
				
			case 'user':
				d('========== USER ==========');
				if(!empty($_SESSION['user']))
					d($_SESSION['user']->getData());
				break;
				
			case 'object':
				d('========== OBJECT ==========');
				if(!empty($_SESSION['object']))
					d($_SESSION['object']->getData());
				break;
				
			case 'login':
				if(isset($_REQUEST['uid']) && !empty($_REQUEST['uid']))
					$_SESSION['user'] = new User(array('id' => $_REQUEST['uid']));
				elseif(isset($_REQUEST['fbid']) && !empty($_REQUEST['fbid']))
					$_SESSION['user'] = new User(array('fbid' => $_REQUEST['fbid']));
				$_SESSION['user']->login();
				break;
				
			case 'logout':
				$_SESSION['user'] = null;
				break;
				
			case 'userupdate':
				if( isset($_REQUEST['col']) &&
					!empty($_REQUEST['col']) &&
					isset($_REQUEST['val'])){
					$_SESSION['user']->update(array($_REQUEST['col'] => $_REQUEST['val']));
				}
				break;
		}
	}
}
?>