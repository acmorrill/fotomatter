var fmAdmin = angular.module('fmAdmin', ['ui.bootstrap', 'fmAdmin.directives', 'fmAdmin.modelServices', 'fmAdmin.utilServices', 'fmAdmin.constants']);

fmAdmin.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
	
	$httpProvider.responseInterceptors.push(function($timeout, $q) {
		return function(promise) {
			return promise.then(function(successResponse) {
				return successResponse;
			}, function(errorResponse) {
				switch (errorResponse.status) {
					case 403:
						window.location.replace("/admin/users/login?ajax_autoredirect=" + window.location.pathname);
					case 500:
					default:
						break;
				}
				return $q.reject(errorResponse);
			});
		};
	});
}]);

