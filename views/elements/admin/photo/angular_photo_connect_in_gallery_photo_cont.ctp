<div ng-repeat="photo_galleries_photo in open_gallery_connected_photos" class="{{photo_galleries_photo.PhotoGalleriesPhoto.photo_cache_class}} connect_photo_container" photo_id="{{photo_galleries_photo.PhotoGalleriesPhoto.photo_id}}">
	<div class="image_cover"></div>
	<div class="remove_from_gallery_button gallery_image_circle_button top_right" ng-click="remove_photo_from_gallery(photo_galleries_photo)">
		<div class="icon-close-01"></div>
	</div>
	<div class="order_in_gallery_button gallery_image_circle_button bottom_left">
		<div class="reorder_icon icon-position-01"></div>
	</div>
	<div class="table">
		<div class="tr">
			<div class="td">
				<div class="image_content_cont">
					<img ng-src="{{photo_galleries_photo.PhotoGalleriesPhoto.photo_cache_url}}" alt="" />
				</div>
			</div>
		</div>
	</div>
</div>