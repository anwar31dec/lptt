<?php

include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
include('function_lib.php');
$gTEXT = $TEXT;

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {

    case "getrptFrequencyData" :
        getrptFrequencyData($conn);
        break;
    case "insertUpdateFrequencyData" :
        insertUpdateFrequencyData($conn);
        break;
    case "deleteFrequencyData" :
        deleteFrequencyData($conn);
        break;

    default :
        echo "{failure:true}";
        break;
}

/* * **********************************************************Frequency Data****************************************************** */

function getrptFrequencyData($conn) {

    global $gTEXT;

    $lan = $_POST['lan'];
    if ($lan == 'en-GB') {
        $CountryName = 'CountryName';
        $GroupName = 'GroupName';
        $MonthName = 'MonthName';
        $FrequencyName = 'FrequencyName';
    } else {
        $CountryName = 'CountryNameFrench';
        $GroupName = 'GroupNameFrench';
        $MonthName = 'MonthNameFrench';
        $FrequencyName = 'FrequencyNameFrench';
    }

    $CountryId = $_POST['CountryId'];

    if ($CountryId) {
        $CountryId = " WHERE a.CountryId = '" . $CountryId . "' ";
    }
    else
        $CountryId = " WHERE 1=1 ";


    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_rptfrequency(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " and ($GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR $CountryName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR e.$MonthName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
					OR $FrequencyName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT a.RepFreqId,a.CountryId,a.ItemGroupId,a.FrequencyId,a.StartMonthId,a.StartYearId
		,b.$CountryName CountryName,c.$GroupName GroupName,d.$FrequencyName FrequencyName		
		, case a.FrequencyId when 1 then e.$MonthName
		else f.$MonthName end MonthName,a.StartYearId YearName

		FROM t_reporting_frequency a
		Inner Join t_country b ON a.CountryId=b.CountryId
		Inner Join t_itemgroup c ON a.ItemGroupId=c.ItemGroupId
		Inner Join t_frequency d ON a.FrequencyId=d.FrequencyId 
		Left Join t_month e ON a.StartMonthId=e.MonthId 
        Left Join t_quarter f ON a.StartMonthId=f.MonthId " . $CountryId . "
		$sWhere $sOrder $sLimit ";
    //echo $sql;

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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>" . $gTEXT['Edit'] . "</span></a>";
    $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>" . $gTEXT['Delete'] . "</span></a>";

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $CountryName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['CountryName'])));
        $GroupName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['GroupName'])));
        $FrequencyName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['FrequencyName'])));
        $YearName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['YearName'])));
        $MonthName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['MonthName'])));

        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . addslashes($aRow['RepFreqId']) . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $CountryName . '",';
        $sOutput .= '"' . $GroupName . '",';
        $sOutput .= '"' . $FrequencyName . '",';
        $sOutput .= '"' . $YearName . '",';
        $sOutput .= '"' . $MonthName . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . addslashes($aRow['CountryId']) . '",';
        $sOutput .= '"' . addslashes($aRow['ItemGroupId']) . '",';
        $sOutput .= '"' . addslashes($aRow['FrequencyId']) . '",';
        $sOutput .= '"' . addslashes($aRow['StartMonthId']) . '",';
        $sOutput .= '"' . addslashes($aRow['StartYearId']) . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_rptfrequency($i) {
    if ($i == 2)
        return "CountryName ";
    else if ($i == 3)
        return "GroupName ";
    else if ($i == 4)
        return "FrequencyName ";
    else if ($i == 5)
        return "YearName ";
    else if ($i == 6)
        return "MonthName ";
}

function insertUpdateFrequencyData($conn) {

    $RecordId = $_POST['RecordId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $CountryId = $_POST['CountryId'];
    $FrequencyId = $_POST['FrequencyId'];
    $StartMonthId = $_POST['StartMonthId'];
    $StartYearId = $_POST['StartYearId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];


    if ($RecordId == '') {
        $sql = "INSERT INTO t_reporting_frequency(CountryId, ItemGroupId, FrequencyId,StartMonthId,StartYearId)
                 VALUES ('" . $CountryId . "', '" . $ItemGroupId . "', '" . $FrequencyId . "', '" . $StartMonthId . "', '" . $StartYearId . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_reporting_frequency', 'pks' => array('RepFreqId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE t_reporting_frequency SET
				ItemGroupId = '" . $ItemGroupId . "',
				FrequencyId = '" . $FrequencyId . "',
				StartMonthId = '" . $StartMonthId . "',
				StartYearId = '" . $StartYearId . "',
				CountryId = '" . $CountryId . "'
				WHERE RepFreqId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_reporting_frequency', 'pks' => array('CountryId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteFrequencyData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {
        $sql = " DELETE FROM t_reporting_frequency WHERE RepFreqId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_reporting_frequency', 'pks' => array('CountryId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

?>