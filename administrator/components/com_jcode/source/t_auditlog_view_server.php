<?php

include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
include_once ("function_lib.php");

mysql_query('SET CHARACTER SET utf8');

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
$gTEXT = isset($TEXT) ? $TEXT : '';


$jBaseUrl = isset($_GET['jBaseUrl']) ? $_GET['jBaseUrl'] : '';


$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {
    case "getAuditLog" :
        getAuditLog();
        break;
    case "getAuditLogList" :
        getAuditLogList();
        break;
    case "getQueryMore" :
        getQueryMore();
        break;
    default :
        echo "{failure:true}";
        break;
}



/* * ****************************************************Audit log Table***************************************************** */

function getAuditLog() {
	$sWhere = '';
    $aColumns = array('SL', 'LogId', 'LogDate', 'UserName', 'RemoteIP', 'QueryType', 'TableName', 'SqlText');

    $sLimit = "";
    if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . intval($_POST['iDisplayStart']) . ", " . intval($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = "ORDER BY  ";
        for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
            if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
                $sOrder .= "`" . $aColumns[intval($_POST['iSortCol_' . $i])] . "` " . ($_POST['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
            }
        }
        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }

    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true" && $_POST['sSearch'] != '') {
            if ($sWhere == "") {
                $sWhere = " AND (";
            } else {
                $sWhere .= " OR ";
            }
            $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ";
        }
    }

    if ($sWhere != "")
        $sWhere .= ")";

    $sQuery = "SELECT SQL_CALC_FOUND_ROWS 
                                     LogId
                                    , LogDate
                                    , UserName
                                    , RemoteIP
                                    , QueryType
                                    , TableName
                                    , SqlText
                                FROM
                                    t_sqllog WHERE 1=1 $sWhere $sOrder $sLimit";

    //echo  $sQuery;

    $rResult = mysql_query($sQuery);
    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    //echo $iFilteredTotal;

    $iTotal = mysql_num_rows($rResult);

    $output = array("sEcho" => intval($_POST['sEcho']), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());

    $k = 0;
    $more = "<a class='moreQery' href='javascript:void(0);'><span class='label label-info'>More</span></a>";

    while ($aRow = mysql_fetch_array($rResult)) {
        $row = array();
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($aColumns[$i] == 'SL'){
                $row[] = intval($_POST['iDisplayStart']) + (++$k);
            }
            else if ($aColumns[$i] == 'SqlText'){
                $row[] = substr($aRow[$aColumns[$i]], 0, 6) . "...";
                $row[] =$more;
            }
            else{
                $row[] = $aRow[$aColumns[$i]];
            }
              
        }
        $output['aaData'][] = $row;
    }
    echo json_encode($output);
}

function getAuditLogList() {
    global $gTEXT;
    mysql_query('SET CHARACTER SET utf8');
    $data = array();
    $logId = $_POST['logId'];
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_AuditLog(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS logId, `jsonText`, `logDate`  FROM t_sqllog WHERE LogId=" . $logId;


    //echo $sql;

    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];
	
	$sOutput = '';
    $output = array();
    $output1 = array();
    $serial = $_POST['iDisplayStart'] + 1;

    // $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>".$gTEXT['Edit']."</span></a>";
    // $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>".$gTEXT['Delete']."</span></a>";
    while ($aRow = mysql_fetch_array($result)) {
        $data = json_decode($aRow['jsonText']);
        foreach ($data as $name => $value) {
            unset($output1);
            $sl = $serial++;
            $sOutput .= ',';
            $output1[] = $sl;
            $output1[] = $value[0];
            $output1[] = $value[1];
            $output1[] = $value[2];
            $output[] = $output1;
        }
    }

    echo ' { "sEcho":' . intval($_POST['sEcho']) . ', "iTotalRecords": ' . $iFilteredTotal . ' , "iTotalDisplayRecords": ' . $iFilteredTotal . ', 
"aaData":' . json_encode($output)
    . '}';
}

function getQueryMore() {

    $logId = $_POST['logId'];
    $sQuery = "SELECT SqlText from t_sqllog where logId='" . $logId . "'";
    $rResult = mysql_query($sQuery);

    $rResult = safe_query($sQuery);
    $query = mysql_fetch_object($rResult);
    $data = array('success' => true, 'query' => $query);
    echo json_encode($data);
}

function fnColumnToField_AuditLog($i) {
    if ($i == 1)
        return "logId";
    if ($i == 2)
        return "RemoteIP";
    if ($i == 3)
        return "userName";
    if ($i == 4)
        return "logDate";
    if ($i == 5)
        return "queryType";
    if ($i == 6)
        return "tableName";
    if ($i == 7)
        return "jsonText";
}

?>