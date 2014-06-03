<div class="portfolioLinks">
	<img id="portfolioThumb" src="/photos/portfolioThumbs/A-Tangerine-Blue.jpg" />
	<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
	<?php foreach ($all_galleries as $curr_gallery): ?>
		<?php
		$curr_gallery_href = $this->Html->url(array(
			'controller' => 'photo_galleries',
			'action' => 'view_gallery',
			$curr_gallery['PhotoGallery']['id']
		));
		?>
		<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/A-Tangerine-Blue.jpg');" href="<?php echo $curr_gallery_href; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a><br />
	<?php endforeach; ?>
</div>









