<?php

include_once ('database_conn.php');
include_once ("function_lib.php");

mysql_query('SET CHARACTER SET utf8');

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {
    case -'getJobListForSearch':
        getJobListForSearch();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getJobListForSearch() {
    $StartDate = $_POST['dp1_start'];
    $EndDate = $_POST['dp1_end'];
    $ProcessId = $_POST['ProcessId'];
    $ProcUnitId = 1;

    if ($_POST['sSearch'] != '') {
        $RegNo = mysql_real_escape_string($_POST['sSearch']);
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS
                t_process_tracking.RegNo
                , t_process_list.ProcessName
                , t_process_tracking.InTime
                , t_process_tracking.OutTime
                , t_process_tracking.bHold
                , t_process_tracking.HoldComments
                , ykx9st_users.Name
                , t_process_tracking.TxtDuration
            FROM
                t_process_tracking
                INNER JOIN ykx9st_users 
                    ON (t_process_tracking.InUserId = ykx9st_users.username)
                INNER JOIN t_process_list 
                    ON (t_process_tracking.ProcessId = t_process_list.ProcessId)
            WHERE t_process_tracking.ProcUnitId = $ProcUnitId"
            . " AND (TrackingNo = '$RegNo' OR RegNo = '$RegNo')"
            . " ORDER BY t_process_list.ProcessId;";
//    echo $sql;
//    exit;

    $result = mysql_query($sql);

    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    $sOutput = '{';
    $sOutput .= '"sEcho": ' . intval($_POST['sEcho']) . ', ';
    $sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
    $sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
    $sOutput .= '"aaData": [ ';
    $serial = $_POST['iDisplayStart'] + 1;
    $f = 0;

    while ($aRow = mysql_fetch_array($result)) {        
        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['RegNo'] . '",';
        $sOutput .= '"' . $aRow['ProcessName'] . '",';
        $sOutput .= '"' . date('d/m/Y g:i A', strtotime($aRow['InTime'])) . '",';
        $sOutput .= '"' . (is_null($aRow['OutTime'])? '' :  date('d/m/Y g:i A', strtotime($aRow['OutTime']))) . '",';
        $sOutput .= '"' . (is_null($aRow['OutTime']) ? "<span style='color:#ff0000;'>Pending</span>" : "<span style='color:#00ff00;'>Completed</span>") . '",';
        $sOutput .= '"' . $aRow['Name'] . '",';
        $sOutput .= '"' . $aRow['TxtDuration'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}
