<?php
include_once 'template/header.php';
include_once 'template/col.left.php';
?>
	
<div class="col center">
	<div id="departments" class="section">
		<h3>Search Results for &quot;<?php if(isset($_SESSION['search_term']) && !empty($_SESSION['search_term'])) echo substr($_SESSION['search_term'], 1, -1); else echo 'Nothing'; ?>&quot;</h3>
		
		<?php if(isset($_SESSION['search_results']) && !empty($_SESSION['search_results'])){ $depts = $_SESSION['search_results']; ?>
			
		<ul id="searchResults">
		
			<?php foreach($depts as $d){ ?>
			<li><a href="<?php echo $config['baseurl']; ?>/department/<?php echo $d->id; ?>"><?php echo $d->name; ?></a></li>	
			<?php } ?>
		
		</ul>
		
		<?php unset($_SESSION['search_term']); unset($_SESSION['search_results']); }else{ ?>
		
		<br /><p>Nothing was found :(</p>
		
		<?php } ?>
	
	</div><!-- section depts -->
</div><!-- col center -->
	
<?php
include_once 'template/col.right.php';
include_once 'template/footer.php';
?>