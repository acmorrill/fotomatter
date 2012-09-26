<?php
	// DREW TODO - make sure the below code doesn't do anything it doesn't need to

	$logo_max_width = $logo_context_width = isset($theme_config['admin_config']['logo_config']['available_space']['width']) ? $theme_config['admin_config']['logo_config']['available_space']['width'] : 400;
	$logo_max_height = $logo_context_height = isset($theme_config['admin_config']['logo_config']['available_space']['height']) ? $theme_config['admin_config']['logo_config']['available_space']['height'] : 200;

	$avail_space_screenshot_web_path = '';
	$padding = isset($theme_config['admin_config']['logo_config']['available_space_screenshot']['padding']) ? $theme_config['admin_config']['logo_config']['available_space_screenshot']['padding'] : '0px';
	if (!empty($theme_config['admin_config']['logo_config']['available_space_screenshot'])) {
		$avail_space_screenshot_web_path = $theme_config['admin_config']['logo_config']['available_space_screenshot']['web_path'];
		$avail_space_screenshot_path = $theme_config['admin_config']['logo_config']['available_space_screenshot']['absolute_path'];
		$avail_space_screenshot_size = getimagesize($avail_space_screenshot_path);
		$logo_context_width = $avail_space_screenshot_size[0];
		$logo_context_height = $avail_space_screenshot_size[1];
		$logo_max_width = $avail_space_screenshot_size[0] - $padding['left'] - $padding['right'];
		$logo_max_height = $avail_space_screenshot_size[1] - $padding['top'] - $padding['bottom'];
	}


	$logo_default_width = isset($theme_config['admin_config']['logo_config']['default_space']['width']) ? $theme_config['admin_config']['logo_config']['default_space']['width'] : 300;
	$logo_default_height = isset($theme_config['admin_config']['logo_config']['default_space']['height']) ? $theme_config['admin_config']['logo_config']['default_space']['height'] : 150;
	$logo_current_width = $this->Theme->get_theme_setting('logo_current_width', $logo_default_width);
	$logo_current_height = $this->Theme->get_theme_setting('logo_current_height', $logo_default_height);

	$use_logo_width = min($logo_current_width, $logo_max_width);
	$use_logo_height = min($logo_current_height, $logo_max_height);

	$use_theme_logo = $this->Theme->get_theme_setting('use_theme_logo', true);
	$start_logo_path = $this->ThemeLogo->get_logo_cache_size_path($use_logo_height, $use_logo_width, true, $use_theme_logo);
	$image_size = getimagesize($start_logo_path);
	$use_logo_width = $image_size[0];
	$use_logo_height = $image_size[1];
	$start_logo_web_path = $this->ThemeLogo->get_logo_cache_size_path($use_logo_height, $use_logo_width, false, $use_theme_logo);

	$logo_current_top = $this->Theme->get_theme_setting('logo_current_top', 0);
	$logo_current_left = $this->Theme->get_theme_setting('logo_current_left', 0);

	// check to see that the logo is still in the specified spot
	if (($logo_current_left + $use_logo_width) > $logo_max_width) {
		$logo_current_left = $logo_max_width - $use_logo_width;
	}
	if (($logo_current_top + $use_logo_height) > $logo_max_height) {
		$logo_current_top = $logo_max_height - $use_logo_height;
	}
?>

<style type="text/css">
	#logo_cont {
		position: absolute; top: 0px; left: 89px; width: <?php echo $logo_max_width; ?>px; height: <?php echo $logo_max_height; ?>px;
	}
	#mainName {
		top: <?php echo $logo_current_top; ?>px;
		left: <?php echo $logo_current_left; ?>px;
		
	}
</style>

<div id="logo_cont" style="<?php if (Configure::read('debug') >= 3): ?>outline: 1px solid invert<?php endif; ?>">
	<a id="mainName" href="/"><img src="<?php echo $start_logo_web_path; ?>"></a>
</div>
		