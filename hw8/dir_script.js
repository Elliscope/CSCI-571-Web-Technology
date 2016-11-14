// Code goes here

var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);

function MyController($scope) {

  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.people_data= [];

    var php_path = "backend/index.php?";
    $.ajax({
      url: php_path,
      data: {category: "legistlator",chamber: "house",keyword: "CA", }
    })
      .done(function( data ) {
            var doc = eval('(' + data + ')');
            $scope.people_data = doc.results;
              console.log($scope.people_data);
        });

  //replace the meals item with the data object

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