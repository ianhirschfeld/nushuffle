<?php

if(isset($_REQUEST['pass']) && !empty($_REQUEST['pass'])){
	$salt = '0G9@!a39ga987*&^%ga9320vaweDSasFa';
	$password = hash('whirlpool', $salt.$_REQUEST['pass']);
	echo $password;
}

?>