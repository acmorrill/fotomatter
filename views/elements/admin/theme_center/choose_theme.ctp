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
		var in_callback = false;
		jQuery(document).ready(function() {
			jQuery('#progress_dialog').dialog({
				autoOpen: false,
				closeOnEscape: false,
				draggable: false,
				dialogClass: "wide_dialog",
				title: "<?php echo __('Switching Theme', true); ?>",
				modal: true,
				resizable: false,
				minHeight: 200,
				create: function(event, ui) {
					$(".ui-dialog-titlebar-close", ui.dialog).hide();
				}
			});
			jQuery('.ui-dialog').append("<div class='ui-dialog-progresspane'><div id='modal_progressbar' class='custom_progress'><div class='progress' role='progressbar' aria-valuemin='0' aria-valuemax='100'></div></div></div>");

			jQuery('.button_switch_theme').click(function() {
				<?php if ($this->params['controller'] === 'welcome'): ?>
				var switch_to_theme_id = jQuery(this).attr('data-current-theme-id');
				jQuery("#choose_theme_"+switch_to_theme_id+"_form").submit();
				<?php else: ?>
				if (in_callback === true) {
					return;
				}
				in_callback = true;
				var $this = jQuery(this);
				var switch_to_theme_id = $this.attr('data-current-theme-id');
				show_universal_save();
				jQuery('#modal_progressbar').progressbar("option", "value", 0 );
				jQuery('#progress_dialog').dialog('open');
				$this.closest('.theme_item_container').addClass('switching');
				jQuery.ajax({
					type : 'post',
					url : '<?php echo $change_theme_action; ?>',
					data : {data:{'new_theme_id':switch_to_theme_id}},
					success : function () {
						jQuery('.current').removeClass('current');
						$this.closest('.theme_item_container').addClass('current');
					},
					error : function (jqXHR, textStatus, errorThrown) {
						if (textStatus === 'timeout') {
							jQuery('.current').removeClass('current');
							$this.closest('.theme_item_container').addClass('current');
						}
					},
					complete : function () {
						jQuery('.switching').removeClass('switching');
						jQuery('#progress_dialog').dialog('close');
						hide_universal_save();
						in_callback = false;
					},
					timeout: 1000 * 60 * 3
				});
				setTimeout(progress, 2000, switch_to_theme_id);
				<?php endif; ?>
			});

			function progress(switch_to_theme_id) {
				if (in_callback) {
					jQuery.ajax({
						type : 'get',
						dataType: "json",
						url : '/admin/theme_centers/ajax_get_choose_theme_progress',
						data : {data:{'new_theme_id]':switch_to_theme_id}},
						success : function (data) {
							// update the progress bar value
							if (typeof data['progress'] !== 'undefined') {
								jQuery('#modal_progressbar').progressbar("option", "value", data['progress']);
							}
						},
						complete : function () {
							// recall self after 1 second delay
							setTimeout(progress, 1000, switch_to_theme_id);
						},
						timeout: 10000
					});
				}
			}

			jQuery('.custom_progress').progressbar({
				value: false
			});
		});
	</script>
	
	<div id="theme_chooser_container">
		<div style="clear: both;"></div>
		<?php $count = 1; foreach ($all_themes as $curr_theme): ?>
				<?php $is_current_theme = $curr_theme['Theme']['ref_name'] == $current_theme; ?>
				<?php 
					$screenshot_help = ''; 		
					$select_help = ''; 		
					if ($count === 1) {
						$screenshot_help = 'data-step="1" data-intro="' . __("Click on the screenshot to view the theme screenshot full-size. This will not change your current theme.", true) . '" data-position="right"';
						$select_help = 'data-step="2" data-intro="' . __("To select a new theme, click the “select” button beneath your chosen theme. It will change the theme to “current.” To view your current theme live, click “Live Site” (bottom left of page).", true) . '" data-position="top"';
					}
				?>
				<div class="theme_item_container <?php if ($is_current_theme === true && $hide_current === false) echo 'current'; ?>">
					<div class="theme_item_outer_container">
						<div class="theme_item_inner_container">
							<?php 
								$large_image_abs_path = $this->Util->global_cdn($curr_theme['Theme']['ref_name'].'_large.jpg');
								$small_image_abs_path = $this->Util->global_cdn($curr_theme['Theme']['ref_name'].'_small.jpg');
								$large_image_web_path = $large_image_abs_path;
								$small_image_web_path = $small_image_abs_path;

								$large_default_web_path = '/img/theme_screenshots/default_large.jpg';
								$small_default_web_path = '/img/theme_screenshots/default_small.jpg';

//								if (!$this->Util->url_exists($large_image_abs_path)) {
//									$large_image_web_path = $large_default_web_path;
//								}
//								if (!$this->Util->url_exists($small_image_abs_path)) {
//									$small_image_web_path = $small_default_web_path;
//								}
								
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
							<div class="screenshot_container" <?php echo $screenshot_help; ?>>
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
												<?php if ($this->params['controller'] === 'welcome'): ?>
												<form id="choose_theme_<?php echo $curr_theme['Theme']['id']; ?>_form" action="<?php echo $change_theme_action; ?>" method="POST">
													<input type="hidden" name="data[new_theme_id]" value="<?php echo $curr_theme['Theme']['id']; ?>" />
												</form>
												<?php endif; ?>
												<div type="submit" value="" ><div class="content icon-Success-01">&nbsp;</div></div>
												<div class="button_switch_theme add_button" data-current-theme-id="<?php echo $curr_theme['Theme']['id']; ?>" <?php echo $select_help; ?>>
													<div class="content"><?php echo __('Switch To Theme', true); ?></div>
												</div>
												<div class="custom_progress">
													<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>	
				</div>
		<?php $count++; endforeach; ?>
		<div id="progress_dialog">
			Preparing image caches for the new theme. This may take a long time if you haven't used a theme recently.
		</div>
	</div>

	

