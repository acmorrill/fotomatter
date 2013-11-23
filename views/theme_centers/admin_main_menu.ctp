<div id="configure_main_menu_cont" style="padding: 20px; margin: 20px; margin-top: 0px; margin-left: 0px;">
	<?php // class="content-background" ?>
	<?php // echo $this->Element('admin/theme_center/main_menu/list_main_menu_items'); ?>
	<?php echo $this->Element('admin/sub_submenu', array( 
		'tabs' => array(
			'One Level Menu' => 'admin/theme_center/main_menu/single_level_menu_listing',
			'Two Level Menu' => 'admin/theme_center/main_menu/two_level_menu_listing',
			'Menu Global Settings' => 'admin/theme_center/main_menu/menu_global_config'
		)
	)); ?>
	
	
	<div class="clear"></div>
</div>



<div style="float: left; margin-left: 20px;">
	<?php /*<div class='basic_page_heading header'>
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
			<li>If </li>
		</ol>
	</li>
	<li>It would good to not change the design of this page too much - be sure to give a design for all the elements</li>
	<li>Notice there is a saving thing that popups in the corner on change - we need a design for this</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>


