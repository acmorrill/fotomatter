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
	$scope.photo_galleries = new Array();
	$scope.open_gallery = null;	
	$scope.open_gallery_connected_photos = null;	

		
	$scope.initGallery = function() {
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
				if (typeof result[0]['PhotoGallery'].id == 'number') {
					if ($scope.open_gallery == null) {
						$scope.open_gallery = result[0];
					}
					$scope.open_gallery_connected_photos = result[0].PhotoGalleriesPhoto;
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
	
	PhotoGalleries.index().$promise.then(function(photo_galleries) {
		$scope.loading = false;
		$scope.photo_galleries = photo_galleries;
	});
}]);

