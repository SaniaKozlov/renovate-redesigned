Renovate.controller('ProjectsController', function($scope,$http,$modal){

	$scope.projects = [];
	$scope.projectsAvailable = [];
	$scope.workers = [];

	$scope.totalItems = 0;
	$scope.currentPage = 1;
	$scope.itemsPerPage = 10;

	var $calendar = $('#calendar');

	$scope.filterHandler = null;

	$scope.filter = {
		offset: null,
		limit: null
	};

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

	var times = [
		'07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'
	];

	$scope.times = times;

	$scope.urlsProjectsNg = URLS.projectsNg;
	$scope.urlsProjectsCountNg = URLS.projectsCountNg;
	$scope.urlsProjectsRemoveNg = URLS.projectsRemoveNg;
	$scope.urlsProjectsReportFull = URLS.projectsReportFull;
	$scope.urlsProjectsReport = URLS.projectsReport;
	$scope.urlsUsersGetWorkersNg = URLS.usersGetWorkersNg;
	$scope.urlsEventsNg = URLS.eventsNg;
	$scope.urlsEventsEditNg = URLS.eventsEditNg;


	function Initialize(){
		var user = JSON.parse(USER);

		_.each(user.roles, function(role){
			if (role.role == "ROLE_ADMIN")
			{
				$scope.isAdmin = true;
			}
		});

		if ($scope.isAdmin){
			getProjectsAvailable();
			getWorkers();
			initFullCalendar();
		}

		$scope.$watch('itemsPerPage', function(){
			console.log("itemsPerPage => ", $scope.itemsPerPage);
			getProjectsCount();
		});

		$scope.$watch('currentPage', function(){
			console.log("currentPage => ", $scope.currentPage);
			getProjects();
		});

		$scope.$watch('filter', function(){
			clearTimeout($scope.filterHandler);
			$scope.filterHandler = setTimeout(function(){
				console.log("filter => ", $scope.filter);
				getProjectsCount();
			}, 500);
		}, true);

		getProjectsCount();
	}
	Initialize();

	function generateProjectReportFullHref(id){
		return $scope.urlsProjectsReportFull.replace('0', id);
	}

	function generateProjectReportHref(id){
		return $scope.urlsProjectsReport.replace('0', id);
	}

	function getProjectsAvailable(){
		$http({
			method: "GET",
			url: $scope.urlsProjectsNg,
			params: {finished: 0}
		})
			.success(function(response){
				console.log(" projects available => ",response);
				if (response.result)
				{
					$scope.projectsAvailable = response.result;
				}
			})
	};

	function getWorkers(){
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
	};

	function getProjects() {
		$scope.filter.offset = $scope.itemsPerPage*($scope.currentPage - 1);
		$scope.filter.limit = $scope.itemsPerPage;
		$http({
			method: "GET",
			url: $scope.urlsProjectsNg,
			params: $scope.filter
		})
			.success(function(response){
				console.log(" projects => ",response);
				if (response.result)
				{
					_.map(response.result, function(project){
						project.reportFull = generateProjectReportFullHref(project.id);
						project.report = generateProjectReportHref(project.id);
					});
					$scope.projects = response.result;
				}
			})
	}

	function getProjectsCount() {
		$http.get($scope.urlsProjectsCountNg, $scope.filter)
			.success(function(response){
				console.log(response);
				if (response.result)
				{
					$scope.totalItems = parseInt(response.result);
					getProjects();
				}
			})
	}

	$scope.addProject = function(){
		var modalInstance = $modal.open({
			templateUrl: 'addProject.html',
			controller: 'AddProjectController',
			backdrop: "static",
			size: 'lg'
		});

		modalInstance.result.then(function (added) {
			if (added) {
				showAlert();
				getProjectsCount();
				getProjectsAvailable();
			}
		}, function () {
			//bad
		});
	}

	$scope.editProject = function(project){
		var modalInstance = $modal.open({
			templateUrl: 'editProject.html',
			controller: 'EditProjectController',
			backdrop: "static",
			size: 'lg',
			resolve: {
				project: function(){return project;}
			}
		});

		modalInstance.result.then(function (edited) {
			if (edited) {
				showAlert();
				getProjectsCount();
				getProjectsAvailable();
				var view = $calendar.fullCalendar('getView');
				getEvents(view.start.format(),view.end.format());
			}
		}, function () {
			//bad
		});
	}

	$scope.removeProject = function(project){
		var remove = confirm("Дійсно бажаєте видалити: " + project.name + " ?");
		if (!remove) return;

		var url = $scope.urlsProjectsRemoveNg.replace('0', project.id);

		$http({
			method: "GET",
			url: url
		})
			.success(function(response){
				console.log(response);
				if (response.result)
				{
					showAlert();
					getProjectsCount();
					getProjectsAvailable();
					var view = $calendar.fullCalendar('getView');
					getEvents(view.start.format(),view.end.format());
				}
			});
	}

	//---Events
	function getEvents(from, to) {
		$http({
			method: "GET",
			url: $scope.urlsEventsNg,
			params: {from: from, to: to}
		})
			.success(function(response){
				console.log(" events => ",response);
				if (response.result)
				{
					$calendar.fullCalendar('removeEvents');
					$calendar.fullCalendar('addEventSource', response.result);
				}
			})
	}

	function editEvent(eventFC){
		var event = {
			id: eventFC.id,
			userId: eventFC.userId,
			projectId: eventFC.projectId,
			start: eventFC.start.format(),
			end: eventFC.end.format()
		}

		var url = $scope.urlsEventsEditNg.replace('0', event.id);

		$http({
			method: "POST",
			url: url,
			data: event
		})
			.success(function(response){
				console.log("edited event => ", response);
				if (response.result)
				{
					showAlert();
				}
			})
	}

	function showAlert(){
		$("#infoAlert").fadeIn(2000, function(){$("#infoAlert").fadeOut(2000);});
	};

	function initFullCalendar(){
		$calendar.fullCalendar({
			eventLimit: 6,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			allDaySlot: false,
			dayClick: function(date, jsEvent, view) {
				if (!confirm("Дійсно бажаєте додати подію ?")) return false;

				var eventDate = date.format("YYYY-MM-DD");
				var eventTime = date.format("HH:mm");

				var modalInstance = $modal.open({
					templateUrl: 'addEvent.html',
					controller: 'AddEventController',
					backdrop: "static",
					size: 'lg',
					resolve: {
						eventDate: function(){return eventDate;},
						eventTime: function(){return eventTime;},
						projects: function(){return $scope.projectsAvailable;},
						workers: function(){return $scope.workers;},
						times: function(){return times;},
					}
				});

				modalInstance.result.then(function (added) {
					if (added) {
						showAlert();
						getEvents(view.start.format(),view.end.format());
					}
				}, function () {
					//bad
				});
			},
			eventClick: function(calEvent, jsEvent, view) {
				if (!calEvent.editable) return false;

				var modalInstance = $modal.open({
					templateUrl: 'editEvent.html',
					controller: 'EditEventController',
					backdrop: "static",
					size: 'lg',
					resolve: {
						event: function(){return calEvent;},
						projects: function(){return $scope.projectsAvailable;},
						workers: function(){return $scope.workers;},
						times: function(){return times;},
					}
				});

				modalInstance.result.then(function (edited) {
					if (edited) {
						showAlert();
						getEvents(view.start.format(),view.end.format());
					}
				}, function () {
					//bad
				});
			},
			eventDrop: function(event){
				editEvent(event);
			},
			eventResize: function(event){
				editEvent(event);
			},
			viewRender: function(view, element){
				if (view.name != 'month'){
					$calendar.fullCalendar('option', 'height', 'auto');
				}else{
					$calendar.fullCalendar('option', 'height', '0');
				}
				getEvents(view.start.format(),view.end.format());
			}
		});
	}

	console.log('ProjectsController loaded!');
})
	.controller('AddProjectController', function($scope,$http,$modalInstance){
		console.log('AddProjectController loaded!');
		$scope.urlsProjectsAddNg = URLS.projectsAddNg;

		function addProject(){
			$http({
				method: "POST",
				url: $scope.urlsProjectsAddNg,
				data: $scope.project
			})
				.success(function(response){
					console.log("added project => ", response);
					if (response.result)
					{
						$modalInstance.close(response.result);
					}
				})
		}

		$scope.ok = function () {
			if (!$scope.projectForm.$valid) return;
			addProject();
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	})
	.controller('EditProjectController', function($scope,$http,$modalInstance,project){
		console.log('EditProjectController loaded!');
		$scope.urlsProjectsEditNg = URLS.projectsEditNg;
		$scope.project = project;

		function editProject(){
			var url = $scope.urlsProjectsEditNg.replace('0', $scope.project.id);

			$http({
				method: "POST",
				url: url,
				data: $scope.project
			})
				.success(function(response){
					console.log("edited project => ", response);
					if (response.result)
					{
						$modalInstance.close(response.result);
					}
				})
		}

		$scope.ok = function () {
			if (!$scope.projectForm.$valid) return;
			editProject();
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	})
	.controller('AddEventController', function($scope,$http,$modalInstance, eventDate, eventTime, projects, workers, times){
		console.log('AddEventController loaded!');
		$scope.urlsEventsAddNg = URLS.eventsAddNg;

		$scope.projects = projects;
		$scope.workers = workers;

		$scope.times = times;

		$scope.event ={
			eventStart: eventTime,
			eventEnd: $scope.times[$scope.times.indexOf(eventTime)+1]
		};

		function addEvent(){
			$scope.event.start = eventDate + ' ' + $scope.event.eventStart;
			$scope.event.end = eventDate + ' ' + $scope.event.eventEnd;

			$http({
				method: "POST",
				url: $scope.urlsEventsAddNg,
				data: $scope.event
			})
				.success(function(response){
					console.log("added event => ", response);
					if (response.result)
					{
						$modalInstance.close(response.result);
					}
				})
		}

		$scope.ok = function () {
			if (!$scope.eventForm.$valid) return;
			addEvent();
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	})
	.controller('EditEventController', function($scope,$http,$modalInstance, event, projects, workers, times){
		console.log('EditEventController loaded!');
		$scope.urlsEventsEditNg = URLS.eventsEditNg;
		$scope.urlsEventsRemoveNg = URLS.eventsRemoveNg;

		$scope.projects = projects;
		$scope.workers = workers;
		$scope.times = times;

		$scope.event = {
			userId: event.userId,
			projectId: event.projectId,
			eventStart: event.start.format("HH:mm"),
			eventEnd: event.end.format("HH:mm")
		};

		function editEvent(){
			$scope.event.start = event.start.format("YYYY-MM-DD") + ' ' + $scope.event.eventStart;
			$scope.event.end = event.end.format("YYYY-MM-DD") + ' ' + $scope.event.eventEnd;

			var url = $scope.urlsEventsEditNg.replace('0', event.id);

			$http({
				method: "POST",
				url: url,
				data: $scope.event
			})
				.success(function(response){
					console.log("edited event => ", response);
					if (response.result)
					{
						$modalInstance.close(response.result);
					}
				})
		}

		$scope.ok = function () {
			if (!$scope.eventForm.$valid) return;
			editEvent();
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};

		$scope.remove = function () {
			if (!confirm("Дійсно бажаєте видалити подію: " + event.title + " ?")) return false;

			var url = $scope.urlsEventsRemoveNg.replace('0', event.id);

			$http({
				method: "GET",
				url: url
			})
				.success(function(response){
					console.log(response);
					if (response.result)
					{
						$modalInstance.close(response.result);
					}
				});
		};
	});