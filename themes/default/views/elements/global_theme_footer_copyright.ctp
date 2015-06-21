<div style="clear: both;"></div>
<?php
	if (!isset($classes)) {
		$classes = array();
	}
	if (!isset($inverse)) {
		$inverse = false;
	}
?>
<div id="footer_tagline" class="<?php echo implode(' ', $classes); ?> <?php if ($inverse): ?> inverse <?php endif; ?>">
	<?php
		$first_name = $this->SiteSetting->getVal('first_name', '');
		$last_name = $this->SiteSetting->getVal('last_name', '');
	?>
	<div class="footer_tagline_container_outer">
		<div class="footer_tagline_container_inner">
			<?php $hide_fotomatter_link = empty($current_on_off_features['remove_fotomatter_branding']) ? false : true; ?>
			<div class="copyright_container">All material &copy; copyright by <?php echo "$first_name $last_name"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&mdash;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="login_link" href="/admin" target="_blank">Login</a></div>
			<div class="br" style="clear: both;"></div>
			<?php /*<div class="mdash" <?php if ($hide_fotomatter_link): ?>style="display: none;"<?php endif; ?>>&mdash; </div>*/ ?>
			<div class="powered_by" <?php if ($hide_fotomatter_link): ?>style="display: none;"<?php endif; ?> >
				<a href="https://fotomatter.net/recieve_generic/frontend_site" target="_blank">
					<span>Powered by </span>
					<span class="text_replacement">fotomatter.net</span>
					<?php if ($inverse): ?>
						<img class="fotomatter_logo" src="<?php echo $this->Util->global_cdn('fotomatter_footer_logo_inverse.png'); ?>" alt="Fotomatter Logo" />
					<?php else: ?>
						<img class="fotomatter_logo" src="<?php echo $this->Util->global_cdn('fotomatter_footer_logo_whitebg.png'); ?>" alt="Fotomatter Logo" />
					<?php endif; ?>
				</a>
			</div>
		</div>
	</div>
</div>