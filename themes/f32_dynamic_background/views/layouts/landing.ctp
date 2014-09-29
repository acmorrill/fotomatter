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
        <?php $accent_colors = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'accent_colors'); ?> 
         <?php $welcome_paragraph = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'welcome_paragraph'); ?>
        <?php //echo $content_for_layout; ?>
        <div class="outer_nav">
            <?php echo $this->Element('nameTitle'); ?>
            <div class="nav <?php echo $accent_colors; ?>">
                <?php echo $this->Element('menu/navBar', array('page' => 'home')); ?>											
            </div>					
        </div>		
        <div class="content">
            <!-- The slideshow .js needs to be replaced-->
            <div id="slide_show">
                    <?php echo $this->Element('landing_slideshows/basic', array(
                            'width' => 800,
                            'height' => 510,
                            'background_color' => '#efefef',
                    )); ?>                    
            </div>
            <div class="welcome_outer">
            <div class="welcome_paragraph">
                <?php echo substr($welcome_paragraph, 0, 370); ?>
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