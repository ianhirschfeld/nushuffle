<?php
include_once 'template/header.php';
include_once 'template/col.left.php';
?>

<div class="col center">
	<div id="departments" class="section">
		<h3>All Departments</h3>
		
		<?php
		$curLetter = 'A';
		$temp_d = new Department();
		$dids = $temp_d->loadAllDeptIds();
		?>
		
		<h4><?php echo $curLetter; ?></h4>
		<ul>
		
		<?php foreach($dids as $did){
			$d = new Department(array('id' => $did));
			if($curLetter != substr($d->name, 0, 1)){
				$curLetter = substr($d->name, 0, 1); ?>
			
		</ul>
		<h4><?php echo $curLetter; ?></h4>
		<ul>
		
			<?php } // endif ?>
		
			<li><span><?php echo $d->displayGrade(); ?></span><a href="<?php echo $config['baseurl']; ?>/department/<?php echo $d->id; ?>"><?php echo $d->name; ?></a></li>
		
		<?php } // endforeach ?>
		
		</ul>
	</div>
</div><!-- col center -->

<?php
include_once 'template/col.right.php';
include_once 'template/footer.php';
?>