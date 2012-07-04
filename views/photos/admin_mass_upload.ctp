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
<input id="fileupload" type="file" name="files[]" data-url="/admin/photos/process_mass_photos" multiple>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="/js/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="/js/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<script src="/js/jquery-file-upload/js/jquery.fileupload.js"></script>
<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
	     console.log(e);
            $.each(data.result, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
});
</script>