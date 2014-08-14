'use strict';

/* App Module */

var fotomatterApp = angular.module('fotomatterApp', [
	'fotomatterControllers',
	'fotomatterServices'
//  'ngRoute',
//  'phonecatAnimations',
//  'phonecatFilters',
]);

//fotomatterApp.config(['$routeProvider',
//function($routeProvider) {
//	$routeProvider.when('/phones', {
//		templateUrl: 'partials/phone-list.html',
//		controller: 'PhoneListCtrl'
//	}).when('/phones/:phoneId', {
//		templateUrl: 'partials/phone-detail.html',
//		controller: 'PhoneDetailCtrl'
//	}).otherwise({
//		redirectTo: '/phones'
//	});
//}]);
