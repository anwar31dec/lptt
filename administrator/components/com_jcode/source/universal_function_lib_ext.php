<?php

include ("define.inc");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die("Could not connect: " . mysql_error());
 if(!mysql_select_db(DBNAME, $conn)) {
	echo 'Could not select database';
	exit ;
}

define('EN_GB', 'en-GB');
define('FR_FR', 'fr-FR');


$MONTH_NAME = array(1 => 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC');
$MONTH_NAME_SMALL = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

function getYearForLastMonth($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-1 month"));
	$lastYearMonthArray = explode("-", $lastYearMonth);
	$lastYear = $lastYearMonthArray[0];
	return $lastYear;
}

function getLastMonth($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-1 month"));
	$lastYearMonthArray = explode("-", $lastYearMonth);
	$lastMonth = $lastYearMonthArray[1];
	return $lastMonth;
}

function getYearForLast2Month($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-2 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastYear = $beforeLastYearMonthArray[0];
	return $beforeLastYear;
}

function getBeforeLastMonth($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-2 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastMonth = $beforeLastYearMonthArray[1];
	return $beforeLastMonth;
}

function getYearFor12MonthsAgo($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-6 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastYear = $beforeLastYearMonthArray[0];
	return $beforeLastYear;
}

function getMonthFor12MonthsAgo($currentYear, $currentMonth) {
	$currentYearMonth = $currentYear . "-" . $currentMonth . "-" . "01";
	$beforeLastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-6 month"));
	$beforeLastYearMonthArray = explode("-", $beforeLastYearMonth);
	$beforeLastMonth = $beforeLastYearMonthArray[1];
	return $beforeLastMonth;
}

function getTable_PO_YearForCurrentMonth($currentYear) {
	return 't_cfm_patientoverview';
}

function getTable_PO_YearForLastMonth($currentYear, $currentMonth) {
	return 't_cfm_patientoverview';
}

function getTable_PO_YearForLast2Month($currentYear, $currentMonth) {
	return 't_cfm_patientoverview';
}

function getTable_RP_YearForCurrentMonth($currentYear) {
	return 't_cfm_regimenpatient';
}

function getTable_RP_YearForLastMonth($currentYear, $currentMonth) {
	return 't_cfm_regimenpatient';
}

function getTable_RP_YearForLast2Month($currentYear, $currentMonth) {
	return 't_cfm_regimenpatient';
}

function getTable_SD_YearForCurrentMonth($currentYear) {
	return 't_cfm_stockstatus';
}

function getTable_SD_YearForLastMonth($currentYear, $currentMonth) {
	return 't_cfm_stockstatus';
}

function getTable_SD_YearForLast2Month($currentYear, $currentMonth) {
	return 't_cfm_stockstatus';
}

function getTable_MSD_YearForCurrentMonth($currentYear) {
	return 't_cfm_masterstockstatus';
}

function getTable_MSD_YearForLastMonth($currentYear, $currentMonth) {
	return 't_cfm_masterstockstatus';
}

function checkNumber($postData) {
	return ((isset($postData) && !empty($postData)) ? $postData : "NULL");
}

function zeroEmptyUnsetToNull($postData) {
	return ((isset($postData) && !empty($postData)&& $postData != 0 ) ? $postData : "NULL");
}

function checkString($postData) {
	return ((isset($postData) && !empty($postData)) ? "'" . $postData . "'" : "NULL");
}

function getFacilityReportingRate($frrpMonthId, $frrpYear) {

	$query1 = "SELECT COUNT(*) as totReportedFacility FROM ";
	$query1 .= "(SELECT DISTINCT FacilityId FROM " . getTable_SD_YearForCurrentMonth($frrpYear) . " WHERE MonthId = $frrpMonthId AND Year = '$frrpYear' and FacilityId NOT IN (0,-1) and ItemGroupId = 1) a ";
	//echo $query1;
	$query2 = "SELECT COUNT(*) as totFacility FROM facility a, facility_service b
				WHERE a.FacilityId = b.FacilityId AND a.FacilityId  NOT IN (0,-1) AND b.ItemGroupId = 1;";
	// echo $query2;
	//$result = mysql_query($query1);

	//while($row = mysql_fetch_assoc($result))
	$totReportedFacility = getSingleValue($query1);

	///$result2 = mysql_query($query2);

	//while($row = mysql_fetch_assoc($result2))
	$totFacility = getSingleValue($query2);

	if($totFacility > 0){
		$ratio = $totReportedFacility / $totFacility;
	}

	// echo $totReportedFacility."<br/>";
	// echo $totFacility."<br/>";
	// echo $ratio;

	return   number_format($ratio, 4);
}
function getItemFacilityReportingRate($frrpMonthId, $frrpYear,$frrpItemGroupId) {

	$query1 = "SELECT COUNT(*) as totReportedFacility FROM ";
	$query1 .= "(SELECT DISTINCT a.FacilityId FROM " . getTable_MSD_YearForCurrentMonth($frrpYear) . " a, facility_service b 
	WHERE a.FacilityId = b.FacilityId AND a.MonthId = $frrpMonthId AND a.Year = '$frrpYear' and a.FacilityId NOT IN (0,-1) and a.ItemGroupId = $frrpItemGroupId and a.bSubmitted=1) a ";
	// echo $query1;
	$query2 = "SELECT COUNT(*) as totFacility FROM facility a, facility_service b
				WHERE a.FacilityId = b.FacilityId AND a.FacilityId  NOT IN (0,-1) AND b.ItemGroupId = $frrpItemGroupId;";
	//echo $query2;
	//$result = mysql_query($query1);

	//while($row = mysql_fetch_assoc($result))
	$totReportedFacility = getSingleValue($query1);

	///$result2 = mysql_query($query2);

	//while($row = mysql_fetch_assoc($result2))
	$totFacility = getSingleValue($query2);
    if($totFacility > 0){
	$ratio = $totReportedFacility / $totFacility;
}
	 //echo $totReportedFacility."<br/>";
	 //echo $totFacility."<br/>";
	// echo $ratio;

	return   number_format($ratio, 4);
}
function getHeadingWithItemGroup($frrpItemGroupId) {

	$query= "SELECT upc_name FROM itemgroup WHERE ItemGroupId=$frrpItemGroupId";
	// echo $totReportedFacility."<br/>";
	// echo $totFacility."<br/>";
	// echo $ratio;
$totReportedFacility = getSingleValue($query);
	return $totReportedFacility ;
	// getJson($query);
}
function getMinMos($frrpItemGroupId) {

	$query1= "SELECT MinMos FROM minmaxmos WHERE ItemGroupId=$frrpItemGroupId and FacLevel=0";
	// echo $totReportedFacility."<br/>";
	// echo $totFacility."<br/>";
	// echo $ratio;
$minmos = getSingleValue($query1);
	return $minmos ;
	
	// $query2= "SELECT MaxMos FROM minmaxmos WHERE ItemgroupId=$frrpItemGroupId";
	// $maxmos = getSingleValue($query2);
	// return $maxmos ;
	// getJson($query);
}
function getMaxMos($frrpItemGroupId) {

	$query= "SELECT MaxMos FROM minmaxmos WHERE ItemGroupId=$frrpItemGroupId and FacLevel=0";
	// echo $totReportedFacility."<br/>";
	// echo $totFacility."<br/>";
	// echo $ratio;
$maxmos = getSingleValue($query);
	return $maxmos ;
	
	// $query2= "SELECT MaxMos FROM minmaxmos WHERE ItemgroupId=$frrpItemGroupId";
	// $maxmos = getSingleValue($query2);
	// return $maxmos ;
	// getJson($query);
}

