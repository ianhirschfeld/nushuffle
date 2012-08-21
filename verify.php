<?php include_once 'template/header.misc.php'; ?>

<p class="msg">A verification email has been sent to you.<br />Follow the link contained within it or manually enter your verification code here.</p>

<form id="verifyForm" name="verifyForm" method="post" action="<?php echo $config['baseurl']; ?>/handlers/verify.handler.php">
	<p><input id="verify" class="text selected" name="verify" type="text" value="" /></p>
	<p class="submit"><input id="submit" class="submit" name="submit" type="submit" value="Verify" /></p>
</form>

<?php include_once 'template/footer.misc.php'; ?>