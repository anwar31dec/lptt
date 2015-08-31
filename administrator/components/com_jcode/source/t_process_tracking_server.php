<?php
include_once ('database_conn.php');
include_once ("function_lib.php");
include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');

$gTEXT = $TEXT;
$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {
    case "getProcessTrackingData" :
        getProcessTrackingData($conn);
        break;
    case "getProcessTrackingData8" :
        getProcessTrackingData8($conn);
        break;
    case "getProcessTrackingData18" :
        getProcessTrackingData18($conn);
        break;
    case "getProcessTrackingData19" :
        getProcessTrackingData19($conn);
        break;
    case "getWaitingProcessList" :
        getWaitingProcessList($conn);
        break;
    case "insertUpdateProcessTracking" :
        insertUpdateProcessTracking($conn);
        break;
    default :
        echo "{failure:true}";
        break;
}

function getProcessTrackingData($conn) {

    global $gTEXT;

    date_default_timezone_set("Asia/Dhaka");

    $lan = $_POST['lan'];
    $ProcessId = $_POST['ProcessId'];
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ProcessTrackingList(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND  (a.TrackingNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
                " a.RegNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " b.ProcessName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.InTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.OutTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }
	
    $sql = '';
	
	if($ProcessId == 1){
		$sql = "SELECT 
			SQL_CALC_FOUND_ROWS a.ProTrackId, a.TrackingNo, a.RegNo, b.ProcessId, b.ProcessName, b.ProcessOrder, a.InTime, a.OutTime 
			, TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
			, UsualDuration
			, (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
			FROM
			  t_process_tracking a 
			  INNER JOIN t_process_list b 
				ON (a.ProcessId = b.ProcessId)
			WHERE a.ProcessId IN (1, 23) 
			  AND a.OutTime IS NULL 
                    $sWhere 
                    $sOrder 
                    $sLimit ";
	} else{
		$sql = "SELECT 
			SQL_CALC_FOUND_ROWS a.ProTrackId, a.TrackingNo, a.RegNo, b.ProcessId, b.ProcessName, b.ProcessOrder, a.InTime, a.OutTime 
			, TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
			, UsualDuration
			, (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
			FROM
			  t_process_tracking a 
			  INNER JOIN t_process_list b 
				ON (a.ProcessId = b.ProcessId)
			WHERE a.ProcessId = $ProcessId 
			  AND a.OutTime IS NULL 
                    $sWhere 
                    $sOrder 
                    $sLimit ";
	}



    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
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
        $sOutput .= '"' . $aRow['ProTrackId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . ($aRow['RegNo'] ? $aRow['RegNo'] : $aRow['TrackingNo']) . '",';
        $sOutput .= '"' . $aRow['ProcessName'] . '",';
        $sOutput .= '"' . date('d/m/Y g:i A', strtotime($aRow['InTime'])) . '",';
        $sOutput .= '"' . $aRow['OutTime'] . '",';
        $sOutput .= '"' . convertToHoursMins($aRow['Duration'], '%02d hours %02d minutes') . '",';

        //$statusTime = $aRow['Status'] < 0 ? abs($aRow['Status']) : abs($aRow['Status']);
        $statusTime = $aRow['Status'] < 0 ? "<span style='color:#078C09;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes remaining') . "</span>" : "<span style='color:#ff0000;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes delay' . "</span>");

        $sOutput .= '"' . $statusTime . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

/* Get process data 8 */

function getProcessTrackingData8($conn) {

    global $gTEXT;

    date_default_timezone_set("Asia/Dhaka");

    $lan = $_POST['lan'];
    $ProcessId = $_POST['ProcessId'];
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ProcessTrackingList(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND  (a.TrackingNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
                " a.RegNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " b.ProcessName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.InTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.OutTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    /*  $sql = "SELECT SQL_CALC_FOUND_ROWS
      t_process_tracking.ProTrackId
      , t_process_tracking.TrackingNo
      , t_process_tracking.RegNo
      , t_process_list.ProcessId
      , t_process_list.ProcessName
      , t_process_list.ProcessOrder
      , t_process_tracking.InTime
      , t_process_tracking.OutTime
      , TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
      , UsualDuration
      , (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
      FROM
      t_process_tracking
      INNER JOIN t_process_list
      ON (t_process_tracking.ProcessId = t_process_list.ProcessId)
      WHERE t_process_tracking.ProcessId = $ProcessId AND t_process_tracking.OutTime IS NULL
      $sWhere
      $sOrder
      $sLimit "; */

    /* $sql = "SELECT 
      SQL_CALC_FOUND_ROWS a.ProTrackId, a.TrackingNo, a.RegNo, b.ProcessId, b.ProcessName, b.ProcessOrder, a.InTime, a.OutTime
      , TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
      , UsualDuration
      , (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
      , (SELECT
      OutTime
      FROM
      t_process_tracking
      WHERE RegNo = a.RegNo
      AND ProcessId = 5) OutTimeWet,
      (SELECT
      OutTime
      FROM
      t_process_tracking
      WHERE RegNo = a.RegNo
      AND ProcessId = 6) OutTimeMec,
      (SELECT
      OutTime
      FROM
      t_process_tracking
      WHERE RegNo = a.RegNo
      AND ProcessId = 7) OutTimePil
      FROM
      t_process_tracking a
      INNER JOIN t_process_list b
      ON (a.ProcessId = b.ProcessId)
      WHERE a.ProcessId = 8
      AND a.OutTime IS NULL
      $sWhere
      $sOrder
      $sLimit "; */
    $sql = "SELECT 
			SQL_CALC_FOUND_ROWS a.ProTrackId, a.TrackingNo, a.RegNo, b.ProcessId, b.ProcessName, b.ProcessOrder, a.InTime, a.OutTime 
			, TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
			, UsualDuration
			, (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
			, p.RegNo RegNoWet, p.OutTime OutTimeWet, q.RegNo RegNoMec, q.OutTime OutTimeMec, r.RegNo RegNoPil, r.OutTime OutTimePil 
			FROM
			  t_process_tracking a 
			  INNER JOIN t_process_list b 
				ON (a.ProcessId = b.ProcessId) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 5) p 
				ON (a.RegNo = p.RegNo) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 6) q 
				ON (a.RegNo = q.RegNo) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 7) r 
				ON (a.RegNo = r.RegNo) 
			WHERE a.ProcessId = $ProcessId 
			  AND a.OutTime IS NULL 
                    $sWhere 
                    $sOrder 
                    $sLimit ";
    // echo $sql;
    // exit;



    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
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
        $sOutput .= '"' . $aRow['ProTrackId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . ($aRow['RegNo'] ? $aRow['RegNo'] : $aRow['TrackingNo']) . '",';
        $sOutput .= '"' . $aRow['ProcessName'] . '",';
        $sOutput .= '"' . date('d/m/Y g:i A', strtotime($aRow['InTime'])) . '",';
        $sOutput .= '"' . $aRow['OutTime'] . '",';
        $sOutput .= '"' . convertToHoursMins($aRow['Duration'], '%02d hours %02d minutes') . '",';

        //$statusTime = $aRow['Status'] < 0 ? abs($aRow['Status']) : abs($aRow['Status']);
        $statusTime = $aRow['Status'] < 0 ? "<span style='color:#078C09;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes remaining') . "</span>" : "<span style='color:#ff0000;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes delay' . "</span>");

        $sOutput .= '"' . $statusTime . '",';

        $wetStatus = '';
        if ($aRow['RegNoWet'] && $aRow['OutTimeWet']) {
            $parentStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoWet'] && !$aRow['OutTimeWet']) {
            $parentStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoWet'] && !$aRow['OutTimeWet']) {
            $parentStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $mecStatus = '';
        if ($aRow['RegNoMec'] && $aRow['OutTimeMec']) {
            $mecStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoMec'] && !$aRow['OutTimeMec']) {
            $mecStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoMec'] && !$aRow['OutTimeMec']) {
            $mecStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $pilStatus = '';
        if ($aRow['RegNoPil'] && $aRow['OutTimePil']) {
            $pilStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoPil'] && !$aRow['OutTimePil']) {
            $pilStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoPil'] && !$aRow['OutTimePil']) {
            $pilStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $sOutput .= '"' . $parentStatus . '",';
        $sOutput .= '"' . $mecStatus . '",';
        $sOutput .= '"' . $pilStatus . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

/* Get data for process 18 */

function getProcessTrackingData18($conn) {

    global $gTEXT;

    date_default_timezone_set("Asia/Dhaka");

    $lan = $_POST['lan'];
    $ProcessId = $_POST['ProcessId'];
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ProcessTrackingList(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND  (a.TrackingNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
                " a.RegNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " b.ProcessName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.InTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.OutTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT 
			SQL_CALC_FOUND_ROWS a.ProTrackId, a.TrackingNo, a.RegNo, b.ProcessId, b.ProcessName, b.ProcessOrder, a.InTime, a.OutTime 
			, TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
			, UsualDuration
			, (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
			, p.RegNo RegNoPhy, p.OutTime OutTimePhy, q.RegNo RegNoCol, q.OutTime OutTimeCol, r.RegNo RegNoFib, r.OutTime OutTimeFib 
			FROM
			  t_process_tracking a 
			  INNER JOIN t_process_list b 
				ON (a.ProcessId = b.ProcessId) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 9) p 
				ON (a.RegNo = p.RegNo) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 13) q 
				ON (a.RegNo = q.RegNo) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 16) r 
				ON (a.RegNo = r.RegNo) 
			WHERE a.ProcessId = $ProcessId 
			  AND a.OutTime IS NULL 
                    $sWhere 
                    $sOrder 
                    $sLimit ";
    // echo $sql;
    // exit;



    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
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
        $sOutput .= '"' . $aRow['ProTrackId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . ($aRow['RegNo'] ? $aRow['RegNo'] : $aRow['TrackingNo']) . '",';
        $sOutput .= '"' . $aRow['ProcessName'] . '",';
        $sOutput .= '"' . date('d/m/Y g:i A', strtotime($aRow['InTime'])) . '",';
        $sOutput .= '"' . $aRow['OutTime'] . '",';
        $sOutput .= '"' . convertToHoursMins($aRow['Duration'], '%02d hours %02d minutes') . '",';

        //$statusTime = $aRow['Status'] < 0 ? abs($aRow['Status']) : abs($aRow['Status']);
        $statusTime = $aRow['Status'] < 0 ? "<span style='color:#078C09;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes remaining') . "</span>" : "<span style='color:#ff0000;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes delay' . "</span>");

        $sOutput .= '"' . $statusTime . '",';

        $phyStatus = '';
        if ($aRow['RegNoPhy'] && $aRow['OutTimePhy']) {
            $phyStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoPhy'] && !$aRow['OutTimePhy']) {
            $phyStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoPhy'] && !$aRow['OutTimePhy']) {
            $phyStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $colStatus = '';
        if ($aRow['RegNoCol'] && $aRow['OutTimeCol']) {
            $colStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoCol'] && !$aRow['OutTimeCol']) {
            $colStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoCol'] && !$aRow['OutTimeCol']) {
            $colStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $fibStatus = '';
        if ($aRow['RegNoFib'] && $aRow['OutTimeFib']) {
            $fibStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoFib'] && !$aRow['OutTimeFib']) {
            $fibStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoFib'] && !$aRow['OutTimeFib']) {
            $fibStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $sOutput .= '"' . $phyStatus . '",';
        $sOutput .= '"' . $colStatus . '",';
        $sOutput .= '"' . $fibStatus . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

/* Get data for process 19 */

function getProcessTrackingData19($conn) {

    global $gTEXT;

    date_default_timezone_set("Asia/Dhaka");

    $lan = $_POST['lan'];
    $ProcessId = $_POST['ProcessId'];
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ProcessTrackingList(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND  (a.TrackingNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
                " a.RegNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " b.ProcessName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.InTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " a.OutTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT 
			SQL_CALC_FOUND_ROWS a.ProTrackId, a.TrackingNo, a.RegNo, b.ProcessId, b.ProcessName, b.ProcessOrder, a.InTime, a.OutTime 
			, TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
			, UsualDuration
			, (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
			, p.RegNo RegNoCom, p.OutTime OutTimeCom, q.RegNo RegNoSub, q.OutTime OutTimeSub
			FROM
			  t_process_tracking a 
			  INNER JOIN t_process_list b 
				ON (a.ProcessId = b.ProcessId) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 18) p 
				ON (a.RegNo = p.RegNo) 
			  LEFT JOIN 
				(SELECT 
				  RegNo, OutTime 
				FROM
				  t_process_tracking 
				WHERE ProcessId = 17) q 
				ON (a.RegNo = q.RegNo)
			WHERE a.ProcessId = $ProcessId 
			  AND a.OutTime IS NULL 
                    $sWhere 
                    $sOrder 
                    $sLimit ";
    // echo $sql;
    // exit;

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
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
        $sOutput .= '"' . $aRow['ProTrackId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . ($aRow['RegNo'] ? $aRow['RegNo'] : $aRow['TrackingNo']) . '",';
        $sOutput .= '"' . $aRow['ProcessName'] . '",';
        $sOutput .= '"' . date('d/m/Y g:i A', strtotime($aRow['InTime'])) . '",';
        $sOutput .= '"' . $aRow['OutTime'] . '",';
        $sOutput .= '"' . convertToHoursMins($aRow['Duration'], '%02d hours %02d minutes') . '",';

        //$statusTime = $aRow['Status'] < 0 ? abs($aRow['Status']) : abs($aRow['Status']);
        $statusTime = $aRow['Status'] < 0 ? "<span style='color:#078C09;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes remaining') . "</span>" : "<span style='color:#ff0000;'>" . convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes delay' . "</span>");

        $sOutput .= '"' . $statusTime . '",';

        $comStatus = '';
        if ($aRow['RegNoCom'] && $aRow['OutTimeCom']) {
            $comStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoCom'] && !$aRow['OutTimeCom']) {
            $comStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoCom'] && !$aRow['OutTimeCom']) {
            $comStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $subStatus = '';
        if ($aRow['RegNoSub'] && $aRow['OutTimeSub']) {
            $subStatus = "<span style='color:#00ff00;'>Received</span>";
        } else if ($aRow['RegNoSub'] && !$aRow['OutTimeSub']) {
            $subStatus = "<span style='color:#ff0000;'>Not Received</span>";
        } else if (!$aRow['RegNoSub'] && !$aRow['OutTimeSub']) {
            $subStatus = "<span style='color:#0000ff;'>na</span>";
        }

        $sOutput .= '"' . $comStatus . '",';
        $sOutput .= '"' . $subStatus . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_ProcessTrackingList($i) {
    if ($i == 0)
        return "ProTrackId";
    elseif ($i == 1)
        return "TrackingNo";
    elseif ($i == 2)
        return "ProcessName";
}

function getWaitingProcessList($conn) {

    global $gTEXT;

    date_default_timezone_set("Asia/Dhaka");

    $lan = $_POST['lan'];
    $ProcessId = $_POST['ProcessId'];
    $ProcessOrder = $_POST['ProcessOrder'];
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ProcessTrackingList(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND  (t_process_tracking.TrackingNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
                " t_process_list.ProcessName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " t_process_tracking.InTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                " t_process_tracking.OutTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT 
				  SQL_CALC_FOUND_ROWS DISTINCT a.RegNo, p.RegNo RegNoWet,  p.InTime InTimeWet, p.OutTime OutTimeWet, q.RegNo RegNoMec,  q.InTime InTimeMec, q.OutTime OutTimeMec, r.RegNo RegNoPil, r.InTime InTimePil, r.OutTime OutTimePil 
				FROM
				  t_process_tracking a 
				  INNER JOIN t_process_list b 
					ON (a.ProcessId = b.ProcessId) 
				  LEFT JOIN 
					(SELECT 
					  RegNo, InTime, OutTime 
					FROM
					  t_process_tracking 
					WHERE ProcessId = 5) p 
					ON (a.RegNo = p.RegNo) 
				  LEFT JOIN 
					(SELECT 
					  RegNo, InTime, OutTime 
					FROM
					  t_process_tracking 
					WHERE ProcessId = 6) q 
					ON (a.RegNo = q.RegNo) 
				  LEFT JOIN 
					(SELECT 
					  RegNo, InTime, OutTime 
					FROM
					  t_process_tracking 
					WHERE ProcessId = 7) r 
					ON (a.RegNo = r.RegNo) 
				WHERE a.ProcessId IN (5,6,7)
                    $sWhere 
                    $sOrder 
                    $sLimit ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
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
        $sOutput .= '"' . $aRow['ProTrackId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['TrackingNo'] . '",';
        $sOutput .= '"' . $aRow['ProcessName'] . '",';
        $sOutput .= '"' . date('d/m/Y g:i A', strtotime($aRow['InTime'])) . '",';
        $sOutput .= '"' . $aRow['OutTime'] . '",';
        $sOutput .= '"' . convertToHoursMins($aRow['Duration'], '%02d hours %02d minutes') . '",';
        $sOutput .= '"' . ($aRow['Status'] < 0 ? abs($aRow['Status']) . ' minutes ahead' : abs($aRow['Status']) . ' minutes delay') . '",';
        $sOutput .= '"' . $aRow['ProcessId'] . '",';
        $sOutput .= '"' . $aRow['ProcessOrder'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function insertUpdateProcessTracking($conn) {
    date_default_timezone_set("Asia/Dhaka");
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $TrackingNo = strtoupper($_POST['TrackingNo']);
    $RegNo = strtoupper($_POST['RegNo']);
    $ProcessId = $_POST['ProcessId'];
    $ParentProcessId = $_POST['ParentProcessId'];  
	$OptUnit = $_POST['OptUnit'];
	$ProcUnitId = 1;	


    switch ($ProcessId) {
        case 1:
        case 2:
			if($ProcessId == 1){
				$ProcUnitId = $OptUnit;
			}

            if (!$TrackingNo) {
                echo json_encode(array('msgType' => 'error', 'msg' => 'Job no can not be empty.'));
                return;
            }

            if ($TrackingNo) {
                $aRecExistData2 = getRecExistInProcByInwardNo($TrackingNo, $ProcessId);
                // print_r($aRecExistData2);
                // exit;
                $OwnProTrackId = $aRecExistData2['ProTrackId'];
                //$OwnInTime = $aRecExistData2['InTime'];
                //$OwnOutTime = $aRecExistData2['OutTime'];

                if ($OwnProTrackId) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This Job is scanned already.'));
                    return;
                }
            }

            /* Update out time of parent */
            if ($ParentProcessId) {
                $aParentData = getParentProcessByInwardNo($TrackingNo, $ParentProcessId);

                if (!$aParentData) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'Job is not scanned by the previous process.'));
                    return;
                }

                $ProTrackId = $aParentData['ProTrackId'];
                $ParentInTime = $aParentData['InTime'];
                $ParentBHold = $aParentData['bHold'];
                
                if ($ParentBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                if (!$ProTrackId) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is not scanned by Inward.'));
                    exit();
                }

                $aParentTimeDuration = getTimeDuration($ParentInTime);
                $duration = $aParentTimeDuration['Duration'];
                $txtDuration = $aParentTimeDuration['txtDuration'];

                if ($ProTrackId) {
					$aQuery2 = getProTrackUpdateCmd($duration, $txtDuration, $jUserId, $ProTrackId);
                    $aQuerys[] = $aQuery2;
                }
            }

            /* Insert the current process */
            $aQuery1 = getProTrackInsertCmdInward($TrackingNo, $ProcessId, $jUserId, $ProcUnitId);
            $aQuerys[] = $aQuery1;

            echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE, FALSE));

            break;
        case 3:
            /* Start receiving from Photo Taking */
            $TrackingNoPt = $_POST['TrackingNoPt'];

            if ($TrackingNoPt) {

                $aRecExistData2 = getRecExistInProcByInwardNo($TrackingNoPt, $ParentProcessId);

                if (!$aRecExistData2) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'Job is not scanned by the previous process.'));
                    return;
                }

                $ParentProTrackId = $aRecExistData2['ProTrackId'];
                $ParentInTime = $aRecExistData2['InTime'];
                $ParentOutTime = $aRecExistData2['OutTime'];
                $ParentBHold = $aRecExistData2['bHold'];
                
                if ($ParentBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                if ($ParentOutTime) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'Photo taking job is received already.'));
                    return;
                }

                $aParentTimeDuration = getTimeDuration($ParentInTime);
                $duration = $aParentTimeDuration['Duration'];
                $txtDuration = $aParentTimeDuration['txtDuration'];

                if ($ParentProTrackId) {
                    $sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $ParentProTrackId;";

                    $aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('ProTrackId'), 'pk_values' => array($ParentProTrackId), 'bUseInsetId' => FALSE);

                    $aQuerys[] = $aQuery2;
                }

                echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));
                return;
            }
            /* End receiving from Photo Taking */

            /* Starting sample registration */
            if (!$TrackingNo) {
                echo json_encode(array('msgType' => 'error', 'msg' => 'Inward no can not be empty.'));
                return;
            }
            if (!$RegNo) {
                echo json_encode(array('msgType' => 'error', 'msg' => 'Registration no can not be empty.'));
                return;
            }
            if (getRecExistInProcByInwardNo($TrackingNo, $ProcessId)) {
                if ($TrackingNo && $RegNo) {
                    if (getRecExistInProcByInwardNoAndRegNo($TrackingNo, $RegNo, $ProcessId)) {
                        echo json_encode(array('msgType' => 'error', 'msg' => 'This Job is scanned already.'));
                        return;
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msg' => 'Inward no and Registration are not relevant.'));
                        return;
                    }
                }
            }

            /* Taking parent out time for registration in time */
            if ($ParentProcessId) {
                $aRecExistData3 = getParentProcessByInwardNo($TrackingNo, $ParentProcessId);
                if (!$aRecExistData3) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'Job is not scanned by the previous process.'));
                    return;
                }
                $ProTrackId = $aRecExistData3['ProTrackId'];
                $ParentInTime = $aRecExistData3['InTime'];
                $ParentOutTime = $aRecExistData3['OutTime'];
                $ParentEntryDate = $aRecExistData3['EntryDate'];
                
                $ParentBHold = $aRecExistData3['bHold'];
                
                if ($ParentBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                if (!$ParentOutTime) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'Photo taking out time is not set.'));
                    return;
                }

                if (!$ProTrackId) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is not scanned by Photo Taking.'));
                    exit();
                }
            }

            /* Insert the current process */
            $sql = "INSERT INTO t_process_tracking
				(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
				VALUES ('$TrackingNo', '$RegNo', $ProcessId, '$ParentOutTime', '$ParentEntryDate', YEAR('$ParentEntryDate'), MONTH('$ParentEntryDate'), '$jUserId', 1);";
            $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
            $aQuerys[] = $aQuery1;

            /* Update RegNo of ancestors */
            $sql3 = "UPDATE t_process_tracking
				SET RegNo = '$RegNo'
				WHERE TrackingNo = '$TrackingNo' AND RegNo = '$TrackingNo';";
            $aQuery3 = array('command' => 'UPDATE', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
            $aQuerys[] = $aQuery3;

            echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));

            break;
        case 8:
            if ($_POST['RegNoWet']) {
                $ParentProcessId = 5;
                $RegNo = $_POST['RegNoWet'];
            } else if ($_POST['RegNoMec']) {
                $ParentProcessId = 6;
                $RegNo = $_POST['RegNoMec'];
            } else if ($_POST['RegNoPil']) {
                $ParentProcessId = 7;
                $RegNo = $_POST['RegNoPil'];
            }

            if (!$RegNo) {
                echo json_encode(array('msgType' => 'error', 'msg' => 'Registration no can not be empty.'));
                return;
            }

            /* Update out time of parent */
            if ($ParentProcessId) {
                $aParentData = getParentProcessByRegNo($RegNo, $ParentProcessId);

                if (!$aParentData) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job has no previous record.'));
                    return;
                }

                $ProTrackId = $aParentData['ProTrackId'];
                $ParentInTime = $aParentData['InTime'];
                $ParentOutTime = $aParentData['OutTime'];                
                $ParentBHold = $aParentData['bHold'];
                
                if ($ParentBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                if ($ParentOutTime) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is received already.'));
                    return;
                }

                $aParentTimeDuration = getTimeDuration($ParentInTime);
                $duration = $aParentTimeDuration['Duration'];
                $txtDuration = $aParentTimeDuration['txtDuration'];

                if ($ProTrackId) {
                    $sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";

                    $aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

                    $aQuerys[] = $aQuery2;
                }
            }

            $aRecExistData2 = getRecExistInProcByRegNo($RegNo, $ProcessId);

            if (!$aRecExistData2) {
                /* Insert the current process */
                $sql = "INSERT INTO t_process_tracking
					(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
					VALUES ('$RegNo', '$RegNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
                $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                $aQuerys[] = $aQuery1;
            }

            if ($ParentProcessId) {
                $aParentData2 = getInwardNoByRegNo($RegNo, $ParentProcessId);

                $TrackingNo = $aParentData2['TrackingNo'];

                if ($TrackingNo) {
                    /* Update RegNo of ancestors */
                    $sql3 = "UPDATE t_process_tracking
						SET TrackingNo = '$TrackingNo'
						WHERE TrackingNo = '$RegNo' AND RegNo = '$RegNo';";
                    $aQuery3 = array('command' => 'UPDATE', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                    $aQuerys[] = $aQuery3;
                }
            }

            echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));

            break;
        case 18:
            if ($_POST['RegNoPhy']) {
                $ParentProcessId = 9;
                $RegNo = $_POST['RegNoPhy'];
            } else if ($_POST['RegNoCol']) {
                $ParentProcessId = 13;
                $RegNo = $_POST['RegNoCol'];
            } else if ($_POST['RegNoFib']) {
                $ParentProcessId = 16;
                $RegNo = $_POST['RegNoFib'];
            }

            if (!$RegNo) {
                echo json_encode(array('msgType' => 'error', 'msg' => 'Registration no can not be empty.'));
                return;
            }

            /* Update out time of parent */
            if ($ParentProcessId) {
                $aParentData = getParentProcessByRegNo($RegNo, $ParentProcessId);

                if (!$aParentData) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job has no previous record.'));
                    return;
                }

                $ProTrackId = $aParentData['ProTrackId'];
                $ParentInTime = $aParentData['InTime'];
                $ParentOutTime = $aParentData['OutTime'];
                $ParentBHold = $aParentData['bHold'];
                
                if ($ParentBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                if ($ParentOutTime) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is received already.'));
                    return;
                }

                $aParentTimeDuration = getTimeDuration($ParentInTime);
                $duration = $aParentTimeDuration['Duration'];
                $txtDuration = $aParentTimeDuration['txtDuration'];

                if ($ProTrackId) {
                    $sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";

                    $aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

                    $aQuerys[] = $aQuery2;
                }
            }

            $aRecExistData2 = getRecExistInProcByRegNo($RegNo, $ProcessId);

            if (!$aRecExistData2) {
                /* Insert the current process */
                $sql = "INSERT INTO t_process_tracking
					(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
					VALUES ('$RegNo', '$RegNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
                $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                $aQuerys[] = $aQuery1;
            }

            if ($ParentProcessId) {
                $aParentData2 = getInwardNoByRegNo($RegNo, $ParentProcessId);

                $TrackingNo = $aParentData2['TrackingNo'];

                if ($TrackingNo) {
                    /* Update RegNo of ancestors */
                    $sql3 = "UPDATE t_process_tracking
						SET TrackingNo = '$TrackingNo'
						WHERE TrackingNo = '$RegNo' AND RegNo = '$RegNo';";
                    $aQuery3 = array('command' => 'UPDATE', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                    $aQuerys[] = $aQuery3;
                }
            }

            echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));

            break;
        case 19:
            if ($_POST['RegNoSub']) {
                $ParentProcessId = 17;
                $RegNo = $_POST['RegNoSub'];
            } else if ($_POST['RegNoCom']) {
                $ParentProcessId = 18;
                $RegNo = $_POST['RegNoCom'];
            }

            if (!$RegNo) {
                echo json_encode(array('msgType' => 'error', 'msg' => 'Registration no can not be empty.'));
                return;
            }

            /* Update out time of parent */
            if ($ParentProcessId) {
                $aParentData = getParentProcessByRegNo($RegNo, $ParentProcessId);

                if (!$aParentData) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job has no previous record.'));
                    return;
                }

                $ProTrackId = $aParentData['ProTrackId'];
                $ParentInTime = $aParentData['InTime'];
                $ParentOutTime = $aParentData['OutTime'];
                $ParentBHold = $aParentData['bHold'];
                
                if ($ParentBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                if ($ParentOutTime) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is received already.'));
                    return;
                }

                $aParentTimeDuration = getTimeDuration($ParentInTime);
                $duration = $aParentTimeDuration['Duration'];
                $txtDuration = $aParentTimeDuration['txtDuration'];

                if ($ProTrackId) {
                    $sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";

                    $aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

                    $aQuerys[] = $aQuery2;
                }
            }

            $aRecExistData2 = getRecExistInProcByRegNo($RegNo, $ProcessId);

            if (!$aRecExistData2) {
                /* Insert the current process */
                $sql = "INSERT INTO t_process_tracking
					(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
					VALUES ('$RegNo', '$RegNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
                $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                $aQuerys[] = $aQuery1;
            }

            if ($ParentProcessId) {
                $aParentData2 = getInwardNoByRegNo($RegNo, $ParentProcessId);

                $TrackingNo = $aParentData2['TrackingNo'];

                if ($TrackingNo) {
                    /* Update RegNo of ancestors */
                    $sql3 = "UPDATE t_process_tracking
						SET TrackingNo = '$TrackingNo'
						WHERE TrackingNo = '$RegNo' AND RegNo = '$RegNo';";
                    $aQuery3 = array('command' => 'UPDATE', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                    $aQuerys[] = $aQuery3;
                }
            }

            echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));

            break;
        case 22:
            if ($_POST['RegNoRec']) {
                $RegNo = $_POST['RegNoRec'];

                $aRecExistData2 = getRecExistInProcByRegNo($RegNo, $ProcessId);
                $CurProTrackId = $aRecExistData2['ProTrackId'];

                if ($CurProTrackId) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is scanned already.'));
                    return;
                }

                /* Update out time of parent */
                if ($ParentProcessId) {
                    $aParentData = getParentProcessByRegNo($RegNo, $ParentProcessId);

                    $ProTrackId = $aParentData['ProTrackId'];
                    $ParentInTime = $aParentData['InTime'];
                    $ParentBHold = $aParentData['bHold'];
                
                    if ($ParentBHold) {
                        echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                        exit();
                    }

                    if (!$ProTrackId) {
                        echo json_encode(array('msgType' => 'error', 'msg' => 'This job has no previous record.'));
                        return;
                    }

                    $aParentTimeDuration = getTimeDuration($ParentInTime);
                    $duration = $aParentTimeDuration['Duration'];
                    $txtDuration = $aParentTimeDuration['txtDuration'];

                    if ($ProTrackId) {
                        $sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";

                        $aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

                        $aQuerys[] = $aQuery2;
                    }
                }

                /* Insert the current process */
                $sql = "INSERT INTO t_process_tracking
					(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
					VALUES ('$RegNo', '$RegNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
                $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                $aQuerys[] = $aQuery1;

                if ($ParentProcessId) {
                    $aParentData2 = getInwardNoByRegNo($RegNo, $ParentProcessId);

                    $TrackingNo = $aParentData2['TrackingNo'];

                    if ($TrackingNo) {
                        /* Update RegNo of ancestors */
                        $sql3 = "UPDATE t_process_tracking
							SET TrackingNo = '$TrackingNo'
							WHERE TrackingNo = '$RegNo' AND RegNo = '$RegNo';";
                        $aQuery3 = array('command' => 'UPDATE', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                        $aQuerys[] = $aQuery3;
                    }
                }

                echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));
            }
            /* FOR JOB DELIVERED */ else if ($_POST['RegNoDel']) {
                $RegNo = $_POST['RegNoDel'];

                $aParentData = getParentProcessByRegNo($RegNo, $ParentProcessId);

                if (!$aParentData) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job has no previous record.'));
                    return;
                }

                $aRecExistData2 = getRecExistInProcByRegNo($RegNo, $ProcessId);
                // print_r($aRecExistData2);
                // exit;
                $OwnProTrackId = $aRecExistData2['ProTrackId'];
                $OwnInTime = $aRecExistData2['InTime'];
                $OwnOutTime = $aRecExistData2['OutTime'];
                $OwnBHold = $aRecExistData2['bHold'];
                
                if ($OwnBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                if ($OwnOutTime) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is delivered already.'));
                    return;
                }

                $aOwnTimeDuration = getTimeDuration($OwnInTime);
                $duration = $aOwnTimeDuration['Duration'];
                $txtDuration = $aOwnTimeDuration['txtDuration'];

                if ($OwnProTrackId) {
                    $sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $OwnProTrackId;";

                    $aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('ProTrackId'), 'pk_values' => array($OwnProTrackId), 'bUseInsetId' => FALSE);

                    $aQuerys[] = $aQuery2;
                }

                echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));
            }

            break;
        default:
            if (!$RegNo) {
                echo json_encode(array('msgType' => 'error', 'msg' => 'Registration no can not be empty.'));
                return;
            }

            if ($RegNo) {
                if (getRecExistInProcByRegNo($RegNo, $ProcessId)) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This Job is scanned already.'));
                    return;
                }
            }

            /* Update out time of parent */
            if ($ParentProcessId) {
                $aParentData = getParentProcessByRegNo($RegNo, $ParentProcessId);

                if (!$aParentData) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job has no previous record.'));
                    return;
                }

                $ProTrackId = $aParentData['ProTrackId'];
                $ParentInTime = $aParentData['InTime'];
                $ParentBHold = $aParentData['bHold'];
                
                if ($ParentBHold) {
                    echo json_encode(array('msgType' => 'error', 'msg' => 'This job is holded by previous process.'));
                    exit();
                }

                $aParentTimeDuration = getTimeDuration($ParentInTime);
                $duration = $aParentTimeDuration['Duration'];
                $txtDuration = $aParentTimeDuration['txtDuration'];

                if ($ProTrackId) {
                    $sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";

                    $aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

                    $aQuerys[] = $aQuery2;
                }
            }

            /* Insert the current process */
            $sql = "INSERT INTO t_process_tracking
				(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
				VALUES ('$RegNo', '$RegNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
            $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
            $aQuerys[] = $aQuery1;

            if ($ParentProcessId) {
                $aParentData2 = getInwardNoByRegNo($RegNo, $ParentProcessId);

                $TrackingNo = $aParentData2['TrackingNo'];

                if ($TrackingNo) {
                    /* Update RegNo of ancestors */
                    $sql3 = "UPDATE t_process_tracking
						SET TrackingNo = '$TrackingNo'
						WHERE TrackingNo = '$RegNo' AND RegNo = '$RegNo';";
                    $aQuery3 = array('command' => 'UPDATE', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
                    $aQuerys[] = $aQuery3;
                }
            }

            echo json_encode(exec_query($aQuerys, $jUserId, $language, FALSE));

            break;
    }
}

function getProTrackInsertCmd($TrackingNo, $ProcessId, $jUserId, $ProcUnitId){
	$sql = "INSERT INTO t_process_tracking
		(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
		VALUES ('$TrackingNo', '$TrackingNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', $ProcUnitId);";
	return array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
}

function getProTrackInsertCmdInward($TrackingNo, $ProcessId, $jUserId, $ProcUnitId){
	
	if($ProcUnitId == '1'){
		$ProcessId = 1;
	}
	else if($ProcUnitId == '2'){
		$ProcessId = 23;
	}
		
	$sql = "INSERT INTO t_process_tracking
		(TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
		VALUES ('$TrackingNo', '$TrackingNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', $ProcUnitId);";
	return array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
}

function getProTrackUpdateCmd($duration, $txtDuration, $jUserId, $ProTrackId){
	$sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration', OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";
    return array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);
}

/* Get max NoOfScann record of the job 
  return value/NULL
 */

function getMaxNoOfScann($JobNo, $ProcessId) {
    if (!$JobNo)
        return 'Job No is empty';

    $query = "SELECT MAX(NoOfScann) MaxNoOfScann FROM t_process_tracking WHERE (TrackingNo = '$JobNo' OR RegNo = '$JobNo') AND ProcessId = $ProcessId;";
    $result = mysql_query($query);
    $MaxNoOfScann = 0;
    $aData = array();
    if ($result)
        $aData = mysql_fetch_assoc($result);
    if ($aData) {
        $MaxNoOfScann = $aData['MaxNoOfScann'];
    }
    return $MaxNoOfScann;
}

function getParentProcessByInwardNo($JobNo, $ProcessId) {
    if (!$JobNo)
        echo 'Job No is empty';
    else if (!$ProcessId)
        echo 'ProcessId is empty';

    $aData = array();
    try {
        $query = "SELECT
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime, t_process_tracking.EntryDate, t_process_tracking.bHold
			FROM
				t_process_tracking
			WHERE (t_process_tracking.TrackingNo = '$JobNo'
				AND t_process_tracking.ProcessId = $ProcessId);";
        //exit;
        $result = mysql_query($query);

        if ($result)
            $aData = mysql_fetch_assoc($result);

        //var_dump($aData);

        return $aData;
        //var_dump($aData);
    } catch (Exception $e) {
        return $e;
    }
}

function getParentProcessByRegNo($JobNo, $ProcessId) {
    if (!$JobNo)
        echo 'Job No is empty';
    else if (!$ProcessId)
        echo 'ProcessId is empty';

    $aData = array();
    try {
        $query = "SELECT
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime, t_process_tracking.bHold
			FROM
				t_process_tracking
			WHERE (t_process_tracking.RegNo = '$JobNo'
				AND t_process_tracking.ProcessId = $ProcessId);";
        //exit;
        $result = mysql_query($query);

        if ($result)
            $aData = mysql_fetch_assoc($result);
        return $aData;
        //var_dump($aData);
    } catch (Exception $e) {
        return $e;
    }
}

function getInwardNoByRegNo($JobNo, $ProcessId) {
    if (!$JobNo)
        echo 'Job No is empty';
    else if (!$ProcessId)
        echo 'ProcessId is empty';

    $aData = array();
    try {
        $query = "SELECT TrackingNo FROM t_process_tracking WHERE RegNo = '$JobNo' AND ProcessId = $ProcessId AND TrackingNo IS NOT NULL LIMIT 1;";

        $result = mysql_query($query);

        if ($result)
            $aData = mysql_fetch_assoc($result);
        return $aData;
        //var_dump($aData);
    } catch (Exception $e) {
        return $e;
    }
}

function getRecExistInProcByInwardNo($JobNo, $ProcessId) {
    if (!$JobNo)
        echo 'Job No is empty';
    else if (!$ProcessId)
        echo 'ProcessId is empty';

    $aData = array();
    try {
        $query = "SELECT
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime, t_process_tracking.bHold
			FROM
				t_process_tracking
			WHERE (t_process_tracking.TrackingNo = '$JobNo'
				AND t_process_tracking.ProcessId = $ProcessId);";
        //exit;
        $result = mysql_query($query);

        if ($result)
            $aData = mysql_fetch_assoc($result);
        return $aData;
        //var_dump($aData);
    } catch (Exception $e) {
        return $e;
    }
}

function getRecExistInProcByInwardNoAndRegNo($InwarNo, $RegNo, $ProcessId) {
    if (!$InwarNo)
        echo 'InwarNo No is empty';
    else if (!$RegNo)
        echo 'RegNo No is empty';
    else if (!$ProcessId)
        echo 'ProcessId is empty';

    $aData = array();
    try {
        $query = "SELECT
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime, t_process_tracking.bHold
			FROM
				t_process_tracking
			WHERE (t_process_tracking.TrackingNo = '$InwarNo'
				AND t_process_tracking.RegNo = '$RegNo'
				AND t_process_tracking.ProcessId = $ProcessId);";
        //exit;
        $result = mysql_query($query);

        if ($result)
            $aData = mysql_fetch_assoc($result);
        return $aData;
        //var_dump($aData);
    } catch (Exception $e) {
        return $e;
    }
}

function getRecExistInProcByRegNo($JobNo, $ProcessId) {
    if (!$JobNo)
        echo 'Job No is empty';
    else if (!$ProcessId)
        echo 'ProcessId is empty';

    $aData = array();
    try {
        $query = "SELECT
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime, t_process_tracking.bHold
			FROM
				t_process_tracking
			WHERE (t_process_tracking.RegNo = '$JobNo'
				AND t_process_tracking.ProcessId = $ProcessId);";
        //exit;
        $result = mysql_query($query);

        if ($result)
            $aData = mysql_fetch_assoc($result);
        return $aData;
        //var_dump($aData);
    } catch (Exception $e) {
        return $e;
    }
}

function getTimeDuration($ParentInTime) {
    if (!$ParentInTime) {
        echo 'Parent Time is empty';
    }

    $now = date('Y-m-d H:i:s');

    $timeStampParentInTime = strtotime($ParentInTime); //1434650400

    $timeStampNow = strtotime($now);

    $strNowDate = date('Y-m-d', $timeStampNow);
    $strInDate = date('Y-m-d', $timeStampParentInTime);

    $sql44 = "SELECT COUNT(*) CountNwdDays FROM t_non_working_days WHERE NwdDate > '$strInDate' AND NwdDate < '$strNowDate';";

    $result44 = mysql_query($sql44);
    if ($result44)
        $aData44 = mysql_fetch_assoc($result44);
    $countNwdDays = 0;
    if ($aData44) {
        $countNwdDays = $aData44['CountNwdDays'];
    }

    $diff = $timeStampNow - $timeStampParentInTime;

    $duration = $diff - ($countNwdDays * 86400);

    $txtDuration = convertToHoursMins($duration, '%02d hours %02d minutes');

    return array('Duration' => $duration, 'txtDuration' => $txtDuration);
}

function getTotNoOfReturn($JobNo, $NextProcessId, $MaxNoOfScann) {
    if (!$JobNo)
        echo 'Job No is empty';
    else if (!$NextProcessId)
        echo 'ProcessId is empty';

    $aData = array();
    try {
        $query = "SELECT 
					  COUNT(*) TotNoOfReturn 
					FROM
					  t_process_tracking 
					WHERE (
						TrackingNo = '$JobNo' 
						AND NoOfScann = $MaxNoOfScann
						AND ProcessId = $NextProcessId
						AND bReturn = 1
					  ) ;";
        //exit;
        $result = mysql_query($query);

        if ($result)
            $aData = mysql_fetch_assoc($result);
        return $aData['TotNoOfReturn'];
        //var_dump($aData);
    } catch (Exception $e) {
        return $e;
    }
}

?>