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
    case "getRecExistInCurProc" :
        getRecExistInCurProc('BGDT9856256q');
        break;
	case "getRecExistInLastProc" :
		getRecExistInLastProc('BGDT9856256');
	break;
    case "getMaxNoOfScann" :
        getMaxNoOfScann('BGDT9856256');
        break; 
	case "getInwardNoByRegNo" :
        var_dump(getInwardNoByRegNo('34343', '1'));
        break;
	case "getParentProTrackId" :
        var_dump(getParentProTrackId('1', 1));
        break;
		
    default :
        echo "{failure:true}";
        break;
}

/* Get any process max record of a job following the NoOfScann field
 as a job can repeat if failed in certain stage
and to check this job is scanned already in this process */
 
function getRecExistInProc($JobNo, $ProcessId){	
	if(!$JobNo)
		echo 'Job No is empty';
	else if(!$ProcessId)
		echo 'ProcessId is empty';
	
	$aData = array();
	try {
		$MaxNoOfScann = getMaxNoOfScann($JobNo, $ProcessId);
		
		if (!$MaxNoOfScann) {
			return $aData;
		}
		
		$query = "SELECT * FROM t_process_tracking WHERE (TrackingNo = '$JobNo' OR RegNo = '$JobNo') AND ProcessId = $ProcessId AND NoOfScann = $MaxNoOfScann ;";
		$result = mysql_query($query);
		
		if ($result)
			$aData = mysql_fetch_assoc($result);
		return $aData;
		//var_dump($aData);
	} catch (Exception $e) {
		return $e;
	}
};

/* Get current process max record of a job following the NoOfScann field
 as a job can repeat if failed in certain stage
and to check this job is scanned already in this process */
 
function getRecExistInCurProc($JobNo){	
	$ProcessId = 1;
	//return getRecExistInProc($JobNo, $ProcessId);
	var_dump(getRecExistInProc($JobNo, $ProcessId));
};

/* Get last process max record of a job following the NoOfScann field
 as a job can repeat if failed in certain stage 
 use getMaxNoOfScann() to get last record of same job (for return purpose)*/
 
function getRecExistInLastProc($JobNo){
	$ProcessId = 1;
	//return getRecExistInProc($JobNo, $ProcessId);
	var_dump(getRecExistInProc($JobNo, $ProcessId));
};

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
};

/* Get Inward no using registration number */
 
function getInwardNoByRegNo($JobNo, $ProcessId){	
	if(!$JobNo)
		echo 'Job No is empty';
	else if(!$ProcessId)
		echo 'ProcessId is empty';
	
	$aData = array();
	try {
		$MaxNoOfScann = getMaxNoOfScann($JobNo, $ProcessId);
		
		if (!$MaxNoOfScann) {
			return $aData;
		}
		
		$query = "SELECT TrackingNo FROM t_process_tracking WHERE RegNo = '$RegNo' AND ProcessId = $ProcessId AND NoOfScann = $MaxNoOfScann AND TrackingNo IS NOT NULL LIMIT 1;";
		$result = mysql_query($query);
		
		if ($result)
			$aData = mysql_fetch_assoc($result);
		return $aData;
		//var_dump($aData);
	} catch (Exception $e) {
		return $e;
	}
};

/* Get Inward no using registration number */
 
function getParentProTrackId($JobNo, $ProcessId){	
	if(!$JobNo)
		echo 'Job No is empty';
	else if(!$ProcessId)
		echo 'ProcessId is empty';
	
	$aData = array();
	try {
		$MaxNoOfScann = getMaxNoOfScann($JobNo, $ProcessId);
		
		if (!$MaxNoOfScann) {
			return $aData;
		}
		
		$query = "SELECT
				t_process_tracking.ProTrackId
			FROM
				t_process_tracking
			WHERE (t_process_tracking.TrackingNo = '$JobNo'
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

?>