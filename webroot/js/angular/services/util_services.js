angular.module('fmAdmin.utilServices', ['fmAdmin.constants'])
	.service('domainUtil', function($http, errorUtil, generalUtil) {
	
		var self = this;
		
		self.domainSearch = function(domain, tld) {
			var promiseResult = $http.post('/admin/domains/search', {
				domain: domain,
				tld: tld
			});
			promiseResult.error = errorUtil.handleError;
			return promiseResult;
		};
		
		self.parseSearchResult = function(data) {
			var domains_to_display = [];
			jQuery.each(data, function(key, domain) {
				if (domain.avail) {
					domain.name = key;
					domain.price = accounting.formatMoney(domain.price);
					domains_to_display.push(domain);
				}
			});
			return domains_to_display;
		};
		
		self.populateDomainContact = function(contact, payment_profile) {
			contact.first_name = payment_profile.billing_firstname;
			contact.last_name = payment_profile.billing_lastname;
			contact.address_1 = payment_profile.billing_address;
			contact.country_id = payment_profile.country_id;
			contact.city = payment_profile.billing_city;
			contact.zip = payment_profile.billing_zip;
		};
		
		self.purchase = function(domain, contact) {
			var toPost = {
				domain: jQuery.extend(true, {}, domain),
			    contact: jQuery.extend(true, {}, contact)
			};
			toPost.domain.price = accounting.unformat(domain.price);
			return $http.post("/admin/domains/purchase", toPost);
		};
	})
	.service('generalUtil', function($http, errorUtil) {
		var self = this;
		
		self.is_empty = function(value) {
			if (value === null || value === undefined || value === '') {
				return true;
			}
			return false;	
		};
		
		self.getStatesForCountry = function(country_id) {
			var result = $http.get('/admin/accounts/ajax_get_states_for_country/' + country_id + '/1');
			result.error = errorUtil.handleError;
			
			result.then(errorUtil.then);
			return result;
		};
	})
	.service('errorUtil', function(serverConstants) {
		var self = this;
		
		self.handleError = function(serverResponse, code) {
			major_error_recover('http request failed with a ' + code);
		};
		
		self.then = function(response) {
			if(response.status === 403) {
				window.location.replace("/admin/users/login?ajax_autoredirect=" + serverConstants.REQUEST_URI);
			}
		};
	});
	