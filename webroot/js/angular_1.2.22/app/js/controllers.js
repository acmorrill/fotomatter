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
		
		$scope.open_gallery_not_connected_photos = null;
		$scope.view_gallery($scope.last_open_gallery_id);
	};
	
	$scope.change_image_size = function(new_size_str) {
		$scope.open_gallery_image_size = new_size_str;
		$cookies.put('gallery_icon_size', new_size_str);
		$scope.open_gallery_connected_photos = null;
		$scope.view_gallery($scope.last_open_gallery_id);
	};
	
	$scope.add_photo_to_gallery = function(photo_id) {
		delete $scope.open_gallery_not_connected_photos[photo_id];
//		$scope.open_gallery_connected_photos.push();
		
		
//		if (disable_gallery_add == true || gallery_add_limit >= sync_ajax_out) {
//			return;
//		}


//		show_universal_save();
//		gallery_add_limit++;
//		disable_gallery_add = true;
//		var when_finished_timeout = new Timeout(function() {
//			disable_gallery_add = false;
//		}, 100);


		/*
		var to_delete = jQuery(this).closest('.connect_photo_container');
		var photo_id = to_delete.attr('photo_id');
		var img_src = jQuery('.image_content_cont img', to_delete).attr('src');


		var new_div = add_new_in_gallery_image(photo_id, img_src);
		var move_to_cont = jQuery('#connect_gallery_photos_cont .in_gallery_photos_cont');
		move_to_cont.append(new_div).scrollTop(move_to_cont.prop("scrollHeight"));
		to_delete.remove();


		// hide the help message for in gallery photos
		jQuery('#connect_gallery_photos_cont .in_gallery_main_cont .empty_help_content').hide();


		jQuery.ajax({
			type: 'post',
			url: '/admin/photo_galleries/ajax_movephoto_into_gallery/'+photo_id+'/<?php //echo $gallery_id; ?>/',
			data: {},
			success: function(data) {
				if (data.code == 1) {
					// its all good
					setup_remove_from_gallery_buttons(jQuery('.remove_from_gallery_button', new_div));

					// check to see if the website photos needs a help message
					if (element_is_empty('endless_scroll_div')) {
						jQuery('#connect_gallery_photos_cont .not_in_gallery_main_cont .empty_help_content').show();
					}

					// check to see if need an endless scroll fire because of lack of images
					var in_gallery_photos_cont = jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont');
					var scrollHeight = in_gallery_photos_cont.prop("scrollHeight");
					var height = in_gallery_photos_cont.height();
					if (cease_fire == false && scrollHeight <= height) {
						do_endless_scroll_callback();
					}
				} else {
					new_div.remove();
					jQuery('#connect_gallery_photos_cont .not_in_gallery_photos_cont').prepend(to_delete);
					// check to see if the help message should now be shown
					if (element_is_empty('in_gallery_photos_cont')) {
						jQuery('#connect_gallery_photos_cont .in_gallery_main_cont .empty_help_content').show();
					}
					major_error_recover(data.message);
				}
			},
			complete: function() {
				gallery_add_limit--;
				hide_universal_save();
				when_finished_timeout.run_now();
			},
			error: function () {
			},
			dataType: 'json'
		});*/
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

