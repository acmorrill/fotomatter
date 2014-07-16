<!DOCTYPE html>
<html>
    <head>
        <title>Kent Gigger's In Your Face Photography</title>
        <meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
        <meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
        <link rel="stylesheet" type="text/css" href="/css/f32_dynamic_background.css" />
        <link href='http://fonts.googleapis.com/css?family=Actor' rel='stylesheet' type='text/css'>
    </head>
    <body>
         <?php $welcome_paragraph = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'welcome_paragraph'); ?>
        <?php //echo $content_for_layout; ?>
        <div class="content">
            <div class="outer_nav">
                <?php echo $this->Element('nameTitle'); ?>
                <div class="nav">
                    <?php echo $this->Element('menu/navBar', array( 'page' => 'home' ));?>											
                </div>					
            </div>
            <!-- The slideshow .js needs to be replaced-->
            <h1><?php echo "<b>", $curr_gallery['PhotoGallery']['display_name'], "</b>"; ?></h1>
            <div class="gallery">
                <?php echo $this->Element('gallery/gallery_image_lists/4_column_dymanic_view_gallery', array(
                        'photos' => $photos,
                        'image_max_size' => 250,
                        //'crop' => $image_cropping,
                )); ?>
            </div> 
            <div class="footer">
                <div class="inner_footer">
                    <?php echo $this->Element('global_theme_footer_copyright'); ?>
                </div>
            </div>
        </div>
    </body>
</html>