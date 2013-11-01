var domains_index = function($scope, $http) {
	$scope.search = function() {
		jQuery.ajax({
			type: 'POST',
			url: '/domains/search',
			data : {
				q : $scope.query
			},
			success: function(data) {
				$scope.$apply(function() {
					var domain_pieces = $scope.query.split('.');
					var domain_title = '';

					if (domain_pieces.length === 3) {
						domain_title = domain_pieces[1] + '.' + domain_pieces[2];
					} else if(domain_pieces.length === 2) {
						domain_title = domain_pieces[0] + '.' + domain_pieces[1];
					} else {
						domain_title = domain_pieces[0] + '.com';
					}

					$scope.domain_searched = domain_title;
					$scope.domain_found = data[domain_title]['avail'];
					
				});
				
			},
			error: function(data) {
				
			},
			dataType: 'json'
		});		
	};
	
	
	
};
domains_index.$inject = ['$scope', '$http'];