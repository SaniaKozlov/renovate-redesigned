Renovate.controller('TasksController', function($scope,$http,$modal){
	console.log('TasksController loaded!');
	
	$scope.tasks = [];
	$scope.users = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	
	$scope.urlsTasksGetNg = URLS.tasksGetNg;
	$scope.urlsTasksCountNg = URLS.tasksCountNg;
	$scope.urlsTasksRemoveNg = URLS.tasksRemoveNg;
	$scope.urlsTasksApproveNg = URLS.tasksApproveNg;
	$scope.urlsTasksDeclineNg = URLS.tasksDeclineNg;
	$scope.urlsUsersGetClientsNg = URLS.usersGetClientsNg;
	$scope.urlsUsersGetWorkforceNg = URLS.usersGetWorkforceNg;
	
	$scope.filter = {
			from: null,
			to:null,
			userid: null,
			'status[]': []
	}
	
	$scope.statuses = {
			ready: true,
			finished: true,
			approved: true
	}
	
	$scope.$watch('filter', function(){
		console.log("filter => ", $scope.filter);
		getTasksCount();
	}, true);
	
	$scope.$watch('statuses', function(){
		console.log("statuses => ", $scope.statuses);
		var status = [];
		_.map($scope.statuses, function(value, key){
			if (value) status.push(key);
		});
		
		$scope.filter['status[]'] = status;
	}, true);
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getTasksCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getTasks();
	});
	
	function getTasks()
	{
		$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsTasksGetNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log("tasks => ",response);
			if (response.result)
			{
				$scope.tasks = response.result;
			}
		})
	}
	
	function getClients()
	{	
		$http({
			method: "GET", 
			url: $scope.urlsUsersGetClientsNg
			  })
		.success(function(response){
			console.log("clients => ",response);
			if (response.result)
			{
				$scope.users = $scope.users.concat(response.result);
			}
		})
	};
	
	(function getWorkforce()
	{	
		$http({
			method: "GET", 
			url: $scope.urlsUsersGetWorkforceNg
			  })
		.success(function(response){
			console.log("workforce => ",response);
			if (response.result)
			{
				$scope.users = response.result;
				getClients();
			}
		})
	})();
	
	function getTasksCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsTasksCountNg,
			params: $scope.filter
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getTasks();
			}
		})
	}
	getTasksCount();
	
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
	
	$scope.addTask = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addTask.html',
		      controller: 'AddTaskController',
		      backdrop: "static",
		      resolve: {
		    	  users: function(){return $scope.users;}
		      }
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getTasksCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editTask = function(task){
		var modalInstance = $modal.open({
		      templateUrl: 'editTask.html',
		      controller: 'EditTaskController',
		      backdrop: "static",
		      resolve: {
		    	  task: function(){return task;},
		    	  users: function(){return $scope.users;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getTasksCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeTask = function(task){
		var remove = confirm("Дійсно бажаєте видалити: " + task.description + " ?");
		if (!remove) return;
		
		var url = $scope.urlsTasksRemoveNg.replace('0', task.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getTasksCount();
			}
		});
	}
	
	$scope.approveTask = function(task){
		var approved = confirm("Дійсно бажаєте підтвердити виконання: " + task.description + " ?");
		if (!approved) return;
		
		var url = $scope.urlsTasksApproveNg.replace('0', task.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getTasksCount();
			}
		});
	}
	
	$scope.declineTask = function(task){
		var declined = confirm("Дійсно бажаєте спростувати виконання: " + task.description + " ?");
		if (!declined) return;
		
		var url = $scope.urlsTasksDeclineNg.replace('0', task.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getTasksCount();
			}
		});
	}
})
.controller('AddTaskController', function($scope,$http,$modalInstance,users){
	console.log('AddTaskController loaded!');
	$scope.urlsTasksAddNg = URLS.tasksAddNg;
	$scope.users = users;
	
	function addTask(){
		$http({
			method: "POST", 
			url: $scope.urlsTasksAddNg,
			data: $scope.task
			  })
		.success(function(response){
			console.log("added task => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.taskForm.$valid) return;
		addTask();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditTaskController', function($scope,$http,$modalInstance,task,users){
	console.log('EditTaskController loaded!');
	$scope.urlsTasksEditNg = URLS.tasksEditNg;
	$scope.users = users;
	$scope.task = task;
	
	function editTask(){
		var url = $scope.urlsTasksEditNg.replace('0', $scope.task.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.task
			  })
		.success(function(response){
			console.log("edited task => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.ok = function () {
		if (!$scope.taskForm.$valid) return;
		editTask();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('UserTasksController', function($scope,$http,$modal){
	console.log('UserTasksController loaded!');
	
	$scope.tasksCount = 0;
	$scope.canUseStorage = true;
	$scope.urlsTasksCountNg = URLS.tasksCountNg;
	
	(function Initialization(){
		$scope.user = (USER == 'undefined') ? null : JSON.parse(USER);
		if ($scope.user == null) return;
		if(typeof(Storage) === "undefined") {
			$scope.canUseStorage = false;
		    console.log('Sorry! No Web Storage support...');
		}

		setInterval(getTasksCount, 2000);
	})();
	
	function showTasksList(){
		if ($scope.tasksCount == 0 || !$scope.canUseStorage) {sessionStorage.showCount = 0; return;};
		if (!sessionStorage.showCount || sessionStorage.showCount == 0) $scope.showTasks();
	}
	
	function getTasksCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsTasksCountNg,
			params: {userid: $scope.user.id, 'status[]': ['ready']}
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.tasksCount = parseInt(response.result);
				showTasksList();
			}
		})
	};
	
	$scope.showTasks = function(){
		sessionStorage.showCount += 1;
		var modalInstance = $modal.open({
		      templateUrl: 'tasksList.html',
		      controller: 'TasksListController',
		      backdrop: "static",
		      size: 'lg',
		      resolve:{
		    	  user: function(){return $scope.user;}
		      }
		});
		
		modalInstance.result.then(function (result) {
		      //ok
		    }, function () {
		      //bad
		});
	}
})
.controller('TasksListController', function($scope,$http,$modalInstance,user){
	console.log('TasksListController loaded!')
	
	$scope.tasks = [];
	$scope.urlsTasksGetNg = URLS.tasksGetNg;
	$scope.urlsTasksFinishNg = URLS.tasksFinishNg;
	$scope.user = user;
	
	function getTasks()
	{
		$http({
			method: "GET", 
			url: $scope.urlsTasksGetNg,
			params: {userid: $scope.user.id, 'status[]': ['ready','finished']}
			  })
		.success(function(response){
			console.log("tasks => ",response);
			if (response.result)
			{
				$scope.tasks = response.result;
				if ($scope.tasks.length == 0) $modalInstance.close(true);
			}
		})
	};
	getTasks();
	
	$scope.ok = function () {
		$modalInstance.close(true);
	};
	
	$scope.finishTask = function(task){
		var finished = confirm("Ви дійсно виконали: " + task.description + " ?");
		if (!finished) return;
		
		var url = $scope.urlsTasksFinishNg.replace('0', task.id);
		
		$http({
			method: "POST", 
			url: url
			  })
		.success(function(response){
			console.log("finished task => ", response);
			if (response.result)
			{
				getTasks();
			}
		});
	}
});