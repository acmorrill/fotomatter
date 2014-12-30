<div id="footer_tagline">
	<?php
		$first_name = $this->SiteSetting->getVal('first_name', '');
		$last_name = $this->SiteSetting->getVal('last_name', '');
	?>
	<div class="footer_tagline_container_outer">
		<div class="footer_tagline_container_inner">
			<?php $hide_fotomatter_link = empty($current_on_off_features['remove_fotomatter_branding']) ? false : true; ?>
			<div class="copyright_container">All material &copy; copyright by <?php echo "$first_name $last_name"; ?></div><br />
			<?php /*<div class="mdash" <?php if ($hide_fotomatter_link): ?>style="display: none;"<?php endif; ?>>&mdash; </div>*/ ?>
			<div class="powered_by" <?php if ($hide_fotomatter_link): ?>style="display: none;"<?php endif; ?> ><a href="https://www.fotomatter.net" target="_blank"><span>Powered by </span><span class="text_replacement">fotomatter.net</span><img class="fotomatter_logo" src="<?php echo $this->Util->global_cdn('fotomatter_footer_logo_whitebg_2.svg'); ?>" alt="Fotomatter Logo" /></a></div>
		</div>
	</div>
</div>