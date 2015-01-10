<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Photo->get_photo_html_title_str($curr_photo, $curr_gallery); ?><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />

		<?php $is_pano = $curr_photo['PhotoFormat']['ref_name'] == "panoramic"; ?>
		
		<?php if ($is_pano): ?>
			<link rel="stylesheet" type="text/css" href="/stylesheets/panoBackground.css" />
		<?php else: ?>
			<link rel="stylesheet" type="text/css" href="/stylesheets/photoBackground.css" />
		<?php endif; ?>

		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<div id="largePhotoPos">
			<?php 
				$dynamic_photo_sizes = $this->Theme->get_dynamic_photo_size(700, 1000, 1200, $curr_photo['PhotoFormat']['ref_name']);
				$img_src = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $dynamic_photo_sizes['photo_size'], $dynamic_photo_sizes['photo_size'], .4, true); 
			?>
			
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#main_image_outer .prev_image img').attr('src', '/images/misc/arrowLeft.png');
					jQuery('#main_image_outer .next_image img').attr('src', '/images/misc/arrowRight.png');
				});
			</script>
			
			<table id="main_image_outer" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<td valign="top">
							<?php $prev_image_web_path = $this->Photo->get_prev_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
							<a class="photo_page_nav prev_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $prev_image_web_path; ?>">
								<img onmouseover="this.src='/images/misc/arrowLeftRed.png';" onmouseout="this.src='/images/misc/arrowLeft.png';" src="/images/misc/arrowLeftRed.png" alt="" />
							</a>
							<?php $next_image_web_path = $this->Photo->get_next_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
							<a class="photo_page_nav next_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $next_image_web_path; ?>">
								<img onmouseover="this.src='/images/misc/arrowRightRed.png';" onmouseout="this.src='/images/misc/arrowRight.png';" src="/images/misc/arrowRightRed.png" alt="" />
							</a>
							<div id="mainImage">
								<img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
								<div id='photo_bread_crumbs'>
									<a href='/'>home</a> >
									<a href='/photo_galleries/choose_gallery'>image galleries</a>
									<?php 
										$curr_gallery_href = '';
										if (!empty($curr_gallery['PhotoGallery']['id'])) {
											$curr_gallery_href = $this->Html->url(array(    
												'controller' => 'photo_galleries',    
												'action' => 'view_gallery',    
												$curr_gallery['PhotoGallery']['id']
											));
										}
									?>
									<?php if (!empty($curr_gallery_href) && !empty($curr_gallery['PhotoGallery']['display_name'])): ?>
										> <a href='<?php echo $curr_gallery_href; ?>'><?php echo strtolower($curr_gallery['PhotoGallery']['display_name']); ?></a>
									<?php endif; ?>
								</div>
								<div id='sizing_tools' class='sizing_tools'>
									<div class='sizing_button small <?php if ($dynamic_photo_sizes['current_size'] == 'small'): ?> active <?php endif; ?>'><span>S</span></div>
									<div class='sizing_button medium <?php if ($dynamic_photo_sizes['current_size'] == 'medium'): ?> active <?php endif; ?>'><span>M</span></div>
									<div class='sizing_button large <?php if ($dynamic_photo_sizes['current_size'] == 'large'): ?> active <?php endif; ?>'><span>L</span></div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<a name="availablePrints"></a>
			<h2 class="photoTitle"><?php print("\"<b>{$curr_photo['Photo']['display_title']}</b>\""); ?></h2>
			<p style="margin-bottom: 13px;">
				<?php if (!empty($curr_photo['Photo']['display_subtitle'])): ?>
					<?php print("{$curr_photo['Photo']['display_subtitle']}"); ?>
					<br/>
				<?php endif; ?>
				<?php $phpdate = strtotime( 'last monday' ); ?>
				<?php echo date("F Y",$phpdate); ?>
			</p>

			<p style="width: 520px"><?php print("{$curr_photo['Photo']['description']}"); ?></p>
			<?php echo $this->Element('cart_checkout/image_add_to_cart_form_simple', array(
				'beforeHtml' => '<img src="/images/misc/horiz_gradientline.png" alt="" />'
			)); ?>
			
			<br/><br/>
			<br/><br/>
			<br/><br/>
			<br/><br/>
		</div>
	</body>
</html>
