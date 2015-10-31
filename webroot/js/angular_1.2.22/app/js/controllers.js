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


	$scope.initGallery = function() {
		console.log($cookies.getAll());
		
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
		if (typeof $scope.flat_formats_str == 'undefined' || $scope.flat_formats_str == "") { 
			$scope.open_gallery_photo_formats = {
				'landscape': false,
				'portrait': false,
				'square': false,
				'panoramic': false,
				'vertical_panoramic': false
			}; 
			$scope.flat_formats_str = "";
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
		
			
			
			
	};
	
	$scope.change_image_size = function(new_size_str) {
		$scope.open_gallery_image_size = new_size_str;
		$cookies.put('gallery_icon_size', new_size_str);
		$scope.open_gallery_connected_photos = null;
		$scope.view_gallery($scope.last_open_gallery_id);
	};
	

	$scope.view_gallery = function(photo_gallery_id) {
		$cookies.put('last_open_gallery_id', photo_gallery_id);
		$scope.last_open_gallery_id = photo_gallery_id;
		if ($scope.open_gallery != null && $scope.open_gallery.PhotoGallery.id != photo_gallery_id) {
			$scope.open_gallery = null;
		}
		
		var view_gallery = {
			id: photo_gallery_id,
			gallery_icon_size: $scope.open_gallery_image_size
		};
		var d = $q.defer();
		PhotoGalleries.view(view_gallery, 
			function(result) {
//				console.log('============================');
//				console.log(result.not_connected_photos);
//				console.log('============================');
				
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


	$scope.sortableOptions = {
		items : '> tbody > tr.sortable',
		handle : '.reorder_gallery_grabber',
		update : function(event, ui) {
			var context = this;
			jQuery(context).sortable('disable');

			// figure the the now position of the dragged element
			var photoGalleryId = jQuery(ui.item).attr('gallery_id');
			var newPosition = position_of_element_among_siblings(jQuery("#photo_gallery_list table.list > tbody > tr.sortable"), jQuery(ui.item));

			jQuery.ajax({
				type: 'post',
				url: '/admin/photo_galleries/ajax_set_photogallery_order/'+photoGalleryId+'/'+newPosition+'/',
				data: {},
				success: function(data) {
					// remove the element from the old scope position
					var element_to_move;
					for (var x in $scope.photo_galleries) {
						if (typeof $scope.photo_galleries[x].PhotoGallery == 'object') {
							if ($scope.photo_galleries[x].PhotoGallery.id == photoGalleryId) {
								element_to_move = $scope.photo_galleries.splice(x, 1);
								break;
							}
						}
					}
					
					// add element to the new scope position
					var count = 1;
					for (var x in $scope.photo_galleries) {
						if (count == newPosition) {
							$scope.photo_galleries.splice(count - 1, 0, element_to_move[0]);
							break;
						}
						count++;
					}
					
					
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
	};
	
	// load the galleries list for left column
	PhotoGalleries.index().$promise.then(function(photo_galleries) {
		$scope.loading = false;
		$scope.photo_galleries = photo_galleries;
	});
}]);

