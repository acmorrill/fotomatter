<h1><?php echo __('Add Photos', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p>
	<?php echo __('This is where you will add photos', true); ?>
</p>

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
			sequentialUploads: true,
			previewMaxWidth: 100,
			previewMaxHeight: 100,
			acceptFileTypes: /(\.|\/)(jpe?g)$/i,
			maxFileSize: <?php echo MAX_UPLOAD_SIZE_MEGS * 1000000; ?>,
			process: function (e, data) {
				var file_tr = jQuery('.files_ready_to_upload_inner_cont table.list tbody:nth-child(' + (1 + data.index) + ')');
				jQuery('.custom_progress', file_tr).progressbar({ value: 0 });
			},
			submit: function (e, data) {
				jQuery('.cancel_photo_upload', data.context).remove();
				jQuery('.progress_td .rightborder', data.context).remove();
			},
			send: function (e, data) {
				jQuery('.custom_progress', data.context).progressbar({ value: false });
			}
		});

		$('#fileupload').bind('fileuploadadd', function (e, data) {
			jQuery('.not_added_yet').remove();
		});
		
		jQuery("#upload_photos_button").click(function(e) {
			jQuery('#upload_photos_file_button').click();
		});
		
		jQuery("#start_upload_button").click(function(e) {
			jQuery('#start_upload_button_old').click();
		});

		jQuery(document).on('click', '.cancel_photo_upload', function(e) {
			jQuery(this).parent().find('.cancel').click();
		});
	});
</script>
<div id="photo_mass_upload_outer_wrapper">
	<form id="fileupload" action="/admin/photos/process_mass_photos" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="height" value="110" />
		<input type="hidden" name="width" value="110" />
		<div class="files_ready_to_upload_cont">
			<div class='files_ready_to_upload_inner_cont custom_ui_radio'>
				<div class="page_content_header">
					<!-- Redirect browsers with JavaScript disabled to the origin page -->
					<noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
					<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
					<div class="row fileupload-buttonbar">
						<div class="fileupload-buttons custom_ui">
							<!-- The fileinput-button span is used to style the file input field as button -->
							<div id="upload_photos_button" class="add_button">
								<div class="content"><?php echo __('Choose Photos', true); ?></div>
								<div class="plus_icon_lines"><div class="one"></div><div class="two"></div></div>
							</div>
							<input id="upload_photos_file_button" type="file" name="files[]" multiple>
							
							
							<div id="start_upload_button" class="add_button">
								<div class="content"><?php echo __('Start Upload', true); ?></div>
								<div class="right_arrow_lines"><div></div></div>
							</div>
							<button id="start_upload_button_old" type="submit" class="start"><?php echo __('Start upload', true); ?></button>
							
							<!-- The global file processing state -->
							<?php /*<span class="fileupload-process"></span>*/ ?>
						</div>
						<!-- The global progress state -->
						<div class="fileupload-progress fade" style="display:none">
							<!-- The global progress bar -->
							<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							<!-- The extended global progress state -->
							<!--<div class="progress-extended">&nbsp;</div>-->
						</div>
					</div>
				</div>
				<div class="generic_palette_container">
					<div class="fade_background_top"></div>
					<div class='table_cont'>
						<table class="list" role="presentation">
							<tbody class="files">
								<tr class="first last not_added_yet">
									<td class="first last" colspan="3">
										<div class="rightborder"></div>
										<span><?php echo __('Drag images here or click "Choose Photos" above.', true); ?></span>
									</td>
								</tr> 	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="generic_photo_gallery_cont">
			<div class="page_content_header">
				<h2>Photo Upload Settings</h2>
			</div>
			<div class="generic_palette_container">
				<div class="upload_setting_container">
					<h3><?php __('Galleries'); ?></h3>
					<?php $galleries = $this->Gallery->get_all_galleries(); ?>
					<select name="data[gallery_ids][]" multiple="multiple" class="chzn-select" data-placeholder="<?php if (empty($galleries)): ?>No Galleries<?php else: ?>Find Galleries ...<?php endif; ?>" style="width: 300px;">
						<?php foreach ($galleries as $gallery): ?>
							<?php if ($gallery['PhotoGallery']['type'] == 'smart') { continue; } ?>
							<option value="<?php echo $gallery['PhotoGallery']['id']; ?>"><?php echo $gallery['PhotoGallery']['display_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="upload_setting_container">
					<h3><?php __('Tags'); ?>&nbsp;&nbsp;(<a href="/admin/tags/manage_tags"><?php echo __('manage tags', true); ?></a>)</h3>
					<?php $tags = $this->Util->get_all_tags(); ?>
					<select name="data[tag_ids][]" multiple="multiple" class="chzn-select" data-placeholder="<?php if (empty($tags)): ?>No Tags<?php else: ?>Find Tags ...<?php endif; ?>" style="width: 300px;">
						<?php foreach ($tags as $tag): ?>
							<option value="<?php echo $tag['Tag']['id']; ?>"><?php echo $tag['Tag']['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>

		<div style='clear:left'></div>
			
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