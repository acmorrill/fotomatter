var domains_index = function($scope, $modal, $http, domainUtil, errorUtil) {
	$scope.search = function() {
		domainUtil.domainSearch($scope.query)
			.success(function(data) {
				$scope.domain_searched = domainUtil.getActualDomainSearched($scope.query);
				$scope.domain_found = data[$scope.domain_searched]['avail'];
				$scope.domains = domainUtil.parseSearchResult(data);
			})
			.error(errorUtil.handleError);
	};
	
	$scope.buyDomain = function(domain) {
		var modal = $modal.open({
			templateUrl: '/domains/domain_checkout',
			windowClass : 'ui-dialog ui-widget ui-widget-content',
			controller : 'domain_checkout'
		});
	};
};
domains_index.$inject = ['$scope', '$modal', '$http', 'domainUtil', 'errorUtil'];

var domain_checkout = function($scope, AuthnetProfile, $http) {
	
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
	
	$http.get('/domains/get_account_details')
		.success(function(page_meta_data) {
			$scope.profile = AuthnetProfile.initObject(page_meta_data.account_details.data.AuthnetProfile);
			$scope.countryChange('states_for_selected_country');

			if (typeof(page_meta_data.account_details.data.AuthnetProfile) === 'object') {
				$scope.setStep('domain_contact');
			} else {
				$scope.setStep('cc_profile');
			}
		});
	
	//initial logic end for opening domain checkout
	$scope.countryChange = function(scope_var_for_state_list) {
		jQuery.ajax({
			type: 'GET',
			url: '/admin/accounts/ajax_get_states_for_country/'+$scope.profile.country_id + "/1",
			success: function(data) {
				$scope.$apply(function() {
					$scope[scope_var_for_state_list] = data;
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
domain_checkout.$inject = ['$scope','AuthnetProfile', '$http'];