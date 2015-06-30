<?php
date_default_timezone_set("Asia/Dhaka");

$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d")));

$lastYearMonthArray = explode("-", $lastYearMonth);
$lastYear = intval($lastYearMonthArray[0]);
$lastMonth = intval($lastYearMonthArray[1]);

$defaultYear;
$defaultMonth;

$defaultMonth = $lastMonth - 4;
$defaultYear = $lastYear;

$initialMY = array();

$initMY['svrLastYear'] = $lastYear;
$initMY['svrLastMonth'] = $lastMonth;

$svrStartYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($lastYearMonth)) . "-11 month"));
$StartYearMonthArray = explode("-", $svrStartYearMonth);
$initMY['svrStartYear'] = intval($StartYearMonthArray[0]);
$initMY['svrStartMonth'] = intval($StartYearMonthArray[1]);
	
// $sQuery = " SELECT a.CountryId, CountryName, StartMonth, StartYear
			// FROM t_country a
            // INNER JOIN t_user_country_map b ON a.CountryId = b.CountryId
            // WHERE b.UserId = '".$userName."' ";
// 					
// $rResult = safe_query($sQuery);
// 
// $output = array();
// 
// while($obj = mysql_fetch_object($rResult)) {
		// $output[] = $obj;
// }
// 		
// echo ' var gCountryList = JSON.parse(\'' . json_encode($output) . '\');';

$initMY['initialYear'] = $defaultYear;
$initMY['initialMonth'] = $defaultMonth;

echo '<script language="javascript">';
echo ' var objInit;';
echo ' objInit = JSON.parse(\'' . json_encode($initMY) . '\');';
echo '</script>';
?>
