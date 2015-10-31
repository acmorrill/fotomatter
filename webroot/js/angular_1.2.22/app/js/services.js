'use strict';


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
		'view': { 
			url: '/admin/photo_galleries/view/:id/:gallery_icon_size/:order_by/:sort_dir/:photo_formats/:photos_not_in_a_gallery',
			method: 'GET', 
			params: { 
				id: '@id', 
				gallery_icon_size: '@gallery_icon_size',
				order_by: '@order_by', 
				sort_dir: '@sort_dir', 
				photo_formats: '@photo_formats', 
				photos_not_in_a_gallery: '@photos_not_in_a_gallery'
			} 
		}
	});
}]);

// need to make this rego
