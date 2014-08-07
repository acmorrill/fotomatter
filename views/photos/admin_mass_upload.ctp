<?php echo $this->Element('/admin/get_help_button'); ?>
<div style="clear: both;"></div>
<h1 class='top_heading'><?php __('Add Photos'); ?></h1>
<script>
	$(document).ready(function() {
		$('#fileupload').fileupload({
			<?php if (empty($current_on_off_features['unlimited_photos'])): ?>
				maxNumberOfFiles: <?php echo $photos_left_to_add; ?>,
			<?php endif; ?>
			disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
			imageMaxWidth: <?php echo FREE_MAX_RES; ?>,
			imageMaxHeight: <?php echo FREE_MAX_RES; ?>,
			dataType: 'json',
			sequentialUploads: true
		});
	});
</script>
<div id="photo_mass_upload_outer_wrapper">
	<form id="fileupload" action="/admin/photos/process_mass_photos" method="POST" enctype="multipart/form-data">
		<div class="upload_content">
			<div class="files_ready_to_upload_cont">
				<div class='files_ready_to_upload_inner_cont custom_ui_radio'>
					<!-- Redirect browsers with JavaScript disabled to the origin page -->
					<noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
					<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
					<div class="row fileupload-buttonbar">
						<div class="fileupload-buttons">
							<!-- The fileinput-button span is used to style the file input field as button -->
							<span class="fileinput-button">
								<span>Add files...</span>
								<input type="file" name="files[]" multiple>
							</span>
							<button type="submit" class="start">Start upload</button>
							<?php /*<button type="reset" class="cancel">Cancel upload</button>
							<button type="button" class="delete">Delete</button>
							<input type="checkbox" class="toggle"> */ ?>
							<!-- The global file processing state -->
							<span class="fileupload-process"></span>
						</div>
						<!-- The global progress state -->
						<div class="fileupload-progress fade" style="display:none">
							<!-- The global progress bar -->
							<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							<!-- The extended global progress state -->
							<div class="progress-extended">&nbsp;</div>
						</div>
					</div>
					<!-- The table listing the files available for upload/download -->
					<div class='table_cont'>
						<table role="presentation"><tbody class="files"></tbody></table>
					</div>
				</div>
			</div>
			<div class="generic_photo_gallery_cont">
				<div class="gallery-upload-chooser-cont">
					<?php echo $this->element('admin/gallery/gallery-chooser'); ?>
				</div>
				<div class="tag-upload-chooser">
					<?php echo $this->element("admin/tag/tag-chooser"); ?>
				</div>
			</div>
			
			<div style='clear:left'></div>
			
		</div>
	</form>
</div>



<?php ob_start(); ?>
<ol>
	<li>This page is where you can upload a lot of photos at once (and assign them to galleries and tags)</li>
	<li>Things to remember
		<ol>
			<li>This page needs a flash message</li>
			<li>We need the style for when you are about to add photos</li>
			<li>We need style for before you've added photos to upload (the Drag and drop help)</li>
			<li>We need style for the uploading popup and the finished uploading popup</li>
			<li>We need style for the upload error dialog box - ask adam about getting an example of this</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>