<?php $uuid = $this->Util->uuid(); ?>
<script type="text/javascript">
	var possible_value_regex = "<?php echo $curr_setting['possible_values']; ?>";
	var patt = undefined;
	if (possible_value_regex != '') {
		patt = new RegExp(possible_value_regex);
	}
	
	
	var save_textarea_timeout_<?php echo $uuid; ?>;
	function save_textarea_<?php echo $uuid; ?>() {
		var setting_name = '<?php echo $setting_name; ?>';
		var setting_value = jQuery('#<?php echo $uuid; ?> form textarea').val();

//				console.log (setting_name);
//				console.log (setting_value);

		if (patt != undefined && patt.test(setting_value) === false) {
			return false;
		}


		save_theme_setting(setting_name, setting_value, 
			function() {
				console.log ("success");
			}, 
			function() {
				console.log ("error");
			}
		);
	}
	
	jQuery(document).ready(function() {
		jQuery('#<?php echo $uuid; ?> form textarea').tinymce({
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
			theme_advanced_resizing : true,

			onchange_callback : function() {
				save_textarea_<?php echo $uuid; ?>();
			},
			setup : function(ed) {
				ed.onKeyUp.add(function(ed, e) {
					clearTimeout(save_textarea_timeout_<?php echo $uuid; ?>);
					save_textarea_timeout_<?php echo $uuid; ?> = setTimeout(function() {
						save_textarea_<?php echo $uuid; ?>();
					}, 700);
				});
			}
		});
		
	});
</script>

<div id="<?php echo $uuid; ?>" class="theme_setting_container">
	<label class="text_area_text"><?php echo $curr_setting['display_name']; ?></label>
	<div class="theme_setting_inputs_container">
		<form class="text_area">
			<textarea rows="4" cols="50"><?php echo $curr_setting['current_value']; ?></textarea>
		</form>
	</div>
	<p>
		<?php echo $curr_setting['description']; ?>
	</p>
</div>
<div style="clear: both"></div>

<?php // debug($setting_name); ?>
<?php // debug($curr_setting); ?>