<!DOCTYPE html>
<html>
	<head>
		<title><?php print($curr_photo['Photo']['display_title']." - ".$curr_photo['Photo']['display_subtitle']." - ".$curr_gallery['PhotoGallery']['display_name']);?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />

		<?php $is_pano = $curr_photo['PhotoFormat']['ref_name'] == "panoramic"; ?>
		
		<?php if ($is_pano): ?>
			<link rel="stylesheet" type="text/css" href="/stylesheets/panoBackground.css" />
		<?php else: ?>
			<link rel="stylesheet" type="text/css" href="/stylesheets/photoBackground.css" />
		<?php endif; ?>
		
	</head>
	<body>
		<?php //$this->log($photo_sellable_prints, 'photo_sellable_prints'); ?>
		
		
		<?php echo $this->Element('nameTitle'); ?>
		
		<div id="largePhotoPos" style="width: 892px">
			<?php $img_src = $this->Photo->get_photo_path($curr_photo['Photo']['id'], 700, 700, .4, true); ?>
			
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#mainImage .prev_image img').attr('src', '/images/misc/arrowLeft.png');
					jQuery('#mainImage .next_image img').attr('src', '/images/misc/arrowRight.png');
				});
			</script>
			
			<div id="mainImage">
				<img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
				<?php $prev_image_web_path = $this->Photo->get_prev_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
				<a class="photo_page_nav prev_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $prev_image_web_path; ?>">
					<img onmouseover="this.src='/images/misc/arrowLeftRed.png';" onmouseout="this.src='/images/misc/arrowLeft.png';" src="/images/misc/arrowLeftRed.png" />
				</a>
				<?php $next_image_web_path = $this->Photo->get_next_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
				<a class="photo_page_nav next_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $next_image_web_path; ?>">
					<img onmouseover="this.src='/images/misc/arrowRightRed.png';" onmouseout="this.src='/images/misc/arrowRight.png';" src="/images/misc/arrowRightRed.png" />
				</a>
			</div>

			<a name="availablePrints"></a>
			<h2 class="photoTitle"><?php print("\"<b>{$curr_photo['Photo']['display_title']}</b>\""); ?></h2>
			<p style="margin-bottom: 13px;"><?php print("{$curr_photo['Photo']['display_subtitle']}"); ?><br/>
				<?php $phpdate = strtotime( 'last monday' ); ?>
				<?php echo date("F Y",$phpdate); ?>
			</p>

			<p style="width: 520px"><?php print("{$curr_photo['Photo']['description']}"); ?></p>
			
			
			<?php echo $this->Element('cart_checkout/image_add_to_cart_form_simple'); ?>
			
			<img src="/images/misc/horiz_gradientline.png" />
			<br/><br/>
			<br/><br/>
			<br/><br/>
			<br/><br/>
		</div>
	</body>
</html>
