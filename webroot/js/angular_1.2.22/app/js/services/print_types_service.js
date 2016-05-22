var fotomatterServices = angular.module('fotomatterServices', ['ngResource']);

fotomatterServices.factory('PrintTypesService', ['PrintTypes', '$cookies', '$q', 'helperMethods', function(PrintTypes, $cookies, $q, helperMethods) {
	var service = {};

	service.reformat_print_type_result = function(list) {
		for (var index in list) {
			list[index]['PhotoAvailSizesPhotoPrintType']['global_default'] = !!list[index]['PhotoAvailSizesPhotoPrintType']['global_default'];
			list[index]['PhotoAvailSizesPhotoPrintType']['force_settings'] = !!list[index]['PhotoAvailSizesPhotoPrintType']['force_settings'];
			list[index]['PhotoAvailSizesPhotoPrintType']['available'] = !!list[index]['PhotoAvailSizesPhotoPrintType']['available'];
		}
	};
	
	service.reformat_print_type_post_data = function(data) {
		data['PhotoAvailSizesPhotoPrintType']['global_default'] = + data['PhotoAvailSizesPhotoPrintType']['global_default'];
		data['PhotoAvailSizesPhotoPrintType']['force_settings'] = + data['PhotoAvailSizesPhotoPrintType']['force_settings'];
		data['PhotoAvailSizesPhotoPrintType']['available'] = + data['PhotoAvailSizesPhotoPrintType']['available'];
	};
		
	service.load_print_type = function(print_type) {
		show_universal_load();

		var deferred = $q.defer();
		var edit_print_type_promise;
		
		if (print_type.PhotoPrintType.print_fulfillment_type == 'self') {
			edit_print_type_promise = PrintTypes.edit({photo_print_type_id: print_type.PhotoPrintType.id}).$promise;
			edit_print_type_promise.then(function(result) {
				$cookies.put('last_open_print_type_id', print_type.PhotoPrintType.id + "|" + print_type.PhotoPrintType.print_fulfillment_type);
				hide_universal_load();
				deferred.resolve(result.data);
			});
		} else {
			edit_print_type_promise = PrintTypes.edit_automatic({photo_print_type_id: print_type.PhotoPrintType.id}).$promise;
			edit_print_type_promise.then(function(result) {
				service.reformat_print_type_result(result.data.autofulfillment_print_list);
				$cookies.put('last_open_print_type_id', print_type.PhotoPrintType.id + "|" + print_type.PhotoPrintType.print_fulfillment_type);
				hide_universal_load();
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
