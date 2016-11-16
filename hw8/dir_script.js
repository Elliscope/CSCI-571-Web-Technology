

var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);

function SortByStateController($scope,$http) {

  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.people_data= [];
  $scope.bio_basic = [];
  $scope.bio_bills = [];
  $scope.bio_com = [];

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
        
        updateBioBasicInfo($scope.bio_basic);
        $("#myCarousel").carousel("next");
    });

  //get the bills that the legistlator sponsors
  $http.get("backend/index.php?category=bills&bioguide="+bioguide)
    .then(function(response) {
        console.log("HERE IS IS THE BILLS SEARCH FUNCTION");
        $scope.bio_bills = response.data.results;
        console.log($scope.bio_bills);
        updateBillInfo($scope.bio_bills);
    });


  //get the committee that the legistlator belongs to
    $http.get("backend/index.php?category=committees&bioguide="+bioguide)
    .then(function(response) {
        console.log("HERE IS IS THE COMMITTEES SEARCH FUNCTION");
        $scope.bio_com = response.data.results;
        updateCommitteeInfo($scope.bio_com);
        console.log($scope.bio_com);

    });


  };

}

function SortByStatePageController($scope) {
  $scope.pageChangeHandler = function(num) {
    console.log('going to page ' + num);
  };
}

function updateBioBasicInfo(bio_basic){
          //javscript to update the html value
        bio = bio_basic[0];

        $("#pro_img").attr('src','https://theunitedstates.io/images/congress/original/'+bio.bioguide_id+'.jpg');

        $("#pro_name").html(bio.title + " " + bio.first_name + " "+ bio.last_name);
        $("#pro_email").html(bio.oc_email);
        $("#pro_chamber").html(bio.chamber);
        $("#pro_contact").html(bio.phone);
        if(bio.party=="R"){
          $("#pro_party").html("Repblic");
        }else{
          $("#pro_party").html("Democrat");
        }
        $("#pro_party_img").attr('src','http://cs-server.usc.edu:45678/hw/hw8/images/'+bio.party.toLowerCase()+'.png');
        
        $("#st_val").html(bio.term_start);
        $("#ed_val").html(bio.term_start);
        $("#").html(bio.term_end);
        $("#of").html(bio.office);
        $("#sta_val").html(bio.state_name);
        $("#fa_val").html(bio.fax);
        $("#bir_val").html(bio.birthday);
}


function updateCommitteeInfo(bio_com) {
  $("#personal_com td").remove();
      for(var i=0 ; i< 5 ;i++){
        console.log(i);
         var data_row = "<tbody><tr>";
         var data_chamber = "<td>"+bio_com[i].chamber+"</td>";
         var data_com_id = "<td>"+bio_com[i].committee_id+"</td>";
         var data_name = "<td>"+ bio_com[i].name+"</td>";
         var table_wrapper = data_row+data_chamber+data_com_id+data_name+"</tr></tbody>";
         $('#personal_com').append(table_wrapper);
      }

}

function updateBillInfo(bio_bills){
  $("#personal_bills td").remove();
     for(var i=0 ; i< 5 ;i++){
           var data_row = "<tbody><tr>";
           var data_bill_id = "<td>"+bio_bills[i].bill_id+"</td>";
           var data_title = "<td>"+bio_bills[i].official_title+"</td>";
           var data_chamber = "<td>"+ bio_bills[i].chamber+"</td>";
           var data_congress = "<td>"+ bio_bills[i].congress+"</td>";
           var data_link = "<td><a href='"+ bio_bills[i].urls.congress+"'>Link</a></td>";
           var table_wrapper = data_row+data_bill_id+data_title+data_chamber+data_congress+data_link+"</tr></tbody>";
           $('#personal_bills').append(table_wrapper);
        }
}


myApp.controller('SortByStateController', SortByStateController);
myApp.controller('SortByStatePageController', SortByStatePageController);