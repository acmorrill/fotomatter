	<h1><?php echo __('Upload Photos to {{upload_to_gallery.PhotoGallery.display_name}}', true); ?>
		<!--<div id="help_tour_button" class="custom_ui"><?php //echo $this->Element('/admin/get_help_button'); ?></div>-->
	</h1>


	<div id="photo_mass_upload_outer_wrapper">
		<form id="fileupload" class="fotomatter_form" action="/admin/photos/process_mass_photos" method="POST" enctype="multipart/form-data">
			<div class="input text">
				<label><?php echo __('Auto Tag Photos', true); ?></label>
				<?php $tags = $this->Tag->get_tags(); ?>
				<select name="data[tag_ids][]" multiple="multiple" class="chzn-select" data-placeholder="Find Tags ..." style="width: 300px;">
					<?php foreach ($tags as $tag): ?>
						<option value="<?php echo $tag['Tag']['id']; ?>"><?php echo $tag['Tag']['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<input type="hidden" name="data[gallery_ids][]" value="{{upload_to_gallery.PhotoGallery.id}}" />
			<input type="hidden" name="height" value="110" />
			<input type="hidden" name="width" value="110" />

			<div class="files_ready_to_upload_cont" data-step="2" data-intro="<?php echo __("Upload your photos by dragging and dropping or use the &ldquo;Choose Photos&rdquo; button. Once you have selected the photos you wish to upload, click &ldquo;Start Upload&rdquo;. The recommended .jpg size is 4000px or less on the long side. Add up to 50 photos for free. When you’re done, you can view all of your photos by clicking the &ldquo;Photos&rdquo; tab above.", true) ?>" data-position="top">
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
									<div class="plus_icon_lines icon-_button-01"><div class="one"></div><div class="two"></div></div>
								</div>
								<input id="upload_photos_file_button" type="file" name="files[]" multiple>


								<div id="start_upload_button" class="add_button">
									<div class="content"><?php echo __('Start Upload', true); ?></div>
									<div class="right_arrow_lines icon-arrow-01"><div></div></div>
								</div>
								<button id="start_upload_button_old" type="submit" class="start"><?php echo __('Start upload', true); ?></button>
								<?php /*<span class="fileupload-process"></span>*/ ?>
							</div>
							<div class="fileupload-progress fade" style="display: none;">
								<div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							</div>

							<div style="clear: both;"></div>
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

			<div style='clear:left'></div>

		</form>
	</div>


