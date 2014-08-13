'use strict';

/* Services */

var fotomatterServices = angular.module('fotomatterServices', ['ngResource']);

fotomatterServices.factory('Tag', ['$resource',	function($resource) {
	return $resource('/:tagId.json', {}, {
		query: {method: 'GET', params: {tagId: 'tags'}, isArray: true}
	});
}]);
