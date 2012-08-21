<div class="col left">
	<div id="reportCard" class="section">
		<h4>NU Report Card</h4>
		<ul id="reportCardGrades">
			<?php if(isset($_SESSION['depts_most_rated']) && !empty($_SESSION['depts_most_rated'])){ foreach($_SESSION['depts_most_rated'] as $d){ ?>
			<li><span><?php echo $d->displayGrade(); ?></span><a href="<?php echo $config['baseurl']; ?>/department/<?php echo $d->id; ?>"><?php echo $d->name; ?></a></li>
			<?php } } // endforeach, endif ?>
		</ul>
		<a id="reportCardViewAll" href="<?php echo $config['baseurl']; ?>/departments">View all departments &raquo;</a>
	</div><!-- reportCard -->
</div><!-- col left -->