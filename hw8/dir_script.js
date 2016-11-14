

var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);

function MyController($scope,$http) {

  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.people_data= [];

    var php_path = "backend/index.php?";
     $http.get("backend/index.php?category=legistlator&chamber=house&keyword=CA")
    .then(function(response) {
        $scope.people_data = response.data.results;
    });

  $scope.pageChangeHandler = function(num) {
      console.log('list page changed to ' + num);
  };
}

function OtherController($scope) {
  $scope.pageChangeHandler = function(num) {
    console.log('going to page ' + num);
  };
}

myApp.controller('MyController', MyController);
myApp.controller('OtherController', OtherController);