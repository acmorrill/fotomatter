'use strict';

/* Controllers */

var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('TagListCtrl', ['$scope', 'Tags', function($scope, Tags) {
	$scope.loading = true;
	
	
	$scope.orderProp = '-Tag.weight';
	$scope.change_sort = function(new_sort) {
		if ($scope.orderProp == new_sort) {
			new_sort = '-' + new_sort;
		}
		$scope.orderProp = new_sort;
	};
		
	Tags.index().$promise.then(function(tags) {
		$scope.loading = false;
		$scope.tags = tags;
	});
	
	$scope.delete_tag = function(tag_index) {
		Tags.delete($scope.tags[tag_index].Tag, function() {
			$scope.tags.splice(tag_index, 1);
		});
	};
	
	$scope.adding_tag = false;
	$scope.add_tag = function() {
		$scope.orderProp = '-Tag.weight';
		$scope.adding_tag = true;
		var data = {};
		data.name = $scope.new_tag;
		$scope.new_tag = '';
		Tags.add(data, function(result) {
			$scope.tags.unshift(result.new_tag);
			$scope.adding_tag = false;
		});
	};
}]);

//fotomatterControllers.controller('PhoneDetailCtrl', ['$scope', '$routeParams', 'Phone',	function($scope, $routeParams, Phone) {
//	$scope.phone = Phone.get({phoneId: $routeParams.phoneId}, function(phone) {
//		$scope.mainImageUrl = phone.images[0];
//	});
//
//	$scope.setImage = function(imageUrl) {
//		$scope.mainImageUrl = imageUrl;
//	};
//}]);
