angular.module('fmAdmin', [])
	.directive('fmButton', function() {
		return {
			restrict: 'A',
			scope: true,
			link: function(scope, element, attrs) {
				element.button();
			}
		}
	});