<!DOCTYPE html>
<html>
    <head>
        <title>Kent Gigger's In Your Face Photography</title>
        <meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
        <meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
        <link rel="stylesheet" type="text/css" href="/css/f32_dynamic_background.css" />
        <link href='http://fonts.googleapis.com/css?family=Actor' rel='stylesheet' type='text/css'>
        <?php echo $this->Element('theme_global_includes'); ?>
    </head>
    <body>
        <?php $accent_colors = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'accent_colors'); ?>	
        <div class="outer_nav">
            <?php echo $this->Element('nameTitle'); ?>
            <div class="nav <?php echo $accent_colors; ?>">
                <?php echo $this->Element('menu/navBar', array('page' => 'home')); ?>											
            </div>					
        </div>
        <div class="content">
                <div class="inner_content <?php echo $accent_colors; ?>">
                    <div class="check_out">                    
                    <?php echo $content_for_layout; ?>
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