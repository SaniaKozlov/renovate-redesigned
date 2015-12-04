Renovate.controller('PaymentsController', function($scope,$http,$modal){
	console.log('PaymentsController loaded!');
	
	$scope.payments = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	(function Initialization(){
		var userid = USERID;
		if (userid){
			$scope.urlsPaymentsGetNg = URLS.paymentsUserGetNg.replace('0', userid);
			$scope.urlsPaymentsCountNg = URLS.paymentsUserGetCountNg.replace('0', userid);
		}else{
			$scope.urlsPaymentsGetNg = URLS.paymentsMyGetNg;
			$scope.urlsPaymentsCountNg = URLS.paymentsMyGetCountNg;
		}
		
		$scope.$watch('itemsPerPage', function(){
			console.log("itemsPerPage => ", $scope.itemsPerPage);
			getPaymentsCount();
		});
		
		$scope.$watch('currentPage', function(){
			console.log("currentPage => ", $scope.currentPage);
			getPayments();
		});
		
		getPaymentsCount();
	})();
	
	function getPayments()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsPaymentsGetNg,
			params: {offset: offset, limit: limit}
			  })
		.success(function(response){
			console.log("payments => ",response);
			if (response.result)
			{
				$scope.payments = response.result;
			}
		})
	}
	
	function getPaymentsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsPaymentsCountNg
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getPayments();
			}
		})
	}
})
.controller('ImportPaymentsController', function($scope,$http,$modal){
	console.log('ImportPaymentsController loaded!');
	$scope.alerts = [];
	
	$scope.closeAlert = function(index) {
	    $scope.alerts.splice(index, 1);
	};
	
    $('#file_upload').uploadify({
    	'fileSizeLimit': 0,
    	'fileType'	: '*.xls',
    	'progressData' : 'speed',
    	'formData'     : {
			'timestamp' : TIMESTAMP,
			'token'     : TOKEN
		},
    	'buttonText' : 'Завантажити...',
        'swf'      : URLS.uploadifySWF,
        'uploader' : URLS.paymentsImport,
        'onUploadSuccess' : function(file, data, response) {
            console.log('The file ' + file.name + ' was successfully uploaded with a response: ' + response + ' : ' + data);
            if (data == 'true') $scope.alerts.push({msg: 'Файл '+file.name+' успішно імпортовано.', type: 'success'});
            if (data == 'false') $scope.alerts.push({msg: 'Файл '+file.name+' не імпортовано.', type: 'danger'});
        }
    });
});