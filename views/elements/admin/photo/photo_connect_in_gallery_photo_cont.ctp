<?php //debug($connected_photos); ?>
<?php foreach ($connected_photos as $the_photo): ?>
<div class="connect_photo_container" photo_id="<?php echo $the_photo['id']; ?>">
	<div class="table">
		<div class="tr">
			<img class="abs_image_tr" src="/img/admin/icons/bw_simple_close_icon.png" />
			<div class="image_content_cont td">
				<img src="<?php echo $this->Photo->get_photo_path($the_photo['id'], 100, 100); ?>" alt="click to remove" />
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>