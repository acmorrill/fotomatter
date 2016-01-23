var fotomatterServices = angular.module('fotomatterServices', ['ngResource']);

/**
 * The `batchLog` service allows for messages to be queued in memory and flushed
 * to the console.log every 50 seconds.
 *
 * @param {*} message Message to be logged.
 */
fotomatterServices.factory('PrintTypesService', ['PrintTypes', '$cookies', '$q', function(PrintTypes, $cookies, $q) {
	// START HERE TOMORROW - need to load PrintTypes better somehow
	function load_print_type(print_type) {
		show_universal_load();

		var edit_print_type_promise = PrintTypes.edit({photo_print_type_id: print_type.PhotoPrintType.id}).$promise;
		var deferred = $q.defer();
		edit_print_type_promise.then(function(result) {
			for (var index in result.data.photo_avail_sizes) {
				result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType'] = $scope.helpers.phpToJs(result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']);
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
				if (result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] == '') { result.data.photo_avail_sizes[index]['PhotoAvailSizesPhotoPrintType']['non_pano_custom_turnaround'] = print_type.PhotoPrintType.turnaround_time; }
			}
			
			$cookies.put('last_open_print_type', print_type.PhotoPrintType.id);
			hide_universal_load();
			deferred.resolve(result.data);
		}).catch(function(result) {
			hide_universal_load();
			deferred.reject('Error');
		});
		
		return deferred;
	}
}]);
