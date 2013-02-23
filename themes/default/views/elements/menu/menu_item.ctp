<?php if (trim($menu_item_data['url'], '/') == trim($this->here, '/')): ?>
	<li class="<?php echo $li_class; ?> hover highlight"><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?><span class="extra"></span></li>
<?php else: ?>
	<li class="<?php echo $li_class; ?>"><a href="<?php echo $menu_item_data['url']; ?>"><?php echo str_replace(' ', '&nbsp;', $menu_item_data['name']); ?><span class="extra"></span></a></li>
<?php endif; ?>