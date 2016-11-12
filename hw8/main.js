

var category;
var chamber;
var keyword;



$(document).ready(function(){
   console.log("Here inside the document.ready function");

   loadingByStateTable(chamber,keyword);
 });

function loadingByStateTable(chamber,keyword){

  var php_path = "backend/index.php?";
    $.ajax({ 
      url: php_path,
      data: {category: "legistlator",chamber: "house",keyword: "CA", }
    })
      .done(function( data ) {
            var doc = eval('(' + data + ')');
            $.each( doc.results, function( i, item ) {
                var data_row = "<tbody><tr>";
                var party = "<td><img width='30' height='30' src = http://cs-server.usc.edu:45678/hw/hw8/images/";

                if(item.party == "R"){
                    party = party + "r.png></td>";
                }else{
                    party = party + "d.png></td>";
                }
                
                var name = "<td>"+item.first_name+ " "+ item.last_name+"</td>";

                var chamber = "<td><img width='30' height='30' src = http://cs-server.usc.edu:45678/hw/hw8/images/";
                if(item.chamber == "house"){
                    chamber = chamber + "h.png>"+capitalizeFirstLetter(item.chamber)+"</td>";
                }else if(item.chamber == "senate"){
                    chamber = chamber + "s.png>"+capitalizeFirstLetter(item.chamber)+"</td>";
                }
                var district;
                if(item.district == " "){
                    district = "<td>N.A</td>";
                }else{
                    district =  "<td>District "+item.district+"</td>";
                }
                var state  = "<td>"+item.state_name+"</td>";
                var viewDetails = "<td><button type='button' class='btn btn-primary'>View Details</button></td></tr>";

                var html = data_row + party + name + chamber + district + state + viewDetails;
                $('#table_by_state').append(html);
                
              });
                // var table_wrapper = "</table>";
                // $('#table_by_state').append(table_wrapper);
        });
}


//helper function
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
