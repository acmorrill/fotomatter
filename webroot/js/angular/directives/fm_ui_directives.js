angular.module('fmAdmin.directives', [])
	.directive('fmButton', function() {
		return {
			restrict: 'A',
			scope: true,
			link: function(scope, element, attrs) {
				element.button();
			}
		}
	})
	.directive('parentFmAbsCenter', function() {
		return {
			restrict : 'A',
			scope: true,
			link: function(scope, element, attrs) {
			/*	var window_half_width = $(window).width() / 2;
				element_half_width = element.closest('.modal').outerWidth() * 2
				element.closest('.modal').css('left', (window_half_width - element_half_width) + 'px');
				element.closest('.modal').css('top', '20px');
				element.closest('.modal').css('width', 'auto'); */
			}
		};
	});
