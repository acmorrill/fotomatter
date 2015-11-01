'use strict';


var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('TagListCtrl', ['$scope', '$q', 'Tags', function($scope, $q, Tags) {
	$scope.loading = true;
	$scope.tag_manager_error = '';
	
	Tags.index().$promise.then(function(tags) {
		$scope.loading = false;
		$scope.tags = tags;
	});
	
	$scope.orderProp = '-Tag.id';
	$scope.change_sort = function(new_sort) {
		if ($scope.orderProp == new_sort) {
			new_sort = '-' + new_sort;
		}
		$scope.orderProp = new_sort;
	};
	
	$scope.edit_tag = function(new_name, tag_id) {
		if (new_name.length === 0) {
			return "Tag cannot be empty.";
		}
		if (new_name.length > 80) {
			return "Tag > 80 characters.";
		}
		var edit_tag = {
			id: tag_id,
			name: new_name
		};
		var d = $q.defer();
		Tags.edit(edit_tag, 
			function(result) {
				if (result.type === 'success') {
					d.resolve();
				} else {
					d.resolve(result.text);
				}
			},
			function(error) {
				d.resolve("Server Error");
			}
		);
		
		return d.promise;
	};
	
	$scope.delete_tag = function(tag_id) {
		$scope.tag_manager_error = '';
		
		var tag_to_delete;
		var tag_to_delete_index;
		for (var tag_index in $scope.tags) {
			if ($scope.tags[tag_index].Tag.id === tag_id) {
				tag_to_delete = $scope.tags[tag_index].Tag;
				tag_to_delete_index = tag_index;
				break;
			}
		}
		
		Tags.delete(tag_to_delete, 
			function(result) {
				if (result.type === 'success') {
					$scope.tags.splice(tag_index, 1);
				} else {
					$scope.tag_manager_error = result.text;
				}
			},
			function(error) {
				$scope.tag_manager_error = 'Server error deleting tag.';
			}
		);
	};
	
	$scope.adding_tag = false;
	
	$scope.add_tag = function() {
		$scope.tag_manager_error = '';
		$scope.orderProp = '-Tag.id';
		$scope.adding_tag = true;
		var data = {};
		data.name = $scope.new_tag;
		$scope.new_tag = '';
		Tags.add(data, 
			function(result) {
				if (result.type === 'success') {
					$scope.tags.unshift(result.new_tag);
				} else {
					$scope.tag_manager_error = result.text;
				}
				$scope.adding_tag = false;
			},
			function(error) {
				$scope.tag_manager_error = 'Add tag server error.';
				$scope.adding_tag = false;
			}
		);
	};
}]);


