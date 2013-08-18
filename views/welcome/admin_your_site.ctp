<h1>this is where you wait for your dns to be done!</h1>




<script type="text/javascript">
	var ping_dns;
	var retry_timeout = 3000;
	function check_dns() {
		jQuery.ajax({
			type: 'get',
			url: 'http://<?php echo $dns_domain; ?>/site_pages/ping?callback=?',
			success: function(the_data) {
				clearTimeout(ping_dns);
				
				// now we need to redirect to the actual site
				alert('DNS is done!');
			},
			dataType: 'jsonp'
		});
	}
	ping_dns = setInterval(function() { 
		check_dns();
	}, retry_timeout);
	check_dns();
</script>




