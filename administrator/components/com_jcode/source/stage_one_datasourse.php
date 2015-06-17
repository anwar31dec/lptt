<?php

include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());


include('function_lib.php');
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
    case "getRegimenMasterData" :
        getRegimenMasterData($conn);
        break;
    case "getProfileParamData" :
        getProfileParamData($conn);
        break;
    case "insertUpdateProfileParamData" :
        insertUpdateProfileParamData($conn);
        break;
    case "deleteProfileParamData" :
        deleteProfileParamData($conn);
        break;
    case "getYearData" :
        getYearData($conn);
        break;
    case "insertUpdateYearData" :
        insertUpdateYearData($conn);
        break;
    case "deleteYearData" :
        deleteYearData($conn);
        break;
    case "getItemData" :
        getItemData($conn);
        break;
    case "insertUpdateItemData" :
        insertUpdateItemData($conn);
        break;
    case "deleteItemData" :
        deleteItemData($conn);
        break;
    case "getDosesFormData" :
        getDosesFormData($conn);
        break;
    case "insertUpdateDosesFormData" :
        insertUpdateDosesFormData($conn);
        break;
    case "deleteDosesFormData" :
        deleteDosesFormData($conn);
        break;
    case "getServiceData" :
        getServiceData($conn);
        break;
    case "insertUpdateServiceData" :
        insertUpdateServiceData($conn);
        break;
    case "deleteServiceData" :
        deleteServiceData($conn);
        break;
    case "getCountryData" :
        getCountryData($conn);
        break;
    case "insertUpdateCountryData" :
        insertUpdateCountryData($conn);
        break;
    case "deleteCountryData" :
        deleteCountryData($conn);
        break;
    case "getMOSTypeData" :
        getMOSTypeData($conn);
        break;
    case "insertUpdateMOSTypeData" :
        insertUpdateMOSTypeData($conn);
        break;
    case "deleteMOSTypeData" :
        deleteMOSTypeData($conn);
        break;
    case "getFundingSourceData" :
        getFundingSourceData($conn);
        break;
    case "insertUpdateFundingSourceData" :
        insertUpdateFundingSourceData($conn);
        break;
    case "deleteFundingSourceData" :
        deleteFundingSourceData($conn);
        break;
    case "getSStatusData" :
        getSStatusData($conn);
        break;
    case "insertUpdateSStatusData" :
        insertUpdateSStatusData($conn);
        break;
    case "deleteSStatusData" :
        deleteSStatusData($conn);
        break;
    case "getFacilityTypeData" :
        getFacilityTypeData($conn);
        break;
    case "insertUpdateFacilityTypeData" :
        insertUpdateFacilityTypeData($conn);
        break;
    case "deleteFacilityTypeData" :
        deleteFacilityTypeData($conn);
        break;
    case "getFacilityLevelData" :
        getFacilityLevelData($conn);
        break;
    case "insertUpdateFacilityLevelData" :
        insertUpdateFacilityLevelData($conn);
        break;
    case "deleteFacilityLevelData" :
        deleteFacilityLevelData($conn);
        break;
    case "getProcuringAgentsData" :
        getProcuringAgentsData($conn);
        break;
    case "insertUpdateProcuringAgentsData" :
        insertUpdateProcuringAgentsData($conn);
        break;
    case "deleteProcuringAgentsData" :
        deleteProcuringAgentsData($conn);
        break;
    case "getAgreementData" :
        getAgreementData($conn);
        break;
    case "insertUpdateAgreementData" :
        insertUpdateAgreementData($conn);
        break;
    case "deleteAgreementData" :
        deleteAgreementData($conn);
        break;
    case "getReportStatusData" :
        getReportStatusData($conn);
        break;
    case "insertUpdateReportStatusData" :
        insertUpdateReportStatusData($conn);
        break;
    case "deleteReportStatusData" :
        deleteReportStatusData($conn);
        break;
    case "getPOMasterData" :
        getPOMasterData($conn);
        break;
    case "insertUpdatePOMasterTableData" :
        insertUpdatePOMasterTableData($conn);
        break;
    case "deletePOMasterData" :
        deletePOMasterData($conn);
        break;
    case "getAdjustReasonData" :
        getAdjustReasonData($conn);
        break;
    case "insertUpdateAdjustReasonData" :
        insertUpdateAdjustReasonData($conn);
        break;
    case "deleteAdjustReasonData" :
        deleteAdjustReasonData($conn);
        break;
    case "getAmcChangeReasonData" :
        getAmcChangeReasonData($conn);
        break;
    case "insertUpdateAmcChangeReasonData" :
        insertUpdateAmcChangeReasonData($conn);
        break;
    case "deleteAmcChangeReasonData" :
        deleteAmcChangeReasonData($conn);
        break;
    case "getMonthData" :
        getMonthData($conn);
        break;
    case "insertUpdateMonthData" :
        insertUpdateMonthData($conn);
        break;
    case "deleteMonthData" :
        deleteMonthData($conn);
        break;
    case "getMOSTypeFacilityData" :
        getMOSTypeFacilityData($conn);
        break;
    case "getMOSTypeFacilityDetailsData" :
        getMOSTypeFacilityDetailsData($conn);
        break;
    case "insertUpdateMOSTypeFacilityData" :
        insertUpdateMOSTypeFacilityData($conn);
        break;
    case "insertUpdateMOSTypeFacilityDetailsData" :
        insertUpdateMOSTypeFacilityDetailsData($conn);
        break;
    case "deleteMOSTypeFacilityData" :
        deleteMOSTypeFacilityData($conn);
        break;
    case "deleteMOSTypeFacilityDetailsData" :
        deleteMOSTypeFacilityDetailsData($conn);
        break;
    case "getDistrictData" :
        getDistrictData($conn);
        break;
    case "insertUpdateDistrictData" :
        insertUpdateDistrictData($conn);
        break;
    case "deleteDistrictData" :
        deleteDistrictData($conn);
        break;
    case "getOwnerTypeData" :
        getOwnerTypeData($conn);
        break;
    case "insertUpdateOwnerTypeData" :
        insertUpdateOwnerTypeData($conn);
        break;
    case "deleteOwnerTypeData" :
        deleteOwnerTypeData($conn);
        break;
    case "getServiceAreaData" :
        getServiceAreaData($conn);
        break;
    case "insertUpdateServiceAreaData" :
        insertUpdateServiceAreaData($conn);
        break;
    case "deleteServiceAreaData" :
        deleteServiceAreaData($conn);
        break;
    case "insertUpdateRegimenMasterData" :
        insertUpdateRegimenMasterData($conn);
        break;
    case "deleteRegimenMasterData" :
        deleteRegimenMasterData($conn);
        break;
    case "getReportByData" :
        getReportByData($conn);
        break;
    case "insertUpdateReportByData" :
        insertUpdateReportByData($conn);
        break;
    case "deleteReportByData" :
        deleteReportByData($conn);
        break;

    default :
        echo "{failure:true}";
        break;
}

//function crnl2br($string) {
//    $patterns = array('/\r/', '/\t/', '/\n/');
//    $replace = array('', ' ', ' ');
//    return preg_replace($patterns, $replace, $string);
//}

/* * ****************************************************Profile Params Table***************************************************** */

