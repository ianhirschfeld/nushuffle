<?php
include_once 'template/header.php';
include_once 'template/col.left.php';
?>

<div class="col center">
	<div class="section">
		<div id="account" class="formWrapper">
			<a class="cancel" href="<?php echo $config['baseurl']; ?>/home">Cancel</a>
			
			<img id="profilePhoto" src="<?php echo $_SESSION['user']->photo; ?>" width="50" height="50" alt="<?php echo $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name; ?>" title="<?php echo $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name; ?>" />
			<h3><?php echo $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name; ?></h3>
			<div class="clear"></div>
			
			<ul id="profileSocial">
				<li class="tw">
				<?php if(!empty($_SESSION['user']->options['twitter'])){ ?>
				<a id="unlinkTwitter" href="oauth/callback.php?t=del">x</a>
				Twitter account linked
				<ul>
					<li>We will tweet your level ups and achievements automatically.</li>
					<li><a href="http://twitter.com/#!/nushuffle" target="_blank">Follow us on Twitter for updates.</a></li>
				</ul>
				<?php }else{ ?>
				<a href="<?php echo $config['baseurl'] ?>/oauth/redirect.php">Link your Twitter account</a>
				<?php } ?>
				</li>
				
				<?php if($_SESSION['user']->fbid){ ?>
				<li class="fb">Facebook account linked</li>
				<?php }else{ ?>
				<li class="fbl"><fb:login-button autologoutlink="true" perms="email,user_birthday,publish_stream"></fb:login-button></li>
				<?php } ?>
			</ul>
			
			<form id="formAccount" name="formAccount" method="post" action="<?php echo $config['baseurl']; ?>/handlers/account.handler.php">
				<ul>
					<li><input id="socialPostDefault" class="accountCheckbox" name="socialPostDefault" type="checkbox" value="true"<?php if($_SESSION['user']->options['social_post_default']) echo ' checked'; ?> /> Post Ratings to social networks by default</li>
					<li><input id="socialBadgeDefault" class="accountCheckbox" name="socialBadgeDefault" type="checkbox" value="true"<?php if($_SESSION['user']->options['social_badge_default']) echo ' checked'; ?> /> Post Badges to social networks</li>
				</ul>
				
				<a id="makeAnonymous" href="#"><?php if($_SESSION['user']->options['anonymous']) echo 'Deanonymousize me'; else echo 'Make me anonymous'; ?></a>
				<div id="anonymous">
					<strong>Are you sure you want to be anonymous?</strong>
					<ul>
						<li>Points earned will only help you level up.</li>
						<li>You will not be eligible to redeem prizes with points earned anonymously.</li>
						<li>Your comments will be moderated before they appear on the site.</li>
						<li>Your existing points and achievements will remain in-tact if you choose to switch back.</li>
					</ul>
					<input id="makeAnon" class="accountCheckbox" name="makeAnon" type="checkbox" value="true"<?php if($_SESSION['user']->options['anonymous']) echo ' checked'; ?> /> I understand. Make me anonymous. 
					<a class="cancel" href="#">Cancel</a>
				</div>
				
				<br />
				<h3>Shipping Address</h3>
				<div class="clear"></div>
				<p>
					<?php
					$fname = $_SESSION['user']->first_name;
					$lname = $_SESSION['user']->last_name;
					$addr1 = $_SESSION['user']->addr1;
					$addr2 = $_SESSION['user']->addr2;
					$city = $_SESSION['user']->city;
					$state = $_SESSION['user']->state;
					$zip = $_SESSION['user']->zip;
					?>
					<input id="first_name" class="<?php if(!empty($fname)){ echo 'selected '; } ?> text short" name="first_name" type="text" tabindex="1" disabled="disabled" value="<?php echo !empty($fname) ? $fname : 'First Name'; ?>" />
					<input id="last_name" class="<?php if(!empty($lname)){ echo 'selected '; } ?> text short" name="last_name" type="text" tabindex="2" disabled="disabled" value="<?php echo !empty($lname) ? $lname : 'Last Name'; ?>" />
					<input id="addr1" class="<?php if(!empty($addr1)){ echo 'selected '; } ?> text" name="addr1" type="text" tabindex="3" value="<?php echo !empty($addr1) ? $addr1 : 'Address'; ?>" />
					<input id="addr2" class="<?php if(!empty($addr2)){ echo 'selected '; } ?> text" name="addr2" type="text" tabindex="4" value="<?php echo !empty($addr2) ? $addr2 : 'Apt or Suite'; ?>" />
					<input id="city" class="<?php if(!empty($city)){ echo 'selected '; } ?> text" name="city" type="text" tabindex="5" value="<?php echo !empty($city) ? $city : 'City'; ?>" />
					<input id="state" class="<?php if(!empty($state)){ echo 'selected '; } ?> text short" name="state" type="text" tabindex="6" value="<?php echo !empty($state) ? $state : 'State'; ?>" />
					<input id="zip" class="<?php if(!empty($zip)){ echo 'selected '; } ?> text short" name="zip" type="text" tabindex="7" value="<?php echo !empty($zip) ? $zip : 'Zip'; ?>" />
				</p>
				<p>
					<input id="hiddenSubmit" name="hiddenSubmit" type="hidden" value="true" />
					<input id="submitAccount" class="submit" name="submit" type="submit" tabindex="8" value="Save Settings" />
				</p> 
			</form>
		</div><!-- account -->
		
		<?php /* ?>
		<a id="deleteAccount" href="#">delete my account</a>
		<div class="clear"></div>
		<div id="delete">
			Are you sure you want to delete your <strong>Northeastern Shuffle</strong> account? 
			<ul>
				<li>You will lose all of your points and achievements.</li>
				<li>Pending prize redemptions may be cancelled.</li>
				<li>All of your personal data will be purged.</li>
				<li>All of your existing posts will remain visible as "Anonymous."</li>
				<li>Your Facebook account will be unaffected.</li>
				<li>Our feelings will be hurt.</li>
			</ul>
			If you still wish to purge your account, click below.<br /><br />
			<span class="warning">There is no further confirmation or undo!</span>
			<form id="formDelete" name="formDelete" method="post" action="<?php echo $config['baseurl']; ?>/handlers/delete.handler.php">
				<p>
					<input id="hiddenDeleteSubmit" name="hiddenDeleteSubmit" type="hidden" value="true" />
					<input id="submitDelete" class="submit" name="submit" type="submit" value="Purge my account" /> :(
				</p> 
			</form>
			<a class="cancel" href="#">Cancel</a>
		</div>
		<?php */ ?>
	</div><!-- section -->
</div><!-- col center -->

<?php
include_once 'template/col.right.php';
include_once 'template/footer.php';
?>