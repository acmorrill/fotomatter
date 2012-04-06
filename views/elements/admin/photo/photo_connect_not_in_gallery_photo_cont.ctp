<?php foreach ($not_connected_photos as $not_connected_photo): ?>
	<div class="connect_photo_container" photo_id="<?php echo $not_connected_photo['Photo']['id']; ?>">
		<div class="table">
			<div class="tr">
				<img class="abs_image_br" src="/img/admin/icons/green_left_circle_arrow.png" />
				<div class="image_content_cont td">
					<img src="<?php echo $this->Photo->get_photo_path($not_connected_photo['Photo']['id'], 100, 100); ?>" alt="click to remove" />
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>



