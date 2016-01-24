'use strict';


var fotomatterResources = angular.module('fotomatterResources', ['ngResource']);



fotomatterResources.factory('Tags', ['$resource', function($resource) {
	return $resource('/tags/:id.json', {}, {
		'index': { url: '/admin/tags/index', method: 'GET', isArray: true },
		'index_no_count': { url: '/admin/tags/index/false', method: 'GET', isArray: true },
		'add': { url: '/admin/tags/add', method: 'POST', params: { name: '@name' } },
		'edit': { url: '/admin/tags/edit/:id', method: 'PUT', params: { id: '@id', name: '@name' } },
		'delete': { url: '/admin/tags/delete/:id', method: 'DELETE', params: { id: '@id' } }
	});
}]);

fotomatterResources.factory('PhotoGalleries', ['$resource', function($resource) {
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
			url: '/admin/photo_galleries/view_smart_gallery/:id',
			method: 'GET',
			params: { 
				id: '@id'
			}
		},
		'edit_gallery': {
			method: 'POST',
			url: '/admin/photo_galleries/edit_gallery'
		},
		'edit_smart_gallery': {
			method: 'POST',
			url: '/admin/photo_galleries/edit_smart_gallery'
		},
		'add_gallery': {
			method: 'GET',
			url: '/admin/photo_galleries/add_gallery/:type',
			params: { 
				type: '@type'
			}
		},
		'delete_gallery': {
			method: 'GET',
			url: '/admin/photo_galleries/delete_gallery/:id',
			params: { 
				id: '@id'
			}
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
		},
		'get_photos_limited': {
			url: '/admin/photo_galleries/get_photo_limits',
			method: 'GET'
		}
	});
}]);


fotomatterResources.factory('PrintTypes', ['$resource', function($resource) {
	return $resource('/admin/ecommerces/:id.json', {}, {
		'list': { url: '/admin/ecommerces/angular_list_print_types', method: 'GET' },
		'reorder': { url: '/admin/ecommerces/angular_set_print_type_order/:photo_print_type_id/:new_order', method: 'GET' },
		'delete': { url: '/admin/ecommerces/angular_delete_print_type/:photo_print_type_id', method: 'GET' },
		'edit': { url: '/admin/ecommerces/angular_add_print_type_and_pricing/:photo_print_type_id', method: 'GET' },
		'edit_automatic': { url: '/admin/ecommerces/angular_add_automatic_print_type_and_pricing/:photo_print_type_id', method: 'GET' },
		'save': { url: '/admin/ecommerces/angular_save_print_type_and_pricing', method: 'POST' },
		'add_self': { url: '/admin/ecommerces/angular_add_print_type_and_pricing', method: 'GET' },
		'add_automatic': { url: '/admin/ecommerces/angular_add_automatic_print_type_and_pricing/0/:print_fulfiller_id/:print_fulfiller_print_type_id', method: 'GET' }
		// ($print_fulfiller_id, $print_fulfiller_print_type_id, $photo_print_type_id = 0)
//		'get_avail_sizes': { url: '/admin/ecommerces/angular_get_photo_avail_sizes', method: 'GET' }
	});
}]);


