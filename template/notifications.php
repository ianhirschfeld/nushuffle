<?php
if(isset($_SESSION['notifications']) && !empty($_SESSION['notifications'])){
	foreach($_SESSION['notifications'] as $notif){
		echo $notif->display();
	}
	unset($_SESSION['notifications']);
}
?>