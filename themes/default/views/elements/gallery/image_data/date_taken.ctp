<?php if (!empty($photo['Photo']['date_taken'])): ?>
	<h3 class='photo_date'>
		<?php $phpdate = strtotime($photo['Photo']['date_taken']); ?>
		<?php echo date("F Y", $phpdate); ?>
	</h3>
<?php endif; ?>