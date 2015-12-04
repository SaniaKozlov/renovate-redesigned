Renovate.controller('PartnersController', function($scope,$http,$modal){
	console.log('PartnersController loaded!');
	
	$scope.partners = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.urlsPartnersGetNg = URLS.partnersGetNg;
	$scope.urlsPartnersCountNg = URLS.partnersCountNg;
	$scope.urlsPartnersRemoveNg = URLS.partnersRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getPartnersCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getPartners();
	});
	
	function getPartners()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsPartnersGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log(" partners => ",response);
			if (response.result)
			{
				$scope.partners = response.result;
			}
		})
	}
	
	function getPartnersCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPartnersCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getPartners();
			}
		})
	}
	getPartnersCount();
	
	$scope.addPartner = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addPartner.html',
		      controller: 'AddPartnerController',
		      backdrop: "static"
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getPartnersCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editPartner = function(partner){
		var modalInstance = $modal.open({
		      templateUrl: 'editPartner.html',
		      controller: 'EditPartnerController',
		      backdrop: "static",
		      resolve: {
		    	  partner: function(){return partner;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getPartnersCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removePartner = function(partner){
		var remove = confirm("Дійсно бажаєте видалити: " + partner.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsPartnersRemoveNg.replace('0', partner.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getPartnersCount();
			}
		});
	}
})
.controller('AddPartnerController', function($scope,$http,$modalInstance){
	console.log('AddPartnerController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsPartnersAddNg = URLS.partnersAddNg;
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
	}, 1000);
	
	function addPartner(){
		$http({
			method: "POST", 
			url: $scope.urlsPartnersAddNg,
			data: $scope.partner
			  })
		.success(function(response){
			console.log("added partner => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.partnerForm.$valid) return;
		addPartner();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditPartnerController', function($scope,$http,$modalInstance,partner){
	console.log('EditPartnerController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsPartnersEditNg = URLS.partnersEditNg;
	$scope.documents = [];
	$scope.partner = partner;
	
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
	}, 1000);
	
	function editPartner(){
		var url = $scope.urlsPartnersEditNg.replace('0', $scope.partner.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.partner
			  })
		.success(function(response){
			console.log("edited partner => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.partnerForm.$valid) return;
		editPartner();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});