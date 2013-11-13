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
		var modal = $modal.open({
			templateUrl: '/domains/domain_checkout',
			windowClass : 'ui-dialog ui-widget ui-widget-content',
			controller : 'domain_checkout'
		});
		
	};
};
domains_index.$inject = ['$scope', '$modal'];

var domain_checkout = function($scope) {
	$scope.currentStep = 'cc_profile';
	jQuery.ajax({
		type: 'POST',
		url: '/domains/get_account_details',
		success: function(accountDetails) {
			$scope.$apply(function() {
				$scope.profile = accountDetails.data.AuthnetProfile;
			});	
		},
		error: function(data) {
			
		},
		dataType: 'json'
	});
	
	$scope.countryChange = function() {
		jQuery.ajax({
			type: 'GET',
			url: '/admin/accounts/ajax_get_states_for_country/'+$scope.profile.billing_country,
			success: function(data) {
				$scope.$apply(function() {
					$("#billing_state").html(data.html);
					
				});
				setInterval(console.log($scope.profile), 10000);
			},
			error: function(data) {
				
			},
			dataType: 'json'		
		});
		
	}
	
	$scope.submitPayment = function() {
		console.log('here');
		$scope.currentStep = 'domain_contact';
	};
	
	
}
domain_checkout.$inject = ['$scope'];