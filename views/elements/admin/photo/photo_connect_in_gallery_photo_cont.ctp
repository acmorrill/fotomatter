<?php 
/*******************************************************
	REMEMBER IF YOU CHANGE THIS YOU ALSO HAVE TO
	TAKE INTO ACCOUNT HOW THIS IS USED IN JAVASCRIPT
*******************************************************/
?>
<?php foreach ($connected_photos as $the_photo): ?>
<div class="connect_photo_container" photo_id="<?php if (!isset($hide_data)) echo $the_photo['Photo']['id']; ?>">
	<div class="table">
		<div class="tr">
			<img class="abs_image_tr remove_from_gallery_button" src="/img/admin/icons/bw_simple_close_icon.png" />
			<img class="abs_image_tl order_in_gallery_button" src="/img/admin/icons/white_arrange.png" />
			<div class="image_content_cont td">
				<img src="<?php if (!isset($hide_data)) echo $this->Photo->get_photo_path($the_photo['Photo']['id'], 100, 100); ?>" alt="click to remove" />
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>