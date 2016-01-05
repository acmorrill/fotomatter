'use strict';

var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('AvailPrintTypesCtrl', ['$scope',  '$timeout', 'PrintTypes', function($scope, $timeout, PrintTypes) {
	var photo_print_types_promise = PrintTypes.list().$promise;
	photo_print_types_promise.then(function(result) {
		$scope.photo_print_types = result.data;
	}).catch(function(result) {
//		console.log(result);
	});
	
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
}]);
