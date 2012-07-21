<script src="/js/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<script src="/js/jquery-file-upload/js/jquery.fileupload.js"></script>

<?php 
	$subnav = array(); 

	$subnav['title'] = array(
		'name' => $this->data['SitePage']['title'],
		'url' => "/admin/site_pages/edit_page/{$this->data['SitePage']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Page Settings', true),
		'url' => "/admin/site_pages/edit_page/{$this->data['SitePage']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Configure Page', true),
		'url' => "/admin/site_pages/configure_page/{$this->data['SitePage']['id']}/",
		'selected' => true
	);
		
	echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
?>

<?php echo $session->flash(); ?>
<br/>

<script type="text/javascript">
	/*
	 *	A PROTOTYPE FUNCTION TO BE PASSED IN FROM ELEMENTS
	 */
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
		
		
//		if (params.save !== undefined) {
//			this.save = params.save;
//		} else {
//			this.save = function() {
//				console.log (jQuery('#'+this.uuid).serialize());
//				console.log ("the generic save function");
//			}
//		}
		
		this.toString = function() {
			console.log ("this is a method to describe this object");
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
			if (jQuery.isFunction(element_callbacks_array[i].init)) {
				element_callbacks_array[i].init(jQuery('#'+i));
				element_callbacks_array[i].toString();
			} else {
				major_error_recover('failed to call init function for a page element');
			}
		}
	}
	
	function save_page_elements() {
		var page_element_data_to_save = {};
		page_element_data_to_save['element_data'] = {};
		
		for (var i in element_callbacks_array) {
			var element_form = jQuery('#'+element_callbacks_array[i].uuid);
			var site_pages_site_page_element_id = element_form.closest('.page_element_cont').attr('site_pages_site_page_element_id');

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
				console.log (data);
			},
			complete: function() {
				console.log ("came into save page complete");
			},
			error: function() {
				console.log ("came into save page error");
			},
			dataType: 'json'
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
				var newPosition = ui.item.index() + 1; // TODO - this must always be set - fail otherwise -- not sure if it will be from jquery ui
				
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
		}, 5000); // 300000 - 5 mins
		
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
						var page_content_cont = jQuery('#configure_page_cont .page_content_cont')
						page_content_cont.append(new_element).scrollTop(page_content_cont.prop("scrollHeight"));
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
	});
</script>


<div id="configure_page_cont" class="clear">
	<div class="avail_page_elements_cont">
		<div class="table_header_darker">
			<h2 style="background: url('/img/admin/icons/page_element.png') center left no-repeat; padding-left: 35px;"><?php __('Page Elements'); ?></h2>
		</div>
		<div class="content-background" style="height: 600px;">
			<?php $avail_elements = $this->Page->get_avail_page_elements(); ?>
			<?php foreach ($avail_elements as $avail_element): ?>
				<div class="avail_element_cont" avail_page_element_id="<?php echo $avail_element['SitePageElement']['id']; ?>">
					<img src="/img/admin/page_elements/<?php echo $avail_element['SitePageElement']['ref_name']; ?>.jpg" />
					<div><?php echo $avail_element['SitePageElement']['ref_name']; ?></div>
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
