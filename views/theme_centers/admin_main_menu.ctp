<?php
	$starting_tab = 0;
	if (isset($theme_config['admin_config']['main_menu']['levels']) && $theme_config['admin_config']['main_menu']['levels'] == 2) {
		$starting_tab = 1;
	}
?>

<div  id="configure_main_menu_cont">
	<?php echo $this->Element('/admin/get_help_button'); ?>
	<div style="clear: both;"></div>
	<?php
		echo $this->Element('admin/sub_submenu', array(
			'tabs' => array(
				'Tier One Menu' => 'admin/theme_center/main_menu/single_level_menu_listing',
				'Tier Two Menu' => 'admin/theme_center/main_menu/two_level_menu_listing',
			),
			'css' => 'margin-top: -26px;',
			'starting_tab' => $starting_tab,
		));
	?>


	<div class="clear"></div>
</div>


<div class="clear"></div>



<?php ob_start(); ?>
<ol>This page is used to build the menu for the theme.
	<li>Things to remember
		<ol>
			<li>Only the one level or two level menu is used at any given time</li>
			<li>If the theme doesn't use a menu type that menu type should be disabled, but visible - we need a design for this</li>
			<li>The "inside" of the menu config containers shouldn't change too much as we'd have to redo javascript - they can look different we just don't want to do html changes</li>
			<li>The "3rd level menu" can change completly or go away. (as well as the outer styles of each menu config "box")</li>
			<li>We don't want the option configs (ie Add Menu Container etc) to change too much</li>
			<li>Don't worry about a design for the "third" section - its not needed and will probobly go away</li>
			<li>For both menu types there are "system" type items that can't be deleted and should be styled differently (currently the "mover" is slightly transparent)</li>
			<li>When an ajax action is happening we need some kind of hint that its happening (saving) - it could be a save popup like on current theme settings) (currently it is a busy cursor icon)</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
echo $this->Element('admin/richard_notes', array(
	'html' => $html
));
?>


