<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');

mysql_query('SET CHARACTER SET utf8');

$gTEXT = $TEXT;

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {   
    case 'getDiffLevelTableData' :
        getDiffLevelTableData();
        break;

    default :
        echo "{failure:true}";
        break;
}

function getDiffLevelTableData() {

	$MonthId = ctype_digit($_POST['MonthId'])? $_POST['MonthId'] : '';
	$YearId = isset($_POST['YearId']) ? $_POST['YearId'] : '';
	
	$StartDate = $YearId . '-' . $MonthId . '-01';
	$EndDate = $YearId . '-' . $MonthId . '-30';
	
$query = "SELECT ProcessId, ProcessName, ProcessOrder
FROM t_process_list
ORDER BY ProcessOrder;";

//echo $query;

$result = mysql_query($query);

$aData = array();
//$aMonthYear = array();
$aTemplateValues = array();
$aColumns = array();

if ($result) {
	while ($rec = mysql_fetch_object($result)) {
		//get dynamic columns for the datatable
		$pia = $rec -> ProcessOrder . 'i'; // for InTime
		
		$aColumns[] = $rec -> ProcessName; // for InTime

		$aData[] = $rec;
		///get the initial value for each facility, if a facility have no value for a specific item then
		//the following array keep track the zero value for that position.
		$aTemplateValues[$pia] = '';
	}
}

$aTemplateValues['Total'] = 0;

//echo json_encode($aTemplateValues);
//exit;

$aaData = array();

$tmpFacilityCode = '';
$tmpFacilityName = '';
$tmpItemName = '';
$tmpUnitName = '';
$sl = 0;

$sQuery = "SELECT 
				  t_process_tracking.RegNo TrackingNo, t_process_tracking.ProcessId, t_process_list.ProcessName, t_process_list.ProcessOrder, t_process_tracking.InTime, t_process_tracking.OutTime,  Duration
				FROM
				  t_process_tracking 
				  INNER JOIN t_process_list 
					ON t_process_tracking.ProcessId = t_process_list.ProcessId 
				WHERE EntryDate >= '$StartDate' 
				  AND EntryDate <= '$EndDate' 
				ORDER BY t_process_tracking.TrackingNo, t_process_list.ProcessOrder;";
	
	
$rResult = mysql_query($sQuery);

//$holidays = array("2015-06-18","2015-06-19");

if ($rResult) {
	while ($data = mysql_fetch_object($rResult)) {
		//print_r($data);
		if ($data -> TrackingNo != $tmpFacilityCode) {

			// get each row to a array when it changes it state so in this case last row always skipped
			if (!is_null($row)) {
				$row['Total'] = is_null($row['Total'])? '' : convertToHoursMins($row['Total'],'%02d hours %02d minutes');
				$tmpRow = array_values($row);
				array_unshift($tmpRow, ++$sl, $tmpFacilityName);
				$aaData[] = $tmpRow;
			}
			// initialize the $row the zero values array sized with the number of facility.
			$row = $aTemplateValues;
			//$row['Total'] = 0;
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = is_null($data -> InTime)? '' : date('d/m/Y g:i A', strtotime($data -> InTime));
			
			//$row[$data -> ProcessOrder.'d'] = is_null($data -> InTime) || is_null($data -> OutTime)? '' : convertToHoursMins(getWorkingDays($data -> InTime,$data -> OutTime,$holidays) * 24 * 60, '%02d hours %02d minutes');
			
			$row['Total'] += is_null($data -> Duration)? 0 : $data -> Duration;
			
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> TrackingNo;
			$tmpFacilityName = $data -> TrackingNo;
		} else {
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = is_null($data -> InTime)? '' : date('d/m/Y g:i A', strtotime($data -> InTime));
			
			//$row[$data -> ProcessOrder.'d'] = is_null($data -> InTime) || is_null($data -> OutTime)? '' : convertToHoursMins(getWorkingDays($data -> InTime,$data -> OutTime,$holidays) * 24 * 60, '%02d hours %02d minutes');
			
			$row['Total'] += is_null($data -> Duration)? 0 : $data -> Duration;
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> TrackingNo;
		}
		//print_r($row);
	}
}



$num_rows = mysql_num_rows($rResult);
//var_dump($num_rows);
if ($num_rows) {
	//print_r(array_values($row));
	// get the last row that is skipped in the above loop
	$row['Total'] = is_null($row['Total'])? '' : convertToHoursMins($row['Total'],'%02d hours %02d minutes');
	$tmpRow = array_values($row);
	array_unshift($tmpRow, ++$sl, $tmpFacilityName);
	$aaData[] = $tmpRow;
	//print_r($aaData);
}

//$clmMonthYear = array_values($aMonthYear);
//array_unshift($clmMonthYear, 'Warhouse Name');

//echo '{"sEcho": 0, "iTotalRecords":"10","iTotalDisplayRecords": "10","aaData":' . json_encode($aaData, JSON_NUMERIC_CHECK) . '}';

 echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aaData) . '';
 echo ',"COLUMNS":' . json_encode($aColumns) . '}';
}

?>









