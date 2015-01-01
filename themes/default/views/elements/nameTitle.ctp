<?php 

$this->log('===============================', 'theme_name');
$this->log($GLOBALS['CURRENT_THEME_PATH'], 'theme_name');
$this->log($theme_config['theme_name'], 'theme_name');
$this->log('===============================', 'theme_name');
$logo_data = $this->ThemeLogo->get_display_logo_data($theme_config); 

?>

<style type="text/css">
	#logo_cont {
		width: <?php echo $logo_data['logo_max_width']; ?>px; height: <?php echo $logo_data['logo_max_height']; ?>px;
	}
	#mainName {
		top: <?php echo $logo_data['logo_current_top']; ?>px;
		left: <?php echo $logo_data['logo_current_left']; ?>px;
	}
</style>
	
<div id="logo_cont" style="<?php if (Configure::read('debug') >= 3): ?>outline: 1px solid invert<?php endif; ?>">
	<a id="mainName" href="/"><img src="<?php echo $logo_data['start_logo_web_path']; ?>"></a>
</div>
		