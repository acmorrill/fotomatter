'use strict';

var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('AvailPrintTypesCtrl', ['$scope',  '$timeout', 'PrintTypes', function($scope, $timeout, PrintTypes) {
	var photo_print_types_promise = PrintTypes.list().$promise;
	photo_print_types_promise.then(function(result) {
		$scope.photo_print_types = result.data;
	}).catch(function(result) {
//		console.log(result);
	});
	
//	var photo_avail_sizes_promise = PrintTypes.get_avail_sizes().$promise;
//	photo_avail_sizes_promise.then(function(result) {
//			console.log(result);
//		$scope.photo_avail_sizes = result.data;
//	}).catch(function(result) {
////		console.log(result);
//	});
	
	$scope.printTypeSortableOptions = {
		items : '> tbody > tr.sortable',
		handle : '.reorder_grabber',
		update : function(event, ui) {
			show_universal_save();
			var context = this;
			jQuery(context).sortable('disable');

			var item_id = jQuery(ui.item).attr('item_id');
			var new_position = position_of_element_among_siblings(jQuery("table.list > tbody > tr.sortable"), jQuery(ui.item));

			// figure the the now position of the dragged element
			var reorder_data = {
				photo_print_type_id: item_id,
				new_order: new_position
			};
			
			var reorder_photo_print_types_promise = PrintTypes.reorder(reorder_data).$promise;
			reorder_photo_print_types_promise.then(function(result) {
				$scope.helpers.refreshScopeAfterReorder($scope.photo_print_types, 'PhotoPrintType', item_id, new_position);
				jQuery(context).sortable('enable');
				hide_universal_save();
			}).catch(function(result) {
//				console.log(result);
				hide_universal_save();
			});
		}
	};
	
	$scope.deletePrintType = function(print_type) {
		show_universal_save();
		var delete_print_type_promise = PrintTypes.delete({photo_print_type_id: print_type.PhotoPrintType.id}).$promise;
		delete_print_type_promise.then(function(result) {
			$scope.helpers.removeItem($scope.photo_print_types, print_type);
			hide_universal_save();
		}).catch(function(result) {
			hide_universal_save();
		});
		
		return delete_print_type_promise;
	};
	
	$scope.editPrintType = function(print_type) {
		delete $scope.open_print_type;
		
		show_universal_load();
		var edit_print_type_promise = PrintTypes.edit({photo_print_type_id: print_type.PhotoPrintType.id}).$promise;
		edit_print_type_promise.then(function(result) {
			for (var index in result.data.photo_avail_sizes) {
				result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType'] = $scope.helpers.phpToJs(result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']);
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
			}
			$scope.open_print_type = result.data;
			hide_universal_load();
		}).catch(function(result) {
			hide_universal_load();
		});
		
		return edit_print_type_promise;
	};
	
	$scope.savePrintType = function(print_type_data, index) {
		show_universal_save();
		var save_data = print_type_data;
		
		save_data.PhotoPrintType = $scope.open_print_type.photo_print_type.PhotoPrintType;
		save_data.PhotoAvailSizesPhotoPrintType.photo_avail_size_id = save_data.PhotoAvailSize.id;
		save_data.PhotoAvailSizesPhotoPrintType.photo_print_type_id = save_data.PhotoPrintType.id;
		
		var save_print_type_promise = PrintTypes.save({}, save_data).$promise;
		save_print_type_promise.then(function(result) {
			result.data.PhotoAvailSizesPhotoPrintType = $scope.helpers.phpToJs(result.data.PhotoAvailSizesPhotoPrintType);
			if (result.data.PhotoAvailSizesPhotoPrintType['pano_custom_turnaround'] == '') { result.data.PhotoAvailSizesPhotoPrintType['pano_custom_turnaround'] = $scope.open_print_type.photo_print_type.PhotoPrintType.turnaround_time; }
			if (result.data.PhotoAvailSizesPhotoPrintType['non_pano_custom_turnaround'] == '') { result.data.PhotoAvailSizesPhotoPrintType['non_pano_custom_turnaround'] = $scope.open_print_type.photo_print_type.PhotoPrintType.turnaround_time; }
			$scope.open_print_type.photo_avail_sizes[index].PhotoAvailSizesPhotoPrintType = result.data.PhotoAvailSizesPhotoPrintType;
			
			hide_universal_save();
		}).catch(function(result) {
			hide_universal_save();
		});
	};
	
	var saving_print_type_settings = false;
	$scope.savePrintTypeSetting = function(print_type_data, old_turnaround_time) {
		if (saving_print_type_settings == true) {
			return;
		}
		saving_print_type_settings = true;
		show_universal_save();
		var save_print_type_promise = PrintTypes.save({}, print_type_data).$promise;
		save_print_type_promise.then(function(result) {
			$scope.helpers.updateArrItem($scope.photo_print_types, 'PhotoPrintType', 'print_name', result.data.PhotoPrintType.id, result.data.PhotoPrintType.print_name);
			if (result.data.PhotoPrintType.turnaround_time != old_turnaround_time) {
				for (var q in $scope.open_print_type.photo_avail_sizes) {
					if ($scope.open_print_type.photo_avail_sizes[q].PhotoAvailSizesPhotoPrintType.non_pano_custom_turnaround == old_turnaround_time) {
						$scope.open_print_type.photo_avail_sizes[q].PhotoAvailSizesPhotoPrintType.non_pano_custom_turnaround = result.data.PhotoPrintType.turnaround_time;
					}
					if ($scope.open_print_type.photo_avail_sizes[q].PhotoAvailSizesPhotoPrintType.pano_custom_turnaround == old_turnaround_time) {
						$scope.open_print_type.photo_avail_sizes[q].PhotoAvailSizesPhotoPrintType.pano_custom_turnaround = result.data.PhotoPrintType.turnaround_time;
					}
				}
			}
			hide_universal_save();
			saving_print_type_settings = false;
		}).catch(function(result) {
			hide_universal_save();
			saving_print_type_settings = false;
		});
	};
}]);
