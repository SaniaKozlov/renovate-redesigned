Renovate.controller('VacanciesController', function($scope,$http,$modal){
	console.log('VacanciesController loaded!');
	
	$scope.vacancies = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;

    $scope.urlsGetSingleVacancy = URLS.getSingleVacancy;
	$scope.urlsVacanciesGetNg = URLS.vacanciesGetNg;
	$scope.urlsVacanciesShowVacancy = URLS.vacanciesShowVacancy;
	$scope.urlsVacanciesCountNg = URLS.vacanciesCountNg;
	$scope.urlsVacanciesRemoveNg = URLS.vacanciesRemoveNg;

    $scope.singleVacancy = null;

    $scope.getSingleVacancy = function (vacancy_id) {
        $http({
            method: "POST",
            url: $scope.urlsGetSingleVacancy,
            params: { vacancy_id: vacancy_id }
        })
            .success(function (response) {
                $scope.singleVacancy = response;
            });
    };
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getVacanciesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getVacancies();
	});
	
	function getVacancies()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsVacanciesGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("vacancies => ",response);
			if (response.result)
			{
				$scope.vacancies = response.result;
                for (var i in $scope.vacancies) {
                    $scope.vacancies[i].document.url = $scope.vacancies[i].document.url.replace('web/', '');
                }
				initVK();
				initFB();
			}
		})
	}
	
	function getVacanciesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsVacanciesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getVacancies();
			}
		})
	}
	getVacanciesCount();
	
	$scope.addVacancy = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addVacancy.html',
		      controller: 'AddVacancyController',
		      backdrop: "static",
		      size: 'lg'
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getVacanciesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editVacancy = function(vacancy){
		var modalInstance = $modal.open({
		      templateUrl: 'editVacancy.html',
		      controller: 'EditVacancyController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  vacancy: function(){return vacancy;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getVacanciesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeVacancy = function(vacancy){
		var remove = confirm("Дійсно бажаєте видалити: " + vacancy.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsVacanciesRemoveNg.replace('0', vacancy.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getVacanciesCount();
			}
		});
	}
	
	$scope.setItemDirectHref = function(vacancy){
		var href = $scope.urlsVacanciesShowVacancy.replace('0', vacancy.nameTranslit);
		vacancy.href = href;
	}
})
.controller('AddVacancyController', function($scope,$http,$modalInstance){
	console.log('AddVacancyController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsVacanciesAddNg = URLS.vacanciesAddNg;
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
	    
	    CKEDITOR.replace('vacancyDescription');
	    CKEDITOR.instances.vacancyDescription.on('change', function(e){
	    	$scope.vacancy.description = e.editor.getData();
	    	$scope.$apply();
	    });
	   
	}, 1000);
	
	function addVacancy(){
		$http({
			method: "POST", 
			url: $scope.urlsVacanciesAddNg,
			data: $scope.vacancy
			  })
		.success(function(response){
			console.log("added vacancy => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.vacancyForm.$valid) return;
		addVacancy();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditVacancyController', function($scope,$http,$modalInstance,vacancy){
	console.log('EditVacancyController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsVacanciesEditNg = URLS.vacanciesEditNg;
	$scope.documents = [];
	$scope.vacancy = vacancy;
	
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
	    
	    CKEDITOR.replace('vacancyDescription');
	    CKEDITOR.instances.vacancyDescription.on('change', function(e){
	    	$scope.vacancy.description = e.editor.getData();
	    	$scope.$apply();
	    });
	    
	}, 1000);
	
	function editVacancy(){
		var url = $scope.urlsVacanciesEditNg.replace('0', $scope.vacancy.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.vacancy
			  })
		.success(function(response){
			console.log("edited vacancy => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.vacancyForm.$valid) return;
		editVacancy();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});