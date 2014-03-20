<?php $uuid = $this->Util->uuid(); ?>


<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#<?php echo $uuid; ?> .sub_submenu_tab_cont:first').show();
		
		
		jQuery('#<?php echo $uuid; ?> .sub_submenu_left_cont .sub_submenu_tab').click(function() {
			jQuery('#<?php echo $uuid; ?> .sub_submenu_tab').removeClass('selected');
			jQuery(this).addClass('selected');
			
			var position = position_of_element_among_siblings('#<?php echo $uuid; ?> .sub_submenu_left_cont .sub_submenu_tab', jQuery(this));
			jQuery('#<?php echo $uuid; ?> .sub_submenu_tab_cont').hide();
			jQuery('#<?php echo $uuid; ?> .sub_submenu_tab_cont:nth-child('+position+')').show();
		});
	});
	
	
</script>

<div id="<?php echo $uuid; ?>" class="sub_submenu_cont">
	<div data-step="2" data-intro="<?php echo __('All themes have menus. Some have one tier menu and others two tier menus. One tier menus are single links. Versus two tier menus have a dropdown menu system. ', true); ?>" data-position="left" class="sub_submenu_left_cont">
		<?php $count = 0; foreach ($tabs as $tab_name => $tab): ?>
			<div data-step="3" data-intro="<?php echo __('This tab will allow you to order...', true); ?>" data-position="left" class="sub_submenu_tab <?php if ($count == 0): ?>selected<?php endif; ?>"><?php //echo $tab_name; ?></div>
		<?php $count++; endforeach; ?>
	</div>
	<div ata-step="1" data-intro="<?php echo __('Create the main menu for your site.', true); ?>" data-position="left" class="sub_submenu_right_cont">
		<?php $count = 0; foreach ($tabs as $tab_name => $tab_element_path): ?>
			<div id="sub_submenu_tab_<?php echo $count; ?>" class="sub_submenu_tab_cont">
				<div class="table_header_darker" style="-moz-border-radius-topleft: 0px;border-top-left-radius: 0px;">
					<div class="actions" style="float: right;"></div>
					<?php // style="background: url('/img/admin/icons/FOLDER - DOWNLOADS.png') center left no-repeat; padding-left: 35px;" ?>
					<h2><?php echo $tab_name; ?></h2>
				</div>
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



