<div class="col right">

	<?php if(!empty($_SESSION['user']) && !$_SESSION['user']->faculty){ // User logged in ?>

	<div id="userScore">
		<div id="userLevel"><h5>Level</h5><div><?php echo $_SESSION['user']->level; ?></div></div>
		<div id="userLevelProgress"><div id="userProgressFill" style="width:<?php echo $_SESSION['user']->levelProgress.'%'; ?>;"></div><div id="userProgressPercent"><?php echo $_SESSION['user']->levelProgress.'%'; ?></div></div>
	</div>
	
	<div id="userInfo" class="section">
		<a id="userPhoto" href="<?php echo $config['baseurl']; ?>/account"><img src="<?php echo $_SESSION['user']->photo; ?>" width="50" height="50" alt="<?php echo $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name; ?>" title="<?php echo $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name; ?>" /></a>
		<ul id="userStats">
			<li id="userName"><a class="userLink" href="<?php echo $config['baseurl']; ?>/account"><?php echo $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name; ?></a></li>
			
			<?php if($_SESSION['user']->prize_pts > 0){ // User has prize points ?>
			
			<li id="userPoints"><?php echo $_SESSION['user']->prize_pts; ?> points</li>
			<li id="userRank">You are ranked #<?php echo $_SESSION['user']->rank; ?><br />out of <?php echo $_SESSION['total_users']; ?> NU Students</li>
			
			<?php }else{ // User does not have prize points ?>
			
			<li id="userPoints">Start rating NU to earn points!</li>
			
			<?php } ?>
			
			<li id="userEditSettings"><a href="<?php echo $config['baseurl']; ?>/account">Edit Settings</a></li>
			<li id="userViewProfile"><a href="<?php echo $config['baseurl'].'/user/'.$_SESSION['user']->id; ?>">View your Profile</a></li>
				
			<?php if($_SESSION['user']->options['anonymous']){ // User is anonymous ?>
			
			<li id="userIsAnonymous">You are anonymous</li>
			
			<?php } ?>
		</ul>
 	</div><!-- userInfo -->
	
	<div id="userAchievements" class="section">
		<h4>Achievements</h4>
		
		<?php if(count($_SESSION['user']->achievements) > 0){ // User has at least one achievement  ?>
		
		<ul class="userBadges">
			<?php foreach($_SESSION['user']->achievements as $a){ ?>
		
			<li><img src="<?php echo $config['badgesurl'].'/'.$a->image_path; ?>" width="50" height="50" alt="<?php echo $a->name; ?>" title="<?php echo $a->name; ?>" /></li>
			
			<?php } ?>
		</ul>
		
		<?php }else{ // User has no achievements ?>
		
			<img src="<?php echo $config['imagesurl']; ?>/badges_info.jpg" alt="Rate departments to unlock new badges!" title="Rate departments to unlock new badges!" />
		
		<?php } ?>
	</div>
	
	<div id="redeem" class="section">
		<h4>Redeem Points</h4>
		<ul id="redeemPrizes">
			<?php if(!$_SESSION['user']->options['anonymous']){ // User is not anonymous
				if(isset($_SESSION['prizes']) && !empty($_SESSION['prizes'])){ foreach($_SESSION['prizes'] as $p){ ?>
				
			<li><a href="<?php echo $config['baseurl'].'/prize/'.$p->id; ?>"><?php echo $p->name; ?></a></li>
			
			<?php } } // endforeach, endif
			}else{ // User is anonymous?>
			
			<li class="sm">You cannot earn prizes while anonymous.</li>
			<li class="sm">Deanonymouseize yourself in your <a href="<?php echo $config['baseurl']; ?>/account">settings</a>.</li>
				
			<?php } ?>
		</ul>
	</div><!-- redeem -->
	
	<?php }elseif(!empty($_SESSION['user']) && $_SESSION['user']->faculty){ // Faculty logged in ?>
	
	<div class="section">
		You are logged in as a faculty or staff member of Northeastern University. Read through student comments and reply to them as appropriate.
	</div><!-- section -->
	
	<?php }else{ // User not logged in ?>

	<?php /* <div class="section">
		<a href="<?php echo $config['baseurl']; ?>/join">Click here</a> to join Northeastern Shuffle and start earning bages and points. Comment on everything Northeastern, win prizes, and help make Northeastern a better environment for us all!
	</div><!-- section --> */ ?>
	<a href="<?php echo $config['baseurl']; ?>/join"><img src="<?php echo $config['imagesurl']; ?>/join_teaser.jpg" alt="Join NU shuffle!" title="Join NU shuffle!" /></a>

	<?php } ?>

</div><!-- col right -->