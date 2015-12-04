Renovate.controller('SharesController', function($scope,$http,$modal){
	console.log('SharesController loaded!');
	
	$scope.shares = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 3;
	
	$scope.urlsSharesGetNg = URLS.sharesGetNg;
	$scope.urlsSharesShowShare = URLS.sharesShowShare;
	$scope.urlsSharesCountNg = URLS.sharesCountNg;
	$scope.urlsSharesRemoveNg = URLS.sharesRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getSharesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getShares();
	});
	
	function getShares()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsSharesGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("shares => ",response);
			if (response.result)
			{
				$scope.shares = response.result;
				initVK();
				initFB();
			}
		})
	}
	
	function getSharesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsSharesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getShares();
			}
		})
	}
	getSharesCount();
	
	$scope.addShare = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addShare.html',
		      controller: 'AddShareController',
		      backdrop: "static",
		      size: 'lg'
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getSharesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editShare = function(share){
		var modalInstance = $modal.open({
		      templateUrl: 'editShare.html',
		      controller: 'EditShareController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  share: function(){return share;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getSharesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeShare = function(share){
		var remove = confirm("Дійсно бажаєте видалити: " + share.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsSharesRemoveNg.replace('0', share.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getSharesCount();
			}
		});
	}
	
	$scope.setItemDirectHref = function(share){
		var href = $scope.urlsSharesShowShare.replace('0', share.nameTranslit);
		share.href = href;
	}
})
.controller('AddShareController', function($scope,$http,$modalInstance){
	console.log('AddShareController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsSharesAddNg = URLS.sharesAddNg;
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
	    
	    CKEDITOR.replace('shareDescription');
	    CKEDITOR.instances.shareDescription.on('change', function(e){
	    	$scope.share.description = e.editor.getData();
	    	$scope.$apply();
	    });
	}, 1000);
	
	function addShare(){
		$http({
			method: "POST", 
			url: $scope.urlsSharesAddNg,
			data: $scope.share
			  })
		.success(function(response){
			console.log("added share => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.shareForm.$valid) return;
		addShare();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditShareController', function($scope,$http,$modalInstance,share){
	console.log('EditShareController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsSharesEditNg = URLS.sharesEditNg;
	$scope.documents = [];
	$scope.share = share;
	
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
	    
	    CKEDITOR.replace('shareDescription');
	    CKEDITOR.instances.shareDescription.on('change', function(e){
	    	$scope.share.description = e.editor.getData();
	    	$scope.$apply();
	    });
	}, 1000);
	
	function editShare(){
		var url = $scope.urlsSharesEditNg.replace('0', $scope.share.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.share
			  })
		.success(function(response){
			console.log("edited share => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.shareForm.$valid) return;
		editShare();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});