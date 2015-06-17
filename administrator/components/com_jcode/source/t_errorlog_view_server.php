<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

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

switch($task) {
	case "getErrorLog" :
		getErrorLog($conn);
		break;					
	default :
		echo "{failure:true}";
		break;
}

/******************************************************Error log Table******************************************************/

function getErrorLog($conn) {
		
	global $gTEXT;     
	mysql_query("SET character_set_results=utf8");
	$data = array();

	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_ErrorLog(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " WHERE  (userName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
							OR " . " RemoteIP LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " queryType LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " errorNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " errorMsg LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ) ";			
	}			

	$sql = "SELECT `logId`,`logDate`,`RemoteIP`,`userName`,`queryType`,`query`, `errorNo`,`errorMsg` FROM t_errorlog $sWhere $sOrder $sLimit";
	// echo $sql;
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
    
   	// $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>".$gTEXT['Edit']."</span></a>";
	// $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>".$gTEXT['Delete']."</span></a>";

	$f = 0;
	while ($aRow = mysql_fetch_array($result)) {	
        $logDate = strtotime($aRow['logDate']);
        $logDate = date( 'd/m/Y H:i:s', $logDate );
		$query = $aRow['query'];
		$errorMsg = $aRow['errorMsg'];


		
		if ($f++) $sOutput .= ',';        
		$sOutput .= "[";
		$sOutput .= '"' . $aRow['logId'] . '",';
		$sOutput .= '"' . $serial++ . '",';		
		$sOutput .= '"' . $aRow['RemoteIP'] . '",';
		$sOutput .= '"' . $aRow['userName'] . '",';
		$sOutput .= '"' . $logDate . '",';
		$sOutput .= '"' . $aRow['queryType']. '",';   
		$sOutput .= '"' . crnl2br($query) . '",';
		$sOutput .= '"' . $aRow['errorNo']. '",';    
		$sOutput .= '"' . crnl2br($errorMsg) . '"';
		$sOutput .= "]";
	}
	$sOutput .= '] }';	
	echo $sOutput;
}

function fnColumnToField_ErrorLog($i) {
	if ($i == 2)
		return "RemoteIP";
	if ($i == 3)
		return "userName";
	if ($i == 4)
		return "logDate";
	if ($i == 5)
		return "queryType";
	if ($i == 6)
		return "query";
	if ($i == 7)
		return "errorNo";
	if ($i == 8)
		return "errorMsg";
}

function crnl2br($string){	
	$patterns = array ('/\r/','/\t/','/\n/');
	$replace = array ('', '', '');
	return preg_replace($patterns, $replace, $string);
}

?>