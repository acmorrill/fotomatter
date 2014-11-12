<!DOCTYPE html>
<html>
	<head>
		<title>Photography by Andrew Morrill</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/white_angular_style.css" />
		<link href='http://fonts.googleapis.com/css?family=Signika+Negative:300' rel='stylesheet' type='text/css'>
		<script src="/js/angular_functions.js"></script>
	</head>
	<body>
<!--		<div style="width: 650px; height: 100px; z-index: 3000; position: fixed; outline: 1px solid orange;"></div>-->
		
		<?php echo $this->Element('nameTitle'); ?>
		
		<?php echo $this->Element('menu/navBar'); ?>
		
		<script type="text/javascript">
			var scroll_to_height = 257;
			
			jQuery(document).ready(function() {
				jQuery('.scroll_up_right').click(function() {
					scroll_to_next_image(true);
				});
				
				jQuery('.scroll_down_left').click(function() {
					scroll_to_prev_image(true);
				});
				
				jQuery('#image_slider_container .float_image_cont.actual_image, #image_slider_container .float_image_cont.fake_image').click(function() {
					load_gallery(jQuery(this).attr('photo_gallery_id'));
				});
			});
		</script>

		<?php 
			$all_galleries = $this->Gallery->get_all_galleries();

			$photos = array();
			foreach ($all_galleries as $curr_gallery) {
				$gallery_landing_photo = $this->Gallery->get_gallery_landing_image($curr_gallery['PhotoGallery']['id']);
				$gallery_landing_photo['PhotoGallery'] = $curr_gallery['PhotoGallery'];
				if (!empty($gallery_landing_photo)) {
					$photos[] = $gallery_landing_photo;
				}
			}

			$this->Photo->add_photo_format($photos);

			
			$this->WhiteAngular->process_photos_for_angular_slide($photos);
		?>
		
		<div id="image_slider_outer_container">
			<div id="slider_info_container">
				<img class="scroll_up_right" src="/img/scroll_up_right.png" alt="" />
				<div class="top_info_line">&nbsp;</div>
				<div class="gallery_info_cont">
					<?php foreach ($photos as $photo): ?>
						<?php if (isset($photo['actual_image'])): ?>
							<?php $photos_count = $this->Gallery->count_gallery_photos($photo); ?>
					
							<div class="curr_gallery_info" photo_gallery_id="<?php echo $photo['PhotoGallery']['id']; ?>">
								<div class="content">
									<h2><?php echo $photo['PhotoGallery']['display_name']; ?></h2>
									<h3><?php echo sprintf(__('%1$d PHOTOS', true), $photos_count); ?></h3>
									<div class="thick_line"></div>
								</div>
								<div class="line"></div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<img class="scroll_down_left" src="/img/scroll_down_left.png" alt="" />
			</div>
			<div id="image_slider_container">
				<?php foreach ($photos as &$photo): ?>
					<?php  
						extract($this->WhiteAngular->process_angular_photo_data($photo));
						//compact('blank', 'img_src', 'alt_img_src', 'total_width', 'total_height', 'alt_total_width', 'alt_total_height', 'distance_to_close', 'cover_width_left', 'cover_width_right', 'left');
					?>
					<div photo_gallery_id="<?php if (isset($photo['PhotoGallery']['id'])) echo $photo['PhotoGallery']['id']; ?>" photo_id="<?php if (isset($photo['Photo']['id'])) { echo $photo['Photo']['id']; } ?>" class="float_image_cont <?php echo implode(' ', $photo['classes']); ?>" style="width: 720px; height: 310px; left: <?php echo $left; ?>px;" start_left="<?php echo $left; ?>" img_width="<?php echo $total_width; ?>" img_height="<?php echo $total_height; ?>">
						<?php if (isset($alt_img_src)): ?>
							<div class="img_outer_cont when_closed">
								<div class="img_cont" style="width: <?php echo $alt_total_width; ?>px; height: <?php echo $alt_total_height; ?>px; margin-left: <?php echo -floor($alt_total_width/2); ?>px; margin-top: <?php echo -floor($alt_total_height/2); ?>px;">
									<div class="img_inner_wrap">
										<img src="<?php echo $alt_img_src['url']; ?>" style="display: block; width: <?php echo $alt_img_src['width']; ?>px; height: <?php echo $alt_img_src['height']; ?>px;" <?php echo $alt_img_src['tag_attributes']; ?> alt="" />
									</div>
								</div>
							</div>
						<?php else: ?>
							<div class="img_outer_cont">
								<div class="img_cont" style="width: <?php echo $total_width; ?>px; height: <?php echo $total_height; ?>px; margin-left: <?php echo -round($total_width/2); ?>px; margin-top: <?php echo -round($total_height/2); ?>px;">
									<div class="img_inner_wrap">
										<img src="<?php echo $img_src['url']; ?>" style="display: block; width: <?php echo $img_src['width']; ?>px; height: <?php echo $img_src['height']; ?>px;" <?php echo $img_src['tag_attributes']; ?> alt="" />
									</div>
								</div>
							</div>
						<?php endif; ?>
						<div class="left_cover_image" open_left="<?php echo $cover_width_left; ?>" closed_left ="<?php echo $cover_width_left + $distance_to_close; ?>" style="left: <?php echo $cover_width_left + $distance_to_close; ?>px;">
							<div class="one">&nbsp;</div>
							<div class="two">&nbsp;</div>
							<div class="three">&nbsp;</div>
							<div class="four">&nbsp;</div>
						</div>
						<div class="right_cover_image" open_left="<?php echo $cover_width_right; ?>" closed_left="<?php echo $cover_width_right - $distance_to_close; ?>" style="left: <?php echo $cover_width_right - $distance_to_close; ?>px;">
							<div class="one">&nbsp;</div>
							<div class="two">&nbsp;</div>
							<div class="three">&nbsp;</div>
							<div class="four">&nbsp;</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div id="images_loading_tab">
				<div id="progress_bar">
					<div class="percent_text"><span>0</span>%</div>
					<div class="ui-progressbar-value">
					</div>
				</div>
			</div>
		</div>
	</body>
</html>