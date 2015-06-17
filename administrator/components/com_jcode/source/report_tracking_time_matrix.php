<?php

require ("define.inc");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_connection=utf8");

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {     
    case 'getSummaryReport':
		getSummaryReport($conn);
		break; 
	case 'getSummaryReportColumns':
		getSummaryReportColumns($conn);
		break;    
    default :
		echo "{failure:true}";
		break;
}

function DMYtoYMD($rdateId){
    $hold=explode('-',$rdateId);
    return $hold[2]."-".$hold[1]."-".$hold[0];
}

function YMDtoDMY($rdateId){
    $hold=explode('-',$rdateId);
    return $hold[2]."-".$hold[1]."-".$hold[0];
}

function MDYtoYMD($rdateId){
    $hold=explode('-',$rdateId);
    return $hold[2]."-".$hold[0]."-".$hold[1];
}

function getSummaryReport_old($conn) {
    
    $DistrictId = ctype_digit($_POST['DistrictId'])? $_POST['DistrictId'] : '';
    $startDate = mysql_real_escape_string($_POST['StartDateId']);
    $endDate = $endDate1 = mysql_real_escape_string($_POST['EndDateId']);
	$condition = $DistrictId == ''? " ": " AND DistrictId = '".$DistrictId."' "; 
	
	if(empty($startDate) AND !empty($endDate)){
        $endDate = MDYtoYMD($endDate);
        $dd = "<='".$endDate."' "; 
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS totalDrugInspection, totalPharmaInspection, totalNewDrugLisence, totalRenewDrugLisence, totalDesCases, totalInfoDrugSampleCol, 
                 totalInfoDrugSampleSend, totalInfoDrugSampleResult, totalOwnerChange, totalrawMaterialState, totalfinishedGoodState FROM
    			(SELECT COUNT(*) totalDrugInspection FROM t_field_pharmacy_insp a WHERE a.DateOfInsp ".$dd." ".$condition." ) p,
    			(SELECT COUNT(*) totalPharmaInspection FROM t_field_phar_industry_insp b WHERE  b.DateOfInsp ".$dd." ".$condition." ) q,
    			(SELECT COUNT(*) totalNewDrugLisence FROM t_field_issue_new_license WHERE DateOfIssue".$dd." ".$condition." ) r,
    			(SELECT COUNT(*) totalRenewDrugLisence FROM t_field_renew_license WHERE DateofRenewal".$dd." ".$condition." ) s,
    			(SELECT COUNT(*) totalDesCases FROM t_field_description_cases WHERE SubDate ".$dd." ".$condition." ) t,
                (SELECT COUNT(*) totalInfoDrugSampleCol FROM t_field_drug_sample_items a INNER JOIN t_field_drug_sample_master b ON a.SampleMasterId = b.SampleMasterId WHERE DrawnDate ".$dd." ".$condition." ) u,
                (SELECT COUNT(*) totalInfoDrugSampleSend FROM t_field_sample_send_items a INNER JOIN t_field_sample_send_master b ON a.SampleSendMasterId = b.SampleSendMasterId WHERE SentDate ".$dd." ".$condition." ) v,
                (SELECT COUNT(*) totalInfoDrugSampleResult FROM t_field_drug_sample_result WHERE DateRecAnalytical ".$dd." ".$condition." ) w,
                (SELECT COUNT(*) totalOwnerChange FROM t_field_change_ownership WHERE DateOfChange ".$dd." ".$condition." ) x,
                (SELECT COUNT(*) totalrawMaterialState FROM t_field_raw_mat_state WHERE IssuanceCerDate ".$dd." ".$condition." ) y,
                (SELECT COUNT(*) totalfinishedGoodState FROM t_field_finished_goods WHERE IssuanceCerDate ".$dd." ".$condition." ) z";
    }        
     if(!empty($startDate) AND !empty($endDate1)) {
        $startDate = MDYtoYMD($startDate);
        $endDate = MDYtoYMD($endDate1);
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS totalDrugInspection, totalPharmaInspection, totalNewDrugLisence, totalRenewDrugLisence, totalDesCases, totalInfoDrugSampleCol, 
                 totalInfoDrugSampleSend, totalInfoDrugSampleResult, totalOwnerChange, totalrawMaterialState, totalfinishedGoodState FROM
    			(SELECT COUNT(*) totalDrugInspection FROM t_field_pharmacy_insp a WHERE a.DateOfInsp>='".$startDate."' AND a.DateOfInsp<='".$endDate."' ".$condition." ) p,
    			(SELECT COUNT(*) totalPharmaInspection FROM t_field_phar_industry_insp b WHERE b.DateOfInsp>='".$startDate."' AND b.DateOfInsp<='".$endDate."' ".$condition." ) q,
    			(SELECT COUNT(*) totalNewDrugLisence FROM t_field_issue_new_license WHERE DateOfIssue>='".$startDate."' AND DateOfIssue<='".$endDate."' ".$condition." ) r,
    			(SELECT COUNT(*) totalRenewDrugLisence FROM t_field_renew_license WHERE DateofRenewal>='".$startDate."' AND DateofRenewal<='".$endDate."' ".$condition." ) s,
    			(SELECT COUNT(*) totalDesCases FROM t_field_description_cases WHERE SubDate>='".$startDate."' AND SubDate<='".$endDate."' ".$condition." ) t,
                (SELECT COUNT(*) totalInfoDrugSampleCol FROM t_field_drug_sample_items a INNER JOIN t_field_drug_sample_master b ON a.SampleMasterId = b.SampleMasterId WHERE DrawnDate>='".$startDate."' AND DrawnDate<='".$endDate."' ".$condition.") u,
                (SELECT COUNT(*) totalInfoDrugSampleSend FROM t_field_sample_send_items a INNER JOIN t_field_sample_send_master b ON a.SampleSendMasterId = b.SampleSendMasterId WHERE SentDate>='".$startDate."' AND SentDate<='".$endDate."' ".$condition." ) v,
                (SELECT COUNT(*) totalInfoDrugSampleResult FROM t_field_drug_sample_result WHERE DateRecAnalytical>='".$startDate."' AND DateRecAnalytical<='".$endDate."' ".$condition." ) w,
                (SELECT COUNT(*) totalOwnerChange FROM t_field_change_ownership WHERE DateOfChange>='".$startDate."' AND DateOfChange<='".$endDate."' ".$condition." ) x,
                (SELECT COUNT(*) totalrawMaterialState FROM t_field_raw_mat_state WHERE IssuanceCerDate>='".$startDate."' AND IssuanceCerDate<='".$endDate."' ".$condition." ) y,
                (SELECT COUNT(*) totalfinishedGoodState FROM t_field_finished_goods WHERE IssuanceCerDate>='".$startDate."' AND IssuanceCerDate<='".$endDate."' ".$condition." ) z";
                
    }
  // echo $sql; exit;
	$pacrs = mysql_query($sql, $conn);
	$sql = "SELECT FOUND_ROWS()";
	$rs = mysql_query($sql, $conn);
	$r = mysql_fetch_array($rs);
	$total = $r[0];
	echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
	$serial = $_POST['iDisplayStart'] + 1;		
	  
	while ($row = @mysql_fetch_object($pacrs)) {
	    $totalDrugInspection = $row -> totalDrugInspection;
		$totalPharmaInspection = $row -> totalPharmaInspection;
		$totalNewDrugLisence = $row -> totalNewDrugLisence;
		$totalRenewDrugLisence = $row -> totalRenewDrugLisence;
        $totalDesCases = $row -> totalDesCases;
        $totalInfoDrugSampleCol = $row -> totalInfoDrugSampleCol;
        $totalInfoDrugSampleSend = $row -> totalInfoDrugSampleSend;
        $totalInfoDrugSampleResult = $row -> totalInfoDrugSampleResult;
        $totalOwnerChange = $row->totalOwnerChange;
        $totalrawMaterialState = $row->totalrawMaterialState;
        $totalfinishedGoodState = $row->totalfinishedGoodState;
        
        echo '[ "1", "Pharmacy Inspection", "'.$totalDrugInspection.'"],
              [ "2", "Pharmaceutical Industry Inspection", "'.$totalPharmaInspection.'"],
              [ "3", "Issue of new Drug License", "'.$totalNewDrugLisence.'"],
              [ "4", "Renewal of Drug License", "'.$totalRenewDrugLisence.'"],
              [ "5", "Description about Cases", "'.$totalDesCases.'"],
              [ "6", "Drug Sample Collection", "'.$totalInfoDrugSampleCol.'"],
              [ "7", "Drug Sample Send", "'.$totalInfoDrugSampleSend.'"],
              [ "8", "Drug Sample Result", "'.$totalInfoDrugSampleResult.'"],
              [ "9", "Ownership change/Address change/Name change/Renewal of Drug License", "'.$totalOwnerChange.'"],
              [ "10", "Raw Material Statement", "'.$totalrawMaterialState.'"],
              [ "11", "Finished Goods Statement", "'.$totalfinishedGoodState.'"]';
                   
		$serial++;
	}
    echo ']}';
}


function getSummaryReportColumns($conn) {
	
	$DistrictId = ctype_digit($_POST['DistrictId'])? $_POST['DistrictId'] : '';
    
	$StartMonthId = isset($_POST['StartMonthId']) ? $_POST['StartMonthId'] : '';
	$StartYearId = isset($_POST['StartYearId']) ? $_POST['StartYearId'] : '';
	$EndMonthId = isset($_POST['EndMonthId']) ? $_POST['EndMonthId'] : '';
	$EndYearId = isset($_POST['EndYearId']) ? $_POST['EndYearId'] : '';
	$MonthNumber = isset($_POST['MonthNumber']) ? $_POST['MonthNumber'] : 0;

	$currentYearMonth = $_POST['EndYearId'] . "-" . $_POST['EndMonthId'] . "-" . "01";
	$Endtdate = date("Y-m-t", strtotime($currentYearMonth));
	
	if($StartMonthId!='' && $StartYearId!='' && $MonthNumber == 0){
		$lastYearMonth = $StartYearId . "-" . $StartMonthId . "-" . "01";
	}else{
		$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-".($MonthNumber-1)." month"));
	}

$query = "SELECT DISTINCT 
			  a.YearId, a.MonthId 
			FROM
			  (SELECT 
				1 SortOrder, 'Pharmacy Inspection' Field_Report_Name, YEAR(DateOfInsp) YearId, MONTH(DateOfInsp) MonthId, COUNT(*) Qty 
			  FROM
				t_field_pharmacy_insp 
			  WHERE DateOfInsp >= '2014-01-01' 
				AND DateOfInsp <= '2015-05-31' 
			  GROUP BY YEAR(DateOfInsp), MONTH(DateOfInsp) 
			  UNION
			  SELECT 
				2 SortOrder, 'Pharmaceutical Industry Inspection' Field_Report_Name, YEAR(DateOfInsp) YearId, MONTH(DateOfInsp) MonthId, COUNT(*) Qty 
			  FROM
				t_field_phar_industry_insp 
			  WHERE DateOfInsp >= '2014-01-01' 
				AND DateOfInsp <= '2015-05-31' 
			  GROUP BY YEAR(DateOfInsp), MONTH(DateOfInsp) 
			  UNION
			  SELECT 
				3 SortOrder, 'Issue of new Drug License' Field_Report_Name, YEAR(DateOfIssue) YearId, MONTH(DateOfIssue) MonthId, COUNT(*) Qty 
			  FROM
				t_field_issue_new_license 
			  WHERE DateOfIssue >= '2014-01-01' 
				AND DateOfIssue <= '2015-05-31' 
			  GROUP BY YEAR(DateOfIssue), MONTH(DateOfIssue) 
			  UNION
			  SELECT 
				4 SortOrder, 'Renewal of Drug License' Field_Report_Name, YEAR(DateofRenewal) YearId, MONTH(DateofRenewal) MonthId, COUNT(*) Qty 
			  FROM
				t_field_renew_license 
			  WHERE DateofRenewal >= '2014-01-01' 
				AND DateofRenewal <= '2015-05-31' 
			  GROUP BY YEAR(DateofRenewal), MONTH(DateofRenewal) 
			  UNION
			  SELECT 
				5 SortOrder, 'Description about Cases' Field_Report_Name, YEAR(`SubDate`) YearId, MONTH(`SubDate`) MonthId, COUNT(*) Qty 
			  FROM
				t_field_description_cases 
			  WHERE `SubDate` >= '2014-01-01' 
				AND `SubDate` <= '2015-05-31' 
			  GROUP BY YEAR(`SubDate`), MONTH(`SubDate`) 
			  UNION
			  SELECT 
				6 SortOrder, 'Drug Sample Collection' Field_Report_Name, YEAR(DrawnDate) YearId, MONTH(DrawnDate) MonthId, COUNT(*) Qty 
			  FROM
				t_field_drug_sample_items a 
				INNER JOIN t_field_drug_sample_master b 
				  ON a.SampleMasterId = b.SampleMasterId 
			  WHERE DrawnDate >= '2014-01-01' 
				AND DrawnDate <= '2015-05-31' 
			  GROUP BY YEAR(DrawnDate), MONTH(DrawnDate) 
			  UNION
			  SELECT 
				7 SortOrder, 'Drug Sample Send' Field_Report_Name, YEAR(SentDate) YearId, MONTH(SentDate) MonthId, COUNT(*) Qty 
			  FROM
				t_field_sample_send_items a 
				INNER JOIN t_field_sample_send_master b 
				  ON a.SampleSendMasterId = b.SampleSendMasterId 
			  WHERE SentDate >= '2014-01-01' 
				AND SentDate <= '2015-05-31' 
			  GROUP BY YEAR(SentDate), MONTH(SentDate) 
			  UNION
			  SELECT 
				8 SortOrder, 'Drug Sample Result' Field_Report_Name, YEAR(DateRecAnalytical) YearId, MONTH(DateRecAnalytical) MonthId, COUNT(*) Qty 
			  FROM
				t_field_drug_sample_result 
			  WHERE DateRecAnalytical >= '2014-01-01' 
				AND DateRecAnalytical <= '2015-05-31' 
			  GROUP BY YEAR(DateRecAnalytical), MONTH(DateRecAnalytical) 
			  UNION
			  SELECT 
				9 SortOrder, 'Ownership change/Address change/Name change/Renewal of Drug License' Field_Report_Name, YEAR(DateOfChange) YearId, MONTH(DateOfChange) MonthId, COUNT(*) Qty 
			  FROM
				t_field_change_ownership 
			  WHERE DateOfChange >= '2014-01-01' 
				AND DateOfChange <= '2015-05-31' 
			  GROUP BY YEAR(DateOfChange), MONTH(DateOfChange) 
			  UNION
			  SELECT 
				10 SortOrder, 'Raw Material Statement' Field_Report_Name, YEAR(IssuanceCerDate) YearId, MONTH(IssuanceCerDate) MonthId, COUNT(*) Qty 
			  FROM
				t_field_raw_mat_state 
			  WHERE IssuanceCerDate >= '2014-01-01' 
				AND IssuanceCerDate <= '2015-05-31' 
			  GROUP BY YEAR(IssuanceCerDate), MONTH(IssuanceCerDate) 
			  UNION
			  SELECT 
				11 SortOrder, 'Finished Goods Statement' Field_Report_Name, YEAR(IssuanceCerDate) YearId, MONTH(IssuanceCerDate) MonthId, COUNT(*) Qty 
			  FROM
				t_field_finished_goods 
			  WHERE IssuanceCerDate >= '2010-01-01' 
				AND IssuanceCerDate <= '2020-06-31' 
			  GROUP BY YEAR(IssuanceCerDate), MONTH(IssuanceCerDate)) a 
			ORDER BY a.YearId, a.MonthId;";

$result = mysql_query($query, $conn);

//$result = mysqli_get_result($query);

$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');

$aMonthYear = array();

if ($result) {
	while ($row = mysql_fetch_object($result)) {
		//get dynamic columns for the datatable
		$my = $monthListShort[$row -> MonthId] . ' ' . $row -> YearId;
		$aMonthYear[$my] = $my;		
	}
}


$clmMonthYear = array_values($aMonthYear);
array_unshift($clmMonthYear, 'Field Report Name');

echo json_encode($clmMonthYear);
	
}


function getSummaryReport($conn) {
	
	$DistrictId = ctype_digit($_POST['DistrictId'])? $_POST['DistrictId'] : '';

	$StartMonthId = isset($_POST['StartMonthId']) ? $_POST['StartMonthId'] : '';
	$StartYearId = isset($_POST['StartYearId']) ? $_POST['StartYearId'] : '';
	$EndMonthId = isset($_POST['EndMonthId']) ? $_POST['EndMonthId'] : '';
	$EndYearId = isset($_POST['EndYearId']) ? $_POST['EndYearId'] : '';
	$MonthNumber = isset($_POST['MonthNumber']) ? $_POST['MonthNumber'] : 0;

	$currentYearMonth = $_POST['EndYearId'] . "-" . $_POST['EndMonthId'] . "-" . "01";
	$Endtdate = date("Y-m-t", strtotime($currentYearMonth));

	if($StartMonthId!='' && $StartYearId!='' && $MonthNumber == 0){
		$lastYearMonth = $StartYearId . "-" . $StartMonthId . "-" . "01";
	}else{
		$lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "-".($MonthNumber-1)." month"));
	}

$query = "SELECT ProcessId, ProcessName, ProcessOrder
FROM t_process_list
ORDER BY ProcessOrder;";

//echo $query;

$result = mysql_query($query, $conn);

//$result = mysqli_get_result($query);

$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');

$aData = array();
//$aMonthYear = array();
$aTemplateValues = array();
$aColumns = array();

if ($result) {
	while ($rec = mysql_fetch_object($result)) {
		//get dynamic columns for the datatable
		$pia = $rec -> ProcessOrder . 'i'; // for InTime
		$pib = $rec -> ProcessOrder . 'o'; // for OutTime
		$pid = $rec -> ProcessOrder . 'd'; // for Duration
		
		$aColumns[] = $rec -> ProcessName; // for InTime
		$aColumns[] = $rec -> ProcessName; // for OutTime
		$aColumns[] = $rec -> ProcessName; // for Duration

		$aData[] = $rec;
		///get the initial value for each facility, if a facility have no value for a specific item then
		//the following array keep track the zero value for that position.
		$aTemplateValues[$pia] = '';
		$aTemplateValues[$pib] = '';
		$aTemplateValues[$pid] = '';
	}
}

//$aTemplateValues['Total'] = 0;

//echo json_encode($aTemplateValues);
//exit;

$aaData = array();

$tmpFacilityCode = '';
$tmpFacilityName = '';
$tmpItemName = '';
$tmpUnitName = '';
$sl = 0;


$sQuery = "SELECT 
				  t_process_tracking.TrackingNo, t_process_tracking.ProcessId, t_process_list.ProcessName, t_process_list.ProcessOrder, t_process_tracking.InTime, t_process_tracking.OutTime, TIMESTAMPDIFF(MINUTE, InTime, OutTime) AS Duration
				FROM
				  t_process_tracking 
				  INNER JOIN t_process_list 
					ON t_process_tracking.ProcessId = t_process_list.ProcessId 
				WHERE EntryDate >= '2014-01-01' 
				  AND EntryDate <= '2015-06-31' 
				ORDER BY t_process_tracking.TrackingNo, t_process_list.ProcessOrder;";
	
	
$rResult = mysql_query($sQuery, $conn);

if ($rResult) {
	while ($data = mysql_fetch_object($rResult)) {
		//print_r($data);
		if ($data -> TrackingNo != $tmpFacilityCode) {

			// get each row to a array when it changes it state so in this case last row always skipped
			if (!is_null($row)) {
				//$row['Total'] = ($row['Total']);
				$tmpRow = array_values($row);
				array_unshift($tmpRow, ++$sl, $tmpFacilityName);
				$aaData[] = $tmpRow;
			}
			// initialize the $row the zero values array sized with the number of facility.
			$row = $aTemplateValues;
			//$row['Total'] = 0;
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = $data -> InTime;
			$row[$data -> ProcessOrder.'o'] = $data -> OutTime;
			$row[$data -> ProcessOrder.'d'] = $data -> Duration;
			//$row['Total'] += $data -> Qty;
			
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> TrackingNo;
			$tmpFacilityName = $data -> TrackingNo;
		} else {
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = $data -> InTime;
			$row[$data -> ProcessOrder.'o'] = $data -> OutTime;
			$row[$data -> ProcessOrder.'d'] = $data -> Duration;
			//$row['Total'] += $data -> Qty;
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> TrackingNo;
		}
		//print_r($row);
	}
}

//print_r($aaData);

$num_rows = mysql_num_rows($result);
if ($num_rows) {
	//print_r(array_values($row));
	// get the last row that is skipped in the above loop
	//$row['Total'] = ($row['Total']);
	$tmpRow = array_values($row);
	array_unshift($tmpRow, ++$sl, $tmpFacilityName);
	$aaData[] = $tmpRow;
}

//$clmMonthYear = array_values($aMonthYear);
//array_unshift($clmMonthYear, 'Warhouse Name');

//echo '{"sEcho": 0, "iTotalRecords":"10","iTotalDisplayRecords": "10","aaData":' . json_encode($aaData, JSON_NUMERIC_CHECK) . '}';

 echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aaData) . '';
 echo ',"COLUMNS":' . json_encode($aColumns) . '}';
	
}


?>






