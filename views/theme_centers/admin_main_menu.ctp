<div id="configure_main_menu_cont" style="padding: 20px; margin: 20px; margin-top: 0px; margin-left: 0px;">
	<?php // class="content-background" ?>
	<?php // echo $this->Element('admin/theme_center/main_menu/list_main_menu_items'); ?>
	<?php
	echo $this->Element('admin/sub_submenu', array(
		'tabs' => array(
			'Tier One Menu' => 'admin/theme_center/main_menu/single_level_menu_listing',
			'Tier Two Menu' => 'admin/theme_center/main_menu/two_level_menu_listing',
		//'Menu Global Settings' => 'admin/theme_center/main_menu/menu_global_config'
		)
	));
	?>


	<div class="clear"></div>
</div>

<div style="float: left; margin-left: 20px;">
	<?php /* <div class='basic_page_heading header'>
	  <div class='title'><?php __('Pages'); ?></div>
	  <p><?php __('Choose a page to add to the menu'); ?></p>
	  </div>



	  <div class="custom_ui">
	  <button id="add_menu_item_button" class="add_button"><?php __('Add Menu Item'); ?></button>
	  </div> */ ?>
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


