<!DOCTYPE html>
<html>
	<head>
		<title>Kent Gigger's In Your Face Photography</title>
        <meta name="keywords" content="Kent Gigger, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
        <meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<link rel="stylesheet" type="text/css" href="/css/large_image_gray_bar.css" />
		<!--[if lt IE 9]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
		<![endif]-->
		<?php echo $this->Element('theme_global_includes'); ?>
	</head>
	<body>
	<?php $accent_colors = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'accent_colors'); ?>	
		<div class="content">
            <div class="outer_nav">
                <?php echo $this->Element('nameTitle'); ?>
                <div class="nav">
                    <?php echo $this->Element('menu/two_level_navbar'); ?>											
                </div>					
			</div>
			 <div class="gallerywrapper">
				 <div class="bottom_margin">
					<div class="background_photo <?php echo $accent_colors; ?>">
<!--						<h1><?php __('Login'); ?></h1>-->
						<?php echo $content_for_layout; ?>
					</div> 
				 </div>	 
				 <div class="clear"></div>
				 <div class="sidebar">
                    <ul class="dark_background" >
                        <li><strong><?php __('Shop'); ?></strong></li>
                    </ul>
                    <ul class="dark_background separator">
                        <li class="small_text_header"><strong><?php __('Galleries'); ?></strong></li>
                        <?php $galleries = $this->Gallery->get_all_galleries(); ?>
                        <?php foreach($galleries as $the_curr_gallery): ?>
                        <li class="list_item">
							<a href="<?php echo '/photo_galleries/view_gallery/'.$the_curr_gallery['PhotoGallery']['id']; ?>">
								<?php echo $the_curr_gallery['PhotoGallery']['display_name']; ?>
							</a>
						</li>
                        <?php endforeach; ?>
                    </ul>
                </div><!--sidebar --> 
			</div><!--gallerwrapper --> 					
		</div>
		<div class="footer">
			<div class="inner_footer">
				<?php echo $this->Element('global_theme_footer_copyright'); ?>
			</div>
		</div>		
	</body>
</html>