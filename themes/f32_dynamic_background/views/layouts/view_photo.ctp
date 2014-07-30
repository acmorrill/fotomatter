<!DOCTYPE html>	
<html>
    <head>
        <title><?php echo $curr_photo['Photo']['display_title'] . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $curr_gallery['PhotoGallery']['display_name']; ?></title>
        <?php echo $this->Element('theme_global_includes'); ?>
        <link rel="stylesheet" type="text/css" href="/css/f32_dynamic_background.css" />
        <link href='http://fonts.googleapis.com/css?family=Actor' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <!--<?php $accent_colors = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'accent_colors'); ?>    <?php echo $accent_colors; ?>-->
        <div class="content">
            <div class="outer_nav">
                <?php echo $this->Element('nameTitle'); ?>
                <div class="nav">
                    <?php echo $this->Element('menu/navBar', array('page' => 'home')); ?>											
                </div>					
            </div>
            <div class="gallerywrapper">
                <div class="gallery">                  
                    <?php $img_src = $this->Photo->get_photo_path($curr_photo['Photo']['id'], 700, 700, .4, true); ?>
                    <img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
                </div>
                <?php if (!empty($curr_photo['Photo']['description'])): ?>
                <div class="extra">
                    <div class="view_page_paragraph">
                        <h1>"<?php echo $curr_photo['Photo']['display_title']; ?>"</h1>
                        <ul class="<?php echo $accent_colors; ?>">
                            <li><?php echo $curr_photo['Photo']['date_taken']; ?></li>						
                            <li><?php echo $curr_photo['Photo']['display_subtitle']; ?></li>
                        </ul>
    <!--                    <h1 class="<?php echo $accent_colors; ?>"><strong><?php __('Photo Description'); ?></strong></h1>-->
                        <ul>                      
                            <li><?php echo $curr_photo['Photo']['description']; ?></li>
                        </ul>
                        <?php endif; ?>
                    </div><!--/view_page_paragraph --> 
                    
                    <div class="cart_bottom">
                            <ul>
        <!--                        <li><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></li>-->
                            </ul>
                            <?php $photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($photo_id); ?>
                            <?php if (!empty($photo_sellable_prints)): ?>
                                <ul>
        <!--                            <li><strong><?php __('Add to cart'); ?></strong></li>-->
                                    <li>
                                        <?php
                                        echo $this->Element('cart_checkout/image_add_to_cart_form_simple', array(
                                            'submit_button_text' => __('Add to Cart', true),
                                            'photo_sellable_prints' => $photo_sellable_prints,
                                        ));
                                        ?>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div><!--/cart_bottom --> 
                </div><!--/extra --> 
            

            <div class="prev_next">  
                <ul>
                    <?php $prev_image_web_path = $this->Photo->get_prev_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
                    <?php $next_image_web_path = $this->Photo->get_next_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
                    <li <?php echo $accent_colors; ?>><a href="<?php echo $prev_image_web_path; ?>"><< previous</a>&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</li>                        
                    <li <?php echo $accent_colors; ?>><a href="<?php echo $next_image_web_path; ?>">next >></a></li>                        
                </ul>
            </div><!--/prev_next-->
        </div>
        <div class="footer">
            <div class="inner_footer">
                <?php echo $this->Element('global_theme_footer_copyright'); ?>
            </div>
        </div>
    </div>
</body>
</html>


