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
        <div class="outer_nav">
            <?php echo $this->Element('nameTitle'); ?>
            <div class="nav">
                <?php echo $this->Element('menu/navBar', array('page' => 'home')); ?>											
            </div>					
        </div>
        <div class="content">
            <div class="gallery_table_outer">   
                <div class="gallery_table_inner">
                <h1 class="gallery_title"><?php __('Choose a Gallery'); ?></h1>
                <?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
                <?php foreach ($all_galleries as $curr_gallery): ?>
                    <?php
                        $curr_gallery_href = $this->Html->url(array(
                            'controller' => 'photo_galleries',
                            'action' => 'view_gallery',
                            $curr_gallery['PhotoGallery']['id']
                        ));
                        $photo_id = $this->Gallery->get_gallery_photo_id($curr_gallery['PhotoGallery']['id']); 
                    ?>
                    <?php $img_src = $this->Photo->get_photo_path($photo_id, 250, 250, .4, true); ?>                      
                    <img class="gallery_image_actual_image" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?>>
                    <div class="gallery_links">
                    <a href="<?php echo $curr_gallery_href; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a>
                    </div>
                <?php endforeach; ?>
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