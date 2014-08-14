'use strict';

/* Services */

var fotomatterServices = angular.module('fotomatterServices', ['ngResource']);

fotomatterServices.factory('Tags', ['$resource', function($resource) {
	return $resource('/tags/:id.json', {}, {
		'index': { method: 'GET', isArray: true },
		'add': { method: 'POST', params: { name: '@name' } },
		'view': { method: 'GET', params: { id: '@id' }, isArray: true },
		'edit': { method: 'PUT', params: { id: '@id' }, isArray: true },
		'delete': { method: 'DELETE', params: { id: '@id' } }
	});
}]);
