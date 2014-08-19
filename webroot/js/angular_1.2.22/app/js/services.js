'use strict';

/* Services */

var fotomatterServices = angular.module('fotomatterServices', ['ngResource']);

fotomatterServices.factory('Tags', ['$resource', function($resource) {
	return $resource('/tags/:id.json', {}, {
		'index': { url: '/admin/tags/index', method: 'GET', isArray: true },
		'add': { url: '/admin/tags/add', method: 'POST', params: { name: '@name' } },
		'edit': { url: '/admin/tags/edit/:id', method: 'PUT', params: { id: '@id', name: '@name' } },
		'delete': { url: '/admin/tags/delete/:id', method: 'DELETE', params: { id: '@id' } }
	});
}]);