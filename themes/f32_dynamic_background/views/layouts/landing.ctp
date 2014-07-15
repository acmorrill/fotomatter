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
        <?php //echo $content_for_layout; ?>
        <div class="content">
            <div class="outer_nav">
                <?php echo $this->Element('nameTitle'); ?>
                <div class="nav">
                    <?php echo $this->Element('menu/navBar', array( 'page' => 'home' ));?>											
                </div>					
            </div>
            <div id="slide_show">
                    <?php echo $this->Element('landing_slideshows/basic', array(
                            'width' => 556,
                            'height' => 453,
                            'background_color' => '#efefef',
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