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
        <form name="congressSearchForm" action="" method="GET">
            <table>
                <tr>
                    <td>Congress Database</td>
                    <td>
                        <select name="sel" id="sel" <?php if (isset($_GET['sel'])) echo 'value="'.$_GET['sel'].'"';?> onchange="updateSearchAttribute()">
                        	<option value="" selected disabled>Choose the below</option>
							<option value="legislators" <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="legislators")? 'selected': ''; ?>>Legislators</option>
							<option value="committees"  <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="committees")? 'selected': ''; ?>>Committees</option>
							<option value="bills"  <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="bills")? 'selected': ''; ?>>Bills</option>
							<option value="amendments" <?php echo (isset($_GET[ "sel"])&&$_GET[ "sel"]=="amendments")? 'selected': ''; ?>>Amendments</option>
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
                    <td id="search-key-caption" name="keyword_caption" >Keyword</td>
                    <td><input name="keyword" type="text" value="<?php echo isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : ''; ?>"></input>
                    </td>
                    <td width="80px"></td>
                </tr>
                <tr>
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
            document.congressSearchForm.congress.value = "";
            document.congressSearchForm.state.value = "";
            document.congressSearchForm.keyword.value = "";
            document.congressSearchForm.chamber.value = "";
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
        }

</script>

<?php

 //store the states
 $states = array (
            'AL'=>'Alabama',
            'AK'=>'Alaska',
            'AZ'=>'Arizona',
            'AR'=>'Arkansas',
            'CA'=>'California',
            'CO'=>'Colorado',
            'CT'=>'Connecticut',
            'DE'=>'Delaware',
            'DC'=>'District Of Columbia',
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
            'NH'=>'New Hampshire',
            'NJ'=>'New Jersey',
            'NM'=>'New Mexico',
            'NY'=>'New York',
            'NC'=>'North Carolina',
            'ND'=>'North Dakota',
            'OH'=>'Ohio',
            'OK'=>'Oklahoma',
            'OR'=>'Oregon',
            'PA'=>'Pennsylvania',
            'RI'=>'Rhode Island',
            'SC'=>'South Carolina',
            'SD'=>'South Dakota',
            'TN'=>'Tennessee',
            'TX'=>'Texas',
            'UT'=>'Utah',
            'VT'=>'Vermont',
            'VA'=>'Virginia',
            'WA'=>'Washington',
            'WV'=>'West Virginia',
            'WI'=>'Wisconsin',
            'WY'=>'Wyoming',
        );


 if($_GET["submit"]){

 	$chamber = $_GET['chamber'];
	$keyword = $_GET['keyword'];
	$selectOption = $_GET['sel'];




 	if($selectOption == "legislators"){
		//LegislatorSearch
		legislatorSearch($chamber,$keyword);
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
		echo "IT'S EMPTY";
	}
 }

function legislatorSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	//$url = "http://congress.api.sunlightfoundation.com/legislators?chamber=".rawurlencode($chamber)."&state=".rawurlencode($keyword)."&apikey=".rawurlencode($apikey);
	$url_test = "http://congress.api.sunlightfoundation.com/legislators?chamber=house&state=WA&apikey=".$apikey;
	// echo $url_test;
	$res = file_get_contents("http://congress.api.sunlightfoundation.com/legislators?chamber=house&state=WA&apikey=ad112f71df2e4109864ee87613db82d8");
	$res = json_decode($res, true);

	// print_r($res);
	//parsing the json object into table

	echo "<table align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Name </th>";
	echo "<th> State </th>";
	echo "<th>Chamber</th>";
	echo "<th>View Details</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td> ".$item["first_name"] . $item["last_name"]."</td>";
	echo "<td>".$item["state_name"]."</td>";
	echo "<td>". $item["chamber"] ."</td>";
	echo "<td><a href=' " . $item["website"]." '>View Details </a></td>";
	echo "</tr>";
	}
	echo "</table>";
}

function CommitteesSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	//$url = "https://congress.api.sunlightfoundation.com/committees?committee_id=".rawurlencode($keyword)."&chamber=".rawurlencode($chamber)."&apikey=".$apikey ;
	$url_test = "https://congress.api.sunlightfoundation.com/committees?committee_id=SSGA18&chamber=senate&apikey=".$apikey;
	// echo $url_test;
	$res = file_get_contents($url_test);
	$res = json_decode($res, true);

	//print_r($res);
	//parsing the json object into table

	echo "<table align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Committee ID</th>";
	echo "<th> Committee Name </th>";
	echo "<th> Chamber</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td> ".$item["committee_id"];
	echo "<td>".$item["name"]."</td>";
	echo "<td>". $item["chamber"] ."</td>";
	echo "</tr>";
	}
	echo "</table>";

}

function BillsSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	//$url = "https://congress.api.sunlightfoundation.com/bills?bill_id=BILL_ID_HERE&chamber=CHAMBER_TYPE_HERE&apikey=YOUR_API_KEY_HERE ;
	$url_test = "https://congress.api.sunlightfoundation.com/bills?bill_id=s3271-114&chamber=senate&apikey=".$apikey;
	// echo $url_test;
	$res = file_get_contents($url_test);
	$res = json_decode($res, true);

	//print_r($res);
	//parsing the json object into table

	echo "<table align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Bill ID</th>";
	echo "<th> Short Title </th>";
	echo "<th> Chamber</th>";
	echo "<th> Details</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td> ".$item["bill_id"];
	echo "<td>".$item["short_title"]."</td>";
	echo "<td>". $item["chamber"] ."</td>";
	echo "<td>". $item["chamber"] ."</td>";
	echo "</tr>";
	}
	echo "</table>";

}

function AmmendmentsSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	//$url = "https://congress.api.sunlightfoundation.com/bills?bill_id=BILL_ID_HERE&chamber=CHAMBER_TYPE_HERE&apikey=YOUR_API_KEY_HERE ;
	$url_test = "https://congress.api.sunlightfoundation.com/amendments?amendment_id=samdt4946-114&chamber=senate&apikey=".$apikey;
	// echo $url_test;
	$res = file_get_contents($url_test);
	$res = json_decode($res, true);

	//print_r($res);
	//parsing the json is_object(var) into table

	echo "<table align='center' style='border-collapse:collapse; margin-top:20px' border='1 solid black'>";
	echo "<tr>";
	echo "<th> Amendment ID</th>";
	echo "<th> Amendment Type </th>";
	echo "<th> Chamber</th>";
	echo "<th> Introduce on</th>";
	echo "</tr>";

	//form into table
	foreach($res["results"] as $item) {
	echo "<tr>";
	echo "<td> ".$item["amendment_id"];
	echo "<td>".$item["amendment_type"]."</td>";
	echo "<td>". $item["chamber"] ."</td>";
	echo "<td>". $item["introduced_on"] ."</td>";
	echo "</tr>";
	}
	echo "</table>";
}

?>


<noscript>
</body>

</html>