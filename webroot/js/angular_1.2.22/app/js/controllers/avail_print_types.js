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
			console.log('=======================');
			console.log(result.data.photo_avail_sizes);
			for (var index in result.data.photo_avail_sizes) {
//				console.log(result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_force_settings']);
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_force_settings'] == true) { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_force_settings'] = true; }
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_global_default'] == true) { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_global_default'] = true; }
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_force_settings'] == true) { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_force_settings'] = true; }
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_global_default'] == true) { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_global_default'] = true; }
			}
			console.log('=======================');
			$scope.open_print_type = result.data;
//			$scope.helpers.removeItem($scope.photo_print_types, print_type);
			hide_universal_load();
		}).catch(function(result) {
			hide_universal_load();
		});
		
		return edit_print_type_promise;
	};
	
	$scope.savePrintType = function(print_type) {
		console.log('------------------------------------------------------------');
		console.log(print_type);
		console.log('------------------------------------------------------------');
	};
}]);
