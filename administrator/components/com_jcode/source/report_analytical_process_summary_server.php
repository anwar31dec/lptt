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
    case 'getProcessCount' :
        getProcessCount();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getProcessCount() {
    $StartDate = $_POST['dp1-start'];
    $EndDate = $_POST['dp1-end'];
    $sQuery = "SELECT
				SQL_CALC_FOUND_ROWS a.ProcessName, b.TotalIn, c.TotalOut, d.TotalPending 
		FROM
		  t_process_list a 
		  LEFT JOIN 
			(SELECT 
			  ProcessId, COUNT(*) TotalIn 
			FROM
			  t_process_tracking 
			WHERE EntryDate BETWEEN '$StartDate' 
			  AND '$EndDate' 
			  AND ProcUnitId = 2 
			GROUP BY ProcessId) b 
			ON a.ProcessId = b.ProcessId 
		  LEFT JOIN 
			(SELECT 
			  ProcessId, COUNT(*) TotalOut 
			FROM
			  t_process_tracking 
			WHERE InTime IS NOT NULL 
			  AND OutTime IS NOT NULL 
			  AND EntryDate BETWEEN '$StartDate' 
			  AND '$EndDate' 
			  AND ProcUnitId = 2 
			GROUP BY ProcessId) c 
			ON a.ProcessId = c.ProcessId 
		  LEFT JOIN 
			(SELECT 
			  ProcessId, COUNT(*) TotalPending 
			FROM
			  t_process_tracking 
			WHERE InTime IS NOT NULL 
			  AND OutTime IS NULL 
			  AND EntryDate BETWEEN '$StartDate' 
			  AND '$EndDate' 
			  AND ProcUnitId = 2 
			GROUP BY ProcessId) d 
			ON a.ProcessId = d.ProcessId 
		WHERE a.ProcUnitId = 2 
		ORDER BY  a.ProcessId;";

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
		$serial = $_POST['iDisplayStart'] + 1;
        $f = 0;
        while ($aRow = mysql_fetch_assoc($rResult)) {
            if ($f++)
                $sOutput .= ',';
            $sOutput .= "[";
            $sOutput .= '"' . $serial++ . '",';
            $sOutput .= '"' . $aRow['ProcessName'] . '",';
            //$sOutput .= '"' . '<a style=\'text-decoration: underline;\' href=\'reports/job-total-time-duration?ProcessId=1\'>' . number_format($aRow['TotalIn']) . '</a>' . '",';
            $sOutput .= '"' . $aRow['TotalIn'] . '",';
            $sOutput .= '"' . $aRow['TotalOut'] . '",';
            $sOutput .= '"' . $aRow['TotalPending'] . '"';
            $sOutput .= "]";
        }
    }
    $sOutput .= ']}';
    echo $sOutput;
}


?>
