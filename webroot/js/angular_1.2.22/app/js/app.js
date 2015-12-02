'use strict';

var fotomatterApp = angular.module('fotomatterApp', [
	'fotomatterControllers',
	'fotomatterServices',
	'xeditable',
	'ngCookies',
	'ui.sortable'
]).factory('helperMethods', function() {
	return {
		objectToArr: function(obj) {
//			console.log(obj);
//			console.log('came into here');
		},
		removeItem: function(items, item) {
			var index = items.indexOf(item);
			items.splice(index, 1); 
		},
		refreshScopeAfterReorder: function(items, model_name, id, new_position) {
			var element_to_move;
			for (var x in items) {
				if (typeof items[x][model_name] == 'object') {
					if (items[x][model_name].id == id) {
						element_to_move = items.splice(x, 1);
						break;
					}
				}
			}

			// add element to the new scope position
			var count = 1;
			for (var x in items) {
				if (count == new_position) {
					items.splice(count - 1, 0, element_to_move[0]);
					break;
				}
				count++;
			}
		},
		updateArrItem: function (items, model_name, field_name, id, new_data) {
			for (var x in items) {
				if (typeof items[x][model_name] == 'object') {
					if (items[x][model_name].id == id) {
						items[x][model_name][field_name] = new_data;
						break;
					}
				}
			}
		},
		getArrItem: function (items, model_name, id) {
			for (var x in items) {
				if (typeof items[x][model_name] == 'object') {
					if (items[x][model_name].id == id) {
						return items[x];
					}
				}
			}
		}
		
	};
});

fotomatterApp.run(function(editableOptions, editableThemes, $rootScope, helperMethods) {
	$rootScope.helpers = helperMethods;
	editableOptions.theme = 'default';
	editableThemes.default.buttonsTpl = '<div class="editable-buttons custom_ui"></div>';
	editableThemes.default.submitTpl = '<input class="add_button" type="submit" value="Save">';
	editableThemes.default.cancelTpl = '<div ng-click="$form.$cancel()" class="add_button"><div class="content">Cancel</div></div>';
	editableThemes.default.controlsTpl = '<div class="editable-controls"></div>';
});



