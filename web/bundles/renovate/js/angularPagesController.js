Renovate.controller('PagesController', function($scope,$http,$modal){
	console.log('PagesController loaded!');
	
	$scope.pages = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.urlsPagesGetNg = URLS.pagesGetNg;
	$scope.urlsPagesCountNg = URLS.pagesCountNg;
	$scope.urlsPagesRemoveNg = URLS.pagesRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getPagesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getPages();
	});
	
	function getPages()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsPagesGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log(" pages => ",response);
			if (response.result)
			{
				$scope.pages = response.result;
			}
		})
	}
	
	function getPagesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPagesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getPages();
			}
		})
	}
	getPagesCount();
	
	$scope.addPage = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addPage.html',
		      controller: 'AddPageController',
		      backdrop: "static",
		      size: 'lg'
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getPagesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editPage = function(page){
		var modalInstance = $modal.open({
		      templateUrl: 'editPage.html',
		      controller: 'EditPageController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  page: function(){return page;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getPagesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removePage = function(page){
		var remove = confirm("Дійсно бажаєте видалити: " + page.url + " ?");
		if (!remove) return;
		
		var url = $scope.urlsPagesRemoveNg.replace('0', page.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getPagesCount();
			}
		});
	}
})
.controller('AddPageController', function($scope,$http,$modalInstance){
	console.log('AddPageController loaded!');
	$scope.urlsPagesAddNg = URLS.pagesAddNg;
	
	setTimeout(function(){
		CKEDITOR.replace('pageContent');
	    CKEDITOR.instances.pageContent.on('change', function(e){
	    	$scope.page.content = e.editor.getData();
	    	$scope.$apply();
	    });
	},1000);
	
	function addPage(){
		$http({
			method: "POST", 
			url: $scope.urlsPagesAddNg,
			data: $scope.page
			  })
		.success(function(response){
			console.log("added page => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.pageForm.$valid) return;
		addPage();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditPageController', function($scope,$http,$modalInstance,page){
	console.log('EditPageController loaded!');
	$scope.urlsPagesEditNg = URLS.pagesEditNg;
	$scope.page = page;
	
	setTimeout(function(){
		CKEDITOR.replace('pageContent');
	    CKEDITOR.instances.pageContent.on('change', function(e){
	    	$scope.page.content = e.editor.getData();
	    	$scope.$apply();
	    });
	},1000);
	
	function editPage(){
		var url = $scope.urlsPagesEditNg.replace('0', $scope.page.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.page
			  })
		.success(function(response){
			console.log("edited page => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.pageForm.$valid) return;
		editPage();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});