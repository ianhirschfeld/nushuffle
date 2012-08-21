<?php
include_once 'template/header.php';
include_once 'template/col.left.php';
?>

<div class="col center">
	<?php if(!empty($_SESSION['user'])){ // User logged in ?>
	
	<div id="infoSection" class="user">
	
		<?php if($_SESSION['object']->id){ // Object exists ?>
		
		<?php if(!$_SESSION['object']->options['anonymous']){ ?>
		<img id="profilePhoto" src="<?php echo $_SESSION['object']->photo; ?>" width="50" height="50" alt="<?php echo $_SESSION['object']->first_name.' '.$_SESSION['object']->last_name; ?>" title="<?php echo $_SESSION['object']->first_name.' '.$_SESSION['object']->last_name; ?>" />
		<?php }else{ ?>
		<img id="profilePhoto" src="<?php echo $config['imagesurl']; ?>/anonymous.gif" width="50" height="50" alt="Anonymous" title="Anonymous" />
		<?php } // endif user image ?>
		
		<h5>LVL <?php echo $_SESSION['object']->level; ?></h5>
		
		<?php if(!$_SESSION['object']->options['anonymous']){ ?>
		<h3><?php echo $_SESSION['object']->first_name.' '.$_SESSION['object']->last_name; ?></h3>
		<?php }else{ ?>
		<h3><?php echo $_SESSION['object']->first_name.' '.substr($_SESSION['object']->last_name, 0, 1); ?></h3>
		<?php } // endif user name ?>
		
		<div class="clear"></div>
		
		<?php if(!$_SESSION['object']->options['anonymous'] &&
				(!empty($_SESSION['object']->options['twitter']) ||
				$_SESSION['object']->fbid)){ ?>
		<ul id="profileSocial">
			<?php if(!empty($_SESSION['object']->options['twitter'])){ // Twitter linked ?>
			<li class="tw"><a href="http://www.twitter.com/#!/<?php echo $_SESSION['object']->options['twitter']['screen_name']; ?>" target="_blank">Follow me on Twitter</a></li>
			<?php } ?>
			<?php if($_SESSION['object']->fbid){ // Facebook linked ?>
			<li class="fb"><a href="http://www.facebook.com/#!/profile.php?id=<?php echo $_SESSION['object']->fbid; ?>" target="_blank">Friend me on Facebook</a></li>
			<?php } ?>
		</ul>
		<?php } // endif user social ?>
		
		<?php }else{ // Object does not exists ?>
		
		<h3>Oops! It looks like the <?php echo $page; ?> you are looking for does not exist :(</h3>
		
		<?php } ?>
	
	</div><!-- infoSection -->
	
	<div id="profileAchievements" class="section">
		<h2><?php echo $_SESSION['object']->first_name; ?>'s Achievements</h2>
		<ul class="userBadges">
		<?php foreach($_SESSION['object']->achievements as $a){ ?>
		<li><img src="<?php echo $config['badgesurl'].'/'.$a->image_path; ?>" width="50" height="50" alt="<?php echo $a->name; ?>" title="<?php echo $a->name; ?>" /></li>
		<?php } ?>
		</ul>
	</div>
	
	<?php if($_SESSION['object']->id){ $rating_args = array('num' => 15, 'uid' => $_SESSION['object']->id); include_once 'template/recent.activity.php'; } ?>
	
	<?php }else{ // User not logged in ?>
	
	<div id="infoSection" class="user">
		<h3>You must be logged in to see a user's profile page.</h3>
	</div><!-- infoSection -->
	
	<?php } ?>
</div><!-- col center -->

<?php
include_once 'template/col.right.php';
include_once 'template/footer.php';
?>