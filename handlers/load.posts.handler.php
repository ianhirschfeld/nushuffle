<?php
// Include necessary files
include_once '../inc/helper.functions.php';
include_once '../inc/config.php';
include_once '../classes/User.class.php';
include_once '../classes/Department.class.php';
include_once '../classes/Rating.class.php';
include_once '../classes/Achievement.class.php';

session_start();

if(isset($_REQUEST['settings'])){ // Data submitted
	
	$page = substr($_REQUEST['settings'], 1, 1);
	
	if(substr($_REQUEST['settings'], 3, 1)) $type = substr($_REQUEST['settings'], 3, 1);
	else $type = null;
	
	if(substr($_REQUEST['settings'], 5)) $oid = substr($_REQUEST['settings'], 5);
	else $oid = null;
	
	if($type == 'u'){
		$rating_args = array(
			'num' => 15,
			'page' => $page,
			'uid' => $oid
		);
	}elseif($type == 'd'){
		$rating_args = array(
			'num' => 15,
			'page' => $page,
			'did' => $oid
		);
	}else{
		$rating_args = array(
			'num' => 15,
			'page' => $page
		);
	}
	
	$data = '';
	$temp_r = new Rating();
	$rids = $temp_r->loadRatingIds($rating_args);
	
	if(!empty($rids)){
		foreach($rids as $rid){
			$data .= getHTML($rid);
		}
	}
	
	echo $data;
	
}else{ // No post data found
	
	header("Location: {$config['baseurl']}/home");
	exit();

}

