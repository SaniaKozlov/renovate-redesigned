Renovate.controller('PriceCategoriesController', function($scope,$http,$modal){
	console.log('PriceCategoriesController loaded!');
	
	$scope.priceCategories = [];
	$scope.urlsPriceCategoriesGetAllNg = URLS.priceCategoriesGetAllNg;
	$scope.urlsPriceCategoriesRemoveNg = URLS.priceCategoriesRemoveNg;
	
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
	
	
	$scope.addPriceCategory = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addPriceCategory.html',
		      controller: 'AddPriceCategoryController',
		      backdrop: "static"
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getPriceCategories();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editPriceCategory = function(priceCategory){
		var modalInstance = $modal.open({
		      templateUrl: 'editPriceCategory.html',
		      controller: 'EditPriceCategoryController',
		      backdrop: "static",
		      resolve: {
		    	  priceCategory: function(){return priceCategory;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getPriceCategories();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removePriceCategory = function(priceCategory){
		var remove = confirm("Дійсно бажаєте видалити: " + priceCategory.name + " ?\nУВАГА!Всі прайси цієї категорії також будуть видалені!Радимо змінити категорію для прайсів.");
		if (!remove) return;
		
		var url = $scope.urlsPriceCategoriesRemoveNg.replace('0', priceCategory.id);
		
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
.controller('AddPriceCategoryController', function($scope,$http,$modalInstance){
	console.log('AddPriceCategoryController loaded!');
	$scope.urlsPriceCategoriesAddNg = URLS.priceCategoriesAddNg;
	
	function addPriceCategory(){
		$http({
			method: "POST", 
			url: $scope.urlsPriceCategoriesAddNg,
			data: $scope.priceCategory
			  })
		.success(function(response){
			console.log("added price category => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.priceCategoryForm.$valid) return;
		addPriceCategory();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditPriceCategoryController', function($scope,$http,$modalInstance,priceCategory){
	console.log('EditPriceCategoryController loaded!');
	$scope.urlsPriceCategoriesEditNg = URLS.priceCategoriesEditNg;
	$scope.priceCategory = priceCategory;
	
	function editPriceCategory(){
		var url = $scope.urlsPriceCategoriesEditNg.replace('0', $scope.priceCategory.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.priceCategory
			  })
		.success(function(response){
			console.log("edited price category => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.priceCategoryForm.$valid) return;
		editPriceCategory();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});