'use strict';

/* Controllers */

var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('TagListCtrl', ['$scope', '$q', 'Tags', function($scope, $q, Tags) {
	$scope.loading = true;
	$scope.tag_manager_error = '';
	
	Tags.index().$promise.then(function(tags) {
		$scope.loading = false;
		$scope.tags = tags;
	});
	
	$scope.orderProp = '-Tag.id';
	$scope.change_sort = function(new_sort) {
		if ($scope.orderProp == new_sort) {
			new_sort = '-' + new_sort;
		}
		$scope.orderProp = new_sort;
	};
	
	$scope.edit_tag = function(new_name, tag_id) {
		if (new_name.length === 0) {
			return "Tag cannot be empty.";
		}
		if (new_name.length > 80) {
			return "Tag > 80 characters.";
		}
		var edit_tag = {
			id: tag_id,
			name: new_name
		};
		var d = $q.defer();
		Tags.edit(edit_tag, 
			function(result) {
				if (result.type === 'success') {
					d.resolve();
				} else {
					d.resolve(result.text);
				}
			},
			function(error) {
				d.resolve("Server Error");
			}
		);
		
		return d.promise;
	};
	
	$scope.delete_tag = function(tag_id) {
		$scope.tag_manager_error = '';
		
		var tag_to_delete;
		var tag_to_delete_index;
		for (var tag_index in $scope.tags) {
			if ($scope.tags[tag_index].Tag.id === tag_id) {
				tag_to_delete = $scope.tags[tag_index].Tag;
				tag_to_delete_index = tag_index;
				break;
			}
		}
		
		Tags.delete(tag_to_delete, 
			function(result) {
				if (result.type === 'success') {
					$scope.tags.splice(tag_index, 1);
				} else {
					$scope.tag_manager_error = result.text;
				}
			},
			function(error) {
				$scope.tag_manager_error = 'Server error deleting tag.';
			}
		);
	};
	
	$scope.adding_tag = false;
	
	$scope.add_tag = function() {
		$scope.tag_manager_error = '';
		$scope.orderProp = '-Tag.id';
		$scope.adding_tag = true;
		var data = {};
		data.name = $scope.new_tag;
		$scope.new_tag = '';
		Tags.add(data, 
			function(result) {
				if (result.type === 'success') {
					$scope.tags.unshift(result.new_tag);
				} else {
					$scope.tag_manager_error = result.text;
				}
				$scope.adding_tag = false;
			},
			function(error) {
				$scope.tag_manager_error = 'Add tag server error.';
				$scope.adding_tag = false;
			}
		);
	};
}]);

