NGApp.factory('DashboardService', function(ResourceFactory, $routeParams, $resource) {

	var service = {};

	var dashboard = ResourceFactory.createResource(App.service + 'dashboard/:id_page', { id_admin: '@id_page'}, {
		'communities_with_shift' : {
			url: App.service + 'dashboard/beta/communities-with-shift',
			method: 'GET',
			params : {},
			isArray: true
		},
		'communities' : {
			url: App.service + 'dashboard/beta/communities',
			method: 'POST',
			params : {},
			isArray: true
		},
		'current_driver_status' : {
			url: App.service + 'dashboard/beta/current-driver-status',
			method: 'GET',
			params : {},
			isArray: true
		},
		'pre_orders' : {
			url: App.service + 'dashboard/beta/pre-orders',
			method: 'GET',
			params : {},
			isArray: true
		},
		'chart_last_orders' : {
			url: App.service + 'dashboard/beta/chart-last-orders',
			method: 'GET',
			params : {}
		},
		'load' : {
			method: 'GET',
			params : {}
		}
	});

	service.communities_with_shift = function(callback) {
		dashboard.communities_with_shift({}, function(data) {
			callback(data);
		});
	}

	service.current_driver_status = function(callback) {
		dashboard.current_driver_status({}, function(data) {
			callback(data);
		});
	}

	service.pre_orders = function(callback) {
		dashboard.pre_orders({}, function(data) {
			callback(data);
		});
	}

	service.communities = function(communities, callback){
		dashboard.communities({communities:communities}, function(data) {
			callback(data);
		});
	}

	service.chart_last_orders = function(callback){
		dashboard.chart_last_orders({}, function(data) {
			callback(data);
		});
	}

	service.get = function(id_page, callback) {
		dashboard.load({}, function(data) {
			callback(data);
		});
	}

	return service;
});
