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