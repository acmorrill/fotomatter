'use strict';

var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('AvailPrintTypesCtrl', ['$scope',  '$timeout', '$uibModal', 'PrintTypes', 'PrintTypesService', '$cookies', function($scope, $timeout, $uibModal, PrintTypes, PrintTypesService, $cookies) {
	var photo_print_types_promise = PrintTypes.list().$promise;
	photo_print_types_promise.then(function(result) {
		$scope.photo_print_types = result.data;
	}).catch(function(result) {
//		console.log(result);
	});
	
	var last_open_print_type_id = $cookies.get('last_open_print_type_id');
	if (typeof last_open_print_type_id != "undefined") {
		var print_type_data = last_open_print_type_id.split("|");
		var start_print_type = {
			PhotoPrintType: {
				id: print_type_data[0],
				print_fulfillment_type: print_type_data[1]
			}
		};
		var print_type_data_promise = PrintTypesService.load_print_type(start_print_type);
		print_type_data_promise.then(
			function(print_type_data) {
				$scope.open_print_type = print_type_data;
			},
			function(reason) {}
		);
		// DREW TODO - scroll to the newly opened one
	}
	
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
		if (typeof $scope.open_print_type != "undefined" && print_type.PhotoPrintType.id == $scope.open_print_type.photo_print_type.PhotoPrintType.id) { 
			$cookies.remove('last_open_print_type_id');
			delete $scope.open_print_type;
		}
		
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
		if (typeof $scope.open_print_type == "undefined" || $scope.open_print_type.photo_print_type.PhotoPrintType.id != print_type.PhotoPrintType.id) { // so if you click the one you are on it will just unselect
			var print_type_data_promise = PrintTypesService.load_print_type(print_type);
			print_type_data_promise.then(
				function(print_type_data) {
					delete $scope.open_print_type;
					$scope.open_print_type = print_type_data;
				},
				function(reason) {}
			);
		} else {
			$cookies.remove('last_open_print_type_id');
			delete $scope.open_print_type;
		}
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
	
	$scope.savePrintTypeSetting = function(print_type_data, old_turnaround_time) {
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
		}).catch(function(result) {
			hide_universal_save();
		});
	};
	
	
	$scope.addNewPrintType = function() {
		var uibModalInstance = $uibModal.open({
			templateUrl: 'myModalContent.html',
			backdrop: true,
			windowClass : 'ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable',
			controller : 'ModalInstanceCtrl'
		});
		
		uibModalInstance.result.then(
			function(result) {
				delete $scope.open_print_type;
				for (var index in result.data.photo_avail_sizes) {
					result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType'] = $scope.helpers.phpToJs(result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']);
					if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
					if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
				}
				$cookies.put('last_open_print_type_id', result.data.photo_print_type.PhotoPrintType.id);
				$scope.open_print_type = result.data;
				$scope.photo_print_types.push(result.data.photo_print_type);
				hide_universal_save();
			},
			function() { hide_universal_save(); }
		);
	};
}]);

fotomatterControllers.controller('ModalInstanceCtrl', ['$scope', '$uibModalInstance', 'PrintTypes', function($scope, $uibModalInstance, PrintTypes) {
	$scope.print_fulfiller_id = 'self';

	$scope.create_print_type = function() {
		var print_type_id;
		if (typeof $scope.printer_print_types[$scope.print_fulfiller_id] == 'object') {
			print_type_id = $scope.printer_print_types[$scope.print_fulfiller_id].id;
		}
		
		if ($scope.print_fulfiller_id == 'self' || typeof print_type_id != 'undefined') {
			show_universal_save();
			if ($scope.print_fulfiller_id == 'self') {
				var create_self_print_type_promise = PrintTypes.add_self().$promise;
				$uibModalInstance.close(create_self_print_type_promise);
			} else {
				var automatic_data = {
					print_fulfiller_id: $scope.print_fulfiller_id,
					print_fulfiller_print_type_id: print_type_id
				};
				var create_automatic_print_type_promise = PrintTypes.add_automatic(automatic_data).$promise;
				$uibModalInstance.close(create_automatic_print_type_promise);
			}
		}
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');
	};
}]);
