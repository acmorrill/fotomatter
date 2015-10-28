'use strict';

var fotomatterApp = angular.module('fotomatterApp', [
	'fotomatterControllers',
	'fotomatterServices',
	'xeditable',
	'ngCookies'
]);


fotomatterApp.run(function(editableOptions, editableThemes) {
	editableOptions.theme = 'default';
	editableThemes.default.buttonsTpl = '<div class="editable-buttons custom_ui"></div>';
	editableThemes.default.submitTpl = '<input class="add_button" type="submit" value="Save">';
	editableThemes.default.cancelTpl = '<div ng-click="$form.$cancel()" class="add_button"><div class="content">Cancel</div></div>';
	editableThemes.default.controlsTpl = '<div class="editable-controls"></div>';
});

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

