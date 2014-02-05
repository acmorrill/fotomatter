var domains_index = function($scope, $modal, $http, domainUtil, errorUtil) {
	$scope.search = function() {
		domainUtil.domainSearch($scope.query)
			.success(function(data, status) {
				if (status !== 200) return;
				
				$scope.domain_searched = domainUtil.getActualDomainSearched($scope.query);
				$scope.domain_found = data[$scope.domain_searched]['avail'];
				$scope.domains = domainUtil.parseSearchResult(data);
			});
	};
	
	$scope.buyDomain = function(domain) {
		var modal = $modal.open({
			templateUrl: '/domains/domain_checkout',
			windowClass : 'ui-dialog ui-widget ui-widget-content',
			controller : 'domain_checkout',
			resolve: {
				domain: function() {
					return domain;
				}
			}
		});
	};
};
domains_index.$inject = ['$scope', '$modal', '$http', 'domainUtil', 'errorUtil'];

var domain_checkout = function($scope, AuthnetProfile, $http, generalUtil, domainUtil, $modalInstance, domain) {
	$scope.domain_to_purchase = domain;

	$scope.setStep = function(step_name) {
		$scope.currentStep = step_name;
	};
	$scope.setStep('loading');
	
	$http.get('/domains/get_account_details')
		.success(function(page_meta_data) {
			$scope.profile = jQuery.extend(true, AuthnetProfile.initObject({}), page_meta_data.account_details.data.AuthnetProfile)
			
			$scope.contact = {};
			$scope.countryChange('states_for_selected_country', $scope.profile.country_id);

			if (jQuery.isEmptyObject(page_meta_data.account_details.data.AuthnetProfile) === false) {
				$scope.setStep('domain_contact');
				domainUtil.populateDomainContact($scope.contact, $scope.profile);
				$scope.countryChange('contact_states_for_selected_country', $scope.contact.country_id, function() {
					$scope.contact.country_state_id = $scope.profile.country_state_id;
				});
				$scope.contact.phone = '2083532813'; //Adam Todo remove this
			} else {
				$scope.setStep('cc_profile');
			}
		});
	
	//initial logic end for opening domain checkout
	$scope.countryChange = function(scope_var_for_state_list, country_id, callback) {
		generalUtil.getStatesForCountry(country_id)
			.success(function(data) {
				$scope[scope_var_for_state_list] = data;
				
				if (callback !== undefined) {
					callback();
				}
			});
	};
	
	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	};
	
	$scope.submitPayment = function() {
		$scope.setStep('loading');
		var profile_to_send = {};
		
		profile_to_send.data = {};
		profile_to_send.data.AuthnetProfile = {};
		profile_to_send.data.AuthnetProfile = $scope.profile;
		
		if($scope.profile.country_state_id !== undefined) {
			$scope.profile.country_state_id = $scope.profile.country_state_id;
		}
		
		AuthnetProfile.save(profile_to_send)
			.success(function(data) {
				if (data.result === false) {
					$scope.setStep('cc_profile');
					$scope.errorMessage = data.message;
				} else {
					$scope.errorMessage = '';
					$scope.setStep('domain_contact');
					domainUtil.populateDomainContact($scope.contact, $scope.profile);
				}
			});
	};
	
	$scope.submitContact = function() {
		
		if (generalUtil.is_empty($scope.contact.first_name)) {
			$scope.errorMessage = 'First name is required';
			return;
		}
		
		if (generalUtil.is_empty($scope.contact.last_name)) {
			$scope.errorMessage = 'Last name is required';
			return;
		}
		
		if (generalUtil.is_empty($scope.contact.address_1)) {
			$scope.errorMessage = "Address is required";
			return;
		}
		
		if (generalUtil.is_empty($scope.contact.country_id)) {
			$scope.errorMessage = 'Country is required';
		}
		
		if (generalUtil.is_empty($scope.contact.city)) {
			$scope.errorMessage = 'City is required';
			return;
		}
		
		if (generalUtil.is_empty($scope.contact.country_state_id)) {
			$scope.errorMessage = 'State is required';
			return;
		}
		
		if (generalUtil.is_empty($scope.contact.zip)) {
			$scope.errorMessage = 'Zip code is required';
			return;
		}
		
		if (generalUtil.is_empty($scope.contact.phone)) {
			$scope.errorMessage = 'Phone number is required';
			return;
		}
		
		$scope.errorMessage = '';
		$scope.setStep('confirm');
		
		return;
	};
	
	$scope.submitPurchase = function() {
		domainUtil.purchase($scope.domain_to_purchase, $scope.contact)
			.success(function(data, status) {
				if (data.result){
					window.location.reload();
				} else {
					$scope.errorMessage = data.message;
				}
			});
	};
}
domain_checkout.$inject = ['$scope','AuthnetProfile', '$http', 'generalUtil', 'domainUtil', '$modalInstance', 'domain'];