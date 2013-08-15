<script src="/js/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<script src="/js/jquery-file-upload/js/jquery.fileupload.js"></script>

<script type="text/javascript">
	/*
	 *	A PROTOTYPE FUNCTION TO BE PASSED IN FROM ELEMENTS
	 */
	var save_page_elements_timeout;
	var save_timeout_count = 2500;
	function element_callbacks(params) {
		if (params.uuid === undefined) {
			major_error_recover('The uuid must be defined');
			return false;
		}
		this.uuid = params.uuid;
		
		if (params.init === undefined || !jQuery.isFunction(params.init)) {
			major_error_recover('The init function must be defined');
			return false;
		}
		
		this.init = params.init;
		
		this.global_init = function() {
			var page_element_cont = jQuery('#'+this.uuid);
			
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
				theme_advanced_resizing : true,
				
				onchange_callback : function() {
					save_page_elements();
				},
				setup : function(ed) {
					ed.onKeyUp.add(function(ed, e) {
						clearTimeout(save_page_elements_timeout);
						save_page_elements_timeout = setTimeout(function() {
							save_page_elements();
						}, save_timeout_count);
					});
				}
				
			});
			
			
			jQuery('input', page_element_cont).change(function() {
				save_page_elements();
			}).keyup(function() {
				clearTimeout(save_page_elements_timeout);
				save_page_elements_timeout = setTimeout(function() {
					save_page_elements();
				}, save_timeout_count);
			});
		}
		
