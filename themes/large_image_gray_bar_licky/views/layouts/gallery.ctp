<!DOCTYPE html>	
<html>
    <head>
        <title><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> </title>
		<?php echo $this->Element('theme_global_includes'); ?>
        <link rel="stylesheet" type="text/css" href="/css/large_image_gray_bar.css" />
		<link href='http://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
    </head>
	<?php $accent_colors = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'accent_colors'); ?>
	<?php $image_cropping = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'image_cropping'); ?>
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
                    <h1 class="<?php echo $accent_colors; ?>"><?php echo "<b>", $curr_gallery['PhotoGallery']['display_name'], "</b>"; ?></h1>
                    <div class="gallery">
                        <?php echo $this->Element('gallery/gallery_image_lists/4_column_dymanic_view_gallery', array(
                                'photos' => $photos,
                                'image_max_size' => 150,
				'crop' => $image_cropping,
                        )); ?>
                    </div> 
                </div>
            </div>
            <div class="footer">
                <div class="inner_footer">
                    <?php echo $this->Element('global_theme_footer_copyright'); ?> 
                </div>
            </div>
        </div>
    </body>
</html>
