<h1><?php echo __('Surveys', true); ?></h1>

<script language="javascript" type="text/javascript">
	document.domain = 'fotomatter.net';
	function resizeIframe(obj) {
		obj.style.height = '0px';
		obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
		window.scrollTo(0, 0);
	}
</script>

<?php if (!empty($survey_data['survey_data']['sid'])): ?>
	<div id="photo_mass_upload_outer_wrapper">
		<div class="files_ready_to_upload_cont">
			<div class='files_ready_to_upload_inner_cont'>
				<div class="page_content_header">
					<div class="row fileupload-buttonbar">
						<div class="fileupload-buttons custom_ui">
							<?php echo $survey_data['survey_data']['surveyls_title']; ?>
						</div>
					</div>
				</div>
				<div class="generic_palette_container">
					<div class="fade_background_top"></div>
					<div class="generic_inner_container">
						<div class="generic_dark_cont">
							<iframe src="//survey.fotomatter.net/index.php/<?php echo $survey_data['survey_data']['sid']; ?>?newtest=Y&accountid=<?php echo $account_id; ?>" width="100%" scrolling="no" onload="resizeIframe(this)"></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>
	<div id="photo_mass_upload_outer_wrapper">
		<div class="files_ready_to_upload_cont">
			<div class='files_ready_to_upload_inner_cont'>
				<div class="page_content_header">
					<div class="row fileupload-buttonbar">
						<div class="fileupload-buttons custom_ui">
							There are no new surveys
						</div>
					</div>
				</div>
				<div class="generic_palette_container">
					<div class="fade_background_top"></div>
					<div class="generic_inner_container">
						<div class="generic_dark_cont">
							<br /><br />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

