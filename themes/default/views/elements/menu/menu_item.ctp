<?php 
	if (!isset($style)) {
		$style = ''; 
	}
?>
<?php if (trim($menu_item_data['url'], '/') == trim($this->here, '/')): ?>
	<li class="<?php echo implode(' ', explode('|', $li_class)); ?> hover highlight" style="<?php echo $style; ?>" ><span class="cart_item_content"><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?></span><span class="extra"></span></li>
<?php else: ?>
	<li class="<?php echo implode(' ', explode('|', $li_class)); ?>" style="<?php echo $style; ?>"><a <?php if (!empty($menu_item_data['target_blank'])): ?>target="_blank"<?php endif; ?> href="<?php echo $menu_item_data['url']; ?>"><span class="cart_item_content"><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?></span><span class="extra"></span></a></li>
<?php endif; ?>