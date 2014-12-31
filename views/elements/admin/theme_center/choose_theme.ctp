<?php 
// current theme
$current_theme = $this->SiteSetting->getVal('current_theme', false);

// get all themes
$all_themes = $this->Theme->get_all_available_themes();

if (empty($change_theme_action)) {
	$change_theme_action = '/admin/theme_centers/choose_theme';
}

if (!isset($hide_current)) {
	$hide_current = false;
}

?>


	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.button_switch_theme').click(function() {
				var switch_to_theme_id = jQuery(this).attr('data-current-theme-id');
				jQuery("#choose_theme_"+switch_to_theme_id+"_form").submit();
			});
		});
	</script>
	
	
	
	<div id="theme_chooser_container">
		<?php echo $this->Element('/admin/get_help_button'); ?>
		<div data-step="1" data-intro="<?php echo __('All the themes are awesome and quite different so try lots of them to see what you’d like to use for your stunning site.', true); ?>" data-position="bottom"></div>
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
								<div class="screenshot_inner_container">
									<a href="<?php echo $large_image_web_path; ?>" class="screenshot_zoom" title="<?php echo $curr_theme['Theme']['display_name']; ?>" rel="gal1">
										<img src="<?php echo $small_image_web_path; ?>" title="<?php echo $curr_theme['Theme']['ref_name']; ?>" alt="" />
									</a>
									<div style="clear: both;"></div>
								</div>
							</div>
							<div style="clear: both;"></div>
							<div class="container_rectangular_box custom_ui" >
								<table>
									<tbody>
										<tr>
											<td class="choose_theme_text">
												<h2><?php echo $curr_theme['Theme']['display_name']; ?></h2>
											</td>	
											<td class="choose_theme_button">
												<?php if (!$is_current_theme || $hide_current === true): ?>
													<form id="choose_theme_<?php echo $curr_theme['Theme']['id']; ?>_form" action="<?php echo $change_theme_action; ?>" method="POST">
														<input type="hidden" name="data[new_theme_id]" value="<?php echo $curr_theme['Theme']['id']; ?>" />
													</form>
													<div class="usable_form" action="<?php echo $this->here; ?>" method="post">
														<div class="button_switch_theme add_button" data-current-theme-id="<?php echo $curr_theme['Theme']['id']; ?>">
															<div class="content"><?php echo __('Select',true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div>
														</div>
													</div>
												<?php else: ?>
													<div class="button_current_theme add_button" data-step="2" data-intro="<?php echo __('Under the current theme the button will stay current.', true); ?>" data-position="top">
														<div type="submit" value="" ><div class="content"><?php echo __('Current',true); ?></div><div class="right_arrow_lines icon-arrow-01"><div></div></div></div>
													</div>
												<?php endif; ?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>	
				</div>
		<?php $count++; endforeach; ?>
	</div>
	
	

