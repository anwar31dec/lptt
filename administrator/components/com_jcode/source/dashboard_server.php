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
    case 'getProcessCount' :
        getProcessCount();
        break;
    case 'getTotalInOutCount' :
        getTotalInOutCount();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getProcessColumns() {

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

    $MonthId = ctype_digit($_POST['MonthId']) ? $_POST['MonthId'] : '';
    $YearId = isset($_POST['YearId']) ? $_POST['YearId'] : '';

    $StartDate = $YearId . '-' . $MonthId . '-01';
    $EndDate = $YearId . '-' . $MonthId . '-30';

    $query = "SELECT ProcessId, ProcessName, ProcessOrder
    FROM t_process_list
	WHERE t_process_list.ProcUnitId = 2
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
            $po = $rec->ProcessOrder; // for InTime

            $aColumns[] = $rec->ProcessName; // for InTime

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
			WHERE OutTime IS NULL AND EntryDate BETWEEN '2015-06-01' AND '2015-08-30'
			GROUP BY t_process_tracking.ProcessId
			ORDER BY t_process_list.ProcessOrder;";


    $rResult = mysql_query($sQuery);

//var_dump($rResult);

    $row = $aTemplateValues;
    $total = 0;

    if ($rResult) {
        while ($data = mysql_fetch_object($rResult)) {
            // collecting data for the facility
            $row[$data->ProcessOrder] = $data->TotalInEachProcess;
            //$aaData[] = array_values($row);
            $total += $data->TotalInEachProcess;
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

function getProcessCount() {
    $StartDate = $_POST['dp1-start'];
    $EndDate = $_POST['dp1-end'];
    $sQuery = "SELECT
				SQL_CALC_FOUND_ROWS t_process_list.ProcessOrder, t_process_list.ProcessName, COUNT(*) Total, t_process_tracking.ProcessId
			FROM
				t_process_tracking
				LEFT JOIN t_process_list 
					ON (t_process_tracking.ProcessId = t_process_list.ProcessId)
			WHERE OutTime IS NULL AND EntryDate BETWEEN '$StartDate' AND '$EndDate'
			AND t_process_tracking.ProcUnitId = 2
			GROUP BY t_process_tracking.ProcessId
			ORDER BY t_process_list.ProcessOrder;";

    $rResult = mysql_query($sQuery);

    //$rows = array();
    //$sOutput .= '"aaData": [ ';
    if ($rResult) {
        $sQuery = "SELECT FOUND_ROWS()";
        $rResultFilterTotal = mysql_query($sQuery);
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];

        $sOutput = '{';
        $sOutput .= '"sEcho": ' . intval($_POST['sEcho']) . ', ';
        $sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
        $sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
        $sOutput .= '"aaData":[';
        $f = 0;
        while ($aRow = mysql_fetch_assoc($rResult)) {
            if ($f++)
                $sOutput .= ',';
            $sOutput .= "[";
            $sOutput .= '"' . $aRow['ProcessOrder'] . '",';
            $sOutput .= '"' . $aRow['ProcessName'] . '",';
            $sOutput .= '"' . '<a style=\'text-decoration: underline;\' href=\'reports/job-total-time-duration?ProcessId=1\'>' . number_format($aRow['Total']) . '</a>' . '",';
            $sOutput .= '"' . $aRow['ProcessId'] . '"';
            $sOutput .= "]";
        }
    }
    $sOutput .= ']}';
    echo $sOutput;
}

function getTotalInOutCount() {
    $StartDate = $_POST['dp1-start'];
    $EndDate = $_POST['dp1-end'];
    $sQuery = "SELECT 
				  SQL_CALC_FOUND_ROWS 'Total In' ProcInOut, COUNT(*) TotalIn 
				FROM
				  (SELECT DISTINCT 
					TrackingNo 
				  FROM
					t_process_tracking
				  WHERE t_process_tracking.ProcessId = 23 AND EntryDate BETWEEN '$StartDate' 
					AND '$EndDate'
					AND t_process_tracking.ProcUnitId = 2) a;";

    $rResult1 = mysql_query($sQuery);

    $aRow1 = mysql_fetch_assoc($rResult1);
    //print_r($aRow);

    $sQuery2 = "SELECT
		SQL_CALC_FOUND_ROWS 'Total Out' ProcInOut, COUNT(*) TotalClosed
	FROM
		t_process_tracking
		INNER JOIN t_process_list 
			ON (t_process_tracking.ProcessId = t_process_list.ProcessId)
	WHERE OutTime IS NOT NULL AND t_process_list.ProcessId = 36 AND EntryDate BETWEEN '$StartDate' AND '$EndDate'
	AND t_process_tracking.ProcUnitId = 2;";

    $rResult2 = mysql_query($sQuery2);

    $aRow2 = mysql_fetch_assoc($rResult2);
    //print_r($aRow2);
    //exit;
    $aaRows = array('TotalIn' => $aRow1['TotalIn'], 'TotalClosed' => $aRow2['TotalClosed']);
    //print_r($aaRows);
    //exit;
    //$rows = array();
    //$sOutput .= '"aaData": [ ';
    if ($rResult1 && $rResult2) {
        $sQuery = "SELECT FOUND_ROWS()";
        $rResultFilterTotal = mysql_query($sQuery);
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];

        $sOutput = '{';
        $sOutput .= '"sEcho": ' . intval($_POST['sEcho']) . ', ';
        $sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
        $sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
        $sOutput .= '"aaData":[';
        $f = 0;

        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aaRows['TotalIn'] . '",';
        $sOutput .= '"' . $aaRows['TotalClosed'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= ']}';
    echo $sOutput;
}

?>
