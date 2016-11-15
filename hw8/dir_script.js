

var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);

function MyController($scope,$http) {

  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.people_data= [];


    var php_path = "backend/index.php?";
     $http.get("backend/index.php?category=legistlator&chamber=house&keyword=CA")
    .then(function(response) {
        $scope.people_data = response.data.results;
        console.log($scope.people_data);

                for (var i = 0; i <= response.data.count; i++) {
                  $scope.people_data[i].party = $scope.people_data[i].party.toLowerCase();

                  if($scope.people_data[i].chamber =="house"){
                    $scope.people_data[i].chamber = "h.png";
                  }else{
                    $scope.people_data[i].chamber = "s.svg";
                  }

                  if($scope.people_data[i].district == " "){
                    $scope.people_data[i].district = "N.A";
                  }else{
                    $scope.people_data[i].district =  "District"+$scope.people_data[i].district;
                  }

                }
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