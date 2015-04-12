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
		<div style="clear: both;"></div>
		<?php $count = 1; foreach ($all_themes as $curr_theme): ?>
				<?php $is_current_theme = $curr_theme['Theme']['ref_name'] == $current_theme; ?>
				<?php 
					$theme_help_code = ''; 		
					if ($count === 1) {
						$theme_help_code = 'data-step="1" data-intro="'.__("All the themes are awesome and quite different so try lots of them to see what you'd like to use for your stunning site.", true).'" data-position="right"';
					}
				?>
				<div class="theme_item_container <?php if ($is_current_theme === true) echo 'current'; ?>" <?php echo $theme_help_code; ?>>
					<div class="theme_item_outer_container">
						<div class="theme_item_inner_container">
							<?php 
//								$base_path = ROOT.DS.APP_DIR.DS.'webroot'.DS.'img'.DS.'theme_screenshots'.DS;
								$base_path = "https://d4f9baf5b96f3eeed9c9-4af0fb97e675ed71f0af4b096cd907ac.ssl.cf2.rackcdn.com/";
							
								$large_image_abs_path = $base_path.$curr_theme['Theme']['ref_name'].'_large.jpg';
								$small_image_abs_path = $base_path.$curr_theme['Theme']['ref_name'].'_small.jpg';
								$large_image_web_path = $large_image_abs_path;
								$small_image_web_path = $small_image_abs_path;

								$large_default_web_path = '/img/theme_screenshots/default_large.jpg';
								$small_default_web_path = '/img/theme_screenshots/default_small.jpg';

								if (!$this->Util->url_exists($large_image_abs_path)) {
									$large_image_web_path = $large_default_web_path;
								}
								if (!$this->Util->url_exists($small_image_abs_path)) {
									$small_image_web_path = $small_default_web_path;
								}
								
								$theme_image_cache_apc_key = $small_image_abs_path;
								if (apc_exists($theme_image_cache_apc_key)) {
									$image_data = apc_fetch($theme_image_cache_apc_key);
								} else {
									$image_data = array();
									$image_data['image_width'] = 400;
									$image_data['image_height'] = 250;
									$image_data['image_attr'] = 'width="400" height="250"';
									if ($this->Util->url_exists($small_image_abs_path)) {
										list($image_width, $image_height, $image_type, $image_attr) = @getimagesize($small_image_abs_path);
										$image_data = array();
										$image_data['image_width'] = $image_width;
										$image_data['image_height'] = $image_height;
										$image_data['image_attr'] = $image_attr;
										apc_store($theme_image_cache_apc_key, $image_data, 604800); // 1 week
									}
								}
							?>								
							<div class="screenshot_container">
								<div class="screenshot_inner_container" style="width: <?php echo $image_data['image_width']; ?>px; height: <?php echo $image_data['image_height']; ?>px; max-height: 319px; overflow: hidden;">
									<a style="width: <?php echo $image_data['image_width']; ?>px; height: <?php echo $image_data['image_height']; ?>px;" target="<?php echo $large_image_web_path; ?>" href="<?php echo $large_image_web_path; ?>" title="<?php echo $curr_theme['Theme']['display_name']; ?>">
										<img <?php echo $image_data['image_attr']; ?> src="<?php echo $small_image_web_path; ?>" title="<?php echo $curr_theme['Theme']['display_name']; ?>" alt="" />
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
														<div type="submit" value="" ><div class="content"><?php echo __('Current',true); ?></div></div>
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
	
	

