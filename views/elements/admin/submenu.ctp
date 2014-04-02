<script type="text/javascript">
//	var subnav = jQuery('#sub-nav');
//	var window = jQuery(window);
//	jQuery(document).ready(function() {
//		console.log ("came freaking here");
//		subnav.hover(
//			function() {
//				console.log ("came into hover 1");
//				if (window.width <= 1280) {
//					subnav.css('width', 'auto');
//				}
//			}, 
//			function() {
//				console.log ("came into hover 2");
//				if (window.width <= 1280) {
//					subnav.css('width', '');
//				}
//			}
//		);
//	});
</script>

<div id="sub-nav" class=" <?php if (isset($curr_page)) { echo $curr_page; } ?>" >
	<ul>
		<?php /*
		<li class="title" id="title-description">
			<a href="<?php echo $subnav['title']['url']; ?>"><?php echo $subnav['title']['name']; ?></a>	
		</li>
		<li class="spacer">&nbsp;</li> */ ?>
		<?php $count = 1; foreach ($subnav['pages'] as $subnav_page): ?>
			<?php 
				$selected = '';
				if ($this->Util->startsWith(trim($subnav_page['url'], '/'), trim($this->here, '/')) || $this->Util->startsWith(trim($this->here, '/'), trim($subnav_page['url'], '/'))) {
					$selected = 'selected';
				}
			?>	
			<li class="<?php if ($count === 1): ?> first<?php endif; ?> <?php echo $selected; ?>">
				<div class="subnav_bg"></div>
				<?php //echo $subnav_page['url']; ?>
				<table>
					<tr>
						<td class="first" onclick="">
							<i class="icon-<?php echo (isset($subnav_page['icon_css'])) ? $subnav_page['icon_css'] : ''; ?>"></i>
						</td>
						<td class="second">
							<a href="<?php echo $subnav_page['url']; ?>">
								<div>
									<p><?php echo $subnav_page['name']; ?></p>
								</div>
							</a>	
						</td>
						<td class="third">
							<span class="circle"></span>
						</td>
					</tr>
				</table>
			</li>
		<?php $count++; endforeach; ?>
	</ul>
</div>