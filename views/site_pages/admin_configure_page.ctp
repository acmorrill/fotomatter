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
		};
		
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
		};
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
	
	function save_page_elements(callback) {
		show_universal_save();
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
				if (typeof callback == 'function') {
					callback();
				}
				hide_universal_save();
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
		
		jQuery('.page_element_delete', page_element_cont).click(function() {
			var context = this;

			jQuery.foto('confirm', {
				'button_title' : '<?php echo __('Delete', true); ?>',
				'onConfirm' : function() {
					show_universal_save();
					
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
							hide_universal_save();
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
			helper: function(e, tr) {
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.find('.page_content_inner_cont').remove();
				$helper.height(77);
				$helper.css('outline', '0px');
				$helper.find('td').css('border', '0px');
				$helper.children().each(function(index) {
					// Set helper cell sizes to match the original sizes
					$(this).width($originals.eq(index).width());
				});
				return $helper;
			},
			containment: 'parent',
			axis: 'y',
			tolerance: 'pointer',
			scrollSensitivity: 500,
			items : '.page_element_cont',
			handle : '.reorder_page_grabber',
			opacity: 1,
			update : function(event, ui) {
				show_universal_save();
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
						hide_universal_save();
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
			show_universal_save();
			
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
						var page_content_cont = jQuery('#configure_page_cont .page_content_cont .large_container .table_border > table > tbody');
						page_content_cont.append(new_element).scrollTop(page_content_cont.prop("scrollHeight"));
						
						// call init on new page element
						var new_uuid = jQuery(new_element).find('form').attr('id');
						call_element_init(new_uuid);
					} else {
						major_error_recover(data.message);
					}
				},
				complete: function() {
					hide_universal_save();
				},
				dataType: 'json'
			});
		});
		
		setup_page_element_sortable('#configure_page_cont .page_content_cont');
		setup_page_element_delete('#configure_page_cont .page_content_cont');
	});
</script>
<div id="confirm_delete_page_element" class="dialog_confirm custom_dialog" title="<?php echo __('Remove Page Element', true); ?>">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo __('Permenently delete page element?', true); ?></p>
</div>


<h1><?php echo __('Configure Page', true); ?>
	<div id="help_tour_button" class="custom_ui"><?php echo $this->Element('/admin/get_help_button'); ?></div>
</h1>
<p><?php echo __('Add images, text, and headings to create your custom page.', true); ?></p>
<div style="clear: both;"></div> 


<div id="configure_page_cont" class="clear">
	<div class="avail_page_elements_cont" data-step="1" data-intro="<?php echo __ ('Choose from two page elements. The first has a heading, image, and text, and the second is a large image. Mix and match the two elements to create the desired result. To add it to your page, simply click to select the element. Add as many page elements as you want.', true); ?>" data-position="right">
		<div class="page_content_header">
			<h2><?php echo __('Page Elements', true); ?></h2>
		</div>
		<div class="generic_palette_container">
			<div class="fade_background_top"></div>
			<?php $avail_elements = $this->Page->get_avail_page_elements(); ?>
			<?php foreach ($avail_elements as $avail_element): ?>
				<div class="avail_element_cont" avail_page_element_id="<?php echo $avail_element['SitePageElement']['id']; ?>">
					<div class="icon_div <?php echo $avail_element['SitePageElement']['ref_name']; ?>_icon"></div>
					<?php /*<div><?php echo $avail_element['SitePageElement']['ref_name']; ?></div>*/ ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="page_content_header">
		<h2 data-step="2" data-intro="<?php echo __ ('Once you have added more than one page element, you can arrange them by clicking and dragging the arrows to move the section up or down. ',true); ?>" data-position="top">modify page content below</h2>
	</div>
	<div class="page_content_cont generic_palette_container">
		<div class="fade_background_top"></div>
		<div class="large_container <?php /*no_td_as_block*/ ?>">
			<div class="table_border configure_table_list">
				<table class="list custom_ui">
					<tbody>
						<?php echo $this->Element('page_elements/list_admin_page_elements', array(compact(
							'sitePagesSitePageElements'
						))); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php /*
<?php ob_start(); ?>
<ol>
	<li>This page where you can add page content</li>
	<li>Things to remember
		<ol>
			<li>This page is quite complicated - we may want to talk about it before you design it</li>
		</ol>
	</li>
</ol>
<?php
$html = ob_get_contents();
ob_end_clean();
	echo $this->Element('admin/richard_notes', array(
	'html' => $html
)); ?>
 */ ?>