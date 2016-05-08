Renovate.controller('ArticlesController', function($scope,$http,$modal, $sce){
	console.log('ArticlesController loaded!');

	$scope.articles = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 5;
	
	$scope.urlsGetSingleArticle = URLS.getSingleArticle;
	$scope.urlsArticlesGetNg = URLS.articlesGetNg;
	$scope.urlsArticlesShowArticle = URLS.articlesShowArticle;
	$scope.urlsArticlesCountNg = URLS.articlesCountNg;
	$scope.urlsArticlesRemoveNg = URLS.articlesRemoveNg;

    $scope.singleArticle = null;

    $scope.getSingleArticle = function (article_id) {
        $http({
            method: "POST",
            url: $scope.urlsGetSingleArticle,
            params: { article_id: article_id }
        })
            .success(function (response) {
                $scope.singleArticle = response;
                $scope.description = $sce.trustAsHtml(response.description);
            });
    };
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getArticlesCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getArticles();
	});
	
	function getArticles()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsArticlesGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("articles => ",response);
			if (response.result)
			{
				$scope.articles = response.result;
				initVK();
				initFB();
			}
		})
	}
	
	function getArticlesCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsArticlesCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getArticles();
			}
		})
	}
	getArticlesCount();
	
	$scope.addArticle = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addArticle.html',
		      controller: 'AddArticleController',
		      backdrop: "static",
		      size: 'lg'
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getArticlesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editArticle = function(article){
		var modalInstance = $modal.open({
		      templateUrl: 'editArticle.html',
		      controller: 'EditArticleController',
		      backdrop: "static",
		      size: 'lg',
		      resolve: {
		    	  article: function(){return article;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getArticlesCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeArticle = function(article){
		var remove = confirm("Дійсно бажаєте видалити: " + article.name + " ?");
		if (!remove) return;
		
		var url = $scope.urlsArticlesRemoveNg.replace('0', article.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getArticlesCount();
			}
		});
	}
	
	$scope.setItemDirectHref = function(article){
		var href = $scope.urlsArticlesShowArticle.replace('0', article.nameTranslit);
		article.href = href;
	}
})
.controller('AddArticleController', function($scope,$http,$modalInstance){
	console.log('AddArticleController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsArticlesAddNg = URLS.articlesAddNg;
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
	    
	    CKEDITOR.replace('articleDescription');
	    CKEDITOR.instances.articleDescription.on('change', function(e){
	    	$scope.article.description = e.editor.getData();
	    	$scope.$apply();
	    });
	   
	}, 1000);
	
	function addArticle(){
		$http({
			method: "POST", 
			url: $scope.urlsArticlesAddNg,
			data: $scope.article
			  })
		.success(function(response){
			console.log("added article => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.articleForm.$valid) return;
		addArticle();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditArticleController', function($scope,$http,$modalInstance,article){
	console.log('EditArticleController loaded!');
	$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
	$scope.urlsArticlesEditNg = URLS.articlesEditNg;
	$scope.documents = [];
	$scope.article = article;
	
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
	    
	    CKEDITOR.replace('articleDescription');
	    CKEDITOR.instances.articleDescription.on('change', function(e){
	    	$scope.article.description = e.editor.getData();
	    	$scope.$apply();
	    });
	    
	}, 1000);
	
	function editArticle(){
		var url = $scope.urlsArticlesEditNg.replace('0', $scope.article.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.article
			  })
		.success(function(response){
			console.log("edited article => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.articleForm.$valid) return;
		editArticle();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});