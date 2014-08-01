<?php echo $this->Element('/admin/get_help_button'); ?>
<div style="clear: both;"></div>
<h1 class='top_heading'><?php __('Add Photos'); ?></h1>
<script>
	
//	function init_global_progress() {
//		$(".upload_in_progress_cont").css('z-index', '2002');
//		$(".upload_in_progress_cont").show();
//		modo_div = jQuery("<div></div>");
//		modo_div.addClass('ui-widget-overlay');
//		modo_div.css('z-index', '2001'); 
//		modo_div.css('height', '200%');
//		
//		jQuery('body').append(modo_div);
//		
//		$(".upload_in_progress_cont .progress").progressbar({
//			value:0
//		});
//	}
//	
//	function fail(data) {
//		var error_to_add;
//		
//		//console.log (data.files[0]);
//		
//		if (data.files[0].fileName == undefined) {
//			$('div.upload_complete .error_cont p').html('<?php __('Error! It seems that you are no longer logged in. Please log in and try to upload your photos again.'); ?>');
//			$('div.upload_complete .error_cont').show();
//		} else {
//			var error_to_add = $("<li>"+data.files[0].fileName+"</li>");
//			$('div.upload_complete .error_cont ul').append(error_to_add);
//		}
//	}
       
	$(document).ready(function() {
		$('#fileupload').fileupload({
			<?php if (empty($current_on_off_features['unlimited_photos'])): ?>
				maxNumberOfFiles: <?php echo $photos_left_to_add; ?>,
			<?php endif; ?>
			dataType: 'json',
			//'fileInput': jQuery('#upload_files'),
			sequentialUploads: true,
//			getNumberOfFiles: function() {
//				console.log ("came freaking here");
//				return 3;
//			},
//			done: function (e, data) {
//				//console.log('upload done');
//				uploaded_complete = parseInt($(".upload_in_progress_cont .count_uploaded_cont .uploaded_complete").html()) + 1;
//				$(".upload_in_progress_cont .count_uploaded_cont .uploaded_complete").html(uploaded_complete);
//				
//				fileupload_count_percentage = ((uploaded_complete / parseInt($(".upload_in_progress_cont .count_uploaded_cont .total_to_upload").html())) * 100) * .5;
//				var progress_to_display = fileupload_count_percentage + fileupload_data_percentage;
//				///console.log(progress_to_display);
//				$(".upload_in_progress_cont .progress").progressbar({
//						value: progress_to_display	
//				});
//				
//				if (data.result.code == -1) {
//					fail(data);
//				}				
//			},
//			fail: function(e, data) {
//				data.context.each(function() {
//					$(this).remove();
//				});
//				
//				if (data.jqXHR != undefined) {
//					fail(data);
//				}
//			},
//			start: function(e, data) {
//				$(".upload_in_progress_cont .count_uploaded_cont .total_to_upload").html($('#fileupload .files_ready_to_upload_cont table tbody tr').length); //-1 to account for header
//				//console.log($(".upload_in_progress_cont .count_uploaded_cont .total_to_upload").html());
//				init_global_progress();				
//			},
//			progressall: function(e, data) {
//				var progress = parseInt(data.loaded / data.total * 100, 10);
//				progress = progress * .5;
//				fileupload_data_percentage = progress;
//				
//				var progress_to_display = progress + fileupload_count_percentage;
//				$(".upload_in_progress_cont .progress").progressbar({
//					value: progress_to_display	
//				});
//			},
//			progress: function(e, data) {
//			},
//			stop: function(e, data) {
//				$("#photo_mass_upload_outer_wrapper .upload_content .files_ready_to_upload_cont table tbody tr").remove();
//				$("#photo_mass_upload_outer_wrapper .upload_content .files_ready_to_upload_cont .files_ready_to_upload_inner_cont .empty_help_content").show();
//				
//				$(".upload_in_progress_cont .progress").progressbar({
//					value:100	
//				});
//				fileupload_progress_percentage = 0;
//				fileupload_count_percentage = 0;
//				$(".upload_in_progress_cont .count_uploaded_cont .uploaded_complete").html('0');
//				$(".upload_in_progress_cont").hide();
//				$(".ui-widget-overlay").remove();
//				
//				if($('div.upload_complete .error_cont ul li').length > 0) {
//					$('div.upload_complete .error_cont').show();
//				}
//				$('div.upload_complete').foto_background_alert(); 
//			}
		});

			
//		$("#fileupload").bind('fileuploadadd', function(e, upload_data) {
//			$("#photo_mass_upload_outer_wrapper .upload_content .files_ready_to_upload_cont .files_ready_to_upload_inner_cont .empty_help_content").hide();
//		});
//                
//		$("#photo_mass_upload_outer_wrapper .files_ready_to_upload_cont button.start").click(function(e) {
//			if ($("#photo_mass_upload_outer_wrapper .files_ready_to_upload_cont table tbody tr").length == 0) {
//			      $.foto('alert', '<?php __('No photos have been choosen. Click on the green add files button to get started.'); ?>');
//			      e.preventDefault();
//			}
//		});
//                
//		$(".upload_files_cont").hover(
//			function() {
//				$(this).find('button').addClass('ui-state-hover');
//			},
//			function() {
//				$(this).find('button').removeClass('ui-state-hover');
//			}
//		);
//		
//		$("#photo_mass_upload_outer_wrapper .upload_content .upload_files_cont button").click(function(e) {
//			e.preventDefault();
//			$("#photo_mass_upload_outer_wrapper .upload_content .upload_files_cont input[type=file]").trigger("click");
//		});
	});
</script>
<div id="photo_mass_upload_outer_wrapper">
	<form id="fileupload" action="/admin/photos/process_mass_photos" method="POST" enctype="multipart/form-data">
		<div class="upload_content fileupload-buttonbar">
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
							<button type="reset" class="cancel">Cancel upload</button>
							<button type="button" class="delete">Delete</button>
							<input type="checkbox" class="toggle">
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
				
				<?php /*<div class='files_ready_to_upload_inner_cont custom_ui_radio'>
					<div class="row fileupload-buttonbar">
						
						<div class="upload_files_cont custom_ui_radio">
							<button>
								<span><?php __('Add Files'); ?></span>
							</button>
							<input type="file" class="upload_files" id="upload_files" accept="image/jpeg" multiple />
						</div>
						<button type="submit" class="btn btn-primary start">
							<i class="icon-upload icon-white"></i>
							<span>Start upload</span>
						</button>
					</div>
					
					<script type="text/javascript">
						$("button").button();
					</script>
					
					<div class='table_cont'>
						<table role="presentation" class="table table-striped">
							<thead>
								<tr>
									<th><?php __('Photo'); ?></th>
									<th><?php __('Name'); ?></th>
									<th><?php __('Size'); ?></th>
									<th class='start_action'><?php __('Remove'); ?></th>
								</tr>
							</thead>
							<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
						</table>
					</div>
					
					<div class="empty_help_content upload_start" style="display:block;">
						<?php __('Drag and drop your photos here. Or choose file here'); ?>
					</div>
				</div> */ ?>
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

<?php /* <div id="photo_mass_upload_outer_wrapper">
	<div class="upload_content fileupload-buttonbar">
		<div class="files_ready_to_upload_cont">
			<form id="fileupload" action="/admin/photos/process_mass_photos" method="POST" enctype="multipart/form-data">
				<!-- Redirect browsers with JavaScript disabled to the origin page -->
				<noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
				<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
				<div class="fileupload-buttonbar">
					<div class="fileupload-buttons">
						<!-- The fileinput-button span is used to style the file input field as button -->
						<span class="fileinput-button">
							<span>Add files...</span>
							<input type="file" name="files[]" multiple>
						</span>
						<button type="submit" class="start">Start upload</button>
						<button type="reset" class="cancel">Cancel upload</button>
						<button type="button" class="delete">Delete</button>
						<input type="checkbox" class="toggle">
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
				<table role="presentation"><tbody class="files"></tbody></table>
			</form>
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
</div> */ ?>


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