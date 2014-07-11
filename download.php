<?php

$usaspending = json_decode(file_get_contents('usaspending.json'),TRUE);

$arg = array();

$synopsis = "Downloads CSVs from usaspending.gov";

$arg[1]['arg'] 				= 'RECORD_TYPE';
$arg[1]['desc']				= 'The type of record you want to import from USASPENDING.GOV (Example: subawardcontracts).';
$arg[2]['arg'] 				= 'FROM_YEAR';
$arg[2]['desc']				= 'Year to start with (Example: 2010)';
$arg[3]['arg'] 				= 'TO_YEAR';
$arg[3]['desc']				= 'Year to end with (Example: 2014)';
$arg[4]['arg'] 				= 'DOWNLOAD_DIR';
$arg[4]['desc']				= 'Where to download files';
$arg[5]['arg'] 				= 'FILE_FORMAT';
$arg[5]['desc']				= 'File format (Valid values: CSV, TSV, XML or Atom)';

if ((count($argv)-1) < count($arg) || $argv[1] == "-help" || $argv[1] == "--help") {
	echo "\n\n";
	echo "==================================================================================================================================\n";
	echo "OVERVIEW\n";
	echo $synopsis."\n";
	echo "----------------------------------------------------------------------------------------------------------------------------------\n";
	echo "USAGE\n";	
	echo "php import.php ";;
	foreach ($arg as $v) {
		echo "[".$v['arg']."] ";
	}
	echo "\n";
	echo "----------------------------------------------------------------------------------------------------------------------------------\n";
	echo "ARGS\n";
	foreach ($arg as $v) {
		echo "[".$v['arg']."]			".$v['desc']."\n";
	}
	echo "==================================================================================================================================\n";
	echo "\n\n";
	die;	
}

echo "\n";
echo "==================================================================================================================================\n";
echo date('c',time())."\n";
echo "START SCRIPT\n";
echo "==================================================================================================================================\n";
for ($x=1; $x < (count($arg)+1); $x++) {
	echo "[".$arg[$x]['arg']."] = ".$argv[$x]."\n";
	$a[$arg[$x]['arg']] = $argv[$x];
}
echo "\n";

for ($year = $a['FROM_YEAR']; $year <= $a['TO_YEAR']; $year++) { 
	
	echo "----------------------------------------------------------------------------------------------------------------------------------\n";
	echo "DOWNLOADING ".$a['RECORD_TYPE']." ".$a['FILE_FORMAT']." FROM USASPENDING.GOV - $year\n";
	echo "----------------------------------------------------------------------------------------------------------------------------------\n";
	
	$query = array();
	$query['usaspend_query'] = $usaspending[$a['RECORD_TYPE']]['query'];
	$query['usaspend_query']['fiscal_year'] = $year;

	$url = $usaspending['base_query_url'];	
	
	foreach ($query['usaspend_query'] as $k=>$v) {
		$url .= $k."=".$v."&";
	}
	$url .= "format=".$a['FILE_FORMAT'];
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLINFO_HTTP_CODE, true); 
	$result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);	

	$xml = new DOMDocument();
	$xml->loadHTML( $result );

	$d_url = "http://www.usaspending.gov/customcode/build_feed.php?";
	
	foreach($xml->getElementsByTagName('input') as $input) { 
	   $d_url .= $input->getAttribute('name')."=".$input->getAttribute('value')."&";

	   if ($input->getAttribute('name') == 'record_count') {
	   	$record_count = $input->getAttribute('value');
	   }
	} 
	
	$csv_file = $a['DOWNLOAD_DIR']."/".$a['RECORD_TYPE'].'_year_'.$year.'.'.$a['FILE_FORMAT'];
	echo "[Downloading $record_count records]\n";
	$wget = 'wget -O '.$csv_file.' "'.$d_url.'" ‘>/dev/null 2>&1';
	exec($wget);
	
	echo $csv_file." saved.\n";
	echo "----------------------------------------------------------------------------------------------------------------------------------\n";

	
}

?>