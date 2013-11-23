angular.module('fmAdmin.modelServices', [])
	.service('AuthnetProfile', function() {
		return function() {
			
			var self = this;
			
			self.initObject = function(obj) {
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
				
				
				
				
			};
		};
	});
	