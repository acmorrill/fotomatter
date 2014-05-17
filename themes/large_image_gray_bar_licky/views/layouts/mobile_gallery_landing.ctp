<?php //echo $this->Element('theme_mobile_global_includes'); ?>

<div data-role="page" data-add-back-btn="true" id="Gallery1" class="gallery-page">

	<div data-role="header">
		<h1><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></h1>
	</div>

	<div data-role="content">	
		
		<ul class="gallery">
			<?php 
				if (!isset($photos)) {
					// treat the landing page as the first gallery
					$curr_gallery = $this->Gallery->get_first_gallery(); 
					if (isset($curr_gallery['PhotoGallery']['id'])) {
						$gallery_id = $curr_gallery['PhotoGallery']['id'];
					} else {
						$gallery_id = 0;
					}
					$photos = $this->Gallery->get_gallery_photos($gallery_id, 15);
				}
			?>
		
			<?php foreach ($photos as $f_photo): ?>
				<?php $large_img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], 1000, 1000, .4, true, true); ?>
				<?php $thumb_img_src = $this->Photo->get_photo_path($f_photo['Photo']['id'], 300, 300, .4, true, true); ?>
				<li>
					<a href="<?php echo $large_img_src['url']; ?>" rel="external">
						<img src="<?php echo $thumb_img_src['url']; ?>" <?php //echo $thumb_img_src['tag_attributes']; ?> />
					</a>
				</li>
<!--				<li><a href="/js/photoswipe_3.0.5/examples/images/full/001.jpg" rel="external"><img src="/js/photoswipe_3.0.5/examples/images/thumb/001.jpg" alt="Image 001" /></a></li>-->
			<?php endforeach; ?>
		</ul>
		
	</div>
	
	<div data-role="footer">
		<h4>&copy; 2012 Code Computerlove</h4>
	</div>
	
</div>