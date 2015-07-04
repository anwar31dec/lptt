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
	case 'getProcessColumns' :
        getProcessColumns();
        break;
    case 'getJobCountInAllProcess' :
        getJobCountInAllProcess();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getProcessColumns(){
	
	$query = "SELECT ProcessId, ProcessName, ProcessOrder
	FROM t_process_list
	ORDER BY ProcessOrder;";

//echo $query;

	$result = mysql_query($query);
	
	$output = array();
	
	//$aColumns = array('ProcessId', 'bEntered', 'bSubmitted', 'bAccepted', 'bPublished');

	while ($row = mysql_fetch_array($result)) {
		/* for ($i = 0; $i < count($aColumns); $i++) {
			if ($aColumns[$i] == "bEntered") {
				//'<span class="glyphicon glyphicon-ok-circle" style="color:#ff0000;font-size:2em;"></span>'
				$row[] = ($aRow[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>';
				
			}
			else if ($aColumns[$i] == "bSubmitted") {
				$row[] = ($aRow[$aColumns[$i]] == "0") ? '<span class="label label-danger">&nbsp No &nbsp</span>' : '<span class="label label-success">&nbsp Yes &nbsp</span>';					
				
			}
		}
		 */
		$tmpRow['sTitle'] = $row['ProcessName'];
		$tmpRow['sClass'] = 'right-aln';
		$tmpRow['sWidth'] = '14%';
		$output[] = $tmpRow;
	}
	$output[] = array('sTitle' => 'Total', 'sWidth' => '5%');
	
	echo json_encode($output);
}


function getJobCountInAllProcess() {

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
		$po = $rec -> ProcessOrder; // for InTime
		
		$aColumns[] = $rec -> ProcessName; // for InTime

		$aData[] = $rec;
		///get the initial value for each facility, if a facility have no value for a specific item then
		//the following array keep track the zero value for that position.
		$aTemplateValues[$po] = '';
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
				t_process_tracking.ProcessId, t_process_list.ProcessOrder, t_process_list.ProcessName, COUNT(*) TotalInEachProcess
			FROM
				t_process_tracking
				INNER JOIN t_process_list 
					ON (t_process_tracking.ProcessId = t_process_list.ProcessId)
			WHERE OutTime IS NULL AND EntryDate BETWEEN '2015-06-01' AND '2015-07-03'
			GROUP BY t_process_tracking.ProcessId
			ORDER BY t_process_list.ProcessOrder;";
	
	
$rResult = mysql_query($sQuery);

//var_dump($rResult);

$row =  $aTemplateValues;
$total = 0;

if ($rResult) {
	while ($data = mysql_fetch_object($rResult)) {		
			// collecting data for the facility
			$row[$data -> ProcessOrder] = $data -> TotalInEachProcess;
			//$aaData[] = array_values($row);
			$total += $data -> TotalInEachProcess;
	}
}
//exit;
$row['Total'] = $total;
//print_r($row);

$tmpRow = array_values($row);
$aaData[] = $tmpRow;


//$clmMonthYear = array_values($aMonthYear);
//array_unshift($clmMonthYear, 'Warhouse Name');

//echo '{"sEcho": 0, "iTotalRecords":"10","iTotalDisplayRecords": "10","aaData":' . json_encode($aaData, JSON_NUMERIC_CHECK) . '}';

 echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aaData) . '';
 echo ',"COLUMNS":' . json_encode($aColumns) . '}';
}

?>
