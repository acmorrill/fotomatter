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

fotomatterServices.factory('PhotoGalleries', ['$resource', function($resource) {
	return $resource('/photo_galleries/:id.json', {}, {
		'index': { url: '/admin/photo_galleries/index', method: 'GET', isArray: true },
		'view': { url: '/admin/photo_galleries/view/:id', method: 'GET', isArray: true, params: { id: '@id' } }
//		'gallery_photos': { url: '/admin/photo_galleries/view/:id', method: 'GET', isArray: true, params: { id: '@id' } }
//		'add': { url: '/admin/photo_galleries/add', method: 'POST', params: { name: '@name' } },
//		'edit': { url: '/admin/photo_galleries/edit/:id', method: 'PUT', params: { id: '@id', name: '@name' } },
//		'delete': { url: '/admin/photo_galleries/delete/:id', method: 'DELETE', params: { id: '@id' } }
	});
}]);
