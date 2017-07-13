var syn = angular.module("syn", []);
syn.controller("modulec", function ($scope) {
	$scope.aa = "測試一下";
	$scope.uInput="123";
	//$scope.first_controller= uInput;
	//myservice.variablename = $scope.first_controller;
	//$scope.second_controller= myservice.variablename;
});

/*
angular.module("test", [])
  .controller('Test1', Test1)
  .controller('Test2', Test2)
  .factory('Data', Data);

function Test1($scope, Data) {
  $scope.lastname = '';

  $scope.$watch('lastname', function(newValue2, oldValue2) {
    if (newValue2 !== oldValue2)
      Data.setLastName(newValue2);
  });
}

function Test2($scope, Data) {
  $scope.$watch(function() {
    return Data.getLastName();
  }, function(newValue2, oldValue2) {
    if (newValue2 !== oldValue2)
      $scope.lastname = newValue2;
  });
}

function Data() {
  var data = {
    LastName: '',
  }

  return {
    getLastName: function() {
      return data.LastName;
    },
    setLastName: function(lastname) {
      data.LastName = lastname;
    }
  }
}
*/