'use strict';

/* Controllers */

var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('TagListCtrl', ['$scope', 'Tag', function($scope, Tag) {
	$scope.tags = Tag.query();
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
