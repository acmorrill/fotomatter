<?php 
// current theme
$current_theme = $this->SiteSetting->getVal('current_theme', false);

// get all themes
$all_themes = $this->Theme->get_all_available_themes();

?>


	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.button_switch_theme').click(function() {
				var switch_to_theme_id = jQuery(this).attr('data-current-theme-id');
				jQuery("#choose_theme_"+switch_to_theme_id+"_form").submit();
			});
		});
	</script>
	
	
	
	<div id="theme_chooser_container" data-step="1" data-intro="<?php echo __('Here you can choose what theme you would like to use for your site.', true); ?>" data-position="left">
		<?php echo $this->Element('/admin/get_help_button'); ?>
		<div style="clear: both;"></div>
		<?php $count = 1; foreach ($all_themes as $curr_theme): ?>
				<?php $is_current_theme = $curr_theme['Theme']['ref_name'] == $current_theme; ?>
				<div class="theme_item_container <?php if ($is_current_theme === true) echo 'current'; ?>">
					<div class="theme_item_outer_container">
						<div class="theme_item_inner_container">
							<?php 
								$large_image_abs_path = ROOT.DS.APP_DIR.DS.'webroot'.DS.'img'.DS.'theme_screenshots'.DS.$curr_theme['Theme']['ref_name'].'_large.jpg';
								$small_image_abs_path = ROOT.DS.APP_DIR.DS.'webroot'.DS.'img'.DS.'theme_screenshots'.DS.$curr_theme['Theme']['ref_name'].'_small.jpg';
								$large_image_web_path = '/img/theme_screenshots/'.$curr_theme['Theme']['ref_name'].'_large.jpg';
								$small_image_web_path = '/img/theme_screenshots/'.$curr_theme['Theme']['ref_name'].'_small.jpg';

								$large_default_web_path = '/img/theme_screenshots/default_large.jpg';
								$small_default_web_path = '/img/theme_screenshots/default_small.jpg';

								if (!file_exists($large_image_abs_path)) {
									$large_image_web_path = $large_default_web_path;
								}
								if (!file_exists($small_image_abs_path)) {
									$small_image_web_path = $small_default_web_path;
								}
							?>
							<div class="screenshot_container">
								<div class="screenshot_inner_container" data-step="3" data-intro="<?php echo __('This sreen shot displays a brief look at what the theme will look like.', true); ?>" data-position="bottom">
									<a href="<?php echo $large_image_web_path; ?>" class="screenshot_zoom" title="<?php echo $curr_theme['Theme']['display_name']; ?>" rel="gal1">
										<img src="<?php echo $small_image_web_path; ?>" title="<?php echo $curr_theme['Theme']['ref_name']; ?>">
									</a>
									<div style="clear: both;"></div>
								</div>
							</div>
							<div style="clear: both;"></div>
							<div class="container_rectangular_box" >
								<div class="add_text">
									<table>
										<tbody>
											<tr>
												<td>
													<h2><?php echo $curr_theme['Theme']['display_name']; ?></h2>
												</td>	
											</tr>
										</tbody>
									</table>
								</div>
								<?php if (!$is_current_theme): ?>
									<form id="choose_theme_<?php echo $curr_theme['Theme']['id']; ?>_form" action="/admin/theme_centers/choose_theme" method="POST">
										<input type="hidden" name="data[new_theme_id]" value="<?php echo $curr_theme['Theme']['id']; ?>" />
									</form>
									<div class="usable_form" action="<?php echo $this->here; ?>" method="post">
										<div class="button_switch_theme add_button" data-current-theme-id="<?php echo $curr_theme['Theme']['id']; ?>" data-step="4" data-intro="<?php echo __('You may try out a new theme by clicking the select button.<br> Test out as many themes as you would like until you find the right one for you.', true); ?>" data-position="left">
											<div class="content"><?php __('Select'); ?></div><div class="right_arrow_lines"><div></div></div>
										</div>
									</div>
								<?php else: ?>
									<div class="button_current_theme add_button" data-step="2" data-intro="<?php echo __('This button displays your current theme selection.', true); ?>" data-position="bottom">
										<div type="submit" value="" ><div class="content"><?php __('Current'); ?></div><div class="right_arrow_lines"><div></div></div></div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>	
				</div>
		<?php $count++; endforeach; ?>
	</div>
	
	

