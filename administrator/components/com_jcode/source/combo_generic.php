
	
<?php
include_once ('database_conn.php');
include_once ("function_lib.php");


		
/////////////////////for multi language/////////		
		
$lan='';
$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}
if (isset($_REQUEST['lan'])) {
	$lan = $_REQUEST['lan'];
}


if($lan == 'en-GB'){
		$MonthName = 'MonthName';
	}else{
		$MonthName = 'MonthNameFrench';
	}  
		
		

switch($task) {
	// case "getItemGroup" :
	// getItemGroup();
	// break;
	// case "getCountryList" :
	// getCountryList();
	// break;
	case "getItemGroupFrequency" :
		getItemGroupFrequency();
		break;
	case "getItemGroupServiceType" :
		getItemGroupServiceType();
		break;
	case "getMonthByFrequencyId" :
		getMonthByFrequencyId();
		break;
	case "getFillRegion":
		getFillRegion();
		break;
	case "getFillRegionForEdit":
		getFillRegionForEdit();
		break;
	case "getFillDistrict":
		getFillDistrict();
		break;
	case "getFillDistrictForEdit":
		getFillDistrictForEdit();
		break;
	default :
		echo "{failure:true}";
		break;
}

// function getYearList() {
// $aColumns = array('YearId', 'YearName');
//
// $sTable = "t_year";
//
// $sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
// FROM   $sTable";
//
// $rResult = safe_query($sQuery);
//
// $output = array();
//
// while($obj = mysql_fetch_object($rResult)) {
// $output[] = $obj;
// }
//
// echo json_encode($output);
// }
//
// function getCountryList() {
// $aColumns = array('CountryId', 'CountryCode', 'CountryName', 'ISO3');
//
// $sTable = "t_country";
//
// $sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
// FROM   $sTable";
//
// $rResult = safe_query($sQuery);
//
// $output = array();
//
// while($obj = mysql_fetch_object($rResult)) {
// $output[] = $obj;
// }
//
// echo json_encode($output);
// }
//
// function getItemGroup() {
// $aColumns = array('ItemGroupId', 'GroupName');
//
// $sTable = "t_itemgroup";
//
// $sQuery = "SELECT  `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
// FROM   $sTable";
//
// $rResult = safe_query($sQuery);
//
// $output = array();
//
// while($obj = mysql_fetch_object($rResult)) {
// $output[] = $obj;
// }
//
// echo json_encode($output);
// }

function getItemGroupFrequency() {
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
	$sQuery = "SELECT t_reporting_frequency.FrequencyId
			    , t_reporting_frequency.StartMonthId
			    , t_reporting_frequency.StartYearId    
			FROM
			    t_reporting_frequency
			WHERE (t_reporting_frequency.CountryId = $countryId
				   AND t_reporting_frequency.ItemGroupId = $itemGroupId)";
	$rResult = mysql_query($sQuery);
	$output = array();
	
	//echo $sQuery;

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}


function getItemGroupServiceType() {
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
	$sQuery = "select distinct t_reporting_frequency.FrequencyId
				, t_reporting_frequency.StartMonthId
				, t_reporting_frequency.StartYearId

				from  t_servicetype
				Inner Join t_formulation ON t_servicetype.ServiceTypeId = t_formulation.ServiceTypeId
				Inner Join t_reporting_frequency ON t_formulation.ItemGroupId = t_reporting_frequency.ItemGroupId

				WHERE (t_reporting_frequency.CountryId = $countryId
				AND t_servicetype.ServiceTypeId = $itemGroupId)";
				   
				   
	$rResult = mysql_query($sQuery);
	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}
	
	echo json_encode($output);
}

function getMonthByFrequencyId() {
global $MonthName;
	$frequencyId = $_POST['FrequencyId'];

	$sQuery = '';
  
	if ($frequencyId == 1)
		$sQuery = "SELECT MonthId,$MonthName MonthName FROM t_month Order By MonthId";
	else if ($frequencyId == 2)
		$sQuery = "SELECT MonthId,$MonthName MonthName FROM t_quarter Order By MonthId";

	$rResult = mysql_query($sQuery);
	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}


function getFillRegion() {
	$CountryId = $_POST['CountryId'];
	$UserId = $_POST['UserId'];

	$sQuery = "";
		$sQuery = " SELECT 0 RegionId,' All' RegionName 
		UNION
		SELECT a.RegionId, RegionName
					FROM t_region a
                    INNER JOIN t_user_region_map b ON a.RegionId = b.RegionId
                    WHERE (a.CountryId = $CountryId OR $CountryId=0) and b.UserId = '$UserId'
                    ORDER BY RegionName;";
	$rResult = mysql_query($sQuery);
	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}

function getFillDistrict() {
	$CountryId = $_POST['CountryId'];
	$RegionId = $_POST['RegionId'];
	//$lan = $_POST['lan'];
	$sQuery = "";
	
		$sQuery = " SELECT 0 DistrictId,' All' DistrictName 
		UNION
		SELECT DistrictId,DistrictName 
		FROM t_districts where (CountryId = $CountryId OR $CountryId = 0) and (RegionId = $RegionId OR $RegionId = 0)
		Order By DistrictName;";
		//echo $sQuery;
	$rResult = mysql_query($sQuery);
	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}


function getFillRegionForEdit() {
	$CountryId = $_POST['CountryId'];
	$UserId = $_POST['UserId'];
	$lan = $_POST['lan'];
	if($lan == 'en-GB')
		$rLebel = 'Select Region';
	else
		$rLebel = 'Sélectionnez région';
	$sQuery = "";
		$sQuery = " SELECT '' RegionId,' ".$rLebel."' RegionName 
		UNION
		SELECT a.RegionId, RegionName
					FROM t_region a
                    INNER JOIN t_user_region_map b ON a.RegionId = b.RegionId
                    WHERE (a.CountryId = $CountryId OR $CountryId=0) and b.UserId = '$UserId'
                    ORDER BY RegionName;";
	$rResult = mysql_query($sQuery);
	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}
function getFillDistrictForEdit() {
	$CountryId = $_POST['CountryId'];
	$RegionId = $_POST['RegionId'];
	$lan = $_POST['lan'];
	if($lan == 'en-GB')
		$distLebel = 'Select District';
	else
		$distLebel = 'Sélectionnez district';
	
	$sQuery = "";
	$pRegionId ='';
	if($RegionId)
		$pRegionId = ' and RegionId ='. $RegionId;
		
		$sQuery = "	SELECT '' DistrictId, ' ".$distLebel."' DistrictName 
		UNION
		SELECT DistrictId,DistrictName 
		FROM t_districts where (CountryId = $CountryId OR $CountryId = 0) $pRegionId
		Order By DistrictName;";
		//echo $sQuery;
	$rResult = mysql_query($sQuery);
	$output = array();

	while ($obj = mysql_fetch_object($rResult)) {
		$output[] = $obj;
	}

	echo json_encode($output);
}

?>
