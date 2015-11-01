<?php 

/*style="height: <?php echo $height; ?>px;width: <?php echo $width; ?>px;""
	. "*/
		?>

<div ng-repeat="photo in open_gallery_not_connected_photos" class="{{photo.Photo.photo_cache_class}} connect_photo_container" photo_id="{{photo.Photo.id}}">
	<div class="image_cover"></div>
	<div class="add_to_gallery_button gallery_image_circle_button bottom_right" ng-click="add_photo_to_gallery(photo)">
		<div class="icon-_button-01"></div>
	</div>
	<div class="table">
		<div class="tr">
			<div class="td">
				<div class="image_content_cont">
					<img src="{{photo.Photo.photo_cache_url}}" alt="" />
				</div>
			</div>
		</div>
	</div>
</div>



