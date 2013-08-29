<script type="text/javascript">
	$(document).ready(function() {
		
	console.log('here');
		jQuery.ajax({
				type : 'GET',
				url : '//<?php echo Configure::read('OVERLORD_URL'); ?>/fm_build/signup_site_build/<?php echo $account_welcome_email_hash; ?>/?callback=?',
				success: function(data) {
					if (data.code) {
						$(".build-pending h3").html("Site built successfully.");
						setTimeout(function() {
							window.location = '/admin/welcome/create_password?wh=<?php echo $account_welcome_email_hash; ?>';
						}, 3000);
					} else {
						$(".build-pending h3").html("Problem with site build");
					}

				},
				
				dataType: 'jsonp'
	       });
	});
</script>
<div style='text-align:center' class='build-pending'>
	<h3>We are building one great site, please wait.</h3>
	<img src='/img/admin/icons/ajax-loader.gif' />
</div>
