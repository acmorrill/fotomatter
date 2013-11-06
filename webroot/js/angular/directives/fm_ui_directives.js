angular.module('fmAdmin.directives', [])
	.directive('fmButton', function() {
		return {
			restrict: 'A',
			scope: true,
			link: function(scope, element, attrs) {
				element.button();
			}
		}
	});