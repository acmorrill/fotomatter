	<style>
	/* Adjust the jQuery UI widget font-size: */
	.ui-widget {
		font-size: 0.95em;
	}
	</style>
	<!-- blueimp Gallery styles -->
	<link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
	<link rel="stylesheet" href="/js/jQuery-File-Upload/css/jquery.fileupload.css">
	<link rel="stylesheet" href="/js/jQuery-File-Upload/css/jquery.fileupload-ui.css">
	<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="/js/jQuery-File-Upload/css/jquery.fileupload-noscript.css"></noscript>
	<noscript><link rel="stylesheet" href="/js/jQuery-File-Upload/css/jquery.fileupload-ui-noscript.css"></noscript>
	
	
	<!-- The template to display files available for upload -->
	<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-upload">
			<td class="first"></td>
			<td>
				<div class="rightborder"></div>
				<p class="name">{%=file.name%}</p>
				<strong class="error"></strong>
			</td>
			<td class="progress_td">
				<div class="rightborder"></div>
				<p class="size">Processing...</p>
				<div class="custom_progress"></div>
				<div class="progress"></div>
			</td>
			<td class="last">
				<div class="rightborder"></div>
		
				{% if (!i && !o.options.autoUpload) { %}
					<button class="start" disabled>Start</button>
				{% } %}
				{% if (!i) { %}
					<button class="cancel">Cancel</button>
			
					<span class="custom_ui cancel_photo_upload">
						<div class="add_button icon"><div class="content">X</div></div>
					</span>
				{% } %}
			</td>
		</tr>
	{% } %}
	</script>
	<!-- The template to display files available for download -->
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download">
			<td class="first thumbnail">
				<span class="preview">
					{% if (file.thumbnailUrl) { %}
						<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
					{% } %}
				</span>
			</td>
			<td class="progress_td">
				<p class="name">
					<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
				</p>
				{% if (file.error) { %}
					<div><span class="error">Error</span> {%=file.error%}</div>
				{% } %}
			</td>
			<td>
				<span class="size">{%=o.formatFileSize(file.size)%}</span>
			</td>
			<td class="last"></td>
		</tr>
	{% } %}
	</script>
	<!-- The Templates plugin is included to render the upload/download listings -->
	<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
	<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
	<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
	<!-- The Canvas to Blob plugin is included for image resizing functionality -->
	<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
	<!-- blueimp Gallery script -->
	<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="/js/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
	<!-- The basic File Upload plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload.js"></script>
	<!-- The File Upload processing plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload-process.js"></script>
	<!-- The File Upload image preview & resize plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload-image.js"></script>
	<!-- The File Upload audio preview plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload-audio.js"></script>
	<!-- The File Upload video preview plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload-video.js"></script>
	<!-- The File Upload validation plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload-validate.js"></script>
	<!-- The File Upload user interface plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload-ui.js"></script>
	<!-- The File Upload jQuery UI plugin -->
	<script src="/js/jQuery-File-Upload/js/jquery.fileupload-jquery-ui.js"></script>
	<!-- The main application script -->
	<!--<script src="/js/jQuery-File-Upload/js/main.js"></script>-->
	<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
	<!--[if (gte IE 8)&(lt IE 10)]>
	<script src="js/cors/jquery.xdr-transport.js"></script>
	<![endif]-->