//		if (params.save !== undefined) {
//			this.save = params.save;
//		} else {
//			this.save = function() {
//				console.log (jQuery('#'+this.uuid).serialize());
//				console.log ("the generic save function");
//			}
//		}
		
		this.toString = function() {
			//console.log ("this is a method to describe this object");
		}
	}
	
	var element_callbacks_array = {};
	function register_page_element_callbacks(callbacks_obj) {
		if (callbacks_obj instanceof element_callbacks) {
			element_callbacks_array[callbacks_obj.uuid] = callbacks_obj;
		} else {
			major_error_recover('you must pass in an instanceof element_callbacks as the second param');
		}
	}
	
	function call_element_inits() {
		for (var i in element_callbacks_array) {
			call_element_init(i);
		}
	}
	
	function call_element_init(uuid) {
		if (jQuery.isFunction(element_callbacks_array[uuid].init)) {
			element_callbacks_array[uuid].init(jQuery('#'+uuid));
			element_callbacks_array[uuid].global_init();
			element_callbacks_array[uuid].toString();
		} else {
			major_error_recover('failed to call init function for a page element');
		}		
	}
	
	function save_page_elements() {
		console.log ("came into save page elements");
		
		var page_element_data_to_save = {};
		page_element_data_to_save['element_data'] = {};
		
		for (var i in element_callbacks_array) {
			var element_form = jQuery('#'+element_callbacks_array[i].uuid);
			var site_pages_site_page_element_id = element_form.closest('.page_element_cont').attr('site_pages_site_page_element_id');

			if (element_form == undefined) { 
				major_error_recover('Page element could not be found');
				return false;
			}
			if (element_form[0].nodeName.toLowerCase() !== 'form') { 
				major_error_recover('Page element is not surrounded by a form element');
				return false;
			}
			
			page_element_data_to_save['element_data'][site_pages_site_page_element_id] = element_form.serialize();
		}
		
		jQuery.ajax({
			type: 'post',
			url: '/admin/site_pages/save_page_elements/',
			data: page_element_data_to_save,
			success: function(data) {
//				console.log (data);
			},
			complete: function() {
//				console.log ("came into save page complete");
			},
			error: function() {
//				console.log ("came into save page error");
			},
			dataType: 'json'
		});
	}
	
	function setup_page_element_delete(selector) {
		var page_element_cont = jQuery(selector);
		
		console.log (element_callbacks_array);
		
		jQuery('.page_element_delete', page_element_cont).click(function() {
			var context = this;

			jQuery.foto('confirm', {
				'button_title' : '<?php __('Delete'); ?>',
				'onConfirm' : function() {
					// remove element from save array
					var element_form = jQuery(context).closest('.page_element_cont').find('form');
					var uuid = element_form.attr('id');
					delete element_callbacks_array[uuid];
					
					var site_pages_site_page_element_id = jQuery(context).closest('.page_element_cont').attr('site_pages_site_page_element_id');
					
					jQuery.ajax({
						type: 'post',
						url: '/admin/site_pages/ajax_remove_page_element/'+site_pages_site_page_element_id+'/',
						data: {},
						success: function(data) {
							if (data.code == 1) {
								jQuery(context).closest('.page_element_cont').remove();
							} else {
								major_error_recover('Failed to remove page element');
							}
						},
						complete: function() {
							// DREW TODO - take care of this case
						},
						error: function() {
							// DREW TODO - take care of the error case
						},
						dataType: 'json'
					});
				},
				'type' : 'alert',
				'message': '<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php __('Permanently delete page element?'); ?></p>',
				'minWidth': 400,
				'minHeight': 160
				
			});
			
			
		});
	}
	
	
	function setup_page_element_sortable(selector) {
		jQuery(selector).sortable(jQuery.extend(verticle_sortable_defaults, {
			items : '.page_element_cont',
			handle : '.reorder_page_grabber',
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the now position of the dragged element
				var site_pages_site_page_element_id = jQuery(ui.item).attr('site_pages_site_page_element_id'); 
				var newPosition = position_of_element_among_siblings(jQuery('.page_element_cont', this), jQuery(ui.item));
				
				jQuery.ajax({
					type: 'post',
					url: '/admin/site_pages/ajax_set_page_element_order/'+site_pages_site_page_element_id+'/'+newPosition+'/',
					data: {},
					success: function(data) {
						if (data.code != 1) {
							// TODO - maybe revert the draggable back to its start position here
						}
					},
					complete: function() {
						jQuery(context).sortable('enable');
					},
					dataType: 'json'
				});
			}
		})).disableSelection();
	}
	
	jQuery(document).ready(function() {
		call_element_inits();
		
		// setup autosave
		setInterval(function() {
			save_page_elements();
		}, 300000); // 300000 - 5 mins
		
		//admin_ajax_add_page_element
		
		jQuery('#configure_page_cont .avail_element_cont').click(function() {
			var element_id = jQuery(this).attr('avail_page_element_id');
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/site_pages/ajax_add_page_element/<?php echo $page_id; ?>/'+element_id+'/',
				data: {},
				success: function(data) {
					//console.log (data);
					if (data.code == 1) {
						// its all good
						var new_element = jQuery(data.element_html);
						setup_page_element_sortable(new_element);
						setup_page_element_delete(new_element);
						var page_content_cont = jQuery('#configure_page_cont .page_content_cont');
						page_content_cont.append(new_element).scrollTop(page_content_cont.prop("scrollHeight"));
						
						
						
						// call init on new page element
						var new_uuid = jQuery(new_element).find('form').attr('id');
						call_element_init(new_uuid);
					} else {
						major_error_recover(data.message);
					}
				},
				complete: function() {

				},
				dataType: 'json'
			});
		});
		
		setup_page_element_sortable('#configure_page_cont .page_content_cont');
		setup_page_element_delete('#configure_page_cont .page_content_cont');
	});
</script>

<div id="confirm_delete_page_element" class="dialog_confirm custom_dialog" title="<?php __('Remove Page Element'); ?>">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php __('Permenently delete page element?'); ?></p>
</div>

<div id="configure_page_cont" class="clear">
	<div class="avail_page_elements_cont">
		<div class="table_header_darker">
			<h2 style="background: url('/img/admin/icons/page_element.png') center left no-repeat; padding-left: 35px;"><?php __('Page Elements'); ?></h2>
		</div>
		<div class="content-background" style="height: 600px;">
			<?php $avail_elements = $this->Page->get_avail_page_elements(); ?>
			<?php foreach ($avail_elements as $avail_element): ?>
				<div class="avail_element_cont" avail_page_element_id="<?php echo $avail_element['SitePageElement']['id']; ?>">
					<div class="icon_div <?php echo $avail_element['SitePageElement']['ref_name']; ?>_icon"></div>
					<?php /*<div><?php echo $avail_element['SitePageElement']['ref_name']; ?></div>*/ ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="page_content_cont content-background">
		<?php echo $this->Element('page_elements/list_admin_page_elements', array(compact(
			'sitePagesSitePageElements'
		))); ?>
	</div>
	<div class="clear"></div>
</div>
