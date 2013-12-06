angular.module('fmAdmin.utilServices', [])
	.service('domainUtil', function($http) {
	
		var self = this;
		
		self.domainSearch = function(query) {
			return $http.post('/domains/search',
				{
					q: query
				});
		};
		
		/**
		 * @param {type} query - The actual keyword searched
		 * @returns {String} - The domain that we are looking for. 
		 * For example example.net needs to be example.net and not example.net.com
		 * 
		 * While if they type example then they are searching for example.com
		 */
		self.getActualDomainSearched = function(query) {
			var domain_pieces = query.split('.');
			var domain_title = '';

			if (domain_pieces.length === 3) {
				domain_title = domain_pieces[1] + '.' + domain_pieces[2];
			} else if(domain_pieces.length === 2) {
				domain_title = domain_pieces[0] + '.' + domain_pieces[1];
			} else {
				domain_title = domain_pieces[0] + '.com';
			}
			return domain_title;
		};
		
		self.parseSearchResult = function(data) {
			var domains_to_display = [];
			jQuery.each(data, function(key, domain) {
				if (domain.avail) {
					domain.name = key;
					domains_to_display.push(domain);
				}
			});
			return domains_to_display;
		};
	})
	.service('errorUtil', function() {
		var self = this;
		
		self.handleError = function(serverResponse, code) {
			major_error_recover('http request failed with a ' + code);
		};
	});
	