function getMinMosFac($frrpItemGroupId) {

	$query1= "SELECT MinMos FROM minmaxmos WHERE ItemGroupId=$frrpItemGroupId and FacLevel=1";
	// echo $totReportedFacility."<br/>";
	// echo $totFacility."<br/>";
	// echo $ratio;
$minmos = getSingleValue($query1);
	return $minmos ;
	
	// $query2= "SELECT MaxMos FROM minmaxmos WHERE ItemgroupId=$frrpItemGroupId";
	// $maxmos = getSingleValue($query2);
	// return $maxmos ;
	// getJson($query);
}
function getMaxMosFac($frrpItemGroupId) {

	$query= "SELECT MaxMos FROM minmaxmos WHERE ItemGroupId=$frrpItemGroupId and FacLevel=1";
	// echo $totReportedFacility."<br/>";
	// echo $totFacility."<br/>";
	// echo $ratio;
$maxmos = getSingleValue($query);
	return $maxmos ;
	
	// $query2= "SELECT MaxMos FROM minmaxmos WHERE ItemgroupId=$frrpItemGroupId";
	// $maxmos = getSingleValue($query2);
	// return $maxmos ;
	// getJson($query);
}
// function getCount($query) {
// $result = safe_query($query);
// while($row = mysql_fetch_array($result))
// $totalcount = $row[0];
// return $totalcount;
// }

