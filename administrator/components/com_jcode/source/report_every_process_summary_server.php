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
    case 'getEveryProcessData' :
        getEveryProcessData();
        break;
    case 'getProcessStatus' :
        getProcessStatus();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getEveryProcessData() {

    $StartDate = $_POST['dp1_start'];
    $EndDate = $_POST['dp1_end'];
    $ProcessId = $_POST['ProcessId'];
    $ProcUnitId = 1;

    $query = "SELECT
  `Name` UserName, COUNT(*) Pending
FROM
  t_process_tracking 
  INNER JOIN ykx9st_users 
    ON t_process_tracking.InUserId = ykx9st_users.username 
WHERE InTime IS NOT NULL 
  AND OutTime IS NULL 
  AND EntryDate BETWEEN '$StartDate' 
  AND '$EndDate' 
  AND ProcUnitId = $ProcUnitId 
  AND ProcessId = $ProcessId 
GROUP BY InUserId;";

//echo $query;

    $result = mysql_query($query);

    $aCriColumns = array();

    if ($result) {
        while ($rec = mysql_fetch_object($result)) {
            //get dynamic columns for the datatable		
            $aCriColumns[] = $rec->UserName . ' (Pending)';
        }
    }

//array_unshift($aCriColumns,'Total In','Total Out', 'Total Pending');

    $aData = array();
    $aaData = array();

    $sQuery = "SELECT 
  a.ProcessName, b.TotalIn, c.TotalOut, d.TotalPending 
FROM
  t_process_list a 
  LEFT JOIN 
    (SELECT 
      ProcessId, COUNT(*) TotalIn 
    FROM
      t_process_tracking 
    WHERE EntryDate BETWEEN '$StartDate' 
      AND '$EndDate' 
      AND ProcUnitId = $ProcUnitId 
      AND ProcessId = $ProcessId
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
      AND ProcUnitId = $ProcUnitId
      AND ProcessId = $ProcessId
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
      AND ProcUnitId = $ProcUnitId 
      AND ProcessId = $ProcessId 
    GROUP BY ProcessId) d 
    ON a.ProcessId = d.ProcessId 
WHERE a.ProcUnitId = $ProcUnitId
  AND a.ProcessId = $ProcessId 
ORDER BY a.ProcessId;";
//echo $sQuery;
//exit;


    $rResult = mysql_query($sQuery);

    if ($rResult) {
        while ($data = mysql_fetch_object($rResult)) {
            //$aData[] = 1;
            $aData[] = $data->TotalIn;
            $aData[] = $data->TotalOut;
            $aData[] = $data->TotalPending;
        }
    }

    $sQuery = "SELECT 
  `Name` UserName, COUNT(*) Pending 
FROM
  t_process_tracking 
  INNER JOIN ykx9st_users 
    ON t_process_tracking.InUserId = ykx9st_users.username 
WHERE InTime IS NOT NULL 
  AND OutTime IS NULL 
  AND EntryDate BETWEEN '$StartDate' 
  AND '$EndDate' 
  AND ProcUnitId = $ProcUnitId
  AND ProcessId = $ProcessId
GROUP BY InUserId;";
//echo $sQuery;
//exit;


    $rResult = mysql_query($sQuery);

    if ($rResult) {
        while ($data = mysql_fetch_object($rResult)) {
            $aData[] = $data->Pending;
        }
    }

    $aaData[] = $aData;

    echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"1","iTotalDisplayRecords": "1", "aaData":' . json_encode($aaData) . '';
    echo ',"COLUMNS":' . json_encode($aCriColumns) . '}';
}

function getProcessStatus() {
    $StartDate = $_POST['dp1_start'];
    $EndDate = $_POST['dp1_end'];
    $ProcessId = $_POST['ProcessId'];
    $ProcUnitId = 1;

//    print_r($_POST);
//    exit;
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
            WHERE (t_process_tracking.ProcessId = $ProcessId
                AND t_process_tracking.ProcUnitId = $ProcUnitId
                AND EntryDate BETWEEN '$StartDate' AND '$EndDate')"
            . " $sWhere $sOrder $sLimit;";
    
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
        $sOutput .= '"' . $aRow['TrackingNo'] . '",';
        $sOutput .= '"' . (is_null($aRow['OutTime']) ? 'Pending' : 'Complited') . '",';
        $sOutput .= '"' . $aRow['Name'] . '",';
        $sOutput .= '"' . $aRow['TxtDuration'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}
