<?php include_once 'template/header.misc.php'; ?>

<div id="fbButton"><fb:login-button autologoutlink="true" perms="email,user_birthday,publish_stream"></fb:login-button></div>
<p class="msg"><strong>Note:</strong> You must add "Northeastern" to your Facebook<br />networks to join. <a href="http://www.facebook.com/editaccount.php?networks" target="_blank">You can do that here</a>.</p>

<form id="loginForm" name="loginForm" method="post" action="<?php echo $config['baseurl']; ?>/handlers/login.handler.php">
	<p class="inline">
		<input id="email" class="text <?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit']) && $_SESSION['submit']['email'] != 'Email') echo 'selected';?>" name="email" type="text" value="<?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit'])) echo $_SESSION['submit']['email']; else echo 'Email'; ?>" />
		<span><input id="password" class="text" name="password" type="text" value="Password" /></span>
	</p>
	<p><input id="submit" class="submit" name="submit" type="submit" value="Login" /></p>
	<?php if(isset($_SESSION['submit'])) unset($_SESSION['submit']); ?>
</form>

<p class="msg">Don't have a login? <a href="<?php echo $config['baseurl'].'/join'; ?>">Sign up here</a>.</p>

<?php include_once 'template/footer.misc.php'; ?>