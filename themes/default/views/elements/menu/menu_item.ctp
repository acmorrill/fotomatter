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
	

	$test_url = $menu_item_data['url'];

	$force_clickable = !empty($menu_item_data['force_clickable']);

	$on_home = $this->here == "/" && $test_url == '/';
	$current_starts_with = $test_url != "/" && $this->Util->startsWith(trim($this->here, '/'), trim($test_url, '/'));
	
	$is_current = $on_home || $current_starts_with;
?>
<?php if ( $is_current ): ?>
	<li class="<?php echo implode(' ', explode('|', $li_class)); ?> hover highlight last_hover" style="<?php echo $style; ?>" ><!--
		<?php if ($force_clickable): ?>
			--><a <?php if (!empty($menu_item_data['target_blank'])): ?>target="_blank"<?php endif; ?> href="<?php echo $menu_item_data['url']; ?>"><!--
		<?php endif; ?>
		--><span class="cart_item_content"><?php echo $menu_item_data['name_html']; ?><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?></span><!--
		--><span class="extra"></span><!--
		<?php if ($force_clickable): ?>
			--></a><!--
		<?php endif; ?>
	--></li>
<?php else: ?>
	<li class="<?php echo implode(' ', explode('|', $li_class)); ?>" style="<?php echo $style; ?>"><!--
		--><a <?php if (!empty($menu_item_data['target_blank'])): ?>target="_blank"<?php endif; ?> href="<?php echo $menu_item_data['url']; ?>"><!--
			--><span class="cart_item_content"><?php echo $menu_item_data['name_html']; ?><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?></span><!--
			--><span class="extra"></span><!--
		--></a><!--
	--></li>
<?php endif; ?>