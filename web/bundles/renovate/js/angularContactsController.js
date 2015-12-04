Renovate.controller('ContactsController', function($scope,$http){
	console.log('ContactsController loaded!');

	$scope.urlsContactUsNg = URLS.contactUsNg;
	$scope.alerts = [];
	$scope.message = {
		topic: "Подяка"
	};

	$scope.sendButtonDisabled = false;


	$scope.urlsJobsShowJob = URLS.jobsShowJob;

    $scope.hiddenBodyScroll = false;

    $scope.$on('PanelChanged', function (event, state) {
        $scope.hiddenBodyScroll = state;
    });

	$scope.modelJob = [];

	$scope.leftCounter = 0;
	$scope.rightCounter = 1;

	$scope.jobLength = 0;

    function slaiderHeight () {
        var slaider = angular.element(document.querySelector('#proposition'));
        var main = angular.element(document.querySelector('.bg'));
        if (slaider.css("height") > 370) {
            main.height(550);
        } else {
            main.height(580);
        }
    }

    $scope.initSlaiderHeight = function () {
        setTimeout(function () {
            var slaider = angular.element(document.querySelector('#proposition'));
            var main = angular.element(document.querySelector('.bg'));
            if (slaider.outerHeight() > 370) {
                main.height(550);
            } else {
                main.height(520);
            }
        }, 100);
    };

    $scope.filterJobName = function (name) {
        var arr = name.split(' ');
        var nameReturn = '';
        for (var i in arr) {
            if ((arr[i].length + nameReturn.length + 1) <= 30) {
                nameReturn = nameReturn.length != 0 ? nameReturn + ' ' + arr[i] : arr[i];
            } else {
                nameReturn = nameReturn + '...';
                break;
            }
        }

        return nameReturn;
    };

    $scope.$watch('jobLength', function (newValue) {
        if ($scope.jobLength != 0) {
            $scope.centerCounter = getRandomNumber(0, newValue);
        }
    });

	$scope.initJobs = function (jobName, jobDescription, jobSrc, jobNameTranslate) {
		var temp = {};

		temp.jobName = jobName;
		temp.jobDescription = jobDescription;
		temp.jobSrc = jobSrc;
		temp.jobNameTranslate = jobNameTranslate;

		temp.jobHref = $scope.urlsJobsShowJob.replace('0', jobNameTranslate);

		$scope.modelJob.push(temp);
	};

	$scope.clickLeftBlock = function () {
		$scope.leftCounter = $scope.centerCounter;
		$scope.centerCounter = $scope.rightCounter;
		($scope.rightCounter < $scope.jobLength) ? $scope.rightCounter++ : $scope.rightCounter = 0;
	};

	$scope.clickRightBlock = function () {
		$scope.rightCounter = $scope.centerCounter;
		$scope.centerCounter = $scope.leftCounter;
		($scope.leftCounter > 0) ? $scope.leftCounter-- : $scope.leftCounter = $scope.jobLength;
    };

	function showAlert()
	{
		$scope.alerts.push({msg: 'Ваш лист успішно надіслано!', type: 'success'});
	}

	$scope.contactUs = function()
	{
		$scope.sendButtonDisabled = true;
		$http({
			method: "POST",
			url: $scope.urlsContactUsNg,
			data: $scope.message
		})
			.success(function(response){
				console.log(response);
				if (response.result)
				{
					showAlert();
				}
			})
	};

	$scope.closeAlert = function(index) {
		$scope.alerts.splice(index, 1);
		$scope.sendButtonDisabled = false;
	};

    function getRandomNumber (min, max) {
        return ~~(Math.random() * (max - min) + min);
    }


});