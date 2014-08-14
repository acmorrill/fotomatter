'use strict';

/* Controllers */

var fotomatterControllers = angular.module('fotomatterControllers', []);

fotomatterControllers.controller('TagListCtrl', ['$scope', 'Tags', function($scope, Tags) {
	$scope.tags = Tags.index();
	
	$scope.delete_tag = function(tag_index) {
		Tags.delete($scope.tags[tag_index].Tag, function() {
			$scope.tags.splice(tag_index, 1);
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
