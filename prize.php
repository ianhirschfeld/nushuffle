<?php
include_once 'template/header.php';
include_once 'template/col.left.php';
?>

<div class="col center">
	<?php if(!empty($_SESSION['user'])){ // User logged in ?>
	
	<div id="infoSection" class="prize">
	
		<?php if($_SESSION['object']->id){ // Object exists ?>
		
		<h5><?php echo $_SESSION['object']->points_req; ?></h5>
		<h3><?php echo $_SESSION['object']->name; ?></h3>
		<div class="clear"></div>

		<ul>
			<?php if($_SESSION['object']->url){ ?><li><a href="<?php echo $_SESSION['object']->url; ?>" target="_blank">Website</a></li><?php } ?>
			<?php if($_SESSION['object']->description){ ?><li><p><?php echo nl2br($_SESSION['object']->description); ?></p></li><?php } ?>
			<li><strong>&raquo; <?php echo $_SESSION['object']->points_req; ?> points will be deducted from your account. &laquo;</strong></li>
			<li>Your posts will be checked for legitimacy before your prize is awarded.</li>
		</ul>
		
		<?php }else{ // Object does not exists ?>
		
		<h3>Oops! It looks like the <?php echo $page; ?> you are looking for does not exist :(</h3>
		
		<?php } ?>
	
	</div><!-- infoSection -->
	
	<div class="section">
		<?php if($_SESSION['user']->prize_pts >= $_SESSION['object']->points_req){ ?>
		<div class="formWrapper">
			<form id="formPrize" name="formPrize" method="post" action="<?php echo $config['baseurl']; ?>/handlers/prize.handler.php">
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
					<input id="hiddenPrize" name="hiddenPrize" type="hidden" value="<?php echo $_SESSION['object']->id; ?>" />
					<input id="hiddenUser" name="hiddenUser" type="hidden" value="<?php echo $_SESSION['user']->id; ?>" />
					<input id="hiddenSubmit" name="hiddenSubmit" type="hidden" value="true" />
					<input id="submitPrize" class="submit" name="submit" type="submit" tabindex="8" value="Redeem" />
				</p>
				<p>Please allow 1-2 weeks for delivery.</p>
			</form>
		</div><!-- formWrapper -->
		<?php }else{ ?>
		<p>You do not have enough points for this prize. Keep rating NU to level up and gain prize points!</p>
		<?php } ?>
	</div><!-- section -->
	
	<?php }else{ // User not logged in ?>
	
	<div id="infoSection" class="prize">
		<h3>You must be logged in to see prize pages.</h3>
	</div><!-- infoSection -->
	
	<?php } ?>
</div><!-- col center -->

<?php
include_once 'template/col.right.php';
include_once 'template/footer.php';
?>