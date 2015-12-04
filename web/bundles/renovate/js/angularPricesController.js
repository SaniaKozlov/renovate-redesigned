Renovate.controller('PricesController', function($scope,$http,$modal){
	console.log('PricesController loaded!');
	
	$scope.urlsPricesRemoveNg = URLS.pricesRemoveNg;
	
	$scope.priceCategories = [];
	$scope.urlsPriceCategoriesGetAllNg = URLS.priceCategoriesGetAllNg;
	
	function getPriceCategories()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPriceCategoriesGetAllNg
			  })
		.success(function(response){
			console.log("price categories => ",response);
			if (response.result)
			{
				$scope.priceCategories = response.result;
			}
		})
	}
	getPriceCategories();
	
	$scope.addPrice = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addPrice.html',
		      controller: 'AddPriceController',
		      backdrop: "static",
		      resolve:{
		    	  priceCategories: function(){return $scope.priceCategories;}
		      }
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getPriceCategories();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editPrice = function(price){
		var modalInstance = $modal.open({
		      templateUrl: 'editPrice.html',
		      controller: 'EditPriceController',
		      backdrop: "static",
		      resolve: {
		    	  price: function(){return price;},
		    	  priceCategories: function(){return $scope.priceCategories;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getPriceCategories();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removePrice = function(price){
		var remove = confirm("Дійсно бажаєте видалити: " + price.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsPricesRemoveNg.replace('0', price.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getPriceCategories();
			}
		});
	}
})
.controller('AddPriceController', function($scope,$http,$modalInstance,priceCategories){
	console.log('AddPriceController loaded!');
	$scope.urlsPricesAddNg = URLS.pricesAddNg;
	$scope.priceCategories = priceCategories;
	
	function addPrice(){
		$http({
			method: "POST", 
			url: $scope.urlsPricesAddNg,
			data: $scope.price
			  })
		.success(function(response){
			console.log("added price => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.priceForm.$valid) return;
		addPrice();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditPriceController', function($scope,$http,$modalInstance,price,priceCategories){
	console.log('EditPriceController loaded!');
	$scope.urlsPricesEditNg = URLS.pricesEditNg;
	$scope.price = price;
	$scope.priceCategories = priceCategories;
	
	function editPrice(){
		var url = $scope.urlsPricesEditNg.replace('0', $scope.price.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.price
			  })
		.success(function(response){
			console.log("edited price => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.priceForm.$valid) return;
		editPrice();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});