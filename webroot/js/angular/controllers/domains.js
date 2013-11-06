var domains_index = function($scope, $modal) {
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
					var domains_to_display = [];
					jQuery.each(data, function(key, domain) {
						if (domain.avail) {
							domain.name = key;
							domains_to_display.push(domain);
						}
					});
					
					$scope.domains = domains_to_display;
				});
				
			},
			error: function(data) {
				
			},
			dataType: 'json'
		});		
	};
	
	$scope.buyDomain = function(domain) {
		$modal.open({
			templateUrl: '/domains/domain_checkout',
			windowClass : 'ui-dialog ui-widget ui-widget-content'
		});
	};
};
//domains_index.$inject = ['$scope', '$modal'];