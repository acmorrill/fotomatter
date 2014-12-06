<script type="text/javascript">
	$(document).ready(function() {
		jQuery('#site_building_progress').progressbar({ value: false });
		
		jQuery.ajax({
			type: 'GET',
			url: '//<?php echo Configure::read('OVERLORD_URL'); ?>/fm_build/signup_site_build/<?php echo $account_welcome_email_hash; ?>/?callback=?',
			success: function(response) {
				if (response.code) {
					jQuery('#site_building_progress').hide();
					jQuery('#site_building_message').html("<?php echo __('Site built succesfully<br />redirecting ...', true); ?>");		
					setTimeout(function() {
						window.location = '/admin/welcome/create_password?wh=' + response.data.welcome_hash;
					}, 3000);
				} else {
					if (typeof response.data == 'object' && typeof response.data.message == 'string') {
						jQuery.foto('alert', response.data.message);
					}
				}

			},
			dataType: 'jsonp'
		});
	});
</script>
<div id="welcome_page_container" class="wider">
	<div class='generic_palette_container'>
		<div class='fade_background_top'></div>
		<?php echo $this->Session->flash('auth'); ?>
		<h1 id="site_building_message"><?php echo __('Your site will finish building momentarily', true); ?></h1>
		<div id="site_building_progress"></div>
	</div>
</div>
