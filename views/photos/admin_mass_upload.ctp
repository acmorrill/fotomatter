
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included --
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>

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
	$(document).ready(function() {
		$('#fileupload').fileupload({
			dataType: 'json',
			//'fileInput': jQuery('#upload_files'),
			done: function (e, data) {
			console.log(e);
				$.each(data.result, function (index, file) {
					$('<p/>').text(file.name).appendTo(document.body);
				});
			}
		});
		$(".upload_files_cont").hover(
			function() {
				$(this).find('button').addClass('ui-state-hover');
			},
			function() {
				$(this).find('button').removeClass('ui-state-hover');
			});
		
		/*$("#fileupload").fileupload({
			'options': {
				'fileInput': jQuery("button")
			}
		}); */
		$(".upload_files_cont button").button();
	});
</script>
<div id="photo_mass_upload_outer_wrapper">
	<form id="fileupload" action="/admin/photos/process_mass_photos" method="POST" enctype="multipart/form-data">
		<div class="upload_content">
			<div class="table_header_darker">
				<h2><?php __('Upload New Photos'); ?></h2>
				<div class="upload_files_cont custom_ui_radio">
					<button>
						<span>Upload here</span>
					</button>
					<input type="file" class="upload_files" id="upload_files">
					
				</div>
			</div>
			
			<div class="files_ready_to_upload_cont">
				<div class='upload_table_header'><?php __('New Photos'); ?></div>
				<div class='table_cont rounded-corners'>
					<table role="presentation" class="table table-striped">
						<thead>
							<tr>
								<th width='20%'>Photo</th>
								<th>Name</th>
								<th>Size</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
					</table>
				</div>
				
				<?php /*<table role="presentation" class="table table-striped">
					<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
				</table>
				<div class="empty_help_content upload_start" style="display:block;">
					<?php __('Drag and drop your photos here. Or choose file here'); ?>
				</div> */ ?>
			</div>
			<div class="generic_photo_category_cont">
				
			</div>
			<div style='clear:both'></div>
			
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
		<tr class="template-upload fade">
			<td class="preview"><span class="fade"></span></td>
			<td class="name"><span>{%=file.name%}</span></td>
			<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
			{% if (file.error) { %}
				<td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
			{% } else if (o.files.valid && !i) { %}
				<td>
					<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
				</td>
				<td class="start">{% if (!o.options.autoUpload) { %}
					<button class="btn btn-primary">
						<i class="icon-upload icon-white"></i>
						<span>{%=locale.fileupload.start%}</span>
					</button>
				{% } %}</td>
			{% } else { %}
				<td colspan="2"></td>
			{% } %}
			<td class="cancel">{% if (!i) { %}
				<button class="btn btn-warning">
					<i class="icon-ban-circle icon-white"></i>
					<span>{%=locale.fileupload.cancel%}</span>
				</button>
			{% } %}</td>
		</tr>
	{% } %}
	</script>
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download fade">
			{% if (file.error) { %}
				<td></td>
				<td class="name"><span>{%=file.name%}</span></td>
				<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
				<td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
			{% } else { %}
				<td class="preview">{% if (file.thumbnail_url) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
				{% } %}</td>
				<td class="name">
					<a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
				</td>
				<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
				<td colspan="2"></td>
			{% } %}
			<td class="delete">
				<button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
					<i class="icon-trash icon-white"></i>
					<span>{%=locale.fileupload.destroy%}</span>
				</button>
				<input type="checkbox" name="delete" value="1">
			</td>
		</tr>
	{% } %}
	</script>
</div>