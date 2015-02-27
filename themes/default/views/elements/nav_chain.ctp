<div id="navChain">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/"><?php __('home'); ?></a>
	<?php foreach ($avail_pages as $avail_page): ?>
		> 
		<?php if (trim($avail_page['url'], '/') == trim($this->here, '/')): ?>
			<?php echo $avail_page['text']; ?>
			<?php break; ?>
		<?php else: ?>
			<a href="<?php echo $avail_page['url']; ?>"><?php echo $avail_page['text']; ?></a>
		<?php endif; ?>
	<?php endforeach; ?>
	<img style="padding-top: 8px;" src="/images/misc/horiz_gradientline.png">
</div>	














