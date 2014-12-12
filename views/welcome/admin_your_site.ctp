<script type="text/javascript">
	var ping_dns;
	var retry_timeout = 10000;
	function check_dns() {
		<?php if (!empty($dns_domain)): ?>
			var random = Math.floor((Math.random() * 100000) + 1);
			jQuery.ajax({
				type: 'get',
				url: 'https://<?php echo $dns_domain; ?>/site_pages/ping/' + random + '?callback=?',
				success: function(the_data) {
					clearTimeout(ping_dns);

					// now we need to redirect to the actual site
					window.location.href = "https://<?php echo $dns_domain; ?>/admin/photos/mass_upload";
				},
				dataType: 'jsonp'
			});
		<?php endif; ?>
	}
	
	jQuery(document).ready(function() {
		jQuery('#site_building_progress').progressbar({ value: false });
		
		ping_dns = setInterval(function() { 
			check_dns();
		}, retry_timeout);
		check_dns();
	});
</script>

<div id="welcome_page_container" class="wider">
	<div class='generic_palette_container'>
		<div class='fade_background_top'></div>
		<h1 style="text-align: left;"><?php echo __('You will be redirected to your finished website shortly', true); ?></h1>
		<h1 style="text-align: left;"><?php echo sprintf(__("If this step takes more than 15 minutes please visit your website directly at %s &mdash; if that doesn't work please alert us at support@fotomatter.net", true), "$dns_domain/admin"); ?></h1>
		<div id="site_building_progress"></div>
	</div>
</div>








