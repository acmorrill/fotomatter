<!DOCTYPE html>	
<html>
    <head>
        <title><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> </title>
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
                        <?php echo $this->Element('gallery/gallery_image_lists/simple_list', array(
                                'photos' => $curr_photo,
                                //'image_max_size' => 150,
                                'height' => 800,
                                'width' => 800
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
