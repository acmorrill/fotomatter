var fotomatterServices = angular.module('fotomatterServices', ['ngResource']);

fotomatterServices.factory('PrintTypesService', ['PrintTypes', '$cookies', '$q', 'helperMethods', function(PrintTypes, $cookies, $q, helperMethods) {
	var service = {};

	service.reformat_print_type_result = function(result, print_type) {
		for (var index in result.data.photo_avail_sizes) {
			result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType'] = helperMethods.phpToJs(result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']);
			if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
			if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
		}
	};
		
	service.load_print_type = function(print_type) {
		show_universal_load();

		var deferred = $q.defer();
		var edit_print_type_promise;
		
		if (print_type.PhotoPrintType.print_fulfillment_type == 'self') {
			edit_print_type_promise = PrintTypes.edit({photo_print_type_id: print_type.PhotoPrintType.id}).$promise;
			edit_print_type_promise.then(function(result) {
				service.reformat_print_type_result(result, print_type);
				$cookies.put('last_open_print_type_id', print_type.PhotoPrintType.id + "|" + print_type.PhotoPrintType.print_fulfillment_type);
				hide_universal_load();
				deferred.resolve(result.data);
			});
		} else {
			edit_print_type_promise = PrintTypes.edit_automatic({photo_print_type_id: print_type.PhotoPrintType.id}).$promise;
			edit_print_type_promise.then(function(result) {
				service.reformat_print_type_result(result, print_type);
				$cookies.put('last_open_print_type_id', print_type.PhotoPrintType.id + "|" + print_type.PhotoPrintType.print_fulfillment_type);
				hide_universal_load();
				console.log(result.data);
				deferred.resolve(result.data);
			});
		}
		
		edit_print_type_promise.catch(function(result) {
			hide_universal_load();
			deferred.reject('Error');
		});
		
		return deferred.promise;
	};
	
	return service;
}]);
