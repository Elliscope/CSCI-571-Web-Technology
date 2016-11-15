
<?php

$category = $_GET['category'];
$chamber = $_GET['chamber'];
$keyword = $_GET['keyword'];

// echo $chamber;
// echo $keyword ;
// echo $category;

if($category=="legistlator"){
	return legislatorSearch($chamber,$keyword);
}else if($category=="bills"){
	return BillsSearch($chamber,$keyword);
}else if($category=="committees"){
	return CommitteesSearch($chamber,$keyword);
}



//"http://104.198.0.197:8080/legislators?apikey=".$apikey."&per_page=all"
function legislatorSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	if($chamber==null){
		$url = "http://104.198.0.197:8080/legislators?apikey=".$apikey."&per_page=all";
	}else{
		$url = "http://104.198.0.197:8080/legislators?chamber=".$chamber."&state=".$keyword."&apikey=".$apikey;
	}
	
	$res = file_get_contents($url);
	echo $res;
	return $res;
}


function CommitteesSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	$url = "http://104.198.0.197:8080/committees?committee_id=".rawurlencode($keyword)."&chamber=".rawurlencode($chamber)."&apikey=".$apikey ;
	$res = file_get_contents($url);
	return $res;
}

function BillsSearch($chamber,$keyword){
	$apikey = 'ad112f71df2e4109864ee87613db82d8';
	$url = "http://104.198.0.197:8080/bills?bill_id=".$keyword."&chamber=".$chamber."&apikey=".$apikey ;
	$res = file_get_contents($url);
	return $res;
}

?>