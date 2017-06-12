<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Congress Information Search</title>
    <style type="text/css">
        #output {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 50px;
        }
        
        #search {
            width: 380px;
            border: 2px solid black;
            margin: auto;
            padding: 20px;
        }
        
        h2 {
            margin: 2px;
        }

    </style>


</head>

<body>

    <h1 align="center">Congress Information Search</h1>
    <div id="search" align="center">
        <form name="congressSearchForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <table>
                <tr>
                    <td>Congress Database</td>
                    <td>
                        <select name="sel" id="sel" <?php if (isset($_GET['sel'])) echo 'value="'.$_GET['sel'].'"';?> onchange="updateSearchAttribute()" class="selectOption">
                        	<option name="emptySel" value= "emptySel" selected disabled>Choose the below</option>
							<option name="legislators" value="legislators" <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="legislators")? 'selected': ''; ?>>Legislators</option>
							<option name="committees" value="committees"  <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="committees")? 'selected': ''; ?>>Committees</option>
							<option name="bills" value="bills"  <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="bills")? 'selected': ''; ?>>Bills</option>
							<option name="amendments" value="amendments" <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="amendments")? 'selected': ''; ?>>Amendments</option>
						</select>
                    </td>
                </tr>
                <tr>
                    <td>Chamber</td>
                    <td>
                        <input type="radio" name="chamber" value="senate" <?php echo (isset($_GET[ "chamber"])&&$_GET[ "chamber"]=="senate")? 'checked': ''; ?>>Senate</input>
                        <input type="radio" name="chamber" value="house" <?php echo (isset($_GET[ "chamber"])&& $_GET[ "chamber"]=="house" )? 'checked': ''; ?>>House</input>
                    </td>
                </tr>
                <tr>
                    <td id="search-key-caption" name="keyword_caption" >Keyword*</td>
                    <td><input id="keyword" name="keyword" type="text" value="<?php echo isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : ''; ?>"></input>
                    </td>
                    <td width="80px"></td>
                </tr>
                <tr>
                	<td></td>
                    <td>
                        <input type="submit" name="submit" value="search" onclick="validate()"></input>
                        <button type="button" name="clear" value="clear" onclick="clearForm()">Clear</button>
                    </td>
                </tr>
            </table>
        </form>
        <a id="bottom-caption" href="http://sunlightfoundation.com/">Powered by Sunlight Foundation</a>
    </div>


    <script type="text/javascript">

        function clearForm() {
            document.congressSearchForm.sel.value = "";
            document.congressSearchForm.keyword.value = "";
            document.congressSearchForm.chamber.value = "";
			document.getElementById("output").remove();
        }

        function updateSearchAttribute() {
            var sel = document.getElementById('sel');
            var opts = sel.options[sel.selectedIndex];

            if (opts.value == "legislators") {
                document.getElementById("search-key-caption").innerHTML = "State/Representative*";
            } else if (opts.value == "committees") {
                document.getElementById("search-key-caption").innerHTML = "Committee ID*";
            } else if (opts.value == "bills") {
                document.getElementById("search-key-caption").innerHTML = "Bill ID*";
            } else if (opts.value == "amendments") {
                document.getElementById("search-key-caption").innerHTML = "Amendment ID*";
            }
        }

        function validate() {
        	console.log("inside the validate");
        	var congressDatabase = "";
        	var keyword = "" ;
        	var chamber = "chamber";

        	var e = document.getElementById("sel");
			var selection = e.options[e.selectedIndex].value;


        	if ( selection == 'emptySel' ){
        		console.log("empty inside the ");
        		congressDatabase = "congressDatabase"
        	}

        	var chx = document.getElementsByTagName('input');
			  for (var i=0; i<chx.length; i++) {
			    if (chx[i].type == 'radio' && chx[i].checked) {
			      chamber = "";
			    } 
  			  }

  			var key_word = document.getElementById('keyword').value;
  			if(key_word==""){
  				 keyword = "keyword";
  			}

  			if(congressDatabase!="" || keyword!="" || chamber!=""){
  				alert("Please enter the following missing Information: "+ congressDatabase+" "+chamber+" "+keyword);
  			}
        }

</script>

<?php
	error_reporting(0);
	global $a ;
