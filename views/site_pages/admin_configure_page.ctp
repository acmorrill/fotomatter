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
	
	function setup_para_header_image() {
		// setup tiny mce for paragraph edits
		jQuery('.tinymce textarea').tinymce({
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
		
		jQuery('.para_header_image_cont .para_image_header_image_pos').buttonset();
		
		jQuery('.para_header_image_cont .para_image_header_image_pos').change(function() {
			var container = jQuery(this).closest('.para_header_image_cont');

			if (container.find('.image_cont').hasClass('left')) {
				container.find('.image_cont').removeClass('left');
				container.find('.image_cont').addClass('right');
			} else {
				container.find('.image_cont').removeClass('right');
				container.find('.image_cont').addClass('left');
			}
			
			console.log ("the thing was changed");
		});
	}
	
	jQuery(document).ready(function() {
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
		
		setup_para_header_image();
		
		
		
		
		/////////////////////////////////////////
		// testing code
		jQuery('.generic_sort_and_filters .tiny_mce_test').click(function() {
			var textarea_val = jQuery(this).closest('.page_element_cont').find('.tinymce textarea').val();
			alert(textarea_val);
		});
		
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