function getProfileParamData($conn) {

    global $gTEXT;
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }
	
	
    // mysql_query('SET CHARACTER SET utf8');
    $data = array();
    $itemGroupId = $_POST['itemGroupId'];


    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ProfileParam(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "where t_itemgroup.ItemGroupId = " . $itemGroupId . " OR " . $itemGroupId . "= 0";

    if ($_POST['sSearch'] != "") {
        $sWhere = " and  (ParamName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR
                        ParamNameFrench LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                        OR $GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS ParamId, ParamName,ParamNameFrench, BShow,t_cprofileparams.ItemGroupId,$GroupName GroupName
				FROM t_cprofileparams
				INNER JOIN t_itemgroup ON t_cprofileparams.ItemGroupId = t_itemgroup.ItemGroupId               
				$sWhere $sOrder $sLimit ";
//echo  $sql;
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

        $ParamName = crnl2br($aRow['ParamName']);
        $GroupName = crnl2br($aRow['GroupName']);
        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['ParamId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $GroupName . '",';
        $sOutput .= '"' . $ParamName . '",';
        $sOutput .= '"' . $aRow['ParamNameFrench'] . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['BShow'] . '",';
        $sOutput .= '"' . $aRow['ItemGroupId'] . '"';
        $sOutput .= "]";
    }//ItemGroupId,GroupName
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_ProfileParam($i) {
    if ($i == 2)
        return "GroupName";
    if ($i == 3)
        return "ParamName";
    if ($i == 4)
        return "ParamNameFrench";
}

function insertUpdateProfileParamData($conn) {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $ItemGroup = $_POST['ItemGroup'];
    $RecordId = $_POST['RecordId'];
    $ParamName = str_replace("'", "''", $_POST['ParamName']);  //$_POST['ParamName'];      //************ $_POST['ParamName'];
    $ParamNameFrench = str_replace("'", "''", $_POST['ParamNameFrench']);  //$_POST['ParamNameFrench'];
    $BShow = isset($_POST['BShow'])? $_POST['BShow'] : 'false';
	//var_dump($BShow);
    if ($BShow == 'true')
        $BShow = 1;
    else
        $BShow = 0;
    if ($RecordId == '') {

        $sql = "INSERT INTO t_cprofileparams(ParamName, BShow, ItemGroupId, ParamNameFrench)
                 VALUES ('" . $ParamName . "', " . $BShow . ", '" . $ItemGroup . "', '" . $ParamNameFrench . "')";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_cprofileparams', 'pks' => array('ParamId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE 
                 t_cprofileparams SET 
                 ItemGroupId = '" . $ItemGroup . "',
				 ParamName = '" . $ParamName . "',
				 BShow = " . $BShow . ",
                 ParamNameFrench = '" . $ParamNameFrench . "'				 
                 WHERE ParamId = " . $RecordId;
				 //echo $sql;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_cprofileparams', 'pks' => array('ParamId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
	


    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteProfileParamData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_cprofileparams WHERE ParamId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_cprofileparams', 'pks' => array('ParamId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Year Table***************************************************** */

function getYearData($conn) {

    global $gTEXT;

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Year(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (YearName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS YearID, YearName
				FROM t_year
				$sWhere $sOrder $sLimit ";

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

        $YearName = crnl2br($aRow['YearName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['YearID'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $YearName . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Year($i) {
    if ($i == 2)
        return "YearName";
}

function insertUpdateYearData($conn) {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $RecordId = str_replace("'", "''", $_POST['RecordId']);  //$_POST['RecordId'];
    $YearName = str_replace("'", "''", $_POST['YearName']);  //$_POST['YearName'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_year(YearID, YearName)
                 VALUES ('" . $YearName . "', '" . $YearName . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_year', 'pks' => array('YearID'), 'pk_values' => array($YearName), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_year SET 
                 YearName = '" . $YearName . "'
                 WHERE YearID = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_year', 'pks' => array('YearID'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));

//    if (mysql_query($sql, $conn))
//        $error = 1;
//    else
//        $error = 0;
//
//    echo $error;
}

function deleteYearData($conn) {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $RecordId = $_POST['RecordId'];
    if ($RecordId != '') {

        $sql = " DELETE FROM t_year WHERE YearID = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_year', 'pks' => array('YearID'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));

//    if (mysql_query($sql, $conn))
//        $error = 1;
//    else
//        $error = 0;
//
//    echo $error;
}

/* * ***************************************************Amc Change Reason Table************************************************ */

function getAmcChangeReasonData($conn) {

    global $gTEXT;
    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder.= fnColumnToField_AmcChangeReason(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (AmcChangeReasonName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS AmcChangeReasonId, AmcChangeReasonName
				FROM t_amc_change_reason
				$sWhere $sOrder $sLimit  ";

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

        $AmcChangeReasonName = crnl2br($aRow['AmcChangeReasonName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['AmcChangeReasonId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $AmcChangeReasonName . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_AmcChangeReason($i) {
    if ($i == 2)
        return "AmcChangeReasonName";
}

function insertUpdateAmcChangeReasonData($conn) {

    $RecordId = $_POST['RecordId'];
    $AmcChangeReasonName = str_replace("'", "''", $_POST['AmcChangeReasonName']);  //$_POST['AmcChangeReasonName'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_amc_change_reason(AmcChangeReasonName)
                 VALUES ('" . $AmcChangeReasonName . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_amc_change_reason', 'pks' => array('AmcChangeReasonId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_amc_change_reason SET 
                 AmcChangeReasonName = '" . $AmcChangeReasonName . "'
                 WHERE AmcChangeReasonId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_amc_change_reason', 'pks' => array('AmcChangeReasonId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteAmcChangeReasonData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_amc_change_reason WHERE AmcChangeReasonId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_amc_change_reason', 'pks' => array('AmcChangeReasonId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Month Table*************************************************** */

function getMonthData($conn) {

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Month(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (MonthName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT * FROM `t_month`
                ORDER BY `t_month`.`MonthId` ASC
                $sWhere $sOrder $sLimit ";

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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>Edit</span></a>";
    $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>Delete</span></a>";

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $MonthName = crnl2br($aRow['MonthName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['MonthId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $MonthName . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Month($i) {
    if ($i == 2)
        return "MonthName";
}

function insertUpdateMonthData($conn) {

    $RecordId = $_POST['RecordId'];
    $MonthName = $_POST['MonthName'];

    if ($RecordId == '') {

        $sql = "SELECT MAX(MonthId) AS M FROM t_month ";
        $qr = mysql_query($sql);
        $r = mysql_fetch_object($qr);
        $Id = $r->M;
        $Id++;

        $sql = ' INSERT INTO t_month (MonthId, MonthName)
                 VALUES ("' . $Id . '", "' . $MonthName . '")';
    } else {

        $sql = ' UPDATE 
                 t_month SET 
                 MonthName = "' . $MonthName . '"
                 WHERE MonthId = ' . $RecordId;
    }

    if (mysql_query($sql, $conn))
        $error = 1;
    else
        $error = 0;

    echo $error;
}

function deleteMonthData($conn) {

    $RecordId = $_POST['RecordId'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_month WHERE MonthId = " . $RecordId . " ";

        if (mysql_query($sql)) {
            $error = 1;
        }
        else
            $error = 0;

        echo $error;
    }
}

/* * ****************************************************Item Table***************************************************** */

function getItemData($conn) {

    global $gTEXT;
    //mysql_query('SET CHARACTER SET utf8');
    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Item(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS ItemGroupId, GroupName,GroupNameFrench,bPatientInfo	
				FROM t_itemgroup
				$sWhere $sOrder $sLimit ; ";

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

        $GroupName = crnl2br($aRow['GroupName']);
        $l = "<input type='checkbox' " . ($aRow['bPatientInfo'] == 1 ? 'checked' : '') . " value = " . $aRow['bPatientInfo'] . " disabled/><span class='custom-checkbox'></span>";

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['ItemGroupId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $GroupName . '",'; //********** $GroupName . '",';
        $sOutput .= '"' . $aRow['GroupNameFrench'] . '",';
        $sOutput .= '"' . $l . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['bPatientInfo'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Item($i) {
    if ($i == 2)
        return "GroupName";
    if ($i == 3)
        return "GroupNameFrench";
}

function insertUpdateItemData($conn) {

    $RecordId = $_POST['RecordId'];
    $GroupName = str_replace("'", "''", $_POST['GroupName']);;      //**********$_POST['GroupName'];  
    $GroupNameFrench =  str_replace("'", "''", $_POST['GroupNameFrench']);
    //$bPatientInfo = $_POST['bPatientInfo'];
	$bPatientInfo = isset($_POST['bPatientInfo'])? $_POST['bPatientInfo'] : 'false';
	//var_dump($bPatientInfo);
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($bPatientInfo == 'true' || $bPatientInfo == 'on') {
        $bPatientInfo = 1;
    } else {
        $bPatientInfo = 0;
    }

    if ($RecordId == '') {
        $sql = "INSERT INTO t_itemgroup(GroupName,GroupNameFrench,bPatientInfo)
                 VALUES ('" . $GroupName . "','" . $GroupNameFrench . "','" . $bPatientInfo . "');";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_itemgroup', 'pks' => array('ItemGroupId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE 
                 t_itemgroup SET 
                 GroupName = '" . $GroupName . "',
                 GroupNameFrench = '" . $GroupNameFrench . "',
                 bPatientInfo = '" . $bPatientInfo . "'
                 WHERE ItemGroupId = " . $RecordId . ";";

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_itemgroup', 'pks' => array('ItemGroupId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteItemData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_itemgroup WHERE ItemGroupId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_itemgroup', 'pks' => array('ItemGroupId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Doses Form Table***************************************************** */

function getDosesFormData($conn) {

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_DosesForm(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (DosesFormName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS DosesFormId, DosesFormName	
				FROM t_dosesform
				$sWhere $sOrder $sLimit ; ";

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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>Edit</span></a>";
    $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>Delete</span></a>";

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $DosesFormName = crnl2br($aRow['DosesFormName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['DosesFormId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $DosesFormName . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_DosesForm($i) {
    if ($i == 2)
        return "DosesFormName";
}

function insertUpdateDosesFormData($conn) {

    $RecordId = $_POST['RecordId'];
    $DosesName = $_POST['DosesName'];

    if ($RecordId == '') {

        $sql = "SELECT MAX(DosesFormId) as M FROM t_dosesform ";
        $qr = mysql_query($sql);
        $r = mysql_fetch_object($qr);
        $Id = $r->M;
        $Id++;

        $sql = ' INSERT INTO t_dosesform(DosesFormId,DosesFormName)
                 VALUES ("' . $Id . '", "' . $DosesName . '")';
    } else {

        $sql = ' UPDATE 
                 t_dosesform SET 
                 DosesFormName = "' . $DosesName . '"
                 WHERE DosesFormId = ' . $RecordId;
    }

    if (mysql_query($sql, $conn))
        $error = 1;
    else
        $error = 0;

    echo $error;
}

function deleteDosesFormData($conn) {

    $RecordId = $_POST['RecordId'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_dosesform WHERE DosesFormId = " . $RecordId . " ";

        if (mysql_query($sql)) {
            $error = 1;
        }
        else
            $error = 0;

        echo $error;
    }
}

/* * ****************************************************Service Table***************************************************** */

function getServiceData($conn) {

    global $gTEXT;
    //mysql_query('SET CHARACTER SET utf8');
    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ServiceType(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (ServiceTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS ServiceTypeId,ServiceTypeName,ServiceTypeNameFrench	
				FROM t_servicetype
				$sWhere $sOrder $sLimit ";

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

        $STypeName = crnl2br($aRow['ServiceTypeName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['ServiceTypeId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $STypeName . '",';    //***********$STypeName . '",';
        $sOutput .= '"' . $aRow['ServiceTypeNameFrench'] . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_ServiceType($i) {
    if ($i == 2)
        return "ServiceTypeName";
    if ($i == 3)
        return "ServiceTypeNameFrench";
}

function insertUpdateServiceData($conn) {

    $RecordId = $_POST['RecordId'];
    $STypeName = str_replace("'", "''", $_POST['STypeName']);  //$_POST['STypeName'];  //**********$_POST['STypeName'];  
    $STypeNameFrench = str_replace("'", "''", $_POST['STypeNameFrench']); //$_POST['STypeNameFrench'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_servicetype(ServiceTypeName,ServiceTypeNameFrench)
                 VALUES ('" . $STypeName . "', '" . $STypeNameFrench . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_servicetype', 'pks' => array('ServiceTypeId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_servicetype SET 
                 ServiceTypeName = '" . $STypeName . "',
                 ServiceTypeNameFrench = '" . $STypeNameFrench . "'
                 WHERE ServiceTypeId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_servicetype', 'pks' => array('ServiceTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteServiceData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_servicetype WHERE ServiceTypeId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_servicetype', 'pks' => array('ServiceTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function getCountryData($conn) {

    global $gTEXT;
    //mysql_query('SET CHARACTER SET utf8');

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Country(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (CountryCode LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                            OR " . " ISO3 LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " CenterLat LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
      	                    OR " . " CountryName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " CenterLong LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " ZoomLevel LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ) ";
    }
    $sql = "SELECT SQL_CALC_FOUND_ROWS CountryId, CountryCode, CountryName, CountryNameFrench, ISO3, NumCode, CenterLat, CenterLong, ZoomLevel, LevelType, StartMonth, StartYear
				FROM t_country
				$sWhere $sOrder $sLimit ; ";
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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>" . $gTEXT['Edit'] . "</span></a>";
    $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>" . $gTEXT['Delete'] . "</span></a>";

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $CountryCode = crnl2br($aRow['ISO3']);
        if ($aRow['LevelType'] == 1)
            $LevelName = 'Facility Level';
        else
            $LevelName = 'National Level';
        $MonthName = date("M", mktime(0, 0, 0, $aRow['StartMonth'], 10));

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['CountryId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $CountryCode . '",';
        $sOutput .= '"' . $aRow['CountryName'] . '",'; //$aRow['CountryName'] . '",';
        $sOutput .= '"' . $aRow['CountryNameFrench'] . '",';
        $sOutput .= '"' . $LevelName . '",';
        $sOutput .= '"' . $MonthName . " " . $aRow['StartYear'] . '",';
        $sOutput .= '"' . $aRow['CenterLat'] . ", " . $aRow['CenterLong'] . '",';
        $sOutput .= '"' . $aRow['ZoomLevel'] . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['LevelType'] . '",';
        $sOutput .= '"' . $aRow['StartMonth'] . '",';
        $sOutput .= '"' . $aRow['StartYear'] . '",';
        $sOutput .= '"' . $aRow['CenterLat'] . '",';
        $sOutput .= '"' . $aRow['CenterLong'] . '" ';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Country($i) {
    if ($i == 2)
        return "CountryCode";
    if ($i == 3)
        return "CountryName";
    if ($i == 4)
        return "CountryNameFrench";
}

function insertUpdateCountryData($conn) {

    $RecordId = $_POST['RecordId'];
    $CountryCode = str_replace("'", "''", $_POST['CountryCode']); //$_POST['CountryCode'];
    $CountryName = str_replace("'", "''", $_POST['CountryName']); //$_POST['CountryName']; 
    $CountryNameFrench = str_replace("'", "''", $_POST['CountryNameFrench']); //$_POST['CountryNameFrench'];
    $CenterLat = $_POST['CenterLat'];
    $CenterLong = $_POST['CenterLong'];
    $ZoomLevel = $_POST['ZoomLevel'];
    //$MonthId = $_POST['MonthId'];
    $MonthId = isset($_POST['MonthId'])? $_POST['MonthId'] : '';
    $YearId = isset($_POST['YearId'])? $_POST['YearId'] : '';
    //$YearId = str_replace("'", "''", $_POST['YearId']); //$_POST['YearId'];
    $levelType = $_POST['levelType'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {



        $sql = "INSERT INTO t_country( CountryName, CountryNameFrench, ISO3, CenterLat, CenterLong, ZoomLevel, LevelType, StartMonth, StartYear)
                 VALUES ('" . $CountryName . "', '" . $CountryNameFrench . "', '" . $CountryCode . "', '" . $CenterLat . "', '" . $CenterLong . "', '" . $ZoomLevel . "', '" . $levelType . "', '" . $MonthId . "', '" . $YearId . "')";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_country', 'pks' => array('CountryId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_country SET 
				 CountryName = '" . $CountryName . "',
                 CountryNameFrench = '" . $CountryNameFrench . "', 
				 ISO3 = '" . $CountryCode . "',
				 CenterLat = '" . $CenterLat . "',
				 CenterLong = '" . $CenterLong . "',				 
				 ZoomLevel = '" . $ZoomLevel . "',
                 LevelType = '" . $levelType . "',
                 StartMonth = '" . $MonthId . "',
                 StartYear = '" . $YearId . "'
                 WHERE CountryId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_country', 'pks' => array('CountryId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteCountryData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_country WHERE CountryId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_country', 'pks' => array('CountryId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************MOS Type Table***************************************************** */

function getMOSTypeData($conn) {

    global $gTEXT;
    //mysql_query('SET CHARACTER SET utf8');
    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_MOSType(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (MosTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
							OR " . " MinMos LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " MaxMos LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " ColorCode LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ) ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS MosTypeId, MosTypeName, MosTypeNameFrench, MinMos, MaxMos, MosLabel, ColorCode
				FROM t_mostype
				$sWhere $sOrder $sLimit ; ";

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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>" . $gTEXT['Edit'] . "</span></a>";
    $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>" . $gTEXT['Delete'] . "</span></a>";

    $f = 0;

    while ($aRow = mysql_fetch_array($result)) {

        $MosTypeName = crnl2br($aRow['MosTypeName']); // trim(preg_replace('/\s+/', ' ', addslashes($aRow['MosTypeName'])));
        $ColorCode = mysql_real_escape_string('<span style="width:30px;height:15px;display:block;align:center;background:' . $aRow['ColorCode'] . ';"></span>');
        $MosTypeNameFrench = crnl2br($aRow['MosTypeNameFrench']); // trim(preg_replace('/\s+/', ' ', addslashes($aRow['MosTypeNameFrench'])));
        $MosLabel = crnl2br($aRow['MosLabel']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['MosTypeId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $MosTypeName . '",'; // $aRow['MosTypeName'] . '",';
        $sOutput .= '"' . $MosTypeNameFrench . '",';
        $sOutput .= '"' . $aRow['MinMos'] . '",';
        $sOutput .= '"' . $aRow['MaxMos'] . '",';
        $sOutput .= '"' . $ColorCode . '",';
        $sOutput .= '"' . $MosLabel . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['ColorCode'] . '"';

        $sOutput .= "]";
    }

    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_MOSType($i) {
    if ($i == 1)
        return "MosTypeId";
}

function insertUpdateMOSTypeData($conn) {

    $RecordId = $_POST['RecordId'];
    $MosTypeName = str_replace("'", "''", $_POST['MosTypeName']); //$_POST['MosTypeName'];  
    $MosTypeNameFrench = str_replace("'", "''", $_POST['MosTypeNameFrench']);
    $MinMos = $_POST['MinMos'];
    $MaxMos = $_POST['MaxMos'];
    $ColorCode = str_replace("'", "''", $_POST['ColorCode']);//$_POST['ColorCode']; 
    $MosLabel = str_replace("'", "''", $_POST['MosLabel']); //$_POST['MosLabel'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    if ($RecordId == '') {
        $sql = "INSERT INTO t_mostype(MosTypeName,MosTypeNameFrench, MinMos, MaxMos, ColorCode,MosLabel)
                 VALUES ('" . $MosTypeName . "', '" . $MosTypeNameFrench . "', '" . $MinMos . "', '" . $MaxMos . "', '" . $ColorCode . "', '" . $MosLabel . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_mostype', 'pks' => array('MosTypeId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_mostype SET 
                 MosTypeName = '" . $MosTypeName . "',
                 MosTypeNameFrench = '" . $MosTypeNameFrench . "',
				 MinMos = '" . $MinMos . "',
				 MaxMos = '" . $MaxMos . "',
				 MosLabel = '" . $MosLabel . "',
				 ColorCode = '" . $ColorCode . "'				 
                 WHERE MosTypeId = '" . $RecordId . "'";
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_mostype', 'pks' => array('MosTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteMOSTypeData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_mostype WHERE MosTypeId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_mostype', 'pks' => array('MosTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Agency Table***************************************************** */

function getFundingSourceData($conn) {

    global $gTEXT;
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }
	

    $data = array();
    $itemGroupId = $_POST['itemGroupId'];
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Funding_Source(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "WHERE t_itemgroup.ItemGroupId = " . $itemGroupId . " OR " . $itemGroupId . "= 0";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND  (FundingSourceName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
							OR " . " FundingSourceDesc LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                            OR $GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ) ";
    }
    $sql = "SELECT SQL_CALC_FOUND_ROWS FundingSourceId, FundingSourceName, FundingSourceDesc,t_fundingsource.ItemGroupId,$GroupName GroupName
				FROM  t_fundingsource
				Inner Join t_itemgroup ON t_fundingsource.ItemGroupId = t_itemgroup.ItemGroupId
				$sWhere $sOrder $sLimit ; ";


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

        $FundingSourceName = crnl2br($aRow['FundingSourceName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['FundingSourceId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $aRow['FundingSourceName'] . '",';
        $sOutput .= '"' . $aRow['FundingSourceDesc'] . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['ItemGroupId'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Funding_Source($i) {
    if ($i == 2)
        return "FundingSourceName";
    if ($i == 3)
        return "FundingSourceName";
    else if ($i == 4)
        return "FundingSourceDesc";
}

function insertUpdateFundingSourceData($conn) {
    $ItemGroup = $_POST['ItemGroup'];
    $RecordId = $_POST['RecordId'];
    $FundingSourceName = str_replace("'", "''", $_POST['FundingSourceName']);  //$_POST['FundingSourceName'];
    $FundingSourceDesc = str_replace("'", "''", $_POST['FundingSourceDesc']);  //$_POST['FundingSourceDesc'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_fundingsource(FundingSourceName, FundingSourceDesc,ItemGroupId)
                 VALUES ('" . $FundingSourceName . "', '" . $FundingSourceDesc . "', '" . $ItemGroup . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_fundingsource', 'pks' => array('FundingSourceId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = " UPDATE 
                 t_fundingsource SET 
                 ItemGroupId = '" . $ItemGroup . "',
				 FundingSourceName = '" . $FundingSourceName . "',
				 FundingSourceDesc = '" . $FundingSourceDesc . "'				 				 
                 WHERE FundingSourceId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_fundingsource', 'pks' => array('FundingSourceId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteFundingSourceData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_fundingsource WHERE FundingSourceId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_fundingsource', 'pks' => array('FundingSourceId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
        ;
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Shipment Status Table***************************************************** */

function getSStatusData($conn) {

    global $gTEXT;

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_SStatus(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (ShipmentStatusDesc LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS ShipmentStatusId, ShipmentStatusDesc	
				FROM t_shipmentstatus
				$sWhere $sOrder $sLimit ";

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

        $SStatusDesc = crnl2br($aRow['ShipmentStatusDesc']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['ShipmentStatusId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['ShipmentStatusDesc'] . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_SStatus($i) {
    if ($i == 1)
        return "ShipmentStatusId";
    else if ($i == 2)
        return "ShipmentStatusDesc";
}

function insertUpdateSStatusData($conn) {

    $RecordId = $_POST['RecordId'];
    $SStatusDesc = str_replace("'", "''", $_POST['SStatusDesc']);  //$_POST['SStatusDesc'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_shipmentstatus(ShipmentStatusDesc)
                 VALUES ('" . $SStatusDesc . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_shipmentstatus', 'pks' => array('ShipmentStatusId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE 
                 t_shipmentstatus SET 
                 ShipmentStatusDesc = '" . $SStatusDesc . "'
                 WHERE ShipmentStatusId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_shipmentstatus', 'pks' => array('ShipmentStatusId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteSStatusData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_shipmentstatus WHERE ShipmentStatusId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_shipmentstatus', 'pks' => array('ShipmentStatusId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Facility Type Table***************************************************** */

function getFacilityTypeData($conn) {

    global $gTEXT;

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Facility(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (FTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS FTypeId,FTypeName	
				FROM t_facility_type
				$sWhere $sOrder $sLimit ";

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

        $FTypeName = crnl2br($aRow['FTypeName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['FTypeId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $FTypeName . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Facility($i) {
    if ($i == 2)
        return "FTypeName";
}

function insertUpdateFacilityTypeData($conn) {

    $RecordId = $_POST['RecordId'];
    $FTypeName = str_replace("'", "''", $_POST['FTypeName']);  //$_POST['FTypeName'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_facility_type(FTypeName)
                 VALUES ('" . $FTypeName . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_facility_type', 'pks' => array('FTypeId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_facility_type SET 
                 FTypeName = '" . $FTypeName . "'
                 WHERE FTypeId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_facility_type', 'pks' => array('FTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteFacilityTypeData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_facility_type WHERE FTypeId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_facility_type', 'pks' => array('FTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Facility Level Table***************************************************** */

function getFacilityLevelData($conn) {

    global $gTEXT;

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Facility_level(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (FLevelName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%')
	                    OR (FLevelNameFrench LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS FLevelId,FLevelName,FLevelNameFrench,ColorCode	
				FROM t_facility_level
				$sWhere $sOrder $sLimit ";

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

        $FLevelName = crnl2br($aRow['FLevelName']);
        $FLevelNameFrench = crnl2br($aRow['FLevelNameFrench']);
        $ColorCode = mysql_real_escape_string('<span style="width:30px;height:15px;display:block;align:center;background:' . $aRow['ColorCode'] . ';"></span>');
        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aRow['FLevelId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $FLevelName . '",';
        $sOutput .= '"' . $FLevelNameFrench . '",';
        $sOutput .= '"' . $ColorCode . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['ColorCode'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Facility_level($i) {
    if ($i == 1)
        return "FLevelName";
    else if ($i == 2)
        return "FLevelNameFrench ";
    else if ($i == 3)
        return "ColorCode ";
}

function insertUpdateFacilityLevelData($conn) {

    $RecordId = $_POST['RecordId'];
    $FLevelName = str_replace("'", "''", $_POST['FLevelName']);  //$_POST['FLevelName'];
    $FLevelNameFrench = str_replace("'", "''", $_POST['FLevelNameFrench']);  //$_POST['FLevelNameFrench'];
    $ColorCode = str_replace("'", "''", $_POST['ColorCode']);  //$_POST['ColorCode'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_facility_level(FLevelName,FLevelNameFrench,ColorCode)
                 VALUES ('" . $FLevelName . "', '" . $FLevelNameFrench . "', '" . $ColorCode . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_facility_level', 'pks' => array('FLevelId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE 
                 t_facility_level SET 
                 FLevelName = '" . $FLevelName . "',
                 FLevelNameFrench = '" . $FLevelNameFrench . "',
                 ColorCode = '" . $ColorCode . "'
                 WHERE FLevelId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_facility_level', 'pks' => array('FLevelId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteFacilityLevelData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    if ($RecordId != '') {

        $sql = " DELETE FROM t_facility_level WHERE FLevelId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_facility_level', 'pks' => array('FLevelId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Procuring Agents Table***************************************************** */

function getProcuringAgentsData($conn) {

    global $gTEXT;


    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_procuring_agents(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (PAgencyName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS PAgencyId, PAgencyName	
				FROM t_procurement_agents
				$sWhere $sOrder $sLimit ";

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

        $SStatusDesc = crnl2br($aRow['PAgencyName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['PAgencyId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['PAgencyName'] . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_procuring_agents($i) {
    if ($i == 1)
        return "PAgencyId";
    else if ($i == 2)
        return "PAgencyName";
}

function insertUpdateProcuringAgentsData($conn) {

    $RecordId = $_POST['RecordId'];
    $PAgency = str_replace("'", "''", $_POST['PAgencyName']);  //$_POST['PAgencyName'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_procurement_agents(PAgencyName )
                 VALUES ('" . $PAgency . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_procurement_agents', 'pks' => array('PAgencyId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = " UPDATE 
                 t_procurement_agents SET 
                 PAgencyName = '" . $PAgency . "'
                 WHERE PAgencyId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_procurement_agents', 'pks' => array('PAgencyId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteProcuringAgentsData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_procurement_agents WHERE PAgencyId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_procurement_agents', 'pks' => array('PAgencyId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * *********************************************************Agreement Data*********************************************************** */

function getAgreementData($conn) {

    global $gTEXT;
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }
	
	
    $itemGroupId = $_POST['itemGroupId'];

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_agreement(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }


    $sWhere = "WHERE t_itemgroup.ItemGroupId = " . $itemGroupId . " OR " . $itemGroupId . "= 0";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND (AgreementName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  
                    OR FundingSourceName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS AgreementId, AgreementName, a.FundingSourceId, FundingSourceName
				,a.ItemGroupId,$GroupName GroupName 	
				FROM t_subagreements a
                INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId    
				Inner Join t_itemgroup ON a.ItemGroupId = t_itemgroup.ItemGroupId				
				$sWhere $sOrder $sLimit 
                		;";
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

        $AgreementName = crnl2br($aRow['AgreementName']);

        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aRow['AgreementId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $aRow['FundingSourceName'] . '",';
        $sOutput .= '"' . $aRow['AgreementName'] . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['FundingSourceId'] . '",';
        $sOutput .= '"' . $aRow['ItemGroupId'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_agreement($i) {
    //echo $i."_rjljdljdddkljklkljkddklkddk";
    if ($i == 5)
        return "GroupName ";
    else if ($i == 3)
        return "FundingSourceName ";
    else if ($i == 4)
        return "AgreementName ";
}

function insertUpdateAgreementData($conn) {
    $ItemGroupId = $_POST['ItemGroup'];

    $RecordId = $_POST['RecordId'];
    $AgreementName = str_replace("'", "''", $_POST['AgreementName']);  //$_POST['AgreementName'];
    $FundingSourceId = $_POST['FundingSourceId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_subagreements(AgreementName,FundingSourceId,ItemGroupId)
                 VALUES ('" . $AgreementName . "', '" . $FundingSourceId . "', '" . $ItemGroupId . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_subagreements', 'pks' => array('AgreementId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_subagreements SET 
                 ItemGroupId = '" . $ItemGroupId . "',
				 AgreementName = '" . $AgreementName . "',
                 FundingSourceId = '" . $FundingSourceId . "'
                 WHERE AgreementId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_subagreements', 'pks' => array('AgreementId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteAgreementData($conn) {
    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    if ($RecordId != '') {

        $sql = " DELETE FROM t_subagreements WHERE AgreementId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_subagreements', 'pks' => array('AgreementId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Repport Status Table***************************************************** */

function getReportStatusData($conn) {

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ReportStatus(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (StatusName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS StatusId,StatusName	
				FROM t_status
				$sWhere $sOrder $sLimit 
	       ; ";

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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>Edit</span></a>";
    $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>Delete</span></a>";

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $SStatusDesc = crnl2br($aRow['StatusName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['StatusId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['StatusName'] . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_ReportStatus($i) {
    if ($i == 1)
        return "StatusId";
    else if ($i == 2)
        return "StatusName";
}

function insertUpdateReportStatusData($conn) {

    $RecordId = $_POST['RecordId'];
    $ReportStatusDesc = $_POST['ReportStatusDesc'];

    if ($RecordId == '') {

        $sql = "SELECT MAX(StatusId) as M FROM t_status ";
        $qr = mysql_query($sql);
        $r = mysql_fetch_object($qr);
        $Id = $r->M;
        $Id++;

        $sql = ' INSERT INTO t_status(StatusId,StatusName)
                 VALUES ("' . $Id . '", "' . $ReportStatusDesc . '")';
    } else {

        $sql = ' UPDATE 
                 t_status SET 
                 StatusName = "' . $ReportStatusDesc . '"
                 WHERE StatusId = ' . $RecordId;
    }

    if (mysql_query($sql, $conn))
        $error = 1;
    else
        $error = 0;

    echo $error;
}

function deleteReportStatusData($conn) {

    $RecordId = $_POST['RecordId'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_status WHERE StatusId = " . $RecordId . " ";

        if (mysql_query($sql)) {
            $error = 1;
        }
        else
            $error = 0;

        echo $error;
    }
}

/* * ****************************************************Patient Overview Table***************************************************** */

function getPOMasterData($conn) {

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_POMaster(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (POMasterName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS POMasterId, POMasterName	
				FROM t_pomaster
				$sWhere $sOrder $sLimit ";

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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>Edit</span></a>";
    $z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>Delete</span></a>";

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $SStatusDesc = crnl2br($aRow['POMasterName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['POMasterId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['POMasterName'] . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_POMaster($i) {
    if ($i == 1)
        return "POMasterId";
    else if ($i == 2)
        return "POMasterName";
}

function insertUpdatePOMasterTableData($conn) {

    $RecordId = $_POST['RecordId'];
    $POMasterName = $_POST['POMasterName'];

    if ($RecordId == '') {

        $sql = "SELECT MAX(POMasterId) as M FROM t_pomaster ";
        $qr = mysql_query($sql);
        $r = mysql_fetch_object($qr);
        $Id = $r->M;
        $Id++;

        $sql = ' INSERT INTO t_pomaster(POMasterId,POMasterName)
                 VALUES ("' . $Id . '", "' . $POMasterName . '")';
    } else {

        $sql = ' UPDATE 
                 t_pomaster SET 
                 POMasterName = "' . $POMasterName . '"
                 WHERE POMasterId = ' . $RecordId;
    }

    if (mysql_query($sql, $conn))
        $error = 1;
    else
        $error = 0;

    echo $error;
}

function deletePOMasterData($conn) {

    $RecordId = $_POST['RecordId'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_pomaster WHERE POMasterId = " . $RecordId . " ";

        if (mysql_query($sql)) {
            $error = 1;
        }
        else
            $error = 0;

        echo $error;
    }
}

/* * ****************************************************Adjust Reason Table***************************************************** */

function getAdjustReasonData($conn) {

    global $gTEXT;


    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Adjust_Reason(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (AdjustReason LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
							) ";
    }
    $sql = "	SELECT SQL_CALC_FOUND_ROWS AdjustId, AdjustReason
				FROM  t_adjust_reason
				$sWhere $sOrder $sLimit ; ";

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
        $AdjustReason = crnl2br($aRow['AdjustReason']);

        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aRow['AdjustId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['AdjustReason'] . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Adjust_Reason($i) {
    if ($i == 1)
        return "AdjustId";
    if ($i == 2)
        return "AdjustReason";
}

function insertUpdateAdjustReasonData($conn) {

    $AdjustId = $_POST['AdjustId'];
    $AdjustReason = str_replace("'", "''", $_POST['AdjustReason']);  //$_POST['AdjustReason'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($AdjustId == '') {

        $sql = " INSERT INTO t_adjust_reason(AdjustReason)
                 VALUES ('" . $AdjustReason . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_adjust_reason', 'pks' => array('AdjustId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE
                 t_adjust_reason SET
                 AdjustReason = '" . $AdjustReason . "'
                 WHERE AdjustId = " . $AdjustId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_adjust_reason', 'pks' => array('AdjustId'), 'pk_values' => array($AdjustId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteAdjustReasonData($conn) {
    $AdjustId = $_POST['AdjustId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($AdjustId != '') {

        $sql = " DELETE FROM t_adjust_reason WHERE AdjustId = " . $AdjustId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_adjust_reason', 'pks' => array('AdjustId'), 'pk_values' => array($AdjustId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ************************************************* MOS Type for Facility*********************************************** */

function getMOSTypeFacilityData($conn) {

    global $gTEXT;
    //mysql_query('SET CHARACTER SET utf8');

    $CountryId = $_POST['CountryId'];
    $FacilityLevel = $_POST['FacilityLevel'];

    if ($FacilityLevel) {
        $FacilityLevel = " AND a.FLevelId = " . $FacilityLevel . " ";
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_MOSTypeFacility(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (MosTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
							OR " . " MinMos LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " MaxMos LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . "a. ColorCode LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ) ";
    }

    $sql = "	SELECT MosTypeId, MosTypeName, MinMos, MaxMos,a. ColorCode, IconMos, IconMos_Width, IconMos_Height,MosLabel,a.CountryId,a.FLevelId
				FROM t_mostype_facility a
				INNER JOIN t_country b ON a.CountryId = b.CountryId
				INNER JOIN t_facility_level c ON a.FLevelId = c.FLevelId
				AND (a.CountryId = " . $CountryId . " OR " . $CountryId . " = 0) " . $FacilityLevel . " 
                " . $sWhere . " " . $sOrder . " " . $sLimit . " ";

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

        $MosTypeName = crnl2br($aRow['MosTypeName']);
        $ColorCode = mysql_real_escape_string('<span style="width:30px;height:15px;display:block;align:center;background:' . $aRow['ColorCode'] . ';"></span>');
        //$MosTypeNameFrench = trim(preg_replace('/\s+/', ' ', addslashes($aRow['MosTypeNameFrench'])));		
        $MosLabel = crnl2br($aRow['MosLabel']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['MosTypeId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $MosTypeName . '",';
        $sOutput .= '"' . number_format($aRow['MinMos'], 1) . '",';
        $sOutput .= '"' . number_format($aRow['MaxMos'], 1) . '",';
        $sOutput .= '"' . $ColorCode . '",';
        $sOutput .= '"' . $aRow['IconMos'] . '",';
        $sOutput .= '"' . $aRow['IconMos_Width'] . '",';
        $sOutput .= '"' . $aRow['IconMos_Height'] . '",';
        $sOutput .= '"' . $MosLabel . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['ColorCode'] . '",';
        $sOutput .= '"' . $aRow['CountryId'] . '",';
        $sOutput .= '"' . $aRow['FLevelId'] . '"';
        $sOutput .= "]";
    }

    $sOutput .= ']}';
    echo $sOutput;
}

function fnColumnToField_MOSTypeFacility($i) {
    if ($i == 1)
        return "MosTypeId";
}

function insertUpdateMOSTypeFacilityData($conn) {
    $RecordId = $_POST['RecordId'];
    $CountryId = $_POST['CountryId'];
    $FLevelId = $_POST['FacilityId'];
    $MosTypeName = str_replace("'", "''", $_POST['MosTypeName']);  //$_POST['MosTypeName'];
    $MinMos = $_POST['MinMos'];
    $MaxMos = $_POST['MaxMos'];
    $ColorCode = str_replace("'", "''", $_POST['ColorCode']);  //$_POST['ColorCode'];
    $MosLabel = str_replace("'", "''", $_POST['MosLabel']);  //$_POST['MosLabel'];
    $IconMos = str_replace("'", "''", $_POST['IconMos']);  //$_POST['IconMos'];
    $IconMos_Width = $_POST['IconMos_Width'];
    $IconMos_Height = $_POST['IconMos_Height'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_mostype_facility(FLevelId, MosTypeName, MinMos, MaxMos, ColorCode, IconMos, IconMos_Width, IconMos_Height,MosLabel,CountryId)
                 VALUES ('" . $FLevelId . "', '" . $MosTypeName . "', '" . $MinMos . "', '" . $MaxMos . "', '" . $ColorCode . "', '" . $IconMos . "', '" . $IconMos_Width . "', '" . $IconMos_Height . "', '" . $MosLabel . "', '" . $CountryId . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_mostype_facility', 'pks' => array('MosTypeId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);

        //$query2 = "INSERT INTO t_country_product(CountryId, ItemNo, ItemGroupId, bActive) 
        // VALUES (1, [LastInsertedId], " . $ItemGroupId . ", " . $bActive . ");";

        $query2 = "INSERT INTO t_mostype_facility_details(MosTypeId,FLevelId,MosTypeName, MinMos, MaxMos, ColorCode, IconMos, IconMos_Width, IconMos_Height,CountryId)
			 VALUES ([LastInsertedId], '" . $FLevelId . "', '" . $MosTypeName . "', '" . $MinMos . "', '" . $MaxMos . "', '" . $ColorCode . "', '" . $IconMos . "', '" . $IconMos_Width . "', '" . $IconMos_Height . "', '" . $CountryId . "')";

        $aQuery2 = array('command' => 'INSERT', 'query' => $query2, 'sTable' => 't_mostype_facility_details', 'pks' => array('MostypeDetailsId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1, $aQuery2);
    } else {
        $sql = "UPDATE t_mostype_facility SET 
                 MosTypeName = '" . $MosTypeName . "',
				 CountryId = '" . $CountryId . "',
				  FLevelId = '" . $FLevelId . "',
				 MinMos = '" . $MinMos . "',
				 MaxMos = '" . $MaxMos . "',
				 ColorCode = '" . $ColorCode . "',
				 MosLabel = '" . $MosLabel . "',				 
				 IconMos = '" . $IconMos . "',
				 IconMos_Width = '" . $IconMos_Width . "',
				 IconMos_Height = '" . $IconMos_Height . "'				 
                 WHERE MosTypeId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_mostype_facility', 'pks' => array('MosTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);

        /*
          $query2="UPDATE t_mostype_facility_details SET
          MosTypeName='".$MosTypeName."'  WHERE MosTypeId='".$RecordId."' AND CountryId='".$CountryId."' AND FLevelId='".$FLevelId."'";
          echo $query2;
          $aQuery2 = array('command' => 'UPDATE', 'query' => $query2, 'sTable' => 't_mostype_facility_details', 'pks' => array('MosTypeId', 'CountryId', 'FLevelId'), 'pk_values' => array($RecordId, $CountryId, $FLevelId), 'bUseInsetId' => FALSE);
         */

        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));

//    if (mysql_query($sql, $conn)) {
//        $inserted_id = mysql_insert_id();
//        $sql = 'INSERT INTO t_mostype_facility_details(MosTypeId,FLevelId,MosTypeName, MinMos, MaxMos, ColorCode, IconMos, IconMos_Width, IconMos_Height,CountryId)
//			 VALUES ("' . $inserted_id . '","' . $FLevelId . '","' . $MosTypeName . '", "' . $MinMos . '","' . $MaxMos . '","' . $ColorCode . '","' . $IconMos . '","' . $IconMos_Width . '","' . $IconMos_Height . '","' . $CountryId . '")';
//        //mysql_query($sql, $conn);
//
//        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_mostype_facility_details', 'pks' => array('MostypeDetailsId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
//        $aQuerys = array($aQuery1);
//        echo json_encode(exec_query($aQuerys, $jUserId, $language));
//
//
//        //$error = 1;
//    }
    //else
    //$error = 0;
    //echo $error;
}

function deleteMOSTypeFacilityData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_mostype_facility WHERE MosTypeId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_mostype_facility', 'pks' => array('MosTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ************************************************* MOS Type for Facility Details*********************************************** */

function getMOSTypeFacilityDetailsData($conn) {
    global $gTEXT;
    $MosTypeId = $_POST['MosTypeId'];
    $CountryId = $_POST['CountryId'];
    $FacilityLevel = $_POST['FacilityLevel'];

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_MOSTypeFacilityDetails(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND (MosTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
							OR " . " MinMos LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . " MaxMos LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
							OR " . "a. ColorCode LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ) ";
    }

    /* $sql = "	SELECT MosTypeId, MosTypeName, MinMos, MaxMos,a. ColorCode, IconMos, IconMos_Width, IconMos_Height,MosLabel,a.CountryId,a.FLevelId
      FROM t_mostype_facility a
      INNER JOIN t_country b ON a.CountryId = b.CountryId
      INNER JOIN t_facility_level c ON a.FLevelId = c.FLevelId
      AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) ".$FacilityLevel."
      ".$sWhere." ".$sOrder." ".$sLimit." "; */


    $sql = "SELECT SQL_CALC_FOUND_ROWS MostypeDetailsId, MosTypeId, MosTypeName, MinMos, MaxMos,a.ColorCode, IconMos, IconMos_Width, IconMos_Height,'' MosLabel,a.CountryId,a.FLevelId
				FROM t_mostype_facility_details a
				INNER JOIN t_country b ON a.CountryId = b.CountryId
				INNER JOIN t_facility_level c ON a.FLevelId = c.FLevelId
				where a.CountryId = $CountryId AND MosTypeId = $MosTypeId AND a.FLevelId = $FacilityLevel $sWhere $sOrder $sLimit;";
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

        $MosTypeName = crnl2br($aRow['MosTypeName']);
        $ColorCode = mysql_real_escape_string('<span style="width:30px;height:15px;display:block;align:center;background:' . $aRow['ColorCode'] . ';"></span>');
        $MosLabel = crnl2br($aRow['MosLabel']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['MostypeDetailsId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $MosTypeName . '",';
        $sOutput .= '"' . number_format($aRow['MinMos'], 1) . '",';
        $sOutput .= '"' . number_format($aRow['MaxMos'], 1) . '",';
        $sOutput .= '"' . $ColorCode . '",';
        $sOutput .= '"' . $aRow['IconMos'] . '",';
        $sOutput .= '"' . $aRow['IconMos_Width'] . '",';
        $sOutput .= '"' . $aRow['IconMos_Height'] . '",';
        $sOutput .= '"' . $MosLabel . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['ColorCode'] . '",';
        $sOutput .= '"' . $aRow['CountryId'] . '",';
        $sOutput .= '"' . $aRow['FLevelId'] . '",';
        $sOutput .= '"' . $aRow['MosTypeId'] . '"';
        $sOutput .= "]";
    }

    $sOutput .= ']}';
    echo $sOutput;
}

function fnColumnToField_MOSTypeFacilityDetails($i) {
    if ($i == 1)
        return "MosTypeId";
}

function insertUpdateMOSTypeFacilityDetailsData($conn) {
    $RecordId = $_POST['RecordId1'];
    $MosTypeId = $_POST['MosTypeId'];
    $CountryId = $_POST['CountryId'];
    $FLevelId = $_POST['FLevelId'];
    $MosTypeName = str_replace("'", "''", $_POST['MosTypeName1']);  //$_POST['MosTypeName1'];
    $MinMos = $_POST['MinMos1'];
    $MaxMos = $_POST['MaxMos1'];
    $ColorCode = str_replace("'", "''", $_POST['ColorCode1']);  //$_POST['ColorCode1'];
    $IconMos = str_replace("'", "''", $_POST['IconMos1']);  //$_POST['IconMos1'];
    $IconMos_Width = $_POST['IconMos_Width1'];
    $IconMos_Height = $_POST['IconMos_Height1'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_mostype_facility_details(MosTypeId,FLevelId,MosTypeName, MinMos, MaxMos, ColorCode, IconMos, IconMos_Width, IconMos_Height,CountryId)
			 VALUES ('" . $MosTypeId . "', '" . $FLevelId . "', '" . $MosTypeName . "', '" . $MinMos . "', '" . $MaxMos . "', '" . $ColorCode . "', '" . $IconMos . "', '" . $IconMos_Width . "', '" . $IconMos_Height . "', '" . $CountryId . "')";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_mostype_facility_details', 'pks' => array('MostypeDetailsId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE t_mostype_facility_details SET 
                 MosTypeName = '" . $MosTypeName . "',
				 CountryId = '" . $CountryId . "',
				 FLevelId = '" . $FLevelId . "',
				 MinMos = '" . $MinMos . "',
				 MaxMos = '" . $MaxMos . "',
				 ColorCode = '" . $ColorCode . "',			 
				 IconMos = '" . $IconMos . "',
				 IconMos_Width = '" . $IconMos_Width . "',
				 IconMos_Height = '" . $IconMos_Height . "'				 
                 WHERE MostypeDetailsId = '" . $RecordId . "'";

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_mostype_facility_details', 'pks' => array('MostypeDetailsId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteMOSTypeFacilityDetailsData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_mostype_facility_details WHERE MostypeDetailsId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_mostype_facility_details', 'pks' => array('MostypeDetailsId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

//***************************************************************District Entry***************************************************//
function getDistrictData($conn) {
    //mysql_query('SET CHARACTER SET utf8');
    global $gTEXT;

    //$data = array();
    $CountryId = $_POST['CountryId'];
    $ARegionId = $_POST['ARegionId'];

    if ($ARegionId) {
        $ARegionId = " AND a.RegionId = '" . $ARegionId . "' ";
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_District(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (DistrictName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS DistrictId,DistrictName, a.CountryId, a.RegionId
				FROM t_districts a
                INNER JOIN t_region b ON a.RegionId = b.RegionId
                AND (a.CountryId = " . $CountryId . " OR " . $CountryId . " = 0) " . $ARegionId . "
                $sWhere
                $sOrder
                $sLimit";
    //  echo $sql;
//AND a.CountryId = ".$CountryId." OR ".$CountryId." = 0)INNER JOIN t_country c ON a.CountryId = c.CountryId ORDER BY DistrictName asc
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

        $DistrictName = crnl2br($aRow['DistrictName']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['DistrictId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $DistrictName . '",'; // $aRow['MosTypeName'] . '",';
        $sOutput .= '"' . $aRow['CountryId'] . '",';
        $sOutput .= '"' . $aRow['RegionId'] . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_District($i) {
    if ($i == 2)
        return "DistrictName";
}

function insertUpdateDistrictData($conn) {

    $RecordId = $_POST['RecordId'];
    $DistrictName = str_replace("'", "''", $_POST['DistrictName']);  //$_POST['DistrictName'];
    $CountryId = $_POST['CountryId'];
    $RegionId = $_POST['RegionId'];
    //$ServiceAreaNameFrench = $_POST['ServiceAreaNameFrench'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_districts (CountryId, RegionId, DistrictName)
                 VALUES ('" . $CountryId . "', '" . $RegionId . "', '" . $DistrictName . "')"; //echo $sql;

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_districts', 'pks' => array('DistrictId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_districts SET 
                 CountryId = '" . $CountryId . "',
                 RegionId = '" . $RegionId . "',
                 DistrictName = '" . $DistrictName . "'
                 WHERE DistrictId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_districts', 'pks' => array('DistrictId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteDistrictData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_districts WHERE DistrictId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_districts', 'pks' => array('DistrictId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

//***************************************************************Regimen_Master Entry***************************************************//
function getRegimenMasterData($conn) {
    //mysql_query('SET CHARACTER SET utf8');
    global $gTEXT;

     $lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }


    //$data = array();                                            
    $ItemGroupId = $_POST['ItemGroupId'];
    $AGenderTypeId = $_POST['AGenderTypeId'];

    $sWhere = "";
    $condition = '';
    if ($AGenderTypeId) {
        $sWhere = ' WHERE ';
        $condition.=" a.GenderTypeId = '" . $AGenderTypeId . "' ";
    }

    if ($ItemGroupId) {

        if ($sWhere == '')
            $sWhere = " WHERE ";
        else
            $condition.=" and ";
        $condition.="  a.ItemGroupId = '" . $ItemGroupId . "' ";
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_getRegimenMasterData(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    if ($_POST['sSearch'] != "") {
        if ($sWhere == '')
            $sWhere = " WHERE ";
        else
            $condition.=" and ";

        //$condition.= "   ($GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
        $condition.= "   (RegimenName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
                            OR GenderType LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                            OR $GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
        //$condition.= "   (GenderType LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') "; 
    }

    $sql = "SELECT  RegMasterId,RegimenName, a.GenderTypeId,GenderType,a.ItemGroupId,$GroupName GroupName,STL_Color
				FROM t_regimen_master a INNER JOIN  t_itemgroup b ON a.ItemGroupId=b.ItemGroupId
				INNER JOIN t_gendertype c ON a.GenderTypeId = c.GenderTypeId
				 $sWhere  " . $condition . "							
                $sOrder
                $sLimit";

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

        $GroupName = crnl2br($aRow['GroupName']);
        $RegimenName = crnl2br($aRow['RegimenName']);
        $GenderType = crnl2br($aRow['GenderType']);
        $ColorCode = mysql_real_escape_string('<span style="width:30px;height:15px;display:block;align:center;background:' . $aRow['STL_Color'] . ';"></span>');

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['RegMasterId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $GroupName . '",';
        $sOutput .= '"' . $RegimenName . '",';
        $sOutput .= '"' . $GenderType . '",';
        $sOutput .= '"' . $ColorCode . '",';
        $sOutput .= '"' . $aRow['ItemGroupId'] . '",';
        $sOutput .= '"' . $aRow['GenderTypeId'] . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['STL_Color'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_getRegimenMasterData($i) {
    if ($i == 2)
        return "GroupName";
    if ($i == 3)
        return "RegimenName";
    if ($i == 4)
        return "GenderType";
}

function insertUpdateRegimenMasterData($conn) {

    $RecordId = $_POST['RegMasterId'];
    $RegimenName = str_replace("'", "''", $_POST['RegimenMasterName']);  //$_POST['RegimenMasterName'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $GenderTypeId = str_replace("'", "''", $_POST['GenderTypeId']);  //$_POST['GenderTypeId'];
    $ColorCode = str_replace("'", "''", $_POST['ColorCode']);  //$_POST['ColorCode'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_regimen_master (ItemGroupId, GenderTypeId, RegimenName,STL_Color )
                 VALUES ('" . $ItemGroupId . "', '" . $GenderTypeId . "', '" . $RegimenName . "', '" . $ColorCode . "')"; //echo $sql; 
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_regimen_master', 'pks' => array('RegMasterId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_regimen_master SET 
                 ItemGroupId = '" . $ItemGroupId . "',
                 GenderTypeId = '" . $GenderTypeId . "',
                 RegimenName = '" . $RegimenName . "',
                 STL_Color = '" . $ColorCode . "'
                 WHERE RegMasterId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_regimen_master', 'pks' => array('RegMasterId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteRegimenMasterData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_regimen_master WHERE RegMasterId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_regimen_master', 'pks' => array('RegMasterId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Owner TypeTable***************************************************** */

function getOwnerTypeData($conn) {

    // mysql_query('SET CHARACTER SET utf8');
    global $gTEXT;

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Owner_Type(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (OwnerTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR " . " OwnerTypeNameFrench LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT  SQL_CALC_FOUND_ROWS OwnerTypeId, OwnerTypeName, OwnerTypeNameFrench
				FROM t_owner_type
                $sWhere
                $sOrder
                $sLimit";

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

        $OwnerTypeName = crnl2br($aRow['OwnerTypeName']);
        $OwnerTypeNameFrench = crnl2br($aRow['OwnerTypeNameFrench']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['OwnerTypeId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $OwnerTypeName . '",';
        $sOutput .= '"' . $OwnerTypeNameFrench . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Owner_Type($i) {
    if ($i == 2)
        return "OwnerTypeName";
    if ($i == 3)
        return "OwnerTypeNameFrench";
}

function insertUpdateOwnerTypeData($conn) {

    $RecordId = $_POST['RecordId'];

    $OwnerTypeName = str_replace("'", "''", $_POST['OwnerTypeName']);  //$_POST['OwnerTypeName'];
    $OwnerTypeNameFrench = str_replace("'", "''", $_POST['OwnerTypeNameFrench']);  //$_POST['OwnerTypeNameFrench'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_owner_type(OwnerTypeName, OwnerTypeNameFrench)
                 VALUES ('" . $OwnerTypeName . "', '" . $OwnerTypeNameFrench . "')";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_owner_type', 'pks' => array('OwnerTypeId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_owner_type SET 
                 OwnerTypeName = '" . $OwnerTypeName . "',
                 OwnerTypeNameFrench = '" . $OwnerTypeNameFrench . "'
                 WHERE OwnerTypeId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_owner_type', 'pks' => array('OwnerTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteOwnerTypeData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_owner_type WHERE OwnerTypeId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_owner_type', 'pks' => array('OwnerTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Service Area Table***************************************************** */

function getServiceAreaData($conn) {

    //mysql_query('SET CHARACTER SET utf8');
    global $gTEXT;

    $data = array();

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_Service_Area(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (ServiceAreaName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR " . " ServiceAreaNameFrench LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT  SQL_CALC_FOUND_ROWS ServiceAreaId, ServiceAreaName, ServiceAreaNameFrench
				FROM t_service_area
                $sWhere
                $sOrder
                $sLimit";

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

        $ServiceAreaName = crnl2br($aRow['ServiceAreaName']);
        $ServiceAreaNameFrench = crnl2br($aRow['ServiceAreaNameFrench']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['ServiceAreaId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $ServiceAreaName . '",'; // $aRow['MosTypeName'] . '",';
        $sOutput .= '"' . $ServiceAreaNameFrench . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_Service_Area($i) {
    if ($i == 2)
        return "ServiceAreaName";
    if ($i == 3)
        return "ServiceAreaNameFrench";
}

function insertUpdateServiceAreaData($conn) {

    $RecordId = $_POST['RecordId'];
    $ServiceAreaName = str_replace("'", "''", $_POST['ServiceAreaName']);  //$_POST['ServiceAreaName'];
    $ServiceAreaNameFrench = str_replace("'", "''", $_POST['ServiceAreaNameFrench']);  //$_POST['ServiceAreaNameFrench'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_service_area(ServiceAreaName,ServiceAreaNameFrench)
                 VALUES ('" . $ServiceAreaName . "', '" . $ServiceAreaNameFrench . "')";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_service_area', 'pks' => array('ServiceAreaId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                t_service_area SET 
                 ServiceAreaName = '" . $ServiceAreaName . "',
                 ServiceAreaNameFrench = '" . $ServiceAreaNameFrench . "'
                 WHERE ServiceAreaId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_service_area', 'pks' => array('ServiceAreaId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteServiceAreaData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_service_area WHERE ServiceAreaId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_service_area', 'pks' => array('ServiceAreaId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ****************************************************Report By Table***************************************************** */

function getReportByData($conn) {

    // mysql_query('SET CHARACTER SET utf8');
    global $gTEXT;

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_reportby(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE  (OwnerTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR " . " OwnerTypeNameFrench LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT  SQL_CALC_FOUND_ROWS OwnerTypeId, OwnerTypeName, OwnerTypeNameFrench
				FROM t_reportby
                $sWhere
                $sOrder
                $sLimit";

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

        $OwnerTypeName = crnl2br($aRow['OwnerTypeName']);
        $OwnerTypeNameFrench = crnl2br($aRow['OwnerTypeNameFrench']);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['OwnerTypeId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $OwnerTypeName . '",';
        $sOutput .= '"' . $OwnerTypeNameFrench . '",';
        $sOutput .= '"' . $y . $z . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_reportby($i) {
    if ($i == 0)
        return "OwnerTypeId";
    if ($i == 2)
        return "OwnerTypeName";
    if ($i == 3)
        return "OwnerTypeNameFrench";
}

function insertUpdateReportByData($conn) {

    $RecordId = $_POST['RecordId'];

    $OwnerTypeName = str_replace("'", "''", $_POST['OwnerTypeName']);  //$_POST['OwnerTypeName'];
    $OwnerTypeNameFrench = str_replace("'", "''", $_POST['OwnerTypeNameFrench']); //$_POST['OwnerTypeNameFrench'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_reportby(OwnerTypeName, OwnerTypeNameFrench)
                 VALUES ('" . $OwnerTypeName . "', '" . $OwnerTypeNameFrench . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_reportby', 'pks' => array('OwnerTypeId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE 
                 t_reportby SET 
                 OwnerTypeName = '" . $OwnerTypeName . "',
                 OwnerTypeNameFrench = '" . $OwnerTypeNameFrench . "'
                 WHERE OwnerTypeId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_reportby', 'pks' => array('OwnerTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteReportByData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_reportby WHERE OwnerTypeId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_reportby', 'pks' => array('OwnerTypeId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

?>