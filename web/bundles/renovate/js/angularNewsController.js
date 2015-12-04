Renovate.controller('NewsController', function($scope,$http,$modal){
	console.log('NewsController loaded!');
	
	$scope.news = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;
	
	$scope.urlsNewsGetNg = URLS.newsGetNg;
	$scope.urlsNewsShowNews = URLS.newsShowNews;
	$scope.urlsNewsCountNg = URLS.newsCountNg;
	$scope.urlsNewsRemoveNg = URLS.newsRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getNewsCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getNews();
	});
	
	function getNews()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsNewsGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("news => ",response);
			if (response.result)
			{
				$scope.news = response.result;
				initVK();
				initFB();
			}
		})
	}
	
	function getNewsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsNewsCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getNews();
			}
		})
	}
	getNewsCount();
	
	$scope.addNews = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addNews.html',
		      controller: 'AddNewsController',
		      backdrop: "static",
		      size: 'lg'
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getNewsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editNews = function(newsp){
		var modalInstance = $modal.open({
		      templateUrl: 'editNews.html',
		      controller: 'EditNewsController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  newsp: function(){return newsp;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getNewsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeNews = function(newsp){
		var remove = confirm("Дійсно бажаєте видалити: " + newsp.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsNewsRemoveNg.replace('0', newsp.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getNewsCount();
			}
		});
	}
	
	$scope.setItemDirectHref = function(newsp){
		var href = $scope.urlsNewsShowNews.replace('0', newsp.nameTranslit);
		newsp.href = href;
	}
})
.controller('AddNewsController', function($scope,$http,$modalInstance){
	console.log('AddNewsController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsNewsAddNg = URLS.newsAddNg;
	$scope.documents = [];
	
	function getDocuments(){
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsGetNg
			  })
		.success(function(response){
			console.log("documents => ",response);
			if (response.result)
			{
				$scope.documents = response.result;
				$scope.documents.splice(0,0,{
					id: null,
					name: "--> не обрано <--"
				});
			}
		})
	}
	getDocuments();
	
	setTimeout(function() {
	    $('#file_upload').uploadify({
	    	'fileSizeLimit': 0,
	    	'progressData' : 'speed',
	    	'formData'     : {
				'timestamp' : TIMESTAMP,
				'token'     : TOKEN
			},
	    	'buttonText' : 'Завантажити...',
	        'swf'      : URLS.uploadifySWF,
	        'uploader' : URLS.documentsUpload,
	        'onUploadSuccess' : function(file, data, response) {
	            console.log('The file ' + file.name + ' was successfully uploaded with a response: ' + response + ' : ' + data);
	            getDocuments();
	        }
	    });
	    
	    CKEDITOR.replace('newsDescription');
	    CKEDITOR.instances.newsDescription.on('change', function(e){
	    	$scope.news.description = e.editor.getData();
	    	$scope.$apply();
	    });
	}, 1000);
	
	function addNews(){
		$http({
			method: "POST", 
			url: $scope.urlsNewsAddNg,
			data: $scope.news
			  })
		.success(function(response){
			console.log("added news => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.newsForm.$valid) return;
		addNews();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditNewsController', function($scope,$http,$modalInstance,newsp){
	console.log('EditNewsController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsNewsEditNg = URLS.newsEditNg;
	$scope.documents = [];
	$scope.news = newsp;
	
	function getDocuments(){
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsGetNg
			  })
		.success(function(response){
			console.log("documents => ",response);
			if (response.result)
			{
				$scope.documents = response.result;
				$scope.documents.splice(0,0,{
					id: null,
					name: "--> не обрано <--"
				});
			}
		})
	}
	getDocuments();
	
	setTimeout(function() {
	    $('#file_upload').uploadify({
	    	'fileSizeLimit': 0,
	    	'progressData' : 'speed',
	    	'formData'     : {
				'timestamp' : TIMESTAMP,
				'token'     : TOKEN
			},
	    	'buttonText' : 'Завантажити...',
	        'swf'      : URLS.uploadifySWF,
	        'uploader' : URLS.documentsUpload,
	        'onUploadSuccess' : function(file, data, response) {
	            console.log('The file ' + file.name + ' was successfully uploaded with a response: ' + response + ' : ' + data);
	            getDocuments();
	        }
	    });
	    
	    CKEDITOR.replace('newsDescription');
	    CKEDITOR.instances.newsDescription.on('change', function(e){
	    	$scope.news.description = e.editor.getData();
	    	$scope.$apply();
	    });
	}, 1000);
	
	function editNews(){
		var url = $scope.urlsNewsEditNg.replace('0', $scope.news.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.news
			  })
		.success(function(response){
			console.log("edited news => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.newsForm.$valid) return;
		editNews();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});