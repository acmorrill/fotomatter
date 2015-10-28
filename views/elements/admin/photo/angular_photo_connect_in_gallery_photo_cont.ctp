<div ng-repeat="photo_galleries_photo in open_gallery.PhotoGalleriesPhoto" class="{{photo_galleries_photo.PhotoGalleriesPhoto.photo_cache_class}} connect_photo_container">
	<div class="image_cover">{{photo_galleries_photo.PhotoGalleriesPhoto.photo_id}}</div>
	<div class="remove_from_gallery_button gallery_image_circle_button top_right">
		<div class="icon-close-01"></div>
	</div>
	<div class="order_in_gallery_button gallery_image_circle_button bottom_left">
		<div class="reorder_icon icon-position-01"></div>
	</div>
	<div class="table">
		<div class="tr">
			<div class="td">
				<div class="image_content_cont">
					<img src="{{photo_galleries_photo.PhotoGalleriesPhoto.photo_cache_url}}" alt="" />
				</div>
			</div>
		</div>
	</div>
</div>