Renovate.controller('JobsController', function($scope,$http,$modal){
	console.log('JobsController loaded!');

	$scope.jobs = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 12;

    $scope.urlsGetSingleJob = URLS.getSingleJob;
	$scope.urlsJobsGetNg = URLS.jobsGetNg;
	$scope.urlsJobsShowJob = URLS.jobsShowJob;
	$scope.urlsJobsCountNg = URLS.jobsCountNg;
	$scope.urlsJobsRemoveNg = URLS.jobsRemoveNg;

    $scope.singleJob = null;

    $scope.getSingleJob = function (job_id) {
        $http({
            method: "POST",
            url: $scope.urlsGetSingleJob,
            params: { job_id: job_id }
        })
            .success(function (response) {
                $scope.singleJob = response;
            });
    };

	$scope.$watch('itemsPerPage', function(){
		getJobsCount();
	});

	$scope.$watch('currentPage', function(){
		getJobs();
	});

	function getJobs()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET",
			url: $scope.urlsJobsGetNg,
			params: {offset: offset, limit: limit}
		})
			.success(function(response){
				if (response.result)
				{
					$scope.jobs = response.result;
                    for (var i in $scope.jobs) {
                        $scope.jobs[i].blochHover = false;
                    }
					//initVK();
					//initFB();
				}
			})
	}

	function getJobsCount()
	{
		$http({
			method: "GET",
			url: $scope.urlsJobsCountNg
		})
			.success(function(response){
				if (response.result)
				{
					$scope.totalItems = parseInt(response.result);
					getJobs();
				}
			})
	}
	getJobsCount();

	$scope.addJob = function(){
		var modalInstance = $modal.open({
			templateUrl: 'addJob.html',
			controller: 'AddJobController',
			backdrop: "static",
			size: 'lg'
		});

		modalInstance.result.then(function (added) {
			if (added) getJobsCount();
		}, function () {
			//bad
		});
	};

	$scope.editJob = function(job){
		var modalInstance = $modal.open({
			templateUrl: 'editJob.html',
			controller: 'EditJobController',
			backdrop: "static",
			size: 'lg',
			resolve: {
				job: function(){return job;}
			}
		});

		modalInstance.result.then(function (edited) {
			if (edited) getJobsCount();
		}, function () {
			//bad
		});
	};

	$scope.removeJob = function(job){
		var remove = confirm("Дійсно бажаєте видалити: " + job.name + " ?");
		if (!remove) return;

		var url = $scope.urlsJobsRemoveNg.replace('0', job.id);

		$http({
			method: "GET",
			url: url
		})
			.success(function(response){
				if (response.result)
				{
					getJobsCount();
				}
			});
	};

	$scope.setItemDirectHref = function(job){
		var href = $scope.urlsJobsShowJob.replace('0', job.nameTranslit);
		job.href = href;
	}
})

	.controller('AddJobController', function($scope,$http,$modalInstance){
		console.log('AddJobController loaded!');
		$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
		$scope.urlsJobsAddNg = URLS.jobsAddNg;
		$scope.documents = [];

		function getDocuments(){
			$http({
				method: "GET",
				url: $scope.urlsDocumentsGetNg
			})
				.success(function(response){
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
					getDocuments();
				}
			});

			CKEDITOR.replace('jobDescription');
			CKEDITOR.instances.jobDescription.on('change', function(e){
				$scope.job.description = e.editor.getData();
				$scope.$apply();
			});
		}, 1000);

		function addJob(){
			$http({
				method: "POST",
				url: $scope.urlsJobsAddNg,
				data: $scope.job
			})
				.success(function(response){
					console.log("added job => ", response);
					if (response.result)
					{
						$modalInstance.close(response.result);
					}
				})
		}

		$scope.ok = function () {
			if (!$scope.jobForm.$valid) return;
			addJob();
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	})
	.controller('EditJobController', function($scope,$http,$modalInstance,job){
		console.log('EditJobController loaded!');
		$scope.urlsDocumentsGetNg = URLS.documentsGetNg;
		$scope.urlsJobsEditNg = URLS.jobsEditNg;
		$scope.documents = [];
		$scope.job = job;

		function getDocuments(){
			$http({
				method: "GET",
				url: $scope.urlsDocumentsGetNg
			})
				.success(function(response){
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
					getDocuments();
				}
			});

			CKEDITOR.replace('jobDescription');
			CKEDITOR.instances.jobDescription.on('change', function(e){
				$scope.job.description = e.editor.getData();
				$scope.$apply();
			});
		}, 1000);

		function editJob(){
			var url = $scope.urlsJobsEditNg.replace('0', $scope.job.id);

			$http({
				method: "POST",
				url: url,
				data: $scope.job
			})
				.success(function(response){
					if (response.result)
					{
						$modalInstance.close(response.result);
					}
				})
		}

		$scope.ok = function () {
			if (!$scope.jobForm.$valid) return;
			editJob();
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	});