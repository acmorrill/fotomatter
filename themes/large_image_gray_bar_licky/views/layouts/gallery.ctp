<!DOCTYPE html>	
<html>
	<head>
		<title><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> s</title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/large_image_gray_bar.css" />
	</head>
	<body>
		<div class="content">
			<div class="outer_nav">
				<?php echo $this->Element('nameTitle'); ?>
				<div class="nav">
					<?php echo $this->Element('menu/two_level_navbar'); ?>											
				</div>					
			</div>

			<div class="gallerywrapper">

				<div class="background">

					<h1><?php echo "<b>", $curr_gallery['PhotoGallery']['display_name'], "</b>"; ?></h1>

					<div class="gallery">

						<?php echo $this->Element('gallery/gallery_image_lists/4_column_dymanic_view_gallery', array(
							'photos' => $photos,
							'image_max_size' => 200
						)); ?>

					</div> <!--- gallery-->
				</div><!--- background-->
			</div><!--- gallerywrapper-->
		</div>
	</body>
</html>