function getSingleValue($query) {
	$result = safe_query($query);
	while($row = mysql_fetch_array($result))
		$value = $row[0];
	return $value;
}

// function safe_query($query ="") {
	// if(empty($query)) {
		// return false;
	// }
	// $result = mysql_query($query) or die("Query Fails:" . "<li> Errno=" . mysql_errno() . "<li> ErrDetails=" . mysql_error() . "<li>Query=" . $query);
	// return $result;
// }

function getJson($query) {
	mysql_query('SET CHARACTER SET utf8');
	$result = safe_query($query);

	$nbrows = mysql_num_rows($result);
	$start = (integer)(isset($_POST['start']) ? $_POST['start'] : $_GET['start']);
	$end = (integer)(isset($_POST['limit']) ? $_POST['limit'] : $_GET['limit']);
	$limit = $query . " LIMIT " . $start . "," . $end;
	$result = mysql_query($limit);
	
	//echo $limit;

	if($nbrows > 0) {
		while($rec = mysql_fetch_object($result)) {
			$arr[] = $rec;
		}
		$jsonresult = json_encode($arr);
		echo '({"total":"' . $nbrows . '","results":' . $jsonresult . '})';
	} else {
		echo '({"total":"0", "results":""})';
	}
}

function getJsonMobile($query) {
	mysql_query('SET CHARACTER SET utf8');
	$result = safe_query($query);

	$nbrows = mysql_num_rows($result);
	//$start = (integer)(isset($_POST['start']) ? $_POST['start'] : $_GET['start']);
	//$end = (integer)(isset($_POST['limit']) ? $_POST['limit'] : $_GET['limit']);
	//$limit = $query . " LIMIT " . $start . "," . $end;
	//$result = mysql_query($limit);

	if($nbrows > 0) {
		while($rec = mysql_fetch_array($result)) {                    
                    $arr[] = $rec;
		}
		$jsonresult = JEncode($arr);
		echo '{"total":"' . $nbrows . '","results":' . $jsonresult . '}';
	} else {
		echo '{"total":"0", "results":""}';
	}
}

function getJsonAll($query) {
	mysql_query('SET CHARACTER SET utf8');
	$result = safe_query($query);

	$nbrows = mysql_num_rows($result);
	//$start = (integer)(isset($_POST['start']) ? $_POST['start'] : $_GET['start']);
	//$end = (integer)(isset($_POST['limit']) ? $_POST['limit'] : $_GET['limit']);
	//$limit = $query . " LIMIT " . $start . "," . $end;
	//$result = mysql_query($limit);

	if($nbrows > 0) {
		while($rec = mysql_fetch_object($result)) {
			$arr[] = $rec;
		}
		$jsonresult = JEncode($arr);
		echo '({"total":"' . $nbrows . '","results":' . $jsonresult . '})';
	} else {
		echo '({"total":"0", "results":""})';
	}
}

function runSql($sql) {
	$result = mysql_query($sql);
	if($result) {
		echo '{success = 1; error = "No Error"}';
	} else {
		echo '{success = 0; error = "Invalid query: ' . mysql_error() . ', SQL: ' . $sql . '"}';
	}
}

function JEncode($arr) {
	if(version_compare(PHP_VERSION, "5.2", "<")) {
		require_once ("./JSON.php");
		//if php<5.2 need JSON class
		$json = new Services_JSON();
		//instantiate new json object
		$data = $json -> encode($arr);
		//encode the data in json format
	} else {
		$data = json_encode($arr);
		//encode the data in json format
	}
	return $data;
}

// Encodes a YYYY-MM-DD into a MM-DD-YYYY string
function codeDate($date) {
	$tab = explode("-", $date);
	$r = $tab[1] . "/" . $tab[2] . "/" . $tab[0];
	return $r;
}

function getBarColor($uni_strMOS) {
	$uni_color;
	$ItemGroupId = checkNumber($_POST['pItemGroupId']);
	if($uni_strMOS < getMinMos($ItemGroupId))
		return "#FF0000";
	else if($uni_strMOS >= getMinMos($ItemGroupId) && $uni_strMOS <= getMaxMos($ItemGroupId))
		return "#FFFF00";
	else if($uni_strMOS > getMaxMos($ItemGroupId))
		return "#39B54A";
}

function getBarColorForFacility($uni_strMOS) {
	$ItemGroupId = checkNumber($_POST['pItemGroupId']);
	if($uni_strMOS >= getMinMosFac($ItemGroupId) && $uni_strMOS <= getMaxMosFac($ItemGroupId)) {		
		return "#006600";
	} else if($uni_strMOS > .25 && $uni_strMOS < getMinMosFac($ItemGroupId)) {		
		return "#ED1C24";
	} else if($uni_strMOS > getMaxMosFac($ItemGroupId)) {
		return "#FFF200";
	}
	else 
		return "#FF0000";

	// else if($obj -> MOS > 0 && $obj -> MOS <= 0.25) {
		// $imgfile = "icons/redflag.png";		
	// } else if($obj -> MOS == '0') {
		// $imgfile = "icons/so.png";		
	// } else {
		// if(empty($obj -> MOS)) {
			// $imgfile = "icons/nr.png";			
		// }
	// }
}

function getBarColorForPatient($uni_strMOS) {
	if($uni_strMOS >= 500 && $uni_strMOS <= 2000) {		
		return "#006600";
	} else if($uni_strMOS >= 0 && $uni_strMOS <= 500) {		
		return "#ED1C24";
	} else if($uni_strMOS > 2000) {
		return "#FFF200";
	}
	else 
		return "#FF0000";
}

function getMonthsBtnTwoDate($firstDate, $lastDate) {
	$diff = abs(strtotime($lastDate) - strtotime($firstDate));
	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	//$days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	//printf("%d years, %d months, %d days\n", $years, $months, $days);
	return $months;
}

// echo getYearForLastMonth("2011","01")."<br/>";
// echo getLastMonth("2011","01")."<br/>";
// echo getYearForLast2Month("2011","01")."<br/>";
// echo getBeforeLastMonth("2011","01")."<br/>";
//
// echo getTable_PO_YearForCurrentMonth("2011","01")."<br/>";
// echo getTable_PO_YearForLastMonth("2011", "01")."<br/>";
// echo getTable_PO_YearForLast2Month("2011", "01")."<br/>";
// echo getTable_RP_YearForCurrentMonth("2011","01")."<br/>";
// echo getTable_RP_YearForLastMonth("2011","01")."<br/>";
// echo getTable_RP_YearForLast2Month("2011","01")."<br/>";
// echo getTable_SD_YearForCurrentMonth("2011","01")."<br/>";
// echo getTable_SD_YearForLastMonth("2011","01")."<br/>";
// echo getTable_SD_YearForLast2Month("2011","01")."<br/>";
