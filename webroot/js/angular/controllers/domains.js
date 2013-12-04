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

var domain_checkout = function($scope, AuthnetProfile) {
	
	$scope.profile = {
		billing_firstname : ''
	};
	$scope.cc_profile = {
		loading: false
	};
	
	$scope.setStep = function(step_name) {
		$scope.currentStep = step_name;
	};
	$scope.setStep('loading');
	
	jQuery.ajax({
		type: 'POST',
		url: '/domains/get_account_details',
		success: function(page_meta_data) {
			$scope.$apply(function() {
				//Adam TODO solve type problem 
				//console.log(typeof(page_meta_data.account_details.data.AuthnetProfile));
				//console.log(page_meta_data.account_details.data);
				$scope.profile = AuthnetProfile.initObject(page_meta_data.account_details.data.AuthnetProfile);
				$scope.countryChange();
				
				if (typeof(page_meta_data.account_details.data.AuthnetProfile) === 'object') {
					$scope.setStep('domain_contact');
				} else {
					$scope.setStep('cc_profile');
				}
			});	
		},
		error: function(data) {
			
		},
		dataType: 'json'
	});
	
	$scope.countryChange = function() {
		jQuery.ajax({
			type: 'GET',
			url: '/admin/accounts/ajax_get_states_for_country/'+$scope.profile.country_id + "/1",
			success: function(data) {
				$scope.$apply(function() {
					$scope.states_for_selected_country = data;
				});
				//setInterval(console.log($scope.profile), 10000);
			},
			error: function(data) {
				
			},
			dataType: 'json'		
		});
		
	}
	
	$scope.submitPayment = function() {
		$scope.cc_profile.loading = true;
		var profile_to_send = {};
		
		profile_to_send.data = {};
		profile_to_send.data.AuthnetProfile = {};
		profile_to_send.data.AuthnetProfile = $scope.profile;
		
		if($scope.profile.country_state_id !== undefined) {
			$scope.profile.country_state_id = $scope.profile.country_state_id;
		}
		
		jQuery.ajax({
			type: 'POST',
			url: '/admin/domains/add_profile',
			data: profile_to_send,
			success: function(data) {
				$scope.$apply(function() {
					$scope.cc_profile.loading = false;
					if (data.result == false) {
						$scope.errorMessage = data.message;
					} else {
						$scope.errorMessage = '';
						$scope.setStep('domain_contact');
					}
				});
					
			},
			error: function(data) {
				
			},
			dataType: 'json'
		});	
		//$scope.currentStep = 'domain_contact';
	};
}
domain_checkout.$inject = ['$scope','AuthnetProfile'];