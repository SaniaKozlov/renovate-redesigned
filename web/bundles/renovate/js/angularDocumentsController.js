Renovate.controller('DocumentsController', function($scope, $http){
	console.log('DocumentsController loaded!');
	
	$scope.documents = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsDocumentsCountNg = URLS.documentsCountNg;
	$scope.urlsDocumentsRemoveNg = URLS.documentsRemoveNg;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getDocumentsCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getDocuments();
	});
	
	setTimeout($(function() {
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
	}), 1000);
	
	function getDocuments()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("documents => ",response);
			if (response.result)
			{
				$scope.documents = response.result;
			}
		})
	}
	
	function getDocumentsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsDocumentsCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getDocuments();
			}
		})
	}
	getDocumentsCount();
	
	$scope.removeDocument = function(document){
		if (document.links.count>0){
			alert('Даний файл неможливо видалити!\nВін містить '+document.links.count+' посилань на інші записи.\n\n'
					+'Види робіт (обкладинка/ярлик): '+document.links.items.jobs+'/'+document.links.items.jobsLabels+'\n'
					+'Новини (обкладинка/ярлик): '+document.links.items.news+'/'+document.links.items.newsLabels+'\n'
					+'Результати роботи (обкладинка/ярлик): '+document.links.items.results+'/'+document.links.items.resultsLabels+'\n'
					+'Статті (обкладинка/ярлик): '+document.links.items.articles+'/'+document.links.items.articlesLabels+'\n'
					+'Акції (обкладинка/ярлик): '+document.links.items.shares+'/'+document.links.items.sharesLabels+'\n'
					+'Вакансії (обкладинка/ярлик): '+document.links.items.vacancies+'/'+document.links.items.vacanciesLabels+'\n'
					+'Партнери: '+document.links.items.partners+'\n');
		}else{
			var remove = confirm("Дійсно бажаєте видалити: " + document.name + " ?");
			if (!remove) return;
			
			var url = $scope.urlsDocumentsRemoveNg.replace('0', document.id);
			
			$http({
				method: "GET", 
				url: url
				  })
			.success(function(response){
				console.log(response);
				if (response.result)
				{
					getDocumentsCount();
				}
			});
		}
	}
	
});