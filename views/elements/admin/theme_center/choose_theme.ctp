<?php 
// current theme
$current_theme = $this->SiteSetting->getVal('current_theme', false);

// get all themes
$all_themes = $this->Theme->get_all_available_themes();

if (!isset($switch_text)) {
	$switch_text = __('Switch to Theme', true);
}
?>

<?php /*
<script type="text/javascript" src="/js/jqzoom_ev-2.3/js/jquery.jqzoom-core-pack.js"></script>
<style media="all" type="text/css">@import "/js/jqzoom_ev-2.3/css/jquery.jqzoom.css";</style>
*/ ?>

	<style type="text/css">
/*		#theme_list_container {
			width: 90%;
			margin-left: auto;
			margin-right: auto;
		}
		#theme_list_container tr {}
		#theme_list_container tr td {
			border-bottom: 50px solid transparent;
		}
		#theme_list_container .theme_item_container {
			width: 416px;
			margin: 40px;
			margin-left: auto;
			margin-right: auto;
			text-align: center;
		}
		#theme_list_container .theme_item_container .screenshot_container {
			border: 7px solid white;
		}
		#theme_list_container .theme_item_container .screenshot_inner_container {
			border: 1px solid #BFBFBF;
		}
		#theme_list_container .theme_item_container h2 {
			color: white;
			margin: 4px;
			margin-top: 8px;
			float: left;
			width: 45%;
		}
		#theme_list_container .theme_item_container form {
			float: right;
			width: 45%;
			margin: 4px;
			text-align: right;
		}
		#theme_list_container .theme_item_container form.usable_form input {
			cursor: pointer;
		}*/
	</style>
	
	<?php //debug($all_themes); ?>
	
	<script type="text/javascript">
//		jQuery(document).ready(function() {
//			jQuery('#theme_list_container .theme_item_container .screenshot_zoom').jqzoom({
//				zoomType: 'innerzoom'
//			});
//		});
	</script>
	
	
	
	
	<div id="theme_chooser_container">
		<?php $count = 1; foreach ($all_themes as $curr_theme): ?>
				<div class="theme_item_container">
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
									<img src="<?php echo $small_image_web_path; ?>" title="<?php echo $curr_theme['Theme']['ref_name']; ?>">
								</a>
								<div style="clear: both;"></div>
							</div>
						</div>
						<div style="clear: both;"></div>
						<div class="theme_item_inner_container_text">
							<h2><?php echo $curr_theme['Theme']['display_name']; ?></h2>
							<?php if ($curr_theme['Theme']['ref_name'] != $current_theme): ?>
								<form class="usable_form" action="<?php echo $this->here; ?>" method="post">
									<input type="hidden" name="data[new_theme_id]" value="<?php echo $curr_theme['Theme']['id']; ?>" />
									<input class="theme_button_switch_theme button" type="submit" value="<?php echo $switch_text; ?> &rsaquo;" />
								</form>
							<?php else: ?>
								<form class="theme_button_current_theme">
									<input class="button_active" type="submit" value="<?php __('Current Theme'); ?> &rsaquo;" />
								</form>
							<?php endif; ?>
						</div>
					</div>
				</div>
		<?php $count++; endforeach; ?>
	</div>
	
	
	
	
	
<?php /*	
	
<div>
	
	<table id="theme_list_container">
		<?php $count = 2; foreach ($all_themes as $curr_theme): ?>
			<?php if ($count % 2 == 0): ?><tr><?php endif; ?>
				<td>
					<div class="theme_item_container">
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
									<img src="<?php echo $small_image_web_path; ?>" title="<?php echo $curr_theme['Theme']['ref_name']; ?>">
								</a>
								<div style="clear: both;"></div>
							</div>
						</div>
						<div style="clear: both;"></div>
						<h2><?php echo $curr_theme['Theme']['display_name']; ?></h2>
						<?php if ($curr_theme['Theme']['ref_name'] != $current_theme): ?>
							<form class="usable_form" action="<?php echo $this->here; ?>" method="post">
								<input type="hidden" name="data[new_theme_id]" value="<?php echo $curr_theme['Theme']['id']; ?>" />
								<input type="submit" value="<?php echo $switch_text; ?>" />
							</form>
						<?php else: ?>
							<form>
								<input type="submit" value="<?php __('Current Theme'); ?>" />
							</form>
						<?php endif; ?>
					</div>
				</td>
			<?php if (($count -1) % 2 == 0): ?></tr><?php endif; ?>
		<?php $count++; endforeach; ?>
	</table>
	
</div>


*/ ?>

