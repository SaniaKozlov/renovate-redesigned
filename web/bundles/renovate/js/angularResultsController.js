Renovate.controller('ResultsController', function($scope,$http,$modal){
	console.log('ResultsController loaded!');
	
	$scope.results = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 12;
	
	$scope.urlsResultsGetNg = URLS.resultsGetNg;
	$scope.urlsResultsShowResult = URLS.resultsShowResult;
	$scope.urlsResultsCountNg = URLS.resultsCountNg;
	$scope.urlsResultsRemoveNg = URLS.resultsRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getResultsCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getResults();
	});
	
	function getResults()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsResultsGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("results => ",response);
			if (response.result)
			{
				$scope.results = response.result;
                for (var i in $scope.results) {
                    $scope.results[i].document.url = $scope.results[i].document.url.replace('web/', '');
                }
				initVK();
				initFB();
			}
		})
	}
	
	function getResultsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsResultsCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getResults();
			}
		})
	}
	getResultsCount();
	
	$scope.addResult = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addResult.html',
		      controller: 'AddResultController',
		      backdrop: "static",
		      size: 'lg'
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getResultsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editResult = function(result){
		var modalInstance = $modal.open({
		      templateUrl: 'editResult.html',
		      controller: 'EditResultController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  result: function(){return result;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getResultsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeResult = function(result){
		var remove = confirm("Дійсно бажаєте видалити: " + result.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsResultsRemoveNg.replace('0', result.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getResultsCount();
			}
		});
	}
	
	$scope.setItemDirectHref = function(result){
		var href = $scope.urlsResultsShowResult.replace('0', result.nameTranslit);
		result.href = href;
	}
})
.controller('AddResultController', function($scope,$http,$modalInstance){
	console.log('AddResultController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsResultsAddNg = URLS.resultsAddNg;
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
	    
	    CKEDITOR.replace('resultDescription');
	    CKEDITOR.instances.resultDescription.on('change', function(e){
	    	$scope.result.description = e.editor.getData();
	    	$scope.$apply();
	    });
	}, 1000);
	
	function addResult(){
		$http({
			method: "POST", 
			url: $scope.urlsResultsAddNg,
			data: $scope.result
			  })
		.success(function(response){
			console.log("added result => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.resultForm.$valid) return;
		addResult();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditResultController', function($scope,$http,$modalInstance,result){
	console.log('EditResultController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsResultsEditNg = URLS.resultsEditNg;
	$scope.documents = [];
	$scope.result = result;
	
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
	    
	    CKEDITOR.replace('resultDescription');
	    CKEDITOR.instances.resultDescription.on('change', function(e){
	    	$scope.result.description = e.editor.getData();
	    	$scope.$apply();
	    });
	}, 1000);
	
	function editResult(){
		var url = $scope.urlsResultsEditNg.replace('0', $scope.result.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.result
			  })
		.success(function(response){
			console.log("edited result => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.resultForm.$valid) return;
		editResult();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});