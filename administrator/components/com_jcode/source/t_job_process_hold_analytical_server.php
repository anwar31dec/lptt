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

    $sql = "SELECT 
			SQL_CALC_FOUND_ROWS a.ProTrackId, a.TrackingNo, a.RegNo, b.ProcessId, b.ProcessName, b.ProcessOrder, a.InTime, a.OutTime 
			, TIMESTAMPDIFF(SECOND, InTime, NOW()) AS Duration
			, UsualDuration
			, (TIMESTAMPDIFF(SECOND, InTime, NOW()) - UsualDuration) Status
                        , bHold
                        , HoldComments
			FROM
			  t_process_tracking a 
			  INNER JOIN t_process_list b 
				ON (a.ProcessId = b.ProcessId)
			WHERE a.ProcessId = $ProcessId 
			  AND a.OutTime IS NULL 
                    $sWhere 
                    $sOrder 
                    $sLimit ";
    /* echo $sql;
    exit; */



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
        
        $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>" . 'Edit' . "</span></a>";
        //$z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>" . 'Delete' . "</span></a>";

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
        $sOutput .= '' . ($aRow['bHold']? 1 : 0) . ',';
        $sOutput .= '' . ($aRow['bHold']? '"Yes"' : '""') . ',';
        $sOutput .= '"' . crnl2br($aRow['HoldComments']) . '",';
        $sOutput .= '"' . $y . '",';
        $sOutput .= '' . $aRow['ProcessId'] . '';
       
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

function insertUpdateProcessTracking($conn) {
    date_default_timezone_set("Asia/Dhaka");
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $TrackingNo = $_POST['hTrackingNo'];
    $RegNo = $_POST['RegNo'];
    $ProcessId = $_POST['ProcessId'];
    $bHold = $_POST['bHold']? 1 : 0;
    $HoldComments = mysql_real_escape_string($_POST['HoldComments']);

    $sql = "UPDATE
            t_process_tracking SET				
            bHold = $bHold,
            HoldComments = '$HoldComments',
            HoldTime = NOW()
            WHERE RegNo = '$TrackingNo' AND ProcessId = $ProcessId";
    
//    echo $sql;
//    exit;

    $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('RegNo','ProcessId'), 'pk_values' => array("'".$RegNo."'", $ProcessId), 'bUseInsetId' => FALSE);
    $aQuerys = array($aQuery1);
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
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
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime, t_process_tracking.EntryDate
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
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime
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
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime
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
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime
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
				t_process_tracking.ProTrackId, t_process_tracking.InTime, t_process_tracking.OutTime
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