function getHTML($rid){
	global $config;
	$r = new Rating(array('id' => $rid));
	$u = new User(array('id' => $r->user_id)); ?>

<li id="post-<?php echo $r->id; ?>">
	<?php if(!empty($_SESSION['user']) && !$_SESSION['user']->faculty){ // User logged in ?>
		
		<?php if(!$u->options['anonymous']){ // User name and link ?>
		<a id="postUserImage-<?php echo $u->id.'-'.$r->id; ?>" href="<?php echo $config['baseurl'] ?>/user/<?php echo $u->id; ?>"><img class="postPhoto" src="<?php echo $u->photo; ?>" width="50" height="50" alt="<?php echo $u->first_name.' '.$u->last_name; ?>" title="<?php echo $u->first_name.' '.$u->last_name; ?>" /></a>
		<?php }else{ // User name anonymous ?>
		<img class="postPhoto" src="<?php echo $config['imagesurl']; ?>/anonymous.gif" width="50" height="50" alt="Anonymous" title="Anonymous" />
		<?php } ?>
	
	<?php }else{ // User not logged in ?>
	
		<?php if(!$u->options['anonymous']){ // User name and link ?>
		<img class="postPhoto" src="<?php echo $u->photo; ?>" width="50" height="50" alt="<?php echo $u->first_name.' '.$u->last_name; ?>" title="<?php echo $u->first_name.' '.$u->last_name; ?>" />
		<?php }else{ // User name anonymous ?>
		<img class="postPhoto" src="<?php echo $config['imagesurl']; ?>/anonymous.gif" width="50" height="50" alt="Anonymous" title="Anonymous" />
		<?php } ?>
	
	<?php } // endif user image ?>
	
	<div class="postInfo">
		<p>
		<?php if(!empty($_SESSION['user']) && !$_SESSION['user']->faculty){ // User logged in ?>
		
			<?php if(!$u->options['anonymous']){ // User name and link ?><a class="fblink" href="<?php echo $config['baseurl'] ?>/user/<?php echo $u->id; ?>"><?php echo $u->first_name.' '.$u->last_name; ?></a>
			<?php }else{ echo 'Anonymous'; } // User name anonymous ?>
		
		<?php }else{ // User not logged in ?>
		
			<?php if(!$u->options['anonymous']) echo $u->first_name.' '.substr($u->last_name, 0, 1).'.'; else echo 'Anonymous'; ?>
		
		<?php } // endif user name ?>
		
		rated
		
		<?php
		$dids = json_decode($r->dept_ids, true);
		$len = count($dids);
		$count = 0;
		foreach($dids as $did){
			$d = new Department(array('id' => $did));
		?>
		<a href="<?php echo $config['baseurl'].'/department/'.$d->id ?>"><?php echo $d->name ?></a><?php if($count == $len-2) echo ' and '; elseif($len > 1 && $count != $len-1) echo ', '; ?>
		<?php $count++; } // Department listing ?>
		</p>
		
		<p class="postComment"><?php echo nl2br($r->comment); ?></p>
		
		<div class="postFooter">
			<?php for($i = $r->score; $i > 0; $i--){ ?>
			<div class="starSmall full"></div>
			<?php } ?>
			<?php for($i = 5-$r->score; $i > 0; $i--){ ?>
			<div class="starSmall empty"></div>
			<?php } ?>
			<div class="postTimePassed"><?php echo $r->time_passed; ?> via <?php echo $r->type; ?></div>
			<?php if(!empty($_SESSION['user'])){ ?><a class="postReply" href="#">Reply</a><?php } ?>
		</div>
	</div><!-- postInfo -->
	
	<?php if($r->replies){ // At least one reply ?>
	<ul id="replies">
		<?php foreach($r->replies as $reply){
			$ru = new User(array('id' => $reply['user_id'])); ?>
		
		<li id="post-<?php echo $r->id; ?>"<?php if($ru->faculty){ echo ' class="verified"'; } ?>>
			<?php if(!empty($_SESSION['user'])){ // User logged in ?>
				
				<?php if(!$ru->options['anonymous']){ // User name and link ?>
				<a id="replyUserImage-<?php echo $ru->id.'-'.$reply['id']; ?>" href="<?php echo $config['baseurl'] ?>/user/<?php echo $ru->id; ?>"><img class="postPhoto" src="<?php echo $ru->photo; ?>" width="50" height="50" alt="<?php echo $ru->first_name.' '.$ru->last_name; ?>" title="<?php echo $ru->first_name.' '.$ru->last_name; ?>" /></a>
				<?php }else{ // User name anonymous ?>
				<img class="postPhoto" src="<?php echo $config['imagesurl']; ?>/anonymous.gif" width="50" height="50" alt="Anonymous" title="Anonymous" />
				<?php } ?>
			
			<?php }else{ // User not logged in ?>
			
				<?php if(!$ru->options['anonymous']){ // User name and link ?>
				<img class="postPhoto" src="<?php echo $ru->photo; ?>" width="50" height="50" alt="<?php echo $ru->first_name.' '.$ru->last_name; ?>" title="<?php echo $ru->first_name.' '.$ru->last_name; ?>" />
				<?php }else{ // User name anonymous ?>
				<img class="postPhoto" src="<?php echo $config['imagesurl']; ?>/anonymous.gif" width="50" height="50" alt="Anonymous" title="Anonymous" />
				<?php } ?>
			
			<?php } // endif user image ?>
			
			<div class="replyInfo">
				<p>
				<?php if(!empty($_SESSION['user']) && !$_SESSION['user']->faculty){ // User logged in ?>
				
					<?php if(!$ru->options['anonymous'] && !$ru->faculty){ // User name and link ?><a class="fblink" href="<?php echo $config['baseurl'] ?>/user/<?php echo $ru->id; ?>"><?php echo $ru->first_name.' '.$ru->last_name; ?></a>
					<?php }elseif(!$ru->options['anonymous'] && $ru->faculty){ // Faculty name ?><?php echo $ru->first_name.' '.$ru->last_name; ?>
					<?php }else{ echo 'Anonymous'; } // User name anonymous ?>
				
				<?php }else{ // User not logged in ?>
				
					<?php if(!$ru->options['anonymous']) echo $ru->first_name.' '.substr($ru->last_name, 0, 1).'.'; else echo 'Anonymous'; ?>
				
				<?php } // endif user name ?>
				
				replied
				</p>
			
				<p class="replyComment"><?php echo nl2br($reply['comment']); ?></p>
				
				<div class="replyFooter">
					<div class="replyTimePassed"><?php echo $reply['time_passed']; ?></div>
				</div>
			</div><!-- replyInfo -->
		</li>
			
		<?php } // endforeach ?>
		<?php if(!empty($_SESSION['user'])){ ?><a class="postReply" href="#">Reply</a><?php } ?>
	</ul>
	<?php } ?>
</li>

<?php } ?>