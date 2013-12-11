<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included --
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="/js/jquery-file-upload/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="/js/jquery-file-upload/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="/js/jquery-file-upload/canvas-to-blob.min.js"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/js/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="/js/jquery-file-upload/js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="/js/jquery-file-upload/js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="/js/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
<!-- The localization script -->
<script src="/js/jquery-file-upload/js/locale.js"></script>
<!-- The main application script -->
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="/js/jquery-file-upload/js/cors/jquery.xdr-transport.js"></script><![endif]-->
<h1 class='top_heading'><?php __('Add Photos'); ?></h1>
<?php echo $this->Html->script('/js/foto_matter_plugins/jQuery.foto.background.js'); ?>
<?php 
	$subnav = array(); 

	$subnav['title'] = array(
		'name' => __('Photo List', true),
		'url' => "/admin/photos"
	);
	$subnav['pages'][] = array(
		'name' => __('Add Photos', true),
		'url' => "/admin/photos/mass_upload/",
                "selected"=>true
	);
		
	echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
?>
<div style="clear:both"></div>
<script>
	
	function init_global_progress() {
		$(".upload_in_progress_cont").css('z-index', '2002');
		$(".upload_in_progress_cont").show();
		modo_div = jQuery("<div></div>");
		modo_div.addClass('ui-widget-overlay');
		modo_div.css('z-index', '2001'); 
		modo_div.css('height', '200%');
		
		jQuery('body').append(modo_div);
		
		$(".upload_in_progress_cont .progress").progressbar({
			value:0
		});
	}
	
	function fail(data) {
		var error_to_add;
		
		//console.log (data.files[0]);
		
		if (data.files[0].fileName == undefined) {
			$('div.upload_complete .error_cont p').html('<?php __('Error! It seems that you are no longer logged in. Please log in and try to upload your photos again.'); ?>');
			$('div.upload_complete .error_cont').show();
		} else {
			var error_to_add = $("<li>"+data.files[0].fileName+"</li>");
			$('div.upload_complete .error_cont ul').append(error_to_add);
		}
	}
       
        //TODO Adam Fix formatting. 
	$(document).ready(function() {
		var global_modal;
		var test;
		var fileupload_data_percentage= 0;
		var fileupload_count_percentage = 0;
		$('#fileupload').fileupload({
			dataType: 'json',
			//'fileInput': jQuery('#upload_files'),
			sequentialUploads: true,
			done: function (e, data) {
				//console.log('upload done');
				uploaded_complete = parseInt($(".upload_in_progress_cont .count_uploaded_cont .uploaded_complete").html()) + 1;
				$(".upload_in_progress_cont .count_uploaded_cont .uploaded_complete").html(uploaded_complete);
				
				fileupload_count_percentage = ((uploaded_complete / parseInt($(".upload_in_progress_cont .count_uploaded_cont .total_to_upload").html())) * 100) * .5;
				var progress_to_display = fileupload_count_percentage + fileupload_data_percentage;
				///console.log(progress_to_display);
				$(".upload_in_progress_cont .progress").progressbar({
						value: progress_to_display	
				});
				
				if (data.result.code == -1) {
					fail(data);
				}				
			},
			fail: function(e, data) {
				data.context.each(function() {
					$(this).remove();
				});
				
				if (data.jqXHR != undefined) {
					fail(data);
				}
			},
			start: function(e, data) {
				$(".upload_in_progress_cont .count_uploaded_cont .total_to_upload").html($('#fileupload .files_ready_to_upload_cont table tbody tr').length); //-1 to account for header
				console.log($(".upload_in_progress_cont .count_uploaded_cont .total_to_upload").html());
				init_global_progress();				
			},
			progressall: function(e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				progress = progress * .5;
				fileupload_data_percentage = progress;
				
				var progress_to_display = progress + fileupload_count_percentage;
				$(".upload_in_progress_cont .progress").progressbar({
					value: progress_to_display	
				});
			},
			progress: function(e, data) {
			},
			stop: function(e, data) {
				$("#photo_mass_upload_outer_wrapper .upload_content .files_ready_to_upload_cont table tbody tr").remove();
				$("#photo_mass_upload_outer_wrapper .upload_content .files_ready_to_upload_cont .files_ready_to_upload_inner_cont .empty_help_content").show();
				
				$(".upload_in_progress_cont .progress").progressbar({
					value:100	
				});
				fileupload_progress_percentage = 0;
				fileupload_count_percentage = 0;
				$(".upload_in_progress_cont .count_uploaded_cont .uploaded_complete").html('0');
				$(".upload_in_progress_cont").hide();
				$(".ui-widget-overlay").remove();
				
				if($('div.upload_complete .error_cont ul li').length > 0) {
					$('div.upload_complete .error_cont').show();
				}
				$('div.upload_complete').foto_background_alert(); 
			}
		});

			
		$("#fileupload").bind('fileuploadadd', function(e, upload_data) {
			$("#photo_mass_upload_outer_wrapper .upload_content .files_ready_to_upload_cont .files_ready_to_upload_inner_cont .empty_help_content").hide();
		});
                
		$("#photo_mass_upload_outer_wrapper .files_ready_to_upload_cont button.start").click(function(e) {
			if ($("#photo_mass_upload_outer_wrapper .files_ready_to_upload_cont table tbody tr").length == 0) {
			      $.foto('alert', '<?php __('No photos have been choosen. Click on the green add files button to get started.'); ?>');
			      e.preventDefault();
			}
		});
                
		$(".upload_files_cont").hover(
			function() {
				$(this).find('button').addClass('ui-state-hover');
			},
			function() {
				$(this).find('button').removeClass('ui-state-hover');
			});
		
                $("#photo_mass_upload_outer_wrapper .upload_content .upload_files_cont button").click(function(e) {
                    e.preventDefault();
                    $("#photo_mass_upload_outer_wrapper .upload_content .upload_files_cont input[type=file]").trigger("click");
                });
	});
        var this_data;
</script>
<div id="preview"></div>
<div style="display:none" class='upload_complete'>
	<?php __('Upload Complete'); ?>
	<div style="display:none;" class="error_cont rounded-corners">
		<p><?php __('The following images did not upload successfully:'); ?></p>
		<ul></ul>
	</div>
</div>
<div style="display:none" class="upload_in_progress_cont message_div rounded-corners medium_message_box drop-shadow">
	<div class="upload_in_progress">
		<div style='position:relative'>
			<div class="upload_in_progress_header"><?php __('Upload Processing'); ?></div>
			<div class="overall_upload">
				<div class='label'><?php __('Overall Progress:'); ?></div>
				<div class="progress"></div>
			</div>
			<div class='count_uploaded_cont'>
				<span class='uploaded_complete'>0</span>/<span class='total_to_upload'>0</span>
			</div>
		</div>
	</div>
</div>
<div id="photo_mass_upload_outer_wrapper">
	<form id="fileupload" action="/admin/photos/process_mass_photos" method="POST" enctype="multipart/form-data">
		<div class="upload_content fileupload-buttonbar">
			<div class="files_ready_to_upload_cont">
				<div class='files_ready_to_upload_inner_cont custom_ui_radio'>
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
				</div>
				
				<?php /*<table role="presentation" class="table table-striped">
					<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
				</table>
				<div class="empty_help_content upload_start" style="display:block;">
					<?php __('Drag and drop your photos here. Or choose file here'); ?>
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
			<?php /* <div>
					<!-- The fileinput-button span is used to style the file input field as button -->
					<span class="btn btn-success fileinput-button">
						<i class="icon-plus icon-white"></i>
						<span>Add files...</span>
						<input type="file" name="files[]" multiple>
					</span>
					<button type="submit" class="btn btn-primary start">
						<i class="icon-upload icon-white"></i>
						<span>Start upload</span>
					</button>
					<button type="reset" class="btn btn-warning cancel">
						<i class="icon-ban-circle icon-white"></i>
						<span>Cancel upload</span>
					</button>
					<button type="button" class="btn btn-danger delete">
						<i class="icon-trash icon-white"></i>
						<span>Delete</span>
					</button> 
					<input type="checkbox" class="toggle">
				</div> 
				<!-- The global progress information -->
				<div class="span5 fileupload-progress fade">
					<!-- The global progress bar -->
					<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="bar" style="width:0%;"></div>
					</div>
					<!-- The extended global progress information -->
					<div class="progress-extended">&nbsp;</div>
				</div>
			</div>
			<!-- The loading indicator is shown during file processing -->
			<div class="fileupload-loading"></div>
			<br>
			<!-- The table listing the files available for upload/download -->
			<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>*/ ?>
	</form>
	<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-upload fade rounded-corners">
			<td class="preview middle"><span class="fade"></span></td>
			<td class="name middle"><span class="non-image">{%=file.name%}</span></td>
			<td class="size middle"><span class="non-image">{%=o.formatFileSize(file.size)%}</span></td>
			{% if (file.error) { %}
				<td class="error non-image" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
			{% } else if (o.files.valid && !i) { %}
				<td class="cancel non-image cancel_action middle">{% if (!o.options.autoUpload) { %}
					<button class="btn btn-warning cancel-upload">
						<i class="icon-ban-circle icon-white"></i>
						<span>{%=locale.fileupload.remove%}</span>
					</button>
				{% } %} </td><td class='start' style='display:none'><button></button></td>
			{% } else { %}
				<td colspan="2"></td>
			{% } %}
		</tr>
	{% } %}
	</script>
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download fade">
			{% if (file.error) { %}
				<td></td>
				<td class="name non-image"><span>{%=file.name%}</span></td>
				<td class="size non-image"><span>{%=o.formatFileSize(file.size)%}</span></td>
				<td class="error non-image" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
			{% } else { %}
				<td class="preview">{% if (file.thumbnail_url) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
				{% } %}</td>
				<td class="name non-image">
					<a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
				</td>
				<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
				<td colspan="2"></td>
			{% } %}
		</tr>
	{% } %}
	</script>
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