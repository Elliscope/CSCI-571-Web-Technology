

var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);

function SortByStateController($scope,$http) {

  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.people_data= [];


    var php_path = "backend/index.php?";
     $http.get("backend/index.php?category=legistlator&chamber=&keyword=")
    .then(function(response) {
        $scope.people_data = response.data.results;
        console.log($scope.people_data);

                for (var i = 0; i <= response.data.count; i++) {
                  $scope.people_data[i].party = $scope.people_data[i].party.toLowerCase();

                  if($scope.people_data[i].chamber =="house"){
                    $scope.people_data[i].chamber_img = "h.png";
                    $scope.people_data[i].chamber = "House";
                  }else{
                    $scope.people_data[i].chamber_img = "s.svg";
                    $scope.people_data[i].chamber = "Senate";
                  }

                  if($scope.people_data[i].district == null){
                    $scope.people_data[i].district_name = "N.A";
                  }else{
                    $scope.people_data[i].district_name =  "District "+$scope.people_data[i].district;
                  }

                }
    });



  $scope.pageChangeHandler = function(num) {
      console.log('list page changed to ' + num);
  };

}

function SortByStatePageController($scope) {
  $scope.pageChangeHandler = function(num) {
    console.log('going to page ' + num);
  };
}

myApp.controller('SortByStateController', SortByStateController);
myApp.controller('SortByStatePageController', SortByStatePageController);