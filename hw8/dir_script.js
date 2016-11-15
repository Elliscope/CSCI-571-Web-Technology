

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

  $scope.viewDetails = function(bioguide,chamber,state){
    console.log("HERE INSDIE THE VIEW DETAILS FUNCTION");
    console.log(bioguide);
    console.log(chamber);
    console.log(state);

    //have the bioguide value. Call three function to get the info separately.

    //reget the personal info: 
     $http.get("backend/index.php?category=legistlator&chamber="+chamber.toLowerCase()+"&keyword="+state+"&bioguide="+bioguide)
    .then(function(response) {
        console.log("HERE IS THE BIOGUIDE SEARCH FUNCTION");
        $scope.bio_basic = response.data.results;
        console.log($scope.bio_basic);
                for (var i = 0; i <= response.data.count; i++) {
                  $scope.bio_basic[i].party = $scope.bio_basic[i].party.toLowerCase();

                  if($scope.bio_basic[i].chamber =="house"){
                    $scope.bio_basic[i].chamber_img = "h.png";
                    $scope.bio_basic[i].chamber = "House";
                  }else{
                    $scope.bio_basic[i].chamber_img = "s.svg";
                    $scope.bio_basic[i].chamber = "Senate";
                  }
                  if($scope.bio_basic[i].district == null){
                    $scope.bio_basic[i].district_name = "N.A";
                  }else{
                    $scope.bio_basic[i].district_name =  "District "+$scope.bio_basic[i].district;
                  }
                }
    }); 

  //get the bills that the legistlator sponsors
  $http.get("backend/index.php?category=bills&bioguide="+bioguide)
    .then(function(response) {
        console.log("HERE IS IS THE BILLS SEARCH FUNCTION");
        $scope.bio_bills = response.data.results;
        console.log($scope.bio_bills);
    }); 


  //get the committee that the legistlator belongs to
    // $http.get("backend/index.php?category=bills&bioguide="+bioguide)
    // .then(function(response) {
    //     console.log("HERE IS IS THE BILLS SEARCH FUNCTION");
    //     $scope.bio_bills = response.data.results;
    //     console.log($scope.bio_bills);
    // }); 
  };

}

function SortByStatePageController($scope) {
  $scope.pageChangeHandler = function(num) {
    console.log('going to page ' + num);
  };
}




myApp.controller('SortByStateController', SortByStateController);
myApp.controller('SortByStatePageController', SortByStatePageController);