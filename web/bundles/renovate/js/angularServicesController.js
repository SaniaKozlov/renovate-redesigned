Renovate.controller('ServicesController', function($scope,$http,$modal){
	console.log('ServicesController loaded!');
	
	$scope.services = [];
	$scope.clientRoles = [];
	$scope.serviceCategories = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.filter = {
			category: null,
			logical: null
	}
	
	$scope.urlsServicesGetNg = URLS.servicesGetNg;
	$scope.urlsServicesCountNg = URLS.servicesCountNg;
	$scope.urlsServicesRemoveNg = URLS.servicesRemoveNg;
	$scope.urlsRolesGetClientRolesNg = URLS.rolesGetClientRolesNg;
	$scope.urlsServiceCategoriesGetAllNg = URLS.serviceCategoriesGetAllNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getServicesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getServices();
	});
	
	$scope.$watch('filter', function(){
		console.log("filter => ", $scope.filter);
		getServicesCount();
	}, true);

	
	function getServiceCategories()
	{
		$http({
			method: "GET", 
			url: $scope.urlsServiceCategoriesGetAllNg
			  })
		.success(function(response){
			console.log(" serviceCategories => ",response);
			if (response.result)
			{
				$scope.serviceCategories = response.result;
			}
		})
	}
	getServiceCategories();
	
	function getClientRoles()
	{
		$http({
			method: "GET", 
			url: $scope.urlsRolesGetClientRolesNg
			  })
		.success(function(response){
			console.log(" clientRoles => ",response);
			if (response.result)
			{
				$scope.clientRoles = response.result;
			}
		})
	}
	getClientRoles();
	
	function getServices()
	{
		$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsServicesGetNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log("services => ",response);
			if (response.result)
			{
				$scope.services = response.result;
			}
		})
	}
	
	function getServicesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsServicesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getServices();
			}
		})
	}
	getServicesCount();
	
	$scope.addService = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addService.html',
		      controller: 'AddServiceController',
		      backdrop: "static",
		      resolve: {
		    	  serviceCategories: function(){return $scope.serviceCategories;},
		    	  clientRoles: function(){return $scope.clientRoles;}
		      }
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getServicesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editService = function(service){
		var modalInstance = $modal.open({
		      templateUrl: 'editService.html',
		      controller: 'EditServiceController',
		      backdrop: "static",
		      resolve: {
		    	  service: function(){return service;},
		    	  serviceCategories: function(){return $scope.serviceCategories;},
		    	  clientRoles: function(){return $scope.clientRoles;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getServicesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeService = function(service){
		var remove = confirm("Дійсно бажаєте видалити: " + service.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsServicesRemoveNg.replace('0', service.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getServicesCount();
			}
		});
	}
})
.controller('AddServiceController', function($scope,$http,$modalInstance,serviceCategories,clientRoles){
	console.log('AddServiceController loaded!');
	$scope.urlsServicesAddNg = URLS.servicesAddNg;
	$scope.serviceCategories = serviceCategories;
	$scope.clientRoles = clientRoles;
	$scope.options = [];
	$scope.prices = [];
	$scope.optionsPrices = [];
	
	$scope.formInvalid = true;
	
	function getRolePrices(){
		var prices = [];
		_.map($scope.clientRoles, function(role){
			prices.push({roleid: role.id, value: 0.000001});
		});
		return prices;
	}
	(function initialization(){
		$scope.prices = getRolePrices();
		$scope.options.push({name: 'Варіант 1'},{name: 'Варіант 2'});
		$scope.optionsPrices.push(getRolePrices(), getRolePrices());
	})();
	
	$scope.addOption = function(){
		$scope.options.push({name: 'Варіант '+($scope.options.length+1)});
		$scope.optionsPrices.push(getRolePrices());
	}
	
	$scope.removeOption = function(){
		$scope.options.pop();
		$scope.optionsPrices.pop();
	}
	
	$scope.checkFormInvalid = function(){
		
		var invalid = false;
		
		if ($scope.service.logical == '1'){
			_.map($scope.prices, function(price, i){
					$scope.prices[i].error = false;
			});
			
			_.map($scope.prices, function(price, i){
				if (!price.value) {
					$scope.prices[i].error = true;
					invalid = true;
				}
			});
			
		}else{
			_.map($scope.options, function(option, i){
				$scope.options[i].error = false;
			});
			
			_.map($scope.optionsPrices, function(option, i){
				_.map(option, function(price, j){
					$scope.optionsPrices[i][j].error = false;
				});
			});
			
			_.map($scope.options, function(option, i){
				if (!option.name.trim()) {
					$scope.options[i].error = true;
					invalid = true;
				}
			});
			
			_.map($scope.optionsPrices, function(option, i){
				_.map(option, function(price, j){
					if (!price.value) {
						$scope.optionsPrices[i][j].error = true;
						invalid = true;
					}
				});
			});
		}
		
		$scope.formInvalid = invalid;
		
		return invalid;
	}
	
	function addService(){
		if ($scope.service.logical == '0'){
			$scope.service.options = $scope.options;
			$scope.service.prices = $scope.optionsPrices;
		}else{
			if (_.has($scope.service, 'options')) delete $scope.service.options;
			$scope.service.prices = $scope.prices;
		}
		
		$http({
			method: "POST", 
			url: $scope.urlsServicesAddNg,
			data: $scope.service
			  })
		.success(function(response){
			console.log("added service => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.serviceForm.$valid) return;
		if ($scope.checkFormInvalid()) return;
		addService();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditServiceController', function($scope,$http,$modalInstance,service,serviceCategories,clientRoles){
	console.log('EditServiceController loaded!');
	
	$scope.urlsServicesEditNg = URLS.servicesEditNg;
	$scope.serviceCategories = serviceCategories;
	$scope.clientRoles = clientRoles;
	$scope.service = service;
	$scope.options = [];
	$scope.prices = [];
	$scope.optionsPrices = [];
	
	$scope.formInvalid = true;
	
	$scope.checkFormInvalid = function(){
		
		var invalid = false;
		
		if ($scope.service.logical == '1'){
			_.map($scope.prices, function(price, i){
					$scope.prices[i].error = false;
			});
			
			_.map($scope.prices, function(price, i){
				if (!price.value) {
					$scope.prices[i].error = true;
					invalid = true;
				}
			});
			
		}else{
			_.map($scope.options, function(option, i){
				$scope.options[i].error = false;
			});
			
			_.map($scope.optionsPrices, function(option, i){
				_.map(option, function(price, j){
					$scope.optionsPrices[i][j].error = false;
				});
			});
			
			_.map($scope.options, function(option, i){
				if (!option.name.trim()) {
					$scope.options[i].error = true;
					invalid = true;
				}
			});
			
			_.map($scope.optionsPrices, function(option, i){
				_.map(option, function(price, j){
					if (!price.value) {
						$scope.optionsPrices[i][j].error = true;
						invalid = true;
					}
				});
			});
		}
		
		$scope.formInvalid = invalid;
		
		return invalid;
	}
	
	function getRolePrices(){
		var prices = [];
		_.map($scope.clientRoles, function(role){
			prices.push({roleid: role.id, value: 0.000001});
		});
		return prices;
	};
	
	function copyLogicalPrices(){
		_.map($scope.prices, function(price,i){
			var p = _.find($scope.service.prices, function(p){
				return p.roleid == price.roleid;
			});
			
			if (p != undefined){
				$scope.prices[i].value = p.value;
			}
		});
	};
	
	function copyOptionsPrices(){
		_.map($scope.service.options, function(option,i){
			if(typeof $scope.options[i] == 'undefined') {
				$scope.options.push({name: option.name});
				$scope.optionsPrices.push(getRolePrices());
				
				var prices = _.filter($scope.service.prices, function(p){
					return p.optionid == option.id;
				});
				
				_.map($scope.optionsPrices[i], function(p, j){
					var _p = _.find(prices, function(_p){
						return _p.roleid == p.roleid;
					});
					
					if (_p != undefined){
						$scope.optionsPrices[i][j].value = _p.value;
					}
				});
			}
			else {
				$scope.options[i].name = option.name;
				
				var prices = _.filter($scope.service.prices, function(p){
					return p.optionid == option.id;
				});
				
				_.map($scope.optionsPrices[i], function(p, j){
					var _p = _.find(prices, function(_p){
						return _p.roleid == p.roleid;
					});
					
					if (_p != undefined){
						$scope.optionsPrices[i][j].value = _p.value;
					}
				});
			}
		});
	};
	
	$scope.addOption = function(){
		$scope.options.push({name: 'Варіант '+($scope.options.length+1)});
		$scope.optionsPrices.push(getRolePrices());
	};
	
	$scope.removeOption = function(){
		$scope.options.pop();
		$scope.optionsPrices.pop();
	};
	
	(function initialization(){
		$scope.prices = getRolePrices();
		$scope.options.push({name: 'Варіант 1'},{name: 'Варіант 2'});
		$scope.optionsPrices.push(getRolePrices(), getRolePrices());
		
		if ($scope.service.logical == true){
			copyLogicalPrices();
		}else{
			copyOptionsPrices();
		}
		$scope.checkFormInvalid();
	})();
	
	function editService(){
		if ($scope.service.logical == '0'){
			$scope.service.options = $scope.options;
			$scope.service.prices = $scope.optionsPrices;
		}else{
			if (_.has($scope.service, 'options')) delete $scope.service.options;
			$scope.service.prices = $scope.prices;
		}
		
		var url = $scope.urlsServicesEditNg.replace('0', $scope.service.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.service
			  })
		.success(function(response){
			console.log("edited service => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	};
	
	$scope.ok = function () {
		if (!$scope.serviceForm.$valid) return;
		if ($scope.checkFormInvalid()) return;
		editService();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});