angular.module('fmAdmin.modelServices', ['fmAdmin.utilServices'])
	.service('AuthnetProfile', function($http, errorUtil) {
	
			
			var self = this;
			
			/**
			 * I was having some problems with expected fields not being their when populated from the server. 
			 * The fields aren't in the scope untill a value has been entered, so this function will make sure that they are all there.
			 * @param {type} obj object passed from server. 
			 * @returns {object} object that has been fully initialized
			 */
			self.initObject = function(obj) {
				if (typeof(obj) !== 'object') {
					obj = {};
				}
				
				if (obj.billing_firstName === undefined) {
					obj.billing_firstName = '';
				}
				
				if (obj.billing_lastname === undefined) {
					obj.billing_lastname = '';
				}
				
				if (obj.billing_address === undefined) {
					obj.billing_address = '';
				}
				
				if(obj.billing_city === undefined) {
					obj.billing_city = '';
				}
				
				if (obj.billing_zip === undefined) {
					obj.billing_zip = '';
				}
				
				if (obj.payment_cardNumber === undefined) {
					obj.payment_cardNumber = '';
				}
				
				if(obj.expiration === undefined) {
					obj.expiration = {};
				}
				
				var now = new Date();
				if (obj.expiration.month === undefined) {
					obj.expiration.month = now.getMonth() + 1;
					
					if (obj.expiration.month.length === 1) {
						obj.expiration.month = "0" + obj.expiration.month;
					} else{
						obj.expiration.month = obj.expiration.month.toString();
					}
				}
				
				if (obj.expiration.year === undefined) {
					obj.expiration.year = now.getFullYear().toString();
				}
				
				return obj;				
			};
			
			self.save = function(profile_to_send) {
				var promiseResult = $http.post('/admin/domains/add_profile', profile_to_send);
				promiseResult.error = errorUtil.handleError;
				return promiseResult;
			};
	});
	