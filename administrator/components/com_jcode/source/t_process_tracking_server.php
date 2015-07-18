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
	
	//echo $ParentProcessId;
	//exit();
	
	switch ($ProcessId) {
		case 1:
		case 2:									
			$MaxNoOfScann = getMaxNoOfScann($TrackingNo, $ProcessId);
			$MaxNoOfScann = $MaxNoOfScann + 1;
						
			/* Update out time of parent */
			if($ParentProcessId){
				$aParentData = getParentProTrackId($TrackingNo, $ParentProcessId, $MaxNoOfScann);
				
				$ProTrackId = $aParentData['ProTrackId'];
				
				if (!$ProTrackId && $ProcessId != 1) {
					echo json_encode(array('msgType' => 'success', 'msg' => 'This job is not scanned by Inward.'));
					exit();
				}
				
				if ($ProTrackId) {
					$sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";
					
					$aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

					$aQuerys[] = $aQuery2;
				}
			}
			
			/* Insert the current process */
			$sql = "INSERT INTO t_process_tracking
				(TrackingNo, RegNo, ProcessId, NoOfScann, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
				VALUES ('$TrackingNo', '$RegNo', $ProcessId, $MaxNoOfScann, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
			$aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
			$aQuerys[] = $aQuery1;
			
			echo json_encode(exec_query($aQuerys, $jUserId, $language));
			
			break;
		case 3:
			$MaxNoOfScann = getMaxNoOfScann($TrackingNo, $ProcessId);
			$MaxNoOfScann = $MaxNoOfScann + 1;
						
			/* Update out time of parent */
			if($ParentProcessId){
				$aParentData = getParentProTrackId($TrackingNo, $ParentProcessId, $MaxNoOfScann);
				
				$ProTrackId = $aParentData['ProTrackId'];
								
				if ($ProTrackId) {
					$sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";
					
					$aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

					$aQuerys[] = $aQuery2;
				}
			}
			
			/* Insert the current process */
			$sql = "INSERT INTO t_process_tracking
				(TrackingNo, RegNo, ProcessId, NoOfScann, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
				VALUES ('$TrackingNo', '$RegNo', $ProcessId, $MaxNoOfScann, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
			$aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
			$aQuerys[] = $aQuery1;
			
			/* Update RegNo of ancestors */
			$sql3 = "UPDATE t_process_tracking
				SET RegNo = '$RegNo'
				WHERE TrackingNo = '$TrackingNo' AND RegNo = '';";
			$aQuery3 = array('command' => 'INSERT', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
			$aQuerys[] = $aQuery3;
			
			echo json_encode(exec_query($aQuerys, $jUserId, $language));
			
			break;
		default:
			$MaxNoOfScann = getMaxNoOfScann($RegNo, $ProcessId);
			$MaxNoOfScann = $MaxNoOfScann + 1;
						
			/* Update out time of parent */
			if($ParentProcessId){
				$aParentData = getParentProTrackId($RegNo, $ParentProcessId, $MaxNoOfScann);
				
				$ProTrackId = $aParentData['ProTrackId'];
								
				if ($ProTrackId) {
					$sql2 = "UPDATE t_process_tracking SET OutTime = NOW(), OutUserId = '$jUserId' WHERE ProTrackId = $ProTrackId;";
					
					$aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'" . $TrackingNo . "'"), 'bUseInsetId' => FALSE);

					$aQuerys[] = $aQuery2;
				}
			}
			
			/* Insert the current process */
			$sql = "INSERT INTO t_process_tracking
				(TrackingNo, RegNo, ProcessId, NoOfScann, InTime, EntryDate, YearId, MonthId, InUserId, ProcUnitId)
				VALUES ('$TrackingNo', '$RegNo', $ProcessId, $MaxNoOfScann, NOW(), Now(), YEAR(NOW()), MONTH(NOW()), '$jUserId', 1);";
			$aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
			$aQuerys[] = $aQuery1;
			
			if($ParentProcessId){
				$aParentData2 = getInwardNoByRegNo($RegNo, $ParentProcessId, $MaxNoOfScann);
				
				$TrackingNo = $aParentData2['TrackingNo'];
								
				if ($TrackingNo) {			
					/* Update RegNo of ancestors */
					$sql3 = "UPDATE t_process_tracking
						SET TrackingNo = '$TrackingNo'
						WHERE TrackingNo = '' AND RegNo = '$RegNo';";
					$aQuery3 = array('command' => 'INSERT', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
					$aQuerys[] = $aQuery3;
				}
			}
			
			echo json_encode(exec_query($aQuerys, $jUserId, $language));
			
			break;
	}	
	
}

/* Get max NoOfScann record of the job 
   return value/NULL
*/
 
function getMaxNoOfScann($JobNo, $ProcessId){
	if(!$JobNo)
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

function getParentProTrackId($JobNo, $ProcessId, $MaxNoOfScann){	
	if(!$JobNo)
		echo 'Job No is empty';
	else if(!$ProcessId)
		echo 'ProcessId is empty';
	
	$aData = array();
	try {		
		$query = "SELECT
				t_process_tracking.ProTrackId
			FROM
				t_process_tracking
			WHERE (t_process_tracking.TrackingNo = '$JobNo' OR t_process_tracking.RegNo = '$JobNo'
				AND t_process_tracking.ProcessId = $ProcessId
				AND t_process_tracking.NoOfScann = $MaxNoOfScann);";
		$result = mysql_query($query);
		
		if ($result)
			$aData = mysql_fetch_assoc($result);
		return $aData;
		//var_dump($aData);
	} catch (Exception $e) {
		return $e;
	}
}

function getInwardNoByRegNo($JobNo, $ProcessId, $MaxNoOfScann){	
	if(!$JobNo)
		echo 'Job No is empty';
	else if(!$ProcessId)
		echo 'ProcessId is empty';
	
	$aData = array();
	try {		
		$query = "SELECT TrackingNo FROM t_process_tracking WHERE RegNo = '$JobNo' AND ProcessId = $ProcessId AND NoOfScann = $MaxNoOfScann AND TrackingNo IS NOT NULL LIMIT 1;";
		
		$result = mysql_query($query);
		
		if ($result)
			$aData = mysql_fetch_assoc($result);
		return $aData;
		//var_dump($aData);
	} catch (Exception $e) {
		return $e;
	}
};

?>