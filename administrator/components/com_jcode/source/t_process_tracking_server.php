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
        $sWhere = " AND  (t_process_tracking.TrackingNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
                 " t_process_list.ProcessName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                 " t_process_tracking.InTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                 " t_process_tracking.OutTime LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS
				 t_process_tracking.ProTrackId
				, t_process_tracking.TrackingNo
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
                    $sLimit ";
	//echo $sql;
	//exit;
	
 

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
		
		//$statusTime = $aRow['Status'] < 0 ? abs($aRow['Status']) : abs($aRow['Status']);
		$statusTime = $aRow['Status'] < 0 ? convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes remaining') : convertToHoursMins(abs($aRow['Status']), '%02d hours %02d minutes delay');
		
		$sOutput .= '"' . $statusTime. '",';
		$sOutput .= '"' . $aRow['ProcessId'] . '",';
		$sOutput .= '"' . $aRow['ProcessOrder'] . '"';
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

    $sql = "SELECT SQL_CALC_FOUND_ROWS
				 t_process_tracking.ProTrackId
				, t_process_tracking.TrackingNo
				, t_process_list.ProcessId
				, t_process_list.ProcessName
				, t_process_list.ProcessOrder
				, t_process_tracking.InTime
				, t_process_tracking.OutTime
				, TIMESTAMPDIFF(MINUTE, InTime, NOW()) AS Duration
				, UsualDuration
				, (TIMESTAMPDIFF(MINUTE, InTime, NOW()) - UsualDuration) Status
			FROM
				t_process_tracking
				INNER JOIN t_process_list
					ON (t_process_tracking.ProcessId = t_process_list.ProcessId)
					WHERE t_process_tracking.ReadyForProOrder = $ProcessOrder
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
		$sOutput .= '"' . ($aRow['Status'] < 0 ? abs($aRow['Status']).' minutes ahead' : abs($aRow['Status']).' minutes delay'). '",';
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
	$TrackingNo = $_POST['TrackingNo'];
	$RegNo = $_POST['RegNo'];
	$hTrackingNo = $_POST['hTrackingNo'];
	$ProcessId = $_POST['ProcessId'];
	$ProcessOrder = $_POST['ProcessOrder'];
	$PrevProcessOrder = $_POST['ProcessOrder'] - 1;
	$ReadyForProOrder = $ProcessOrder + 1;
	$ParentProcessId = $_POST['ParentProcessId'];
	$eNewNoPosition = $_POST['eNewNoPosition'];
	$Position = $_POST['Position'];
			
	/* ============ START ================= */
	/* 	
		--PRE CHECKING--
		No tracking number of all process after the registration number.	
		Get tracking number of current process registration number from the
		record of position of REGISTRATION or from other record already 
		filled up by the tracking number that is the top record which has 
		both tracking and registration number.
		
		Refill the tracking number that is empty from the client side, so the tracking number is always present.
	*/
	//echo $RegNo;
	//exit;
	if ($RegNo) {
		$sql22 = "SELECT TrackingNo FROM t_process_tracking WHERE RegNo = '$RegNo' AND TrackingNo IS NOT NULL LIMIT 1;";
		$result22 = mysql_query($sql22);
		if ($result22)
			$aData22 = mysql_fetch_assoc($result22);
		if ($aData22) {
			//Earlier stage tracking number because this process has no tracking number but we need the tracking number to link the next process
			$eTrackingNo = $aData22['TrackingNo'];			
		}
		if (!$TrackingNo) {
			// Replaced empty tracking number with tracking number that come from the earlier stage, the first one is the registration process
			$TrackingNo = $eTrackingNo;
			//exit;
		}
	}
	/* ============ END ================= */
	
	/* ============ START ================= */
	/* 	
		--PRE CHECKING--
		Check the tracking number already scanned by the user that was for START TIME
		and check the out time is empty. This is use for the same text box from the client.
		As tracking number is replaced by the tracking number of registration process so no need to keep track the
		previous registration number, this tracking no will do the same that would do by the previous registration no
		so responsibility replaced by the tracking number e.g $TrackingNo = $eTrackingNo; line no - 1479.
		If the tracking no is already existed and scanned for second time out time must be empty if it is third time out time will have to it.
	*/
	
	// $pTrackingNo is for the previous scanned tracking number
	$pTrackingNo = '';
	$pOutTime = '';	

	$sql = "SELECT TrackingNo, OutTime FROM t_process_tracking WHERE TrackingNo = '$TrackingNo' AND ProcessId = $ProcessId;";
	$result = mysql_query($sql);
	if ($result)
		$aData = mysql_fetch_assoc($result);
	
	if ($aData) {
		$pTrackingNo = $aData['TrackingNo'];		
		$pOutTime = $aData['OutTime'];
	}
	/* ============ END ================= */
	
	/* ============ START ================= */
	/* 
		Get previous process ID that is started but not finished with the second time scan.
		This ID will do the update of the out time of previous process.
	*/
		
	$sql2 = "SELECT 
	  t_process_tracking.ProTrackId, t_process_tracking.InTime
	FROM
	  t_process_tracking 
	  INNER JOIN t_process_list 
		ON t_process_tracking.ProcessId = t_process_list.ProcessId		
	WHERE 
		t_process_tracking.TrackingNo = '$TrackingNo' 
		AND t_process_list.ProcessOrder = $PrevProcessOrder;";

	$result2 = mysql_query($sql2);
	if ($result2)
		$aData2 = mysql_fetch_assoc($result2);
	
	$ProTrackId = '';
	$pInTime = '';
	
	if ($aData2) {
		$ProTrackId = $aData2['ProTrackId'];
		$pInTime = $aData2['InTime'];
		//var_dump($pInTime);
		//exit;
	}
	/* ============ END ================= */
	
	
	/* ============ START ================= */
	/* Get time difference between intime and outtime */
	
	$duration = 0;	
	$txtDuration = '';
	//var_dump($pInTime);
	//exit;
	
	/* if($pInTime){
		
		$now = new DateTime(date('Y-m-d H:i:s'));
		$vInTime = new DateTime($pInTime);
		$diff = $now->diff($vInTime);
		//echo $diff->d;
		//exit;
		$duration = $diff->d*24*60 + $diff->h*60 + $diff->i;
		
		$txtDuration = ($diff->d != 0 ? $diff->d . " Days " : "").($diff->h != 0 ? $diff->h . " Hours ":"") . ($diff->i != 0 ? $diff->i . " Minutes ":"");
	} */
	
	/* $holidays = array("2015-06-18","2015-06-19");
	if($pInTime){
		
		$now = date('Y-m-d H:i:s');
		//$vInTime = $pInTime;
		//$diff = $now->diff($vInTime);
		
		// $durationDays = getWorkingDays($vInTime, $now, $holidays);
		// $duration = $durationDays * 24 * 60;
		
		//$holidays=array("2008-12-25","2008-12-26","2009-01-01");
		//$holidays=array("2015-06-01");
		$holidays=array();

		$durationDays = getWorkingDays($pInTime,$now,$holidays);
		$duration = $durationDays;
	} */
	
	if($pInTime){
		
		$now = date('Y-m-d H:i:s');
		
		$timeStampInTime = strtotime($pInTime); //1434650400
		
		$timeStampNow = strtotime($now);
		
		$strNowDate = date('Y-m-d',$timeStampNow);
		$strInDate = date('Y-m-d',$timeStampInTime);
		
		$sql44 = "SELECT COUNT(*) CountNwdDays FROM t_non_working_days WHERE NwdDate > '$strInDate' AND NwdDate < '$strNowDate';";
		
		$result44 = mysql_query($sql44);
		if ($result44)
			$aData44 = mysql_fetch_assoc($result44);
		$countNwdDays = 0;
		if ($aData44) {
			$countNwdDays = $aData44['CountNwdDays'];			
		}
		
		$diff = $timeStampNow - $timeStampInTime;
	
		$duration = $diff - ($countNwdDays * 86400);
		
		$txtDuration = convertToHoursMins($duration, '%02d hours %02d minutes'); 
	} 

	/* ============ END ================= */
	//echo $duration;
			//exit;

	/* Check the tracking/registration no already scanned. Variable ($pTrackingNo) is the same for
	both tracking/registration for this alternation ($TrackingNo = $eTrackingNo). */
	if ($pTrackingNo == '') {
		/* Update the previous process that out time is empty */
		// echo $pTrackingNo;
			// exit;
		if ($ProTrackId != '') {
			// echo $duration;
			// exit;
	
			$sql2 = "UPDATE t_process_tracking SET TrackingNo = '$TrackingNo', OutTime = NOW(), Duration = $duration, TxtDuration = '$txtDuration' WHERE TrackingNo = '$TrackingNo' AND ProTrackId = $ProTrackId;";
			
			$aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

			$aQuerys[] = $aQuery2;
		}
		/* Insert the current process */
		$sql = "INSERT INTO t_process_tracking
            (TrackingNo, RegNo, ProcessId, InTime, EntryDate, YearId, MonthId)
			VALUES ('$TrackingNo', '$RegNo', $ProcessId, NOW(), Now(), YEAR(NOW()), MONTH(NOW()));";

		$aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
		$aQuerys[] = $aQuery1;
		
		/* Update the previous process registration column those were empty until now as first 2 process use tracking no (inward no)*/
		if ($eNewNoPosition == 'REGISTRATION') {
			$sql3 = "UPDATE t_process_tracking
				SET RegNo = '$RegNo'
				WHERE TrackingNo = '$TrackingNo';";
			$aQuery3 = array('command' => 'INSERT', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
			$aQuerys[] = $aQuery3;
		}

		echo json_encode(exec_query($aQuerys, $jUserId, $language));
		
	} else if ($pTrackingNo != '' && $Position == 'END' && $pOutTime == '') {
		/* Update out time at the end of all processes */
		$sql = "UPDATE t_process_tracking
			SET OutTime = NOW(), duration = $duration, TxtDuration = '$txtDuration'
			WHERE TrackingNo = '$TrackingNo' AND ProcessId = $ProcessId;";

		$aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => FALSE);
		$aQuerys = array($aQuery1);
		echo json_encode(exec_query($aQuerys, $jUserId, $language));
		
	}else if ($pTrackingNo != '' && $Position != 'END') {
		/* No stage can put out time until the last process */
		echo json_encode(array('msgType' => 'success', 'msg' => 'You have scanned already.'));
	}	
	else if ($pTrackingNo != '' && $pOutTime != '' && $Position == 'END') {
		/* The end process is done */
		echo json_encode(array('msgType' => 'success', 'msg' => 'This job is already completed.'));

	}
}

?>