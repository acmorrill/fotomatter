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
			url: '/admin/photo_galleries/view/:id/:gallery_icon_size/:order_by/:sort_dir/:photos_not_in_a_gallery/:last_photo_id/:photo_formats',
			method: 'GET', 
			params: { 
				id: '@id', 
				gallery_icon_size: '@gallery_icon_size',
				order_by: '@order_by', 
				sort_dir: '@sort_dir', 
				photos_not_in_a_gallery: '@photos_not_in_a_gallery',
				last_photo_id: '@last_photo_id',
				photo_formats: '@photo_formats'
			}
		},
		'view_smart': { 
			url: '/admin/photo_galleries/edit_smart_gallery/:id',
			method: 'GET', 
		},
		'edit_gallery': {
			method: 'POST',
			url: '/admin/photo_galleries/edit_gallery'
		},
		'add_photo': {
			url: '/admin/photo_galleries/ajax_movephoto_into_gallery/:photo_id/:gallery_id/:gallery_icon_size',
			method: 'GET', 
			params: { 
				photo_id: '@photo_id', 
				gallery_id: '@gallery_id',
				gallery_icon_size: '@gallery_icon_size'
			} 
		},
		'remove_photo': {
			url: '/admin/photo_galleries/ajax_removephotos_from_gallery/:gallery_id/:photo_id',
			method: 'GET', 
			params: { 
				gallery_id: '@gallery_id',
				photo_id: '@photo_id'
			} 
		},
		'reorder_photo': {
			url: '/admin/photo_galleries/ajax_set_photo_order_in_gallery/:gallery_id/:photo_id/:new_order',
			method: 'GET', 
			params: { 
				gallery_id: '@gallery_id',
				photo_id: '@photo_id',
				new_order: '@new_order'
			} 
		},
		'reorder_gallery': {
			url: '/admin/photo_galleries/ajax_set_photogallery_order/:gallery_id/:new_order',
			method: 'GET', 
			params: { 
				gallery_id: '@gallery_id',
				new_order: '@new_order'
			} 
		}
	});
}]);

// need to make this rego
