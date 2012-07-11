<script type="text/javascript">
	register_page_element_callbacks(new element_callbacks({
		uuid: '<?php echo $uuid; ?>',
		init: function(page_element_cont) {
			// setup tiny mce for paragraph edits
			jQuery('.tinymce textarea', page_element_cont).tinymce({
				// Location of TinyMCE script
				script_url : '/js/tinymce/jscripts/tiny_mce/tiny_mce.js',

				// General options
				theme : "advanced",
				plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist", // DREW TODO - shortent this list

				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,blockquote,link,unlink,anchor,code",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true
			});

			jQuery('.para_header_image_cont .para_image_header_image_pos', page_element_cont).buttonset();

			jQuery('.para_header_image_cont .para_image_header_image_pos', page_element_cont).change(function() {
				var container = jQuery(this).closest('.para_header_image_cont');

				if (container.find('.image_cont').hasClass('left')) {
					container.find('.image_cont').removeClass('left');
					container.find('.image_cont').addClass('right');
				} else {
					container.find('.image_cont').removeClass('right');
					container.find('.image_cont').addClass('left');
				}
			});
			
			
			/////////////////////////////////////////
			// testing code
			jQuery('.generic_sort_and_filters .tiny_mce_test', page_element_cont).click(function() {
				var textarea_val = jQuery(this).closest('.page_element_cont').find('.tinymce textarea').val();
				alert(textarea_val);
			});
		}
	}));
</script>

<form id="<?php echo $uuid; ?>">
	<div class="para_header_image_cont">
		<div class="page_element_top_section rounded-corners-small no-bottom-rounded">
			<input class="defaultText header" type="text" title="Page Element Heading" style="margin-bottom: 10px; width: 260px;" />
			<div class="para_image_cont">
				<div class="image_cont left">
					<img src="<?php echo $this->Photo->get_dummy_error_image_path(100, 100); ?>" />
				</div>
				<div class="paragraph tinymce">
					<textarea class="" title="The default paragraph text" style="width: 75%; height: 124px;"></textarea>
				</div>
			</div>
		</div>
		<div class="generic_sort_and_filters" style="border: 0px; border-top: 1px solid #303030; height: auto; position: relative; padding: 15px;">
			<?php if (Configure::read('debug') > 1): ?>
				<div class="tiny_mce_test" style="position: absolute; top: 0px; right: 0px; background: black; color: white; cursor: pointer; padding: 5px;">Tiny Mce Value</div>
			<?php endif; ?>


			<div class="custom_ui_radio">
				<div class="para_image_header_image_pos">
					<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
					<input type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" checked="checked" /><label for="<?php echo $uuid; ?>"><?php __('Image On Left'); ?></label>
					<?php $uuid = substr(base64_encode(String::uuid()), 0, 25); ?>
					<input type="radio" id="<?php echo $uuid; ?>" name="para_image_header_image_pos" /><label for="<?php echo $uuid; ?>"><?php __('Image On Right'); ?></label>
				</div>
			</div>

			<div style="clear: both;"></div>
		</div>
	</div>
</form>
