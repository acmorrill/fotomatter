<?php $uuid = $this->Util->uuid(); ?>
<?php 
	$_width = 650;
	if (isset($width)) {
		$_width = $width;
	}
?>

<style type="text/css">
	.sub_submenu_cont {
		
	}
	.sub_submenu_cont .sub_submenu_left_cont, .sub_submenu_cont .sub_submenu_right_cont, .sub_submenu_cont .sub_submenu_far_right_cont {
		float: left;
	}
	.sub_submenu_cont .sub_submenu_far_right_cont {
		width: 230px;
		min-height: 100px;
		margin-left: 20px;
	}
	.sub_submenu_cont .sub_submenu_left_cont {
		width: 50px;
	}
	.sub_submenu_cont .sub_submenu_left_cont .sub_submenu_tab {
		width: 50px;
		height: 50px;
		overflow: hidden;
		background: #474747;
		cursor: pointer;
		border: 1px solid #333;
		-moz-border-radius-topleft: 5px;
		border-top-left-radius: 5px;
		-moz-border-radius-bottomleft: 5px;
		border-bottom-left-radius: 5px;
	}
	.sub_submenu_cont .sub_submenu_left_cont .sub_submenu_tab.selected {
		background: #636363;
	}
	.sub_submenu_cont .sub_submenu_left_cont .sub_submenu_tab:hover {
		background: #2b2b2b;
	}
	.sub_submenu_cont .sub_submenu_right_cont {
		width: <?php echo $_width; ?>px;
	}
	.sub_submenu_cont .sub_submenu_right_cont .sub_submenu_tab_cont {
		display: none;
		position: relative;
		margin-bottom: 140px;
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
	<div class="sub_submenu_right_cont">
		<?php $count = 0; foreach ($tabs as $tab_name => $tab_element_path): ?>
			<div id="sub_submenu_tab_<?php echo $count; ?>" class="sub_submenu_tab_cont">
				<div class="table_header_darker" style="-moz-border-radius-topleft: 0px;border-top-left-radius: 0px;">
					<div class="actions" style="float: right;"></div>
					<?php // style="background: url('/img/admin/icons/FOLDER - DOWNLOADS.png') center left no-repeat; padding-left: 35px;" ?>
					<h2><?php echo $tab_name; ?></h2>
				</div>
				<div class="content-background <?php if (isset($lighter)): ?>lighter-content-background<?php endif; ?> block_element_base" style="height: 450px;">
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



