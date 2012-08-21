<div id="globalHeader">
	<h1><a id="logo" href="<?php echo $config['baseurl']; ?>/home">Northeastern Shuffle</a></h1>
	<div id="globalHeaderNav">
		<form id="formSearch" name="formSearch" method="post" action="<?php echo $config['baseurl']; ?>/handlers/search.handler.php">
			<input id="searchDept" name="searchDept" type="text" value="search departments" />
			<input id="hiddenSearchSubmit" name="hiddenSubmit" type="hidden" value="true" />
			<input id="submitSearch" class="submit" name="submit" type="submit" value="go" />
		</form>
		
		<?php if(empty($_SESSION['user'])){ // User not logged in ?>
		
		<div class="signup"><a href="<?php echo $config['baseurl']; ?>/login">Log In</a> or</div>
		<div id="fbButton"><fb:login-button autologoutlink="true" perms="email,user_birthday,publish_stream"></fb:login-button></div>
		
		<?php }elseif($_SESSION['user'] && empty($fbme)){ // User logged in through NUS ?>
		
		<div class="signup"><a href="<?php echo $config['baseurl']; ?>/logout">Logout</a></div>
		
		<?php }else{ // User logged in through FB ?>
		
		<div id="fbButton"><fb:login-button autologoutlink="true" perms="email,user_birthday,publish_stream"></fb:login-button></div>
		
		<?php } ?>
		
	</div>
</div><!-- globalHeader -->

<?php include_once 'notifications.php'; ?>