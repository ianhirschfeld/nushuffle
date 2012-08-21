<?php
include_once 'template/header.php';
include_once 'template/col.left.php';
?>

<div class="col center">
	<?php if(!empty($_SESSION['user']) && !$_SESSION['user']->faculty) include_once 'template/form.shuffle.php'; ?>

	<?php $rating_args = array('num' => 15); include_once 'template/recent.activity.php'; ?>
</div><!-- col center -->

<?php
include_once 'template/col.right.php';
include_once 'template/footer.php';
?>