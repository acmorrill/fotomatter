<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:addthis="http://www.addthis.com/help/api-spec" class="html100">
	<head>


		<title>Kent Gigger's In Your Face Photography</title>
		<meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
			<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
				<link rel="stylesheet" type="text/css" href="/css/large_image_gray_bar.css" />

				<?php echo $this->Element('theme_global_includes'); ?>

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

								<h1><?php __('Galleries'); ?></h1>

								<div class="gallery">

									<?php echo $this->Element('gallery/gallery_image_lists/4_column_dymanic'); ?>


								</div> <!--- gallery-->
							</div><!--- Picture-->
						</div><!--- gallerywrapper-->

						<div class="footer">
							<div class="inner_footer">
								<?php echo $this->Element('global_theme_footer_copyright'); ?>
							</div>
						</div>

					</div><!--- End Content-->

				</body>
				</html>