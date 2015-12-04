Renovate.controller('RepairsController', function($scope,$http,$modal){
	console.log('RepairsController loaded!');
	
	$scope.repairs = [];
	$scope.repairsSelectedIds = [];
	$scope.repairsSelectedAll = false;
	$scope.workers = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.filter = {
			from: null,
			to:null,
			userid: null,
			paid: null
	}
	$scope.totalPrice = 0;
	$scope.isAdmin = false;
	
	$scope.urlsRepairsGetNg = URLS.repairsGetNg;
	$scope.urlsRepairsCountNg = URLS.repairsCountNg;
	$scope.urlsRepairsPaidSetNg = URLS.repairsPaidSetNg;
	$scope.urlsRepairsRemoveNg = URLS.repairsRemoveNg;
	$scope.urlsUsersGetWorkersNg = URLS.usersGetWorkersNg;
	
	function Initialize(){
		var user = JSON.parse(USER);
		
		_.each(user.roles, function(role){
			if (role.role == "ROLE_ADMIN")
			{
				$scope.isAdmin = true;
			}
		});
		
		if ($scope.isAdmin){
			getWorkers();
		}else
		{
			$scope.filter.userid = user.id;
		}
		
		$scope.$watch('itemsPerPage', function(){
			console.log("itemsPerPage => ", $scope.itemsPerPage);
			getRepairsCount();
		});
		
		$scope.$watch('currentPage', function(){
			console.log("currentPage => ", $scope.currentPage);
			getRepairs();
		});
		
		$scope.$watch('filter', function(){
			console.log("filter => ", $scope.filter);
			getRepairsCount();
		}, true);
		
		getRepairsCount();
	}
	Initialize();
	
	$scope.repairsSelectAll = function(){
		if (!$scope.repairsSelectedAll){
			$scope.repairsSelectedIds = _.map($scope.repairs, function(repair){return repair.id;});
			$scope.repairsSelectedAll = true;
		}else
		{
			$scope.repairsSelectedIds = [];
			$scope.repairsSelectedAll = false;
		}
	}
	
	function calculateTotalPrice(){
		$scope.totalPrice = 0;
		_.each($scope.repairs, function(repair){
			$scope.totalPrice += repair.price;
		});
	}
	
	$scope.openFrom = function($event) {
	    $event.preventDefault();
	    $event.stopPropagation();

	    $scope.openedFrom = true;
	};
	
	$scope.openTo = function($event) {
	    $event.preventDefault();
	    $event.stopPropagation();

	    $scope.openedTo = true;
	};
	
	$scope.isSelected = function(id){
		return _.find($scope.repairsSelectedIds, function(repairid){
			return repairid == id;
		})
	}
	
	$scope.selectItem = function(id){
		if (!$scope.isAdmin) return false;
		var selected = $scope.isSelected(id);
		if (selected) {
			var index = _.indexOf($scope.repairsSelectedIds, id);
			$scope.repairsSelectedIds.splice(index, 1);
			$scope.repairsSelectedAll = false;
		}else
		{
			$scope.repairsSelectedIds.push(id);
			if ($scope.repairsSelectedIds.length == $scope.repairs.length)
				$scope.repairsSelectedAll = true;
		}
	}
	
	function getWorkers()
	{
		$http({
			method: "GET", 
			url: $scope.urlsUsersGetWorkersNg
			  })
		.success(function(response){
			console.log(" workers => ",response);
			if (response.result)
			{
				$scope.workers = response.result;
			}
		})
	}
	
	function getRepairs()
	{
		$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsRepairsGetNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log(" repairs => ",response);
			if (response.result)
			{
				$scope.repairs = response.result;
				calculateTotalPrice();
			}
		})
	}
	
	function getRepairsCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsRepairsCountNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getRepairs();
			}
		})
	}
	
	$scope.setPaid = function(paid){
		if (!$scope.isAdmin) return false;
		$http({
			method: "POST", 
			url: $scope.urlsRepairsPaidSetNg,
			data: {ids: $scope.repairsSelectedIds, paid: paid}
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getRepairs();
			}
		})
	}
	
	$scope.addRepair = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addRepair.html',
		      controller: 'AddRepairController',
		      backdrop: "static",
		      resolve: {
		    	  workers: function(){return $scope.workers;}
		      }
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getRepairsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editRepair = function(repair, event){
		event.stopPropagation();
		var modalInstance = $modal.open({
		      templateUrl: 'editRepair.html',
		      controller: 'EditRepairController',
		      backdrop: "static",
		      resolve: {
		    	  repair: function(){return repair;},
		    	  workers: function(){return $scope.workers;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getRepairsCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeRepair = function(repair, event){
		event.stopPropagation();
		var remove = confirm("Дійсно бажаєте видалити: " + repair.description + " ?");
		if (!remove) return;
		
		var url = $scope.urlsRepairsRemoveNg.replace('0', repair.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getRepairsCount();
			}
		});
	}
})
.controller('AddRepairController', function($scope,$http,$modalInstance,workers){
	console.log('AddRepairController loaded!');
	
	$scope.urlsRepairsAddNg = URLS.repairsAddNg;
	$scope.workers = workers;
	
	function addRepair(){
		$http({
			method: "POST", 
			url: $scope.urlsRepairsAddNg,
			data: $scope.repair
			  })
		.success(function(response){
			console.log("added repair => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.repairForm.$valid) return;
		addRepair();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditRepairController', function($scope,$http,$modalInstance,repair,workers){
	console.log('EditRepairController loaded!');
	
	$scope.urlsRepairsEditNg = URLS.repairsEditNg;
	$scope.workers = workers;
	$scope.repair = repair;
	
	function editRepair(){
		var url = $scope.urlsRepairsEditNg.replace('0', $scope.repair.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.repair
			  })
		.success(function(response){
			console.log("edited repair => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.repairForm.$valid) return;
		editRepair();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
});