<script type="text/javascript">
	var subnav = jQuery('#middle .sub-nav');
	var window = jQuery(window);
	jQuery(document).ready(function() {
		subnav.hover(
			function() {
				if (window.width <= 1280) {
					subnav.css('width', 'auto');
				}
			}, 
			function() {
				if (window.width <= 1280) {
					subnav.css('width', '');
				}
			}
		);
	});
</script>

<?php $help_step = ''; ?>
<?php $count = 1; foreach ($subnav['pages'] as $subnav_page) {
	if (!is_array($subnav_page['url'])) {
		if (!empty($subnav_page['help_step'])) {
			if ( $this->Util->on_url($subnav_page['help_step']['url']) ) {
				$help_step = $subnav_page['help_step']['step_code'];
			}
		}
	}
	$count++;
} ?>


<div class="sub-nav <?php if (isset($curr_page)) { echo $curr_page; } ?>" <?php echo $help_step; ?> >
	<ul>
		<?php /*
		<li class="title" id="title-description">
			<a href="<?php echo $subnav['title']['url']; ?>"><?php echo $subnav['title']['name']; ?></a>	
		</li>
		<li class="spacer">&nbsp;</li> */ ?>
		<?php $count = 1; foreach ($subnav['pages'] as $subnav_page): ?>
			<?php
				$help_step = '';
				$selected = '';
				if (is_array($subnav_page['url'])) {
					foreach ($subnav_page['url'] as $curr_url) {
						if ( $this->Util->on_url($curr_url) ) {
							$selected = 'selected';
						}
					}
				} else {
					if ( $this->Util->on_url($subnav_page['url']) ) {
						$selected = 'selected';
					}
					if (!empty($subnav_page['help_step'])) {
						if ( $this->Util->on_url($subnav_page['help_step']['url']) ) {
							$help_step = $subnav_page['help_step']['step_code'];
						}
					}
				}
			?>
			<li class="<?php if (!empty($subnav_page['hide_on_mobile'])): ?> hide_on_mobile <?php endif; ?> <?php if ($count === 1): ?> first<?php endif; ?> <?php echo $selected; ?>">
				<div class="subnav_bg"></div>
				<table>
					<tr>
						<td class="first" onclick="">
							<i class="icon-<?php echo (isset($subnav_page['icon_css'])) ? $subnav_page['icon_css'] : ''; ?>"></i>
						</td>
						<td class="second">
							<a href="<?php echo is_array($subnav_page['url']) ? $subnav_page['url'][0] : $subnav_page['url']; ?>">
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