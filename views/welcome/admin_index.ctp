<script type="text/javascript">
	$(document).ready(function() {
//		jQuery.ajax({
//			type: 'GET',
//			url: '//<?php echo Configure::read('OVERLORD_URL'); ?>/fm_build/signup_site_build/<?php echo $account_welcome_email_hash; ?>/?callback=?',
//			success: function(response) {
//				if (response.code) {
//					$(".build-pending h3").html("Site built successfully.");
//					setTimeout(function() {
//						window.location = '/admin/welcome/create_password?wh=' + response.data.welcome_hash;
//					}, 3000);
//				} else {
//					$(".build-pending h3").html("Problem with site build");
//				}
//
//			},
//			dataType: 'jsonp'
//		});

		jQuery('#site_building_progress').progressbar({ value: false });
	});
</script>
<div id="welcome_page_container">
	<div class='generic_palette_container'>
		<div class='fade_background_top'></div>
		<?php echo $this->Session->flash('auth'); ?>
		<div style='text-align: center' class='build-pending'>
			<h1 style="margin-bottom: 35px;"><?php echo __('Your site will finish building momentarily', true); ?></h1>
			<div id="site_building_progress"></div>
		</div>
	</div>
</div>
