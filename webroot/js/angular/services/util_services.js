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
		
		self.delete_domain = function(domain_id) {
			var promiseResult = $http.post('/admin/domains/delete_domain/' + domain_id);
			promiseResult.error = errorUtil.handleError;
			return promiseResult;
		};
		
		self.parseSearchResult = function(data) {
			var domains_to_display = [];
			jQuery.each(data, function(key, domain) {
				domain.name = key;
				domain.price = accounting.formatMoney(domain.price);
				domains_to_display.push(domain);
			});
			domains_to_display.sort(function(a, b){
				return b.avail - a.avail;
			});
			return domains_to_display;
		};
		
		self.validate_domain_name = function(domain_name) {
			// sanitize the query
			var valid_tlds = {
				'com': true,
				'net': true//,
//				'org': true, // DREW TODO - these don't seem to work - need to test live
//				'me': true,
//				'biz': true
			};
			var tld = domain_name.match(/\..{2,3}$/);
			if  (tld != null) {
				tld = tld[0];
				tld = tld.replace(/[^a-zA-Z]/g, '');
			} else {
				tld = 'com';
			}
			if (typeof valid_tlds[tld] != 'boolean') {
				tld = 'com';
			}

			var query = domain_name.replace(/\..{2,3}$/, '');
			query = query.replace(/[^a-zA-Z-]/g, '');
			query = query.toLowerCase();

			var final_query = '';
			if (tld != '') {
				final_query = query + '.' + tld;
			} else {
				final_query = query + '.com';
			}
			
			return {
				'query': query,
				'tld': tld,
				'final_query': final_query
			};
		};
		
		self.populateDomainContact = function(contact, payment_profile) {
			contact.first_name = payment_profile.billing_firstname;
			contact.last_name = payment_profile.billing_lastname;
			contact.address_1 = payment_profile.billing_address;
			contact.country_id = payment_profile.country_id;
			contact.city = payment_profile.billing_city;
			contact.zip = payment_profile.billing_zip;
		};
		
		self.purchase = function(domain, tld, contact) {
			var toPost = {
				domain: domain,
				tld: tld,
				contact: jQuery.extend(true, {}, contact)
			};
			toPost.domain.price = accounting.unformat(domain.price);
			return $http.post("/admin/domains/purchase", toPost);
		};
		self.add_external_domain = function(domain) {
			var toPost = {
				domain: domain
			};
			return $http.post("/admin/domains/add_external_domain", toPost);
		};
		
		self.renew = function(domain, tld) {
			var toPost = {
				domain: domain,
				tld: tld
			};
			toPost.domain.price = accounting.unformat(domain.price);
			return $http.post("/admin/domains/renew", toPost);
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
	