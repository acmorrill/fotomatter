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
                <h1 class="gallery_header"><?php __('Choose a Gallery'); ?></h1>
             <?php echo $this->Element('gallery/gallery_landing/2_column_gallery_landing', array()); ?>          
            </div>
            <div class="footer">
                <div class="inner_footer">
                    <?php echo $this->Element('global_theme_footer_copyright'); ?>
                </div>
            </div>
        </div>
    </body>
</html>
