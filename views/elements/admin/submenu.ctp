<div id="sub-nav">
	<ul>
		<?php /*
		<li class="title" id="title-description">
			<a href="<?php echo $subnav['title']['url']; ?>"><?php echo $subnav['title']['name']; ?></a>	
		</li>
		<li class="spacer">&nbsp;</li> */ ?>
		<?php $count = 1; foreach ($subnav['pages'] as $subnav_page): ?>
			<li class="<?php if ($count === 1): ?> first<?php endif; ?> <?php if ($this->Util->startsWith(trim($subnav_page['url'], '/'), trim($this->here, '/'))): ?> selected<?php endif; ?>">
				<div class="subnav_bg"></div>
				<?php //echo $subnav_page['url']; ?>
				<a href="<?php echo $subnav_page['url']; ?>">
					<table>
						<tr>
							<td class="first">
								<i class="icon-reorder"></i>
							</td>
							<td class="second">
								<?php echo $subnav_page['name']; ?>
							</td>
						</tr>
					</table>
				</a>	
			</li>
		<?php $count++; endforeach; ?>
	</ul>
</div>