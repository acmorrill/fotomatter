<?php 
	if (!isset($style)) {
		$style = ''; 
	}
	
	if (!empty($menu_item_data['name_html'])) {
		$menu_item_data['name'] = '';
	}
	
	
	if (empty($menu_item_data['name_html'])) {
		$menu_item_data['name_html'] = '';
	}
	
	// START HERE TOMORROW - need to get the below working correctely
//	$test_url = $menu_item_data['url'];
//	if (!empty($menu_item_data['basic_url'])) {
//		$test_url = $menu_item_data['basic_url'];
//	}
//	
//	$is_home = $this->here == "/";
//	$not_home_and_starts_with = $test_url != "/" && $this->Util->startsWith(trim($this->here, '/'), trim($test_url, '/'));
?>
<?php //if ( $is_home || $not_home_and_starts_with ): ?>
<?php if (trim($menu_item_data['url'], '/') == trim($this->here, '/')): ?>
	<li class="<?php echo implode(' ', explode('|', $li_class)); ?> hover highlight last_hover" style="<?php echo $style; ?>" ><!--
		--><span class="cart_item_content"><?php echo $menu_item_data['name_html']; ?><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?></span><!--
		--><span class="extra"></span><!--
	--></li>
<?php else: ?>
	<li class="<?php echo implode(' ', explode('|', $li_class)); ?>" style="<?php echo $style; ?>"><!--
		--><a <?php if (!empty($menu_item_data['target_blank'])): ?>target="_blank"<?php endif; ?> href="<?php echo $menu_item_data['url']; ?>"><!--
			--><span class="cart_item_content"><?php echo $menu_item_data['name_html']; ?><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?></span><!--
			--><span class="extra"></span><!--
		--></a><!--
	--></li>
<?php endif; ?>