<?php $uuid = $this->Util->uuid(); ?>

<style type="text/css">
	.sub_submenu_cont {
		
	}
	.sub_submenu_cont .sub_submenu_left_cont, .sub_submenu_cont .sub_submenu_right_cont {
		float: left;
	}
	.sub_submenu_cont .sub_submenu_left_cont {
		width: 50px;
	}
	.sub_submenu_cont .sub_submenu_left_cont .sub_submenu_tab {
		width: 50px;
		height: 50px;
		overflow: hidden;
		background: #aaa;
		cursor: pointer;
		border: 1px solid #333;
	}
	.sub_submenu_cont .sub_submenu_left_cont .sub_submenu_tab.selected {
		background: #474747;
	}
	.sub_submenu_cont .sub_submenu_left_cont .sub_submenu_tab:hover {
		background: #2b2b2b;
	}
	.sub_submenu_cont .sub_submenu_right_cont {
		width: 400px;
	}
	.sub_submenu_cont .sub_submenu_right_cont .sub_submenu_tab_cont {
		padding: 20px;
		min-height: 300px;
		display: none;
	}
</style>

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
	<div class="sub_submenu_left_cont">
		<?php $count = 0; foreach ($tabs as $tab_name => $tab): ?>
			<div class="sub_submenu_tab <?php if ($count == 0): ?>selected<?php endif; ?>"><?php //echo $tab_name; ?></div>
		<?php $count++; endforeach; ?>
	</div>
	<div class="sub_submenu_right_cont content-background">
		<?php $count = 0; foreach ($tabs as $tab_name => $tab_element_path): ?>
			<div id="sub_submenu_tab_<?php echo $count; ?>" class="sub_submenu_tab_cont"><?php echo $this->Element($tab_element_path); ?></div>
		<?php $count++; endforeach; ?>
	</div>
</div>
<div class="clear"></div>



