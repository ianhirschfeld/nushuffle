<?php include_once 'template/header.misc.php'; ?>

<div id="fbButton"><fb:login-button autologoutlink="true" perms="email,user_birthday,publish_stream"></fb:login-button></div>
<p class="msg"><strong>Note:</strong> You must add "Northeastern" to your Facebook<br />networks to join. <a href="http://www.facebook.com/editaccount.php?networks" target="_blank">You can do that here</a>.</p>

<form id="joinForm" name="joinForm" method="post" action="<?php echo $config['baseurl']; ?>/handlers/join.handler.php">
	<p class="inline">
		<input id="first_name" class="text <?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit']) && $_SESSION['submit']['first_name'] != 'First Name') echo 'selected';?>" name="first_name" type="text" value="<?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit'])) echo $_SESSION['submit']['first_name']; else echo 'First Name'; ?>" />
		<input id="last_name" class="text <?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit']) && $_SESSION['submit']['last_name'] != 'Last Name') echo 'selected';?>" name="last_name" type="text" value="<?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit'])) echo $_SESSION['submit']['last_name']; else echo 'Last Name'; ?>" />
	</p>
	<div class="clear"></div>
	<p>
		<input id="email" class="text <?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit']) && $_SESSION['submit']['email'] != 'Email') echo 'selected';?>" name="email" type="text" value="<?php if(isset($_SESSION['submit']) && !empty($_SESSION['submit'])) echo $_SESSION['submit']['email']; else echo 'Email'; ?>" />
		<span class="msg">(Must be a valid Northeastern email address)</span>
	</p>
	<p class="inline">
		<span><input id="password" class="text" name="password" type="text" value="Password" /></span>
		<span><input id="password_confirm" class="text" name="password_confirm" type="text" value="Confirm Password" /></span>
	</p>
	<p class="msg">The following fields are used for special NU Shuffle awards and promotions. They are optional to fill out.</p><br />
	<p class="inline">
		<label for="birthday_month">Birthday</label>
		<select id="birthday_month" name="birthday_month">
			<option value="">--</option>
			<?php for($i=1;$i<=12;$i++): ?>
			<option value="<?php if($i < 10) echo '0'.$i; else echo $i; ?>"><?php if($i < 10) echo '0'.$i; else echo $i; ?></option>
			<?php endfor; ?>
		</select>
		<select id="birthday_day" name="birthday_day">
			<option value="">--</option>
			<?php for($i=1;$i<=31;$i++): ?>
			<option value="<?php if($i < 10) echo '0'.$i; else echo $i; ?>"><?php if($i < 10) echo '0'.$i; else echo $i; ?></option>
			<?php endfor; ?>
		</select>
		<select id="birthday_year" name="birthday_year">
			<option value="">----</option>
			<?php $thisyear = date('Y');
			for($i=$thisyear;$i>=($thisyear-150);$i--): ?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
		</select>
	</p>
	<p>
		<label for="gender">Gender</label>
		<select id="gender" name="gender">
			<option value="">---</option>
			<option value="male">Male</option>
			<option value="female">Female</option>
		</select>
	</p>
	<p class="submit"><input id="submit" class="submit" name="submit" type="submit" value="Join" /></p>
	<?php if(isset($_SESSION['submit'])) unset($_SESSION['submit']); ?>
</form>

<p class="msg">Problems signing up? <a href="mailto:support@shufl.es">Email us</a>.</p>

<?php include_once 'template/footer.misc.php'; ?>