<?php 
	$subnav = array(); 

	$subnav['title'] = array(
		'name' => $this->data['PhotoGallery']['display_name'],
		'url' => "/admin/photo_galleries/edit_gallery/{$this->data['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Gallery Settings', true),
		'url' => "/admin/photo_galleries/edit_gallery/{$this->data['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Connect Photos', true),
		'url' => "/admin/photo_galleries/edit_gallery_connect_photos/{$this->data['PhotoGallery']['id']}/"
	);
	$subnav['pages'][] = array(
		'name' => __('Arrange Photos', true),
		'url' => "/admin/photo_galleries/edit_gallery_arrange_photos/{$this->data['PhotoGallery']['id']}/",
		'selected' => true
	);
		
	echo $this->Element('/admin/submenu', array( 'subnav' => $subnav ));
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#connect_gallery_photos_cont').sortable({
			items: '.connect_photo_container',
			handle: '.order_in_gallery_button',
			tolerance: 'pointer',
			containment: 'parent',
			scrollSensitivity: 60,
			update : function(event, ui) {
				var context = this;
				jQuery(context).sortable('disable');
				
				// figure the the now position of the dragged element
				var photoId = jQuery(ui.item).attr('photo_id');
				var newPosition = ui.item.index() + 1;// TODO - this must always be set - fail otherwise -- not sure if it will be from jquery ui
				
				jQuery.post('/admin/photo_galleries/ajax_set_photo_order_in_gallery/<?php echo $gallery_id; ?>/'+photoId+'/'+newPosition+'/', function(data) {
					if (data.code == 1) {
						// its all good
					} else {
						major_error_recover(data.message);
					}
					jQuery(context).sortable('enable');
				}, 'json');
		
			}
		}).disableSelection();
	});
</script>

<div id="connect_gallery_photos_cont">
	<div class="in_gallery_main_cont arrange">
		<div class="table_header_darker">
			<h2 style="background: url('/img/admin/icons/gallery_arrange_photos.png') center left no-repeat; padding-left: 35px; height: 25px; line-height: 29px;"><?php __('Arrange Photos in Gallery'); ?></h2>
		</div>
		<div class="empty_help_content" style="<?php if (empty($this->data['PhotoGalleriesPhoto'])): ?>display: block;<?php endif; ?>">
			<?php __('This gallery has no photos to arrange yet<br/> Add photos on the <a href="/admin/photo_galleries/edit_gallery_connect_photos/'.$gallery_id.'/">connect photos page</a>'); ?>
		</div>
		<div class="in_gallery_photos_cont arrange">
			<?php echo $this->Element('/admin/photo/photo_connect_in_gallery_photo_cont', array( 'connected_photos' => $this->data['PhotoGalleriesPhoto'] )); ?>
		</div>
	</div>
	<div style="clear: both;"></div>
</div>