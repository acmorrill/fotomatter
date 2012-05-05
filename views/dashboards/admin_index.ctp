<script type="text/javascript">
	jQuery(document).ready(function() {
			setTimeout(function () {
				jQuery.ajax({
					type: 'post',
					url: '/admin/dashboards/ajax_post_test',
					data: {
						test_name : 'test_value'
					},
					success: function(data) {
						console.log('success');
					},
					complete: function() {
						console.log('complete');
					},
					dataType: 'json'
				});
			}, 5000);
			//show_modal('loading', 20000);
		});
</script>
This is the dashboard