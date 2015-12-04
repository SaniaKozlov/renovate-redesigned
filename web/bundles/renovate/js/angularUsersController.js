Renovate.controller('UsersController', function($scope,$http,$modal){
	console.log('UsersController loaded!');
	
	$scope.users = [];
	$scope.roles = [];
	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;
	$scope.isAdmin = false;
	
	$scope.urlsUsersGetNg = URLS.usersGetNg;
	$scope.urlsUsersCountNg = URLS.usersCountNg;
	$scope.urlsUsersRemoveNg = URLS.usersRemoveNg;
	$scope.urlsUsersShowUser = URLS.usersShowUser;
	$scope.urlsRolesGetClientRolesNg = URLS.rolesGetClientRolesNg;
	$scope.urlsRolesGetSimpleRolesNg = URLS.rolesGetSimpleRolesNg;
	$scope.urlsRolesGetPrivilegesRolesNg = URLS.rolesGetPrivilegesRolesNg;
	$scope.searchHandler = null;
	
	$scope.$watch('itemsPerPage', function(){
		console.log("itemsPerPage => ", $scope.itemsPerPage);
		getUsersCount();
	});
	
	$scope.$watch('currentPage', function(){
		console.log("currentPage => ", $scope.currentPage);
		getUsers();
	});
	
	$scope.$watch('search', function(){
		clearTimeout($scope.searchHandler);
		$scope.searchHandler = setTimeout(function(){
			console.log("search => ", $scope.search);
			getUsersCount();
		}, 500);
	});
	
	function getClientRoles()
	{
		$http({
			method: "GET", 
			url: $scope.urlsRolesGetClientRolesNg
			  })
		.success(function(response){
			console.log(" client roles => ",response);
			if (response.result)
			{
				$scope.clientRoles = response.result;
				$scope.roles = $scope.roles.concat(response.result);
			}
		})
	};
	
	function getSimpleRoles()
	{
		$http({
			method: "GET", 
			url: $scope.urlsRolesGetSimpleRolesNg
			  })
		.success(function(response){
			console.log(" simple roles => ",response);
			if (response.result)
			{
				$scope.roles = $scope.roles.concat(response.result);
			}
		})
	};
	
	function getPrivilegesRoles()
	{
		$http({
			method: "GET", 
			url: $scope.urlsRolesGetPrivilegesRolesNg
			  })
		.success(function(response){
			console.log(" privileges roles => ",response);
			if (response.result)
			{
				$scope.roles = $scope.roles.concat(response.result);
			}
		})
	};
	
	(function getRoles(){
		var user = JSON.parse(USER);
		
		_.each(user.roles, function(role){
			if (role.role == "ROLE_ADMIN")
			{
				$scope.isAdmin = true;
			}
		});
		
		if ($scope.isAdmin)
			getPrivilegesRoles();
		
		getSimpleRoles();
		getClientRoles();
	})();
	
	function getUsers()
	{
		var offset = $scope.itemsPerPage*($scope.currentPage - 1);
		var limit = $scope.itemsPerPage;
		$http({
			method: "GET", 
			url: $scope.urlsUsersGetNg,
			params: {offset: offset, limit: limit, search: $scope.search}
			  })
		.success(function(response){
			console.log("users => ",response);
			if (response.result)
			{
				$scope.users = response.result;
			}
		})
	}
	
	function getUsersCount()
	{
		$http({
			method: "GET", 
			url: $scope.urlsUsersCountNg,
			params: {search: $scope.search}
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				$scope.totalItems = parseInt(response.result);
				getUsers();
			}
		})
	}
	getUsersCount();
	
	$scope.addUser = function(){
		var modalInstance = $modal.open({
		      templateUrl: 'addUser.html',
		      controller: 'AddUserController',
		      backdrop: "static",
		      resolve: {
		    	  roles: function(){return $scope.roles;}
		      }
		});
		
		modalInstance.result.then(function (added) {
		      if (added) getUsersCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.editUser = function(user){
		var modalInstance = $modal.open({
		      templateUrl: 'editUser.html',
		      controller: 'EditUserController',
		      backdrop: "static",
		      resolve: {
		    	  user: function(){return user;},
		    	  roles: function(){return $scope.roles;}
		      }
		});
		
		modalInstance.result.then(function (edited) {
		      if (edited) getUsersCount();
		    }, function () {
		      //bad
		});
	}
	
	$scope.removeUser = function(user){
		var remove = confirm("Дійсно бажаєте видалити: " + user.username + " ?");
		if (!remove) return;
		
		var url = $scope.urlsUsersRemoveNg.replace('0', user.id);
		
		$http({
			method: "GET", 
			url: url
			  })
		.success(function(response){
			console.log(response);
			if (response.result)
			{
				getUsersCount();
			}
		});
	}
	
	$scope.setItemDirectHref = function(user){
		var href = $scope.urlsUsersShowUser.replace('0', user.id);
		user.href = href;
	}
})
.controller('AddUserController', function($scope,$http,$modalInstance, roles){
	console.log('AddUserController loaded!');
	$scope.urlsUsersAddNg = URLS.usersAddNg;
	$scope.urlsUsersCheckUsernameNg = URLS.usersCheckUsernameNg;
	$scope.urlsUsersCheckEmailNg = URLS.usersCheckEmailNg;
	$scope.roles = roles;
	
	$scope.checkUsernameHandler = null;
	$scope.checkEmailHandler = null;
	
	$scope.checkUserUsername = function(){
		clearTimeout($scope.checkUsernameHandler);
		if ($scope.user.username){
			$scope.checkUsernameHandler = setTimeout(function(){
				$http({
					method: "GET", 
					url: $scope.urlsUsersCheckUsernameNg,
					params: {username: $scope.user.username}
					  })
				.success(function(response){
					$scope.userForm.username.$error.existed = response.result;
				})
			}, 500);
		}
	}
	
	$scope.checkUserEmail = function(){
		clearTimeout($scope.checkEmailHandler);
		if ($scope.user.email){
			$scope.checkEmailHandler = setTimeout(function(){
				$http({
					method: "GET", 
					url: $scope.urlsUsersCheckEmailNg,
					params: {email: $scope.user.email}
					  })
				.success(function(response){
					$scope.userForm.email.$error.existed = response.result;
				})
			}, 500);
		}
	}
	
	function addUser(){
		$http({
			method: "POST", 
			url: $scope.urlsUsersAddNg,
			data: $scope.user
			  })
		.success(function(response){
			console.log("added user => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	$scope.checkPassword = function () {
	    $scope.userForm.password2.$error.dontMatch = $scope.user.password !== $scope.user.password2;
	};
	
	$scope.ok = function () {
		
		if (!$scope.userForm.$valid) return;
		
		addUser();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
})
.controller('EditUserController', function($scope,$http,$modalInstance, user, roles){
	console.log('EditUserController loaded!');
	$scope.urlsUsersEditNg = URLS.usersEditNg;
	$scope.urlsUsersCheckUsernameNg = URLS.usersCheckUsernameNg;
	$scope.urlsUsersCheckEmailNg = URLS.usersCheckEmailNg;
	
	$scope.user = user;
	$scope.user.rolesIds = _.map(user.roles, function(role){return role.id;});
	$scope.roles = roles;
	
	$scope.checkUsernameHandler = null;
	$scope.checkEmailHandler = null;
	
	$scope.checkUserUsername = function(){
		clearTimeout($scope.checkUsernameHandler);
		if ($scope.user.username){
			$scope.checkUsernameHandler = setTimeout(function(){
				$http({
					method: "GET", 
					url: $scope.urlsUsersCheckUsernameNg,
					params: {username: $scope.user.username, id: $scope.user.id}
					  })
				.success(function(response){
					$scope.userForm.username.$error.existed = response.result;
				})
			}, 500);
		}
	}
	
	$scope.checkUserEmail = function(){
		clearTimeout($scope.checkEmailHandler);
		if ($scope.user.email){
			$scope.checkEmailHandler = setTimeout(function(){
				$http({
					method: "GET", 
					url: $scope.urlsUsersCheckEmailNg,
					params: {email: $scope.user.email, id: $scope.user.id}
					  })
				.success(function(response){
					$scope.userForm.email.$error.existed = response.result;
				})
			}, 500);
		}
	}
	
	$scope.checkPassword = function () {
		if (($scope.user.password !== undefined)&&($scope.user.password.trim() == ""))
		{
			delete $scope.user.password;
		}
		
		if (($scope.user.password2 !== undefined)&&($scope.user.password2.trim() == ""))
		{
			delete $scope.user.password2;
		}
		
	    $scope.userForm.password2.$error.dontMatch = $scope.user.password !== $scope.user.password2;
	};
	
	function editUser(){
		var url = $scope.urlsUsersEditNg.replace('0', $scope.user.id);
		
		$http({
			method: "POST", 
			url: url,
			data: $scope.user
			  })
		.success(function(response){
			console.log("edited user => ", response);
			if (response.result)
			{
				$modalInstance.close(response.result);
			}
		})
	}
	
	function isValid(){
		if (!$scope.userForm.$valid) return false;
		
		return true;
	}
	
	$scope.ok = function () {
		if (!isValid()) return;
		
		editUser();
	};

	$scope.cancel = function () {
	    $modalInstance.dismiss('cancel');
	};
	
	$scope.hasRole = function(id){
		var hasRole = _.find($scope.user.rolesIds, function(role_id){
			return role_id == id;
		});
		
		return hasRole;
	}
});