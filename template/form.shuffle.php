<div id="shuffleWrapper">
	<ul id="shuffleTabs">
		<li><a href="#">Select a category</a></li>
	</ul>
	<div class="clear"></div>
	<ul id="shufflePanes">
		<li class="pane first">
			<ul id="shuffleSelections">
				<li><a class="current" href="#"><img src="<?php echo $config['imagesurl']; ?>/phonecall.jpg" width="46" height="46" alt="Phone Call" title="Phone Call" /><br /><span>Phone Call</span></a></li>
				<li><a href="#"><img src="<?php echo $config['imagesurl']; ?>/forms.jpg" width="46" height="46" alt="Forms" title="Forms" /><br /><span>Forms</span></a></li>
				<li><a href="#"><img src="<?php echo $config['imagesurl']; ?>/website.jpg" width="46" height="46" alt="Website" title="Website" /><br /><span>Website</span></a></li>
				<li><a href="#"><img src="<?php echo $config['imagesurl']; ?>/inperson.jpg" width="46" height="46" alt="In Person" title="In Person" /><br /><span>In Person</span></a></li>
				<li><a href="#"><img src="<?php echo $config['imagesurl']; ?>/other.jpg" width="46" height="46" alt="Other" title="Other" /><br /><span>Other...</span></a></li>
			</ul>
			
			<div id="shuffle" class="formWrapper">
				<a class="cancel" href="#">Cancel</a>
				<img class="icon" src="" width="46" height="46" alt="" title="" /><h3></h3>
				<div class="clear"></div>
				<form id="formShuffle" name="formShuffle" method="post" action="<?php echo $config['baseurl']; ?>/handlers/post.handler.php">
					<div id="shuffleAddWrapper">
						<p>
							<label for="shuffle_0">Enter colleges, departments, and/or offices to rate:<br /><span class="sub">(You can also type a new department or service name here)</span></label><br />
							<input id="shuffle_0" class="shuffle text <?php if($page == 'department') echo 'selected';?>" name="departments[]" type="text" tabindex="1" value="<?php if($page == 'department') echo $_SESSION['object']->name; else echo 'start typing a college, department, or office'; ?>" />
						</p>
						<p>
							<input id="shuffle_1" class="shuffle text" name="departments[]" type="text" tabindex="1" value="start typing a college, department, or office" />
							<a class="addShuffle" href="#"><img class="add" src="<?php echo $config['imagesurl']; ?>/add.png" width="16" height="16" alt="Add" title="Add" /></a>
						</p>
					</div>
					<p>
						<textarea id="commentsShuffle" name="comment" tabindex="2">comments</textarea>
					</p>
					<div id="shuffleRating">
						<span>Select rating:</span>
						<div class="star empty"></div>
						<div class="star empty"></div>
						<div class="star empty"></div>
						<div class="star empty"></div>
						<div class="star empty"></div>
					</div>
					<p>
						<input id="hiddenType" name="hiddenType" type="hidden" value="" />
						<input id="hiddenRating" name="hiddenRating" type="hidden" value="0" />
						<input id="hiddenReturn" name="hiddenReturn" type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
						<input id="hiddenSubmit" name="hiddenSubmit" type="hidden" value="true" />
						<input id="submitShuffle" class="submit" name="submit" type="submit" tabindex="3" value="Post" />
						
						<?php if($_SESSION['user']->fbid || $_SESSION['user']->options['twitter']){ // Social posting ?>
						<span class="social">Post to:	
							<?php if($_SESSION['user']->fbid && $_SESSION['user']->fb_can_publish){ // Facebook posting ?>
							<img src="<?php echo $config['imagesurl']; ?>/facebook_16.png" width="16px" height="16px" alt="fb" title="Share your post on social networks" />
							<input id="shareFb" class="formSocial" name="shareFb" type="checkbox" value="true"<?php if($_SESSION['user']->options['social_post_default']) echo ' checked'; ?> />
							<?php } ?>
							
							<?php if(!empty($twitter)){ // Twitter posting ?>
							<img src="<?php echo $config['imagesurl']; ?>/twitter_16.png" width="16px" height="16px" alt="tw" title="Share your post on social networks" />
							<input id="shareTw" class="formSocial" name="shareTw" type="checkbox" value="true"<?php if($_SESSION['user']->options['social_post_default']) echo ' checked'; ?> />
							<?php } ?>
						</span>
						<?php } ?>
					</p>
				</form>
			</div>
		</li>
	</ul>
</div><!-- shuffleWrapper --> 