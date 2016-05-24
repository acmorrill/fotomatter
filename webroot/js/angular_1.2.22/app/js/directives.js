'use strict';

/* Directives */
fotomatterApp.directive("confirmDelete", ["$interval", function($interval) {
	return {
		restrict: "A",
		priority: 1,
		terminal: true,
		link: function(scope, element, attr) {
			var clickAction = attr.ngClick;
			
			var confirmMessage = 'Default Message';
			var confirmTitle = 'Default Title';
			var confirmButtonTitle = 'Default Button Title';
			if (typeof attr.confirmMessage == 'string') {
				confirmMessage = attr.confirmMessage;
			}
			if (typeof attr.confirmTitle == 'string') {
				confirmTitle = attr.confirmTitle;
			}
			if (typeof attr.confirmButtonTitle == 'string') {
				confirmButtonTitle = attr.confirmButtonTitle;
			}
			element.bind('click', function() {
				jQuery.foto('confirm', {
					onConfirm: function() {
						scope.$eval(clickAction);
					},
					'message': confirmMessage,
					'title': confirmTitle,
					'button_title': confirmButtonTitle
				});
			});
		}
	};
}]);

fotomatterApp.directive('initToolbar', function($http, $compile, $templateCache) {
	function link(scope, element, attrs) {
		jQuery(element).qtip({
			content: attrs.initToolbar,
			position: {
				my: 'bottom left',
				at: 'top right',
				target: $(element)
			},
			hide: {
				fixed : true,
				delay : 500
			},
			style: { classes: 'qtip-dark' }
		});
	}

	return {
		restrict: 'A',
		link: link
	};
});

fotomatterApp.directive('convertToNumber', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attrs, ngModel) {
			ngModel.$parsers.push(function(val) {
				return val ? parseInt(val, 10) : null;
			});
			ngModel.$formatters.push(function(val) {
				return val ? '' + val : null;
			});
		}
	};
});


//fotomatterApp.directive("ngTagsChooser", ["$interval", function($interval) {
//	return {
//		restrict: "A",
//		priority: 1,
//		transclude: false,
//		scope: {
//			choosertags: "="
//		},
//		link: function(scope, element, attr) {
//		}
//	};
//}]);