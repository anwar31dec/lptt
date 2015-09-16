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
    case 'getDiffLevelTableColumns' :
        getDiffLevelTableColumns();
        break; 
	case 'getDiffLevelTableData' :
        getDiffLevelTableData();
        break;

    default :
        echo "{failure:true}";
        break;
}


function getArrayAllocate($tmpData) {
    $dataArray = array();
    foreach ($tmpData as $key => $value) {
        $dataArray[] = $value;
    }
    return $dataArray;
}

function getDiffLevelTableColumns() {
   
$StartDate = $_POST['dp1_start'];
$EndDate = $_POST['dp1_end'];

$query = "SELECT ProcessId, ProcessName, ProcessOrder
FROM t_process_list
WHERE t_process_list.ProcUnitId = 1
ORDER BY ProcessOrder;";

$result = mysql_query($query);

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
	}
}

 echo '{"COLUMNS":' . json_encode($aColumns) . '}';
}


function getDiffLevelTableData() {
   
$StartDate = $_POST['dp1_start'];
$EndDate = $_POST['dp1_end'];

$query = "SELECT ProcessId, ProcessName, ProcessOrder
FROM t_process_list
WHERE t_process_list.ProcUnitId = 1
ORDER BY ProcessOrder;";

$result = mysql_query($query);

$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');

$aData = array();
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

$aTemplateValues['Total'] = 0;

$aaData = array();

$tmpFacilityCode = '';
$tmpFacilityName = '';
$tmpItemName = '';
$tmpUnitName = '';
$sl = 0;

/* $sQuery = "SELECT 
				  t_process_tracking.TrackingNo, t_process_tracking.RegNo, t_process_tracking.ProcessId, t_process_list.ProcessName, t_process_list.ProcessOrder, t_process_tracking.InTime, t_process_tracking.OutTime,  Duration
				FROM
				  t_process_tracking 
				  INNER JOIN t_process_list 
					ON t_process_tracking.ProcessId = t_process_list.ProcessId 
				WHERE EntryDate >= '$StartDate' 
				  AND EntryDate <= '$EndDate' 
				  AND t_process_tracking.ProcUnitId = 1
				ORDER BY t_process_tracking.TrackingNo, t_process_list.ProcessOrder;"; */
				
$sQuery = "SELECT 
				  RegNo, ProcessName, ProcessOrder, InTime, OutTime,  Duration
				FROM
				  t_process_tracking 
				  INNER JOIN t_process_list 
					ON t_process_tracking.ProcessId = t_process_list.ProcessId 
				WHERE RegNo IN (SELECT RegNo
				FROM
				  t_process_tracking 
				WHERE EntryDate >= '$StartDate' 
				  AND EntryDate <= '$EndDate' 
				  AND ProcUnitId = 1)
				ORDER BY RegNo, ProcessOrder;";

$rResult = mysql_query($sQuery);

//$holidays = array("2015-06-18","2015-06-19");

if ($rResult) {
	while ($data = mysql_fetch_object($rResult)) {
		$TrackNo = $data -> RegNo;
		if ($TrackNo != $tmpFacilityCode) {

			// get each row to a array when it changes it state so in this case last row always skipped
			if (!is_null($row)) {
				$row['Total'] = is_null($row['Total'])? '' : convertToHoursMins($row['Total'],'%02d hours %02d minutes');
				$tmpRow = array_values($row);
				array_unshift($tmpRow, ++$sl, $tmpFacilityName);
				$aaData[] = $tmpRow;
			}
			// initialize the $row the zero values array sized with the number of facility.
			$row = $aTemplateValues;
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = is_null($data -> InTime)? '' : date('d/m/Y g:i A', strtotime($data -> InTime));
			$row[$data -> ProcessOrder.'o'] = is_null($data -> OutTime)? '' : date('d/m/Y g:i A', strtotime($data -> OutTime));
			$row[$data -> ProcessOrder.'d'] = is_null($data -> Duration)? '' : convertToHoursMins($data -> Duration,'%02d hours %02d minutes');
			
			$row['Total'] += is_null($data -> Duration)? 0 : $data -> Duration;
			
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> RegNo;
			$tmpFacilityName = $data -> RegNo? $data -> RegNo : $data -> RegNo;
		} else {
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = is_null($data -> InTime)? '' : date('d/m/Y g:i A', strtotime($data -> InTime));
			$row[$data -> ProcessOrder.'o'] = is_null($data -> OutTime)? '' : date('d/m/Y g:i A', strtotime($data -> OutTime));
			$row[$data -> ProcessOrder.'d'] = is_null($data -> Duration)? '' : convertToHoursMins($data -> Duration,'%02d hours %02d minutes');
			
			$row['Total'] += is_null($data -> Duration)? 0 : $data -> Duration;
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> RegNo;
		}		
	}
}

$num_rows = mysql_num_rows($rResult);
if ($num_rows) {
	// get the last row that is skipped in the above loop
	$row['Total'] = is_null($row['Total'])? '' : convertToHoursMins($row['Total'],'%02d hours %02d minutes');
	$tmpRow = array_values($row);
	array_unshift($tmpRow, ++$sl, $tmpFacilityName);
	$aaData[] = $tmpRow;
}

 echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aaData) . '}';
}






