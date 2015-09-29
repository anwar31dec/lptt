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
function getJobListForSearch(){
    $StartDate = $_POST['dp1_start'];
    $EndDate = $_POST['dp1_end'];
    $ProcessId = $_POST['ProcessId'];
    $ProcUnitId = 1;

    //Criteria columns
    $aCriColumns = array('SL', 'TrackingNo'
        , 'OutTime'
        , 'Name'
        , 'TxtDuration');

    $sWhere = '';
    $sLimit = "";
    if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . intval($_POST['iDisplayStart']) . ", " . intval($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = "ORDER BY  ";
        for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
            if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
                $sOrder .= "`" . $aCriColumns[intval($_POST['iSortCol_' . $i])] . "` " . ($_POST['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
            }
        }
        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }

    for ($i = 0; $i < count($aCriColumns); $i++) {
        if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true" && $_POST['sSearch'] != '') {
            if ($sWhere == "") {
                $sWhere = " AND (";
            } else {
                $sWhere .= " OR ";
            }
            $sWhere .= "`" . $aCriColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ";
        }
    }

    if ($sWhere != "")
        $sWhere .= ")";

    $sql = "SELECT SQL_CALC_FOUND_ROWS
                t_process_tracking.TrackingNo
                , t_process_tracking.OutTime
                , ykx9st_users.Name
                , t_process_tracking.TxtDuration
            FROM
                t_process_tracking
                INNER JOIN ykx9st_users 
                    ON (t_process_tracking.InUserId = ykx9st_users.username)
            WHERE t_process_tracking.ProcUnitId = $ProcUnitId"
            . " $sWhere $sOrder $sLimit;";
//        echo $sql;
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
        $sOutput .= '"' . $aRow['TrackingNo'] . '",';
        $sOutput .= '"' . (is_null($aRow['OutTime']) ? 'Pending' : 'Complited') . '",';
        $sOutput .= '"' . $aRow['Name'] . '",';
        $sOutput .= '"' . $aRow['TxtDuration'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}
    
