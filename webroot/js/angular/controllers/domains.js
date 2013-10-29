var domains_index = function($scope, $http) {
	$scope.search = function() {
		$http({
			method: 'POST',
			url: '/domains/search',
			data: {
				q : $scope.query
			}
		});
		
	};
	
	
	
};
domains_index.$inject = ['$scope', '$http'];