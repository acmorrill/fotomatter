<?php 
	$uuid = $this->Util->uuid(); 
	if (!isset($starting_tab)) {
		$starting_tab = 0;
	}
?>


<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#<?php echo $uuid; ?> .sub_submenu_tab_cont:nth-child(<?php echo $starting_tab + 1; ?>)').show();
		
		jQuery('#<?php echo $uuid; ?> .sub_submenu_left_cont .sub_submenu_tab').click(function() {
			jQuery('#<?php echo $uuid; ?> .sub_submenu_tab').removeClass('selected');
			jQuery(this).addClass('selected');
			
			var position = position_of_element_among_siblings('#<?php echo $uuid; ?> .sub_submenu_left_cont .sub_submenu_tab', jQuery(this));
			jQuery('#<?php echo $uuid; ?> .sub_submenu_tab_cont').hide();
			jQuery('#<?php echo $uuid; ?> .sub_submenu_tab_cont:nth-child('+position+')').show();
		});
	});
	
	
</script>
<?php // KENT TODO - make sure this works for two tier menu as well ?>
<div id="<?php echo $uuid; ?>" class="sub_submenu_cont" style="<?php echo isset($css) ? $css : ''; ?>">
	<div class="sub_submenu_left_cont" data-step="2" data-intro="<?php echo __('All themes have menus. Some have one tier menu and others two tier menus. One tier menus are single links while two tier menus have a dropdown menu system. ', true); ?>" data-position="bottom">
		<div class="sub_menu_bottom_border"></div>
		<?php $count = 0; foreach ($tabs as $tab_name => $tab): ?>
			<div class="sub_submenu_tab <?php if ($count == $starting_tab): ?>selected<?php endif; ?>">
				<div class="sub_menu_tab_right_angle_side"></div>
				<div class="sub_menu_tab_border_bottom"></div>
				<div class="content"><?php echo $tab_name; ?></div>
			</div>
		<?php $count++; endforeach; ?>
	</div>
	<div class="sub_submenu_right_cont" data-step="1" data-intro="<?php echo __('Create the main menu for your site. The main menu will allow visitors to navigate the site', true); ?>" data-position="top">
		<?php $count = 0; foreach ($tabs as $tab_name => $tab_element_path): ?>
			<div id="sub_submenu_tab_<?php echo $count; ?>" class="sub_submenu_tab_cont">
				<div class="content-background <?php if (isset($lighter)): ?>lighter-content-background<?php endif; ?> block_element_base">
					<div class="fade_background_top"></div>
					<?php echo $this->Element($tab_element_path); ?>
				</div>
			</div>
		<?php $count++; endforeach; ?>
	</div>
	<?php if (isset($right_side_content)): ?>
		<div class="sub_submenu_far_right_cont">
			<?php echo $this->Element($right_side_content); ?>
		</div>
	<?php endif; ?>
</div>
<div class="clear"></div>