fotomatterControllers.controller('GalleriesCtrl', ['$scope', '$q', 'PhotoGalleries', '$cookies', function($scope, $q, PhotoGalleries, $cookies) {
	$scope.loading = true;
	$scope.photo_galleries = [];
	$scope.open_gallery = null;	
	$scope.open_gallery_connected_photos = null;	
	$scope.open_gallery_not_connected_photos = null;


	$scope.initGallery = function() {
		$scope.open_gallery_not_connected_order_by = 'modified';
		$scope.open_gallery_not_connected_sort_dir = $cookies.get('open_gallery_not_connected_sort_dir');
		if (typeof $scope.open_gallery_not_connected_sort_dir == 'undefined') { 
			$scope.open_gallery_not_connected_sort_dir = 'asc'; 
		}
		
		$scope.open_gallery_photos_not_in_gallery = $cookies.get('open_gallery_photos_not_in_gallery');
		if (typeof $scope.open_gallery_photos_not_in_gallery == 'undefined' || $scope.open_gallery_photos_not_in_gallery != 'true') { 
			$scope.open_gallery_photos_not_in_gallery = false; 
		} else {
			$scope.open_gallery_photos_not_in_gallery = true; 
		}
		
		$scope.flat_formats_str = $cookies.get('flat_formats_str');
		$scope.open_gallery_photo_formats = {
			'landscape': false,
			'portrait': false,
			'square': false,
			'panoramic': false,
			'vertical_panoramic': false
		}; 
		if (typeof $scope.flat_formats_str == 'undefined' || $scope.flat_formats_str == "") { 
			$scope.flat_formats_str = "";
		} else {
			var file_formats_arr = $scope.flat_formats_str.split('|');
			for(var for_index in file_formats_arr) {
				$scope.open_gallery_photo_formats[file_formats_arr[for_index]] = true;
			}
		}
		
		jQuery(function() {
			jQuery("#filter_photo_by_format, #sort_photo_radio").buttonset();
			jQuery("#photos_not_in_a_gallery").button();
		});
		
		var icon_size = $cookies.get('gallery_icon_size');
		if (typeof icon_size == 'undefined') {
			icon_size = 'small';
		}
		$scope.open_gallery_image_size = icon_size;
		
		$scope.$watch("last_open_gallery_id", function(){
			$scope.view_gallery($scope.last_open_gallery_id);
		});
	};
	$scope.initGallery();
	
	$scope.change_filters_sort = function() {
		var flat_formats_arr = [];
		for(var format_index in $scope.open_gallery_photo_formats) {
			if ($scope.open_gallery_photo_formats[format_index] == true) {
				flat_formats_arr.push(format_index);
			}
		}
		$scope.flat_formats_str = flat_formats_arr.join("|");
		$cookies.put('open_gallery_not_connected_sort_dir', $scope.open_gallery_not_connected_sort_dir);
		$cookies.put('open_gallery_photos_not_in_gallery', $scope.open_gallery_photos_not_in_gallery);
		$cookies.put('flat_formats_str', $scope.flat_formats_str);
		
		$scope.refresh_not_in_gallery_photos();
	};
	
	$scope.change_image_size = function(new_size_str) {
		$scope.open_gallery_image_size = new_size_str;
		$cookies.put('gallery_icon_size', new_size_str);
		$scope.open_gallery_connected_photos = null;
		$scope.view_gallery($scope.last_open_gallery_id);
	};
	
	$scope.add_photo_to_gallery = function(photo) {
		$scope.helpers.removeItem($scope.open_gallery_not_connected_photos, photo);
		var add_photo_data = {
			photo_id: photo.Photo.id,
			gallery_id: $scope.last_open_gallery_id,
			gallery_icon_size: $scope.open_gallery_image_size
		};
		PhotoGalleries.add_photo(add_photo_data, 
			function(result) {
				if (result.code > 0) {
					$scope.open_gallery_connected_photos.push(result.data);
				}
			}
		);
	};
	
	$scope.remove_all_photos_from_gallery = function() {
		$scope.open_gallery_connected_photos = [];
		var remove_photo_data = {
			gallery_id: $scope.last_open_gallery_id
		};
		PhotoGalleries.remove_photo(remove_photo_data, 
			function(result) {
				if (result.code > 0) { /* delete worked */ }
			}
		);
	};
	
	$scope.refresh_not_in_gallery_photos = function() {
		$scope.open_gallery_not_connected_photos = null;
		$scope.view_gallery($scope.last_open_gallery_id);
	};
	
	$scope.remove_photo_from_gallery = function(photo_galleries_photo) {
		$scope.helpers.removeItem($scope.open_gallery_connected_photos, photo_galleries_photo);
		
		var remove_photo_data = {
			photo_id: photo_galleries_photo.PhotoGalleriesPhoto.photo_id,
			gallery_id: $scope.last_open_gallery_id
		};
		PhotoGalleries.remove_photo(remove_photo_data, 
			function(result) {
				if (result.code > 0) { /* delete worked */ }
			}
		);
	};
	

	$scope.view_gallery = function(photo_gallery_id) {
		$cookies.put('last_open_gallery_id', photo_gallery_id);
		$scope.last_open_gallery_id = photo_gallery_id;
		if ($scope.open_gallery != null && $scope.open_gallery.PhotoGallery.id != photo_gallery_id) {
			$scope.open_gallery = null;
		}
		
		var view_gallery = {
			id: photo_gallery_id,
			gallery_icon_size: $scope.open_gallery_image_size,
			order_by: $scope.open_gallery_not_connected_order_by,
			sort_dir: $scope.open_gallery_not_connected_sort_dir,
			photos_not_in_a_gallery: $scope.open_gallery_photos_not_in_gallery,
			last_photo_id: 0,
			photo_formats: $scope.flat_formats_str
		};
		var d = $q.defer();
		PhotoGalleries.view(view_gallery, 
			function(result) {
				if (typeof result.photo_gallery.PhotoGallery.id == 'number') {
					if ($scope.open_gallery == null) {
						$scope.open_gallery = result.photo_gallery;
					}
					$scope.open_gallery_connected_photos = result.photo_gallery.PhotoGalleriesPhoto;
					$scope.open_gallery_not_connected_photos = result.not_connected_photos;
					d.resolve();
				} else {
					d.resolve("Error");
				}
			},
			function(error) {
				d.resolve("Server Error");
			}
		);
		
		return d.promise;
	};


	$scope.gallerySortableOptions = {
		items : '> tbody > tr.sortable',
		handle : '.reorder_gallery_grabber',
		update : function(event, ui) {
			var context = this;
			jQuery(context).sortable('disable');

			// figure the the now position of the dragged element
			var photoGalleryId = jQuery(ui.item).attr('gallery_id');
			var newPosition = position_of_element_among_siblings(jQuery("#photo_gallery_list table.list > tbody > tr.sortable"), jQuery(ui.item));

			var reorder_gallery_data = {
				gallery_id: photoGalleryId,
				new_order: newPosition
			};
			PhotoGalleries.reorder_gallery(reorder_gallery_data, 
				function(result) {
					$scope.helpers.refreshScopeAfterReorder($scope.photo_galleries, 'PhotoGallery', photoGalleryId, newPosition);
					jQuery(context).sortable('enable');
				}
			);
		}
	};
	
	
	$scope.inGalleryPhotosSortableOptions = {
		items : '.connect_photo_container',
		handle : '.order_in_gallery_button',
		tolerance: 'pointer',
		containment: 'parent',
		scrollSensitivity: 60,
		stop : function(event, ui) {
			show_universal_save();
			var context = this;
			jQuery(context).sortable('disable');
//			var new_index = position_of_element_among_siblings(jQuery('#in_gallery_photos_cont .connect_photo_container'), jQuery(ui.item));
			PhotoGalleries.reorder_photo({
					gallery_id: $scope.last_open_gallery_id,
					photo_id: jQuery(ui.item).attr('photo_id'),
					new_order: (ui.item.index() + 1)
				}, 
				function(result) {
					jQuery(context).sortable('enable');
					hide_universal_save();
				}
			);
		}
	};
	
	
	// load the galleries list for left column
	PhotoGalleries.index().$promise.then(function(photo_galleries) {
		$scope.loading = false;
		$scope.photo_galleries = photo_galleries;
	});
}]);

