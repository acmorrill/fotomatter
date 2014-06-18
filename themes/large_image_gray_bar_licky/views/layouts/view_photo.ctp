<!DOCTYPE html>	
<html>
    <head>
        <title><?php echo $curr_photo['Photo']['display_title'] . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $curr_gallery['PhotoGallery']['display_name']; ?></title>
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
                <div class="background_photo">
                    <h1><?php echo $curr_photo['Photo']['display_title']; ?></h1>
                    <div class="gallery">                  
                        <?php $img_src = $this->Photo->get_photo_path($curr_photo['Photo']['id'], 700, 700, .4, true); ?>
                        <img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
                        <?php $prev_image_web_path = $this->Photo->get_prev_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
                        <?php $next_image_web_path = $this->Photo->get_next_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
                    </div>
                </div>
                <div class="photo_description">
                    <h1><strong>Photo Description</strong></h1>
                    <ul class="dark_background separator">
                        <li class="list_item"><?php echo $curr_photo['Photo']['date_taken']; ?></li>
                        <li class="list_item"><?php echo $curr_photo['Photo']['display_title']; ?></li>
                        <li class="list_item"><?php echo $curr_photo['Photo']['display_subtitle']; ?></li>
                        <li class="list_item"><?php echo $curr_photo['Photo']['description']; ?></li>
                    </ul>
                </div>
                <div class="sidebar">

                    <ul class="dark_background" >
                        <li><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></li>
                    </ul>	
                    <ul class="dark_background separator">
                        <li class="small_text_header"><strong>Add to cart</strong></li>
                        <li class="list_item"><?php echo $curr_photo['Photo']['date_taken']; ?></li>
                        <li class="list_item"><?php echo $curr_photo['Photo']['display_title']; ?></li>
                        <li class="list_item"><?php echo $curr_photo['Photo']['display_subtitle']; ?></li>
                        <li class="list_item"><?php echo $curr_photo['Photo']['description']; ?></li>
                    </ul>
                    <ul class="dark_background separator">
                        <li class="small_text_header"><strong>Categories</strong></li>
						<?php $galleries = $this->Gallery->get_all_galleries(); ?>
						<?php foreach($galleries as $curr_gallery): ?>
							<li class="list_item"><a href="<?php '/photo_galleries/view_gallery/'.$curr_gallery['PhotoGallery']['id']; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a></li>
						<?php endforeach; ?>
                    </ul>


                </div><!-- #sidebar -->            
            </div>
            <div class="footer">
                <div class="inner_footer">
                    <?php echo $this->Element('global_theme_footer_copyright'); ?>
                </div>
            </div>
        </div>
    </body>
</html>


