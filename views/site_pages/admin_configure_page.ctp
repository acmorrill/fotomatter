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
	
	jQuery(document).ready(function() {
		//admin_ajax_add_page_element
		
		jQuery('#configure_page_cont .avail_element_cont').click(function() {
			var element_id = jQuery(this).attr('avail_page_element_id');
			
			jQuery.ajax({
				type: 'post',
				url: '/admin/site_pages/ajax_add_page_element/<?php echo $page_id; ?>/'+element_id+'/',
				data: {},
				success: function(data) {
					console.log (data);
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
