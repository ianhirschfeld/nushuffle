<?php
include_once 'template/header.php';
include_once 'template/col.left.php';
?>

<div class="col center">
	<div id="infoSection" class="department">
	
		<?php if($_SESSION['object']->id){ // Object exists ?>
		
		<h5><?php echo $_SESSION['object']->displayGrade(); ?></h5>
		<h3><?php echo $_SESSION['object']->name; ?></h3>
		<div class="clear"></div>
		
		<?php if($_SESSION['object']->url ||
				 $_SESSION['object']->phone ||
				 $_SESSION['object']->description){ ?>
		<ul>
			<?php if($_SESSION['object']->url){ ?><li><a href="<?php echo $_SESSION['object']->url; ?>" target="_blank">Website</a></li><?php } ?>
			<?php if($_SESSION['object']->phone){ ?><li><?php echo $_SESSION['object']->phone; ?></li><?php } ?>
			<?php if($_SESSION['object']->description){ ?><li><p><?php echo nl2br($_SESSION['object']->description); ?></p></li><?php } ?>
		</ul>
		<?php } ?>
		
		<div id="avgRating"><span>Avg. Rating:</span> <?php echo $_SESSION['object']->displayAvgRating(); ?></div>
		
		<?php }else{ // Object does not exists ?>
		
		<h3>Oops! It looks like the <?php echo $page; ?> you are looking for does not exist :(</h3>
		
		<?php } ?>
	
	</div><!-- infoSection -->
	
	<?php if(!empty($_SESSION['user']) && !$_SESSION['user']->faculty) include_once 'template/form.shuffle.php'; ?>
	
	<?php if($_SESSION['object']->id){ $rating_args = array('num' => 15, 'did' => $_SESSION['object']->id); include_once 'template/recent.activity.php'; } ?>
</div><!-- col center -->

<?php
include_once 'template/col.right.php';
include_once 'template/footer.php';
?>