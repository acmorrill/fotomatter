'use strict';

/* Directives */
fotomatterApp.directive("confirmDelete", ["$interval", function($interval) {
	return {
		restrict: "A",
		priority: 1,
		terminal: true,
		link: function(scope, element, attr) {
			var clickAction = attr.ngClick;
			element.bind('click', function() {
				jQuery.foto('confirm', {
					message: 'Do you really want to delete the tag?',
					onConfirm: function() {
						scope.$eval(clickAction);
					},
					'title': 'Really delete tag?',
					'button_title': 'Delete'
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