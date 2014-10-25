<!DOCTYPE html>
<html>
	<head>
		<title>Kent Gigger -- Face Photography</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California, Large Format">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<?php echo $this->Element('grezzo_includes'); ?>
	</head>
	<?php $background_color = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'background_color'); ?>
	<body class="<?php echo $background_color; ?>">
		<?php $header_is_full_width = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'header_is_full_width'); ?>
	<div class="<?php if ($header_is_full_width == 'on') echo ' header_is_full_width '; ?>">
		<div id='outer_nav'>
			<div id="logo_nav_cont">
				<?php echo $this->Element('nameTitle'); ?>
				<?php echo $this->Element('menu/two_level_navbar'); ?>
			</div>	
		</div>
	</div>	
		<div style="clear:both"></div>
			<div class="out_page_content">
				<div id="gray_spacing_bar"></div>
					<div id="cart">
						<div class="page-content">
							<div class="outer_page_cart">								
							<?php echo $content_for_layout; ?>
							<?php echo $this->Element('global_theme_footer_copyright'); ?>
							</div>
						</div>
					</div>
				</div>
		</body>
	</html>
