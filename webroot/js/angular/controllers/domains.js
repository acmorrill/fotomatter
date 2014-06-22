var domains_index = function($scope, $modal, $http, domainUtil, errorUtil) {
	$scope.add_external_domain = function() {
		$scope.external_domain_searched = true;
		
		$scope.external_domain_query = $scope.external_domain_query.toLowerCase();
		var external_domain = $scope.external_domain_query;
		var validated_external_domain = external_domain.match(/^[a-z0-9][a-z0-9-]{1,61}[a-z0-9]\.[a-z]{2,6}$/);
		if (validated_external_domain !== null) {
			$scope.validated_external_domain = validated_external_domain;
			validated_external_domain = validated_external_domain[0];
			var modal = $modal.open({
				templateUrl: '/admin/domains/add_external_domain_confirm',
				windowClass : 'ui-dialog ui-widget ui-widget-content',
				controller : 'add_external',
				resolve: {
					domain: function() {
						return validated_external_domain;
					},
					is_renew: function() {
						return false;
					}
				}
			});
		} else {
			$scope.external_domain_query = '';
		}
	};
	
	$scope.search = function() {
		$scope.domain_searched = true;
		
		var query_data = domainUtil.validate_domain_name($scope.query);
		var tld = query_data.tld;
		var query = query_data.query;
		$scope.query = query_data.final_query;
		
		domainUtil.domainSearch(query, tld)
			.success(function(data, status) {
				if (status !== 200) return;
				$scope.domain_searched = undefined;
				$scope.domain_found = data.domain_available;
				$scope.domains = domainUtil.parseSearchResult(data.domain_list);
			});
	};
	
	$scope.buyDomain = function(domain) {
		var modal = $modal.open({
			templateUrl: '/admin/domains/domain_checkout',
			windowClass : 'ui-dialog ui-widget ui-widget-content',
			controller : 'domain_checkout',
			resolve: {
				domain: function() {
					return domain;
				},
				is_renew: function() {
					return false;
				}
			}
		});
	};
	
	$scope.renewDomain = function(owned_domain) {
		var modal = $modal.open({
			templateUrl: '/admin/domains/domain_renew_checkout/' + owned_domain,
			windowClass : 'ui-dialog ui-widget ui-widget-content',
			controller : 'domain_checkout',
			resolve: {
				domain: function() {
					return owned_domain;
				},
				is_renew: function() {
					return true;
				}
			}
		});
	};
	
	$scope.setDomainPrimary = function(domain_id) {
		var toPost = {};
		toPost.primary_domain_id = domain_id;
		$http.post("/admin/domains/set_as_primary", toPost).success(function(result) {

		});
		
	};
	
	if (typeof primary_domain_id !== 'undefined') {
		$scope.primary_domain = primary_domain_id;
	}
	
};
domains_index.$inject = ['$scope', '$modal', '$http', 'domainUtil', 'errorUtil'];

var add_external = function($scope, AuthnetProfile, $http, generalUtil, domainUtil, $modalInstance, domain) {
	$scope.external_domain = domain;

	$scope.setStep = function(step_name) {
		$scope.currentStep = step_name;
	};
	$scope.setStep('add_external');
	
	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	};
	
	$scope.submit_external_domain = function() {
		$scope.setStep('loading');
		domainUtil.add_external_domain($scope.external_domain).success(function(data, status) {
			console.log(data);
			if (data.result) {
				window.location.reload();
			} else {
				$scope.setStep('add_external');
				$scope.errorMessage = data.message;
			}
		});
	};
};
add_external.$inject = ['$scope','AuthnetProfile', '$http', 'generalUtil', 'domainUtil', '$modalInstance', 'domain'];

var domain_checkout = function($scope, AuthnetProfile, $http, generalUtil, domainUtil, $modalInstance, domain, is_renew) {
	$scope.is_renew = is_renew;
	$scope.domain_to_purchase = domain;

	$scope.setStep = function(step_name) {
		$scope.currentStep = step_name;
	};
	$scope.setStep('loading');
	
	$http.get('/admin/domains/get_account_details').success(function(page_meta_data) {
		$scope.profile = jQuery.extend(true, AuthnetProfile.initObject({}), page_meta_data.account_details.data.AuthnetProfile)

		$scope.contact = {};
		$scope.countryChange('states_for_selected_country', $scope.profile.country_id);

		if (jQuery.isEmptyObject(page_meta_data.account_details.data.AuthnetProfile) === false) {
			domainUtil.populateDomainContact($scope.contact, $scope.profile);
			$scope.countryChange('contact_states_for_selected_country', $scope.contact.country_id, function() {
				$scope.contact.country_state_id = $scope.profile.country_state_id;
			});
			
			if ($scope.is_renew == true) {
				$scope.setStep('renew_confirm');
			} else {
				$scope.setStep('domain_contact');
			}
//			$scope.contact.phone = '2083532813'; //Adam Todo remove this
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
					if ($scope.is_renew) {
						$scope.setStep('renew_confirm');
					} else {
						$scope.setStep('domain_contact');
						domainUtil.populateDomainContact($scope.contact, $scope.profile);
						$scope.countryChange('contact_states_for_selected_country', $scope.contact.country_id);
					}
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
		if ($scope.is_renew) {
			$scope.setStep('renew_confirm');
		} else {
			$scope.setStep('confirm');
		}
		
		return;
	};
	
	$scope.submitPurchase = function() {
		$scope.setStep('loading');
		
		var query_data = domainUtil.validate_domain_name($scope.domain_to_purchase.name);
		var tld = query_data.tld;
		var domain_name = query_data.query;
		$scope.domain_to_purchase = query_data.final_query;
		
		domainUtil.purchase(domain_name, tld, $scope.contact).success(function(data, status) {
			if (data.result) {
				window.location.reload();
			} else {
				$scope.setStep('confirm');
				$scope.errorMessage = data.message;
			}
		});
	};
	
	$scope.submit_renew_purchase = function(renew_domain_name) {
		$scope.setStep('loading');
		
		var query_data = domainUtil.validate_domain_name(renew_domain_name);
		var tld = query_data.tld;
		var domain_name = query_data.query;
		
		domainUtil.renew(domain_name, tld).success(function(data, status) {
			if (data.result) {
				window.location.reload();
			} else {
				$scope.setStep('renew_confirm');
				$scope.errorMessage = data.message;
			}
		});
	};
};
domain_checkout.$inject = ['$scope','AuthnetProfile', '$http', 'generalUtil', 'domainUtil', '$modalInstance', 'domain', 'is_renew'];