<div id="sub-nav">
	<ul>
		<li class="title" id="title-description">
			<a href="<?php echo $subnav['title']['url']; ?>"><?php echo $subnav['title']['name']; ?></a>	
		</li>
		<li class="spacer">&nbsp;</li>
		<?php $count = 1; foreach ($subnav['pages'] as $subnav_page): ?>
			<li class="<?php if ($count === 1): ?> first<?php endif; ?> <?php if (isset($subnav_page['selected'])): ?> selected<?php endif; ?>">
				<a href="<?php echo $subnav_page['url']; ?>"><?php echo $subnav_page['name']; ?></a>	
			</li>
		<?php $count++; endforeach; ?>
	</ul>
</div>