if ($_SERVER["REQUEST_METHOD"] == "GET") {

	//Validation Failed

	if (isset($_GET['chamber'])) {
		$chamber = $_GET['chamber'];
	}
	
	if (isset($_GET['keyword'])) {
		$keyword = $_GET['keyword'];
	}
	
	if (isset($_GET['sel'])) {
		$selectOption = $_GET['sel'];
	}

	if (isset($_GET['viewDetails'])){


      $apikey = 'ad112f71df2e4109864ee87613db82d8';
	  $url = "http://congress.api.sunlightfoundation.com/legislators?chamber=".$_GET['chamber']."&state=".$_GET['state_detail']."&bioguide_id=".$_GET['viewDetails']."&apikey=".$apikey;
	  $res = file_get_contents($url);
	  $res = json_decode($res, true);

	  if($res["count"]==0){
		echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
		return;
	  }


	  foreach($res["results"] as $item) {

		echo "<div id='output' align='center' style='border: 2px solid black;'><h2>".$summary."</h2>";
	    echo "<img src='https://theunitedstates.io/images/congress/225x275/".$item["bioguide_id"].".jpg' >";
	   	echo "<table><tr><td width='240px'></td><td width='100px'></td></tr>";
	   	echo "<tr><td>Full Name</td><td>".$item["first_name"] ." ". $item["last_name"]."</td></tr>";
	   	echo "<tr><td>Term Ends on</td><td>".$item["term_end"]."</td></tr>";
	   	echo "<tr><td>Website</td><td>".$item["website"]."</td></tr>";
	    echo "<tr><td>Office</td><td>".$item["office"]."</td></tr>";

	    if($item["facebook_id"]!=""){
	    	echo "<tr><td>Facebook</td><td><a href='https://www.facebook.com/".$item["facebook_id"]."'>".$item["first_name"] ." ". $item["last_name"]." </a></td></tr>";
	    }else{
	    	echo "<tr><td>Facebook</td><td>N/A</td></tr>";
	    }
	   	

	    if($item["facebook_id"]!=""){
	    	echo "<tr><td>Twitter</td><td><a href='https://www.twitter.com/".$item["twitter_id"]."'>".$item["first_name"] ." ". $item["last_name"]." </a></td></tr>";
	    }else{
	    	echo "<tr><td>Twitter</td><td>N/A</td></tr>";
	    }
	   	
		}

	}

	if (isset($_GET['viewBills'])){

	  $apikey = 'ad112f71df2e4109864ee87613db82d8';
	  $url = "http://congress.api.sunlightfoundation.com/bills?bill_id=".$_GET['viewBills']."&chamber=".$bill_chamber."&apikey=".$apikey ;
	  $res = file_get_contents($url);
	  $res = json_decode($res, true);

	  if($res["count"]==0){
		echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
		return;
	  }

	  foreach($res["results"] as $item) {

		echo "<div id='output' align='center' style='border: 2px solid black;'><h2>".$summary."</h2>";
	   	echo "<table><tr><td width='240px'></td><td width='100px'></td></tr>";
	   	echo "<tr><td>Bill ID	</td><td>".$item["bill_id"]."</td></tr>";
	   	echo "<tr><td>Bill Title</td><td>".$item["short_title"]."</td></tr>";
	   	echo "<tr><td>Sponsor</td><td>".$item["sponsor"]["title"]." ".$item["sponsor"]["first_name"]." ".$item["sponsor"]["last_name"]."</td></tr>";
	    echo "<tr><td>Introduced On</td><td>".$item["introduced_on"]."</td></tr>";
	   	echo "<tr><td>Last Action with date</td><td>".$item["last_version"]["version_name"]." ".$item[last_action_at]."</td></tr>";
	   	echo "<tr><td>Bill URL</td><td><a href='".$item["last_version"]["urls"]["pdf"]."'>".$item["sponsor"]["title"]." </a></td></tr>";
		}
	}
	

   if(!isset($_GET['viewDetails'])&& !isset($_GET['viewBills'])){
	 if($selectOption == "legislators"){
		//LegislatorSearch
		$state_name = validateState($keyword);
		if($state_name!=-1){
			legislatorSearch($chamber,$state_name);
		}else{
			nameSearch($chamber,$keyword);
		}
	}else if($selectOption =="committees"){
		//committees
		CommitteesSearch($chamber,$keyword);
	}else if($selectOption == "bills"){
		//bills
		BillsSearch($chamber,$keyword);
	}else if($selectOption == "amendments"){
		//ammendments
		AmmendmentsSearch($chamber,$keyword);
	}else if($selectOption == ""){
		// echo "IT'S EMPTY";
	}
   }

 }

function validateState($keyword){


	$keyword = ucfirst(strtolower($keyword));

 	 $states = array (
            'AL'=>'Alabama',
            'AK'=>'Alaska',
            'AZ'=>'Arizona',
            'AR'=>'Arkansas',
            'CA'=>'California',
            'CO'=>'Colorado',
            'CT'=>'Connecticut',
            'DE'=>'Delaware',
            'DC'=>'District of columbia',
            'FL'=>'Florida',
            'GA'=>'Georgia',
            'HI'=>'Hawaii',
            'ID'=>'Idaho',
            'IL'=>'Illinois',
            'IN'=>'Indiana',
            'IA'=>'Iowa',
            'KS'=>'Kansas',
            'KY'=>'Kentucky',
            'LA'=>'Louisiana',
            'ME'=>'Maine',
            'MD'=>'Maryland',
            'MA'=>'Massachusetts',
            'MI'=>'Michigan',
            'MN'=>'Minnesota',
            'MS'=>'Mississippi',
            'MO'=>'Missouri',
            'MT'=>'Montana',
            'NE'=>'Nebraska',
            'NV'=>'Nevada',
            'NH'=>'New hampshire',
            'NJ'=>'New jersey',
            'NM'=>'New mexico',
            'NY'=>'New york',
            'NC'=>'North carolina',
            'ND'=>'North dakota',
            'OH'=>'Ohio',
            'OK'=>'Oklahoma',
            'OR'=>'Oregon',
            'PA'=>'Pennsylvania',
            'RI'=>'Rhode island',
            'SC'=>'South carolina',
            'SD'=>'South dakota',
            'TN'=>'Tennessee',
            'TX'=>'Texas',
            'UT'=>'Utah',
            'VT'=>'Vermont',
            'VA'=>'Virginia',
            'WA'=>'Washington',
            'WV'=>'West virginia',
            'WI'=>'Wisconsin',
            'WY'=>'Wyoming',
        );
	foreach ($states as $ss => $s) {
		if($keyword==$s){
			$keyword= $ss ;
			return $keyword;
		}
	}
	return -1;
}

function nameSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	$url_name = "http://congress.api.sunlightfoundation.com/legislators?chamber=house&query=".REPRESENTATIVE_NAME_HERE."&apikey=".$apikey;

	//String Split
	$pieces = explode(" ", (string)$keyword);
	$res = "";
	$result_item;

	if(count($pieces) == 1){

		if($pieces[0]==""){
			echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
			return;
		}

		$url_name = "http://congress.api.sunlightfoundation.com/legislators?chamber=".$chamber."&query=".$pieces[0]."&apikey=".$apikey;
		$res = file_get_contents($url_name);
		$res = json_decode($res,true);

		if($res["count"]==0){
			echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
		}else{

			echo "<table id='output' align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
			echo "<tr>";
			echo "<th> Name </th>";
			echo "<th> State </th>";
			echo "<th>Chamber</th>";
			echo "<th>View Details</th>";
			echo "</tr>";

			//form into table
			foreach($res["results"] as $item) {
			echo "<tr>";
			echo "<td align='center'> ".$item["first_name"] ." ". $item["last_name"]."</td>";
			echo "<td align='center'>".$item["state_name"]."</td>";
			echo "<td align='center'>". $item["chamber"] ."</td>";
			echo "<td align='center'><a href='?viewDetails=".$item["bioguide_id"]."&state_detail=".$item["state"]."&sel=legislators"."&chamber=".$item["chamber"]."&keyword=".$item["state_name"]."'>View Details </a></td>";
			echo "</tr>";
			}
			echo "</table>";
		}

	}else if(count($pieces)==2){
		$url_first_name = "http://congress.api.sunlightfoundation.com/legislators?chamber=".$chamber."&query=".$pieces[0]."&apikey=".$apikey;
		$url_last_name = "http://congress.api.sunlightfoundation.com/legislators?chamber=".$chamber."&query=".$pieces[1]."&apikey=".$apikey;

		$res1 = file_get_contents($url_first_name);
		$res2 = file_get_contents($url_last_name);

		$res1 = json_decode($res1,true);
		$res2 = json_decode($res2,true);

		if($res1["count"]==0 ||$res2["count"]==0){
			echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
		}else{
			foreach($res1["results"] as $item1) {
				foreach($res2["results"] as $item2) {
					if($item1["first_name"] == $item2["first_name"]){
						$res = $item1;
					}
				}
			}
			if($res!=""){
			echo "<table id='output' align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
			echo "<tr>";
			echo "<th> Name </th>";
			echo "<th> State </th>";
			echo "<th>Chamber</th>";
			echo "<th>View Details</th>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align='center'> ".$res["first_name"] ." ". $res["last_name"]."</td>";
			echo "<td align='center'>".$res["state_name"]."</td>";
			echo "<td align='center'>". $res["chamber"] ."</td>";
			echo "<td align='center'><a href='?viewDetails=".$res["bioguide_id"]."&state_detail=".$res["state"]."&sel=legislators"."&chamber=".$res["chamber"]."&keyword=".$res["state_name"]."'>View Details </a></td>";
			echo "</tr>";
			echo "</table>";
			}
			}}
	else{
		echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
	}
}


function legislatorSearch($chamber,$keyword){

	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	$url = "http://congress.api.sunlightfoundation.com/legislators?chamber=".$chamber."&state=".$keyword."&apikey=".$apikey;
	$res = file_get_contents($url);
	$res = json_decode($res, true);


	echo "<table id='output' align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Name </th>";
	echo "<th> State </th>";
	echo "<th>Chamber</th>";
	echo "<th>View Details</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td align='center'> ".$item["first_name"] ." ". $item["last_name"]."</td>";
	echo "<td align='center'>".$item["state_name"]."</td>";
	echo "<td align='center'>". $item["chamber"] ."</td>";
	echo "<td align='center'><a href='?viewDetails=".$item["bioguide_id"]."&state_detail=".$item["state"]."&chamber=".$item["chamber"]."&sel=legislators"."&keyword=".$item["state_name"]."'>View Details </a></td>";
	echo "</tr>";
	}
	echo "</table>";
}

function CommitteesSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	$url = "http://congress.api.sunlightfoundation.com/committees?committee_id=".rawurlencode($keyword)."&chamber=".rawurlencode($chamber)."&apikey=".$apikey ;
	$res = file_get_contents($url);
	$res = json_decode($res, true);

	if($res["count"]==0||$keyword ==""){
		echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
		return;
	}

	echo "<table id='output' align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Committee ID</th>";
	echo "<th> Committee Name </th>";
	echo "<th> Chamber</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td align='center'> ".$item["committee_id"];
	echo "<td align='center'>".$item["name"]."</td>";
	echo "<td align='center'>". $item["chamber"] ."</td>";
	echo "</tr>";
	}
	echo "</table>";

}

function BillsSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	$url = "http://congress.api.sunlightfoundation.com/bills?bill_id=".$keyword."&chamber=".$chamber."&apikey=".$apikey ;

	$res = file_get_contents($url);
	$res = json_decode($res, true);

	//print_r($res);
	//parsing the json object into table
	if($res["count"]==0||$keyword ==""){
		echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
		return;
	}

	echo "<table id='output' align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Bill ID</th>";
	echo "<th> Short Title </th>";
	echo "<th> Chamber</th>";
	echo "<th> Details</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td align='center'> ".$item["bill_id"];
	echo "<td align='center'>".$item["short_title"]."</td>";
	echo "<td align='center'>". $item["chamber"] ."</td>";
	echo "<td align='center'><a href='?viewBills=".$item["bill_id"]."&bill_chamber=".$item[chamber]."'>View Details </a></td>";
	echo "</tr>";
	}	
	echo "</table>";

}

function AmmendmentsSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	$url = "http://congress.api.sunlightfoundation.com/amendments?amendment_id=".$keyword."&chamber=".$chamber."&apikey=".$apikey;
	

	$res = file_get_contents($url);
	$res = json_decode($res, true);

	if($res["count"]==0||$keyword ==""){
		echo "<div id='output'> <center>The API returned zero results for the request.</center></div>";
		return;
	}

	echo "<table id='output' align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Amendment ID</th>";
	echo "<th> Amendment Type </th>";
	echo "<th> Chamber</th>";
	echo "<th> Introduce on</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td align='center'> ".$item["amendment_id"];
	echo "<td align='center'>".$item["amendment_type"]."</td>";
	echo "<td align='center'>". $item["chamber"] ."</td>";
	echo "<td align='center'>". $item["introduced_on"] ."</td>";
	echo "</tr>";
	}
	echo "</table>";
}

?>


<noscript>
</body>

</html>