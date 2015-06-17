<?php

include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

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
    case "getRegimenData" :
        getRegimenData($conn);
        break;
    case "insertUpdateRegimenData" :
        insertUpdateRegimenData($conn);
        break;
    case "deleteRegimenData" :
        deleteRegimenData($conn);
        break;
    case "getOptionNumber" :
        getOptionNumber($conn);
        break;
    case "getRegimenItemListData" :
        getRegimenItemListData($conn);
        break;
    case "getDefaultRegimenItemListData" :
        getDefaultRegimenItemListData($conn);
        break;
    case "getSelectedRegimenItemListData" :
        getSelectedRegimenItemListData($conn);
        break;
    case "saveRegimenItemList" :
        saveRegimenItemList($conn);
        break;
    case "deleteRegimenItemList" :
        deleteRegimenItemList($conn);
        break;
    case "getCombinationData" :
        getCombinationData($conn);
        break;
    case "updateCombinationPer" :
        updateCombinationPer($conn);
        break;
    default :
        echo "{failure:true}";
        break;
}

function getRegimenData($conn) {

    global $gTEXT;

    $lan = $_POST['lan'];
    if ($lan == 'en-GB') {
        $FormulationName = 'FormulationName';
    } else {
        $FormulationName = 'FormulationNameFrench';
    }

    $ItemGroupId = $_POST['ItemGroupId'];

    if ($ItemGroupId) {
        $ItemGroupId = " WHERE b.ItemGroupId = '" . $ItemGroupId . "' ";
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    $sOrder = " ORDER BY  a.FormulationId asc, ";
    if (isset($_POST['iSortCol_0'])) {
        //$sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_regimen(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND ($FormulationName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
                  OR RegimenName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'   
            ) ";
        }

    $sql = "SELECT SQL_CALC_FOUND_ROWS RegimenId, RegimenName, b.FormulationId,$FormulationName FormulationName,a.RegMasterId,a.GenderTypeId, c.GenderType
			FROM t_regimen a
            INNER JOIN t_formulation b ON a.FormulationId = b.FormulationId 
            INNER JOIN t_gendertype c ON a.GenderTypeId = c.GenderTypeId
			" . $ItemGroupId . "
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

        $RegimenName = crnl2br($aRow['RegimenName']);
        $FormulationName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['FormulationName'])));

        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . addslashes($aRow['RegimenId']) . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $RegimenName . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $FormulationName . '",';
        $sOutput .= '"' . addslashes($aRow['FormulationId']) . '",';
        $sOutput .= '"' . addslashes($aRow['RegMasterId']) . '",';
        $sOutput .= '"' . addslashes($aRow['GenderTypeId']) . '",';
        $sOutput .= '"' . addslashes($aRow['GenderType']) . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_regimen($i) {
    if ($i == 2)
        return "RegimenName ";
    else if ($i == 4)
        return "FormulationName ";
}

function insertUpdateRegimenData($conn) {
    $RegimenName = str_replace("'", "''", $_POST['RegimenName']);  //$_POST['RegimenName']; //echo $RegimenName;
    //$RegimenId = $_POST['RegimenId'];
    $RegimenId = isset($_POST['RegimenId'])? $_POST['RegimenId'] : '';
    $RegMasterId = $_POST['regimenMaster-list'];
    $FormulationId = $_POST['FormulationId'];
    $GenderTypeId = str_replace("'", "''", $_POST['GenderTypeId']); //$_POST['GenderTypeId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RegimenId == '') {
        $sql = "INSERT INTO t_regimen( RegMasterId, FormulationId,RegimenName,GenderTypeId)
                 VALUES ('" . $RegMasterId . "', '" . $FormulationId . "', CONCAT('" . $RegimenName . "','" . $GenderTypeId . "'), '" . $GenderTypeId . "')";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_regimen', 'pks' => array('RegimenId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE
                 t_regimen SET
                 RegMasterId = '" . $RegMasterId . "',
				 RegimenName = CONCAT('" . $RegimenName . "', '" . $GenderTypeId . "'),
                 GenderTypeId = '" . $GenderTypeId . "',
                 FormulationId = '" . $FormulationId . "'
                 WHERE RegimenId = " . $RegimenId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_regimen', 'pks' => array('RegimenId'), 'pk_values' => array($RegimenId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
//echo $sql;
}

function deleteRegimenData($conn) {

    $RegimenId = $_POST['RegimenId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RegimenId != '') {

        $sql = " DELETE FROM t_regimen WHERE RegimenId = " . $RegimenId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_regimen', 'pks' => array('RegimenId'), 'pk_values' => array($RegimenId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function getRegimenItemListData($conn) {

    global $gTEXT;

    $RegimenId = $_POST['RegimenId'];
    $CountryId = $_POST['Country'];

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_regimen_item_list(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND ItemName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' ";
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS a.RegimenItemId, a.RegimenId, a.ItemNo, a.OptionId, a.PatientPercentage, b.RegimenName, ItemName, c.ShortName
			FROM t_regimenitems a 
            INNER JOIN t_regimen b ON a.RegimenId = b.RegimenId
            INNER JOIN t_itemlist c ON a.ItemNo = c.ItemNo 
            INNER JOIN t_country d ON a.CountryId = d.CountryId 
            WHERE a.RegimenId = '" . $RegimenId . "'
            AND a.CountryId = '" . $CountryId . "' $sWhere $sOrder $sLimit ";

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

        $ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . addslashes($aRow['RegimenItemId']) . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $ItemName . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . addslashes($aRow['OptionId']) . '",';
        $sOutput .= '"' . addslashes($aRow['RegimenName']) . '",';
        $sOutput .= '"' . addslashes($aRow['OptionId']) . " - (" . $aRow['PatientPercentage'] . "%)" . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_regimen_item_list($i) {
    if ($i == 2)
        return "ItemName ";
    else if ($i == 4)
        return "OptionId ";
}

function getOptionNumber($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $RegimenId = $_POST['RegimenId'];
    $CountryId = $_POST['Country'];

    $query = "SELECT DISTINCT MAX(OptionId) S FROM t_regimenitems WHERE RegimenId='" . $RegimenId . "'  AND CountryId = '" . $CountryId . "' ";
    $qr = mysql_query($query, $conn);
    $r = mysql_fetch_object($qr);
    $OptionId = $r->S;

    if ($qr) {
        echo '{"success":true, "OptionId": "' . $OptionId . '"}';
    } else {
        echo '{"success":false, "Error": "Invalid query: ' . mysql_error() . '"}';
    }
}

function getDefaultRegimenItemListData($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $RegimenId = $_GET['RegimenId'];
    $ItemGroupId = $_GET['ItemGroupId'];
    $CountryId = $_GET['Country'];

    $sql = " SELECT a.ItemNo, ItemCode, ItemName
             FROM t_itemlist a 
             INNER JOIN t_country_product b ON a.ItemNo = b.ItemNo AND b.CountryId = " . $CountryId . "
             WHERE a.ItemGroupId = " . $ItemGroupId . " ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr);
}

function getSelectedRegimenItemListData($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $RegimenId = $_GET['RegimenId'];
    $OptionId = $_GET['OptionId'];
    $CountryId = $_GET['Country'];

    $sql = "SELECT a.RegimenItemId, a.RegimenId, a.ItemNo, a.OptionId, b.RegimenName, ItemName, c.ShortName
			FROM t_regimenitems as a, t_regimen as b, t_itemlist as c
			WHERE a.RegimenId = b.RegimenId 
            AND a.ItemNo = c.ItemNo 
            AND a.RegimenId = '" . $RegimenId . "' 
            AND a.OptionId = '" . $OptionId . "' 
            AND a.CountryId = '" . $CountryId . "' ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr);
}

function saveRegimenItemList($conn) {

    $RegimenItemListItem = json_decode($_POST['RegimenItemList'], TRUE);
    $RegimenId = $_POST['RegimenId'];
    $OptionId = $_POST['OptionId'];
    if ($OptionId == 1) {
        $PatientPercentage = 100;
    }
    else
        $PatientPercentage = 0;
    $CountryId = $_POST['Country'];
    $Mode = $_POST['Mode'];

    if ($Mode == 'add') {

        $dataItemInsert = array();
        $dataItemNo = array();

        foreach ($RegimenItemListItem as $key => $value) {
            $Id = $value['Id'];
            $ItemNo = $value['ItemNo'];

            if ($Id === 0) {
                array_push($dataItemInsert, $ItemNo);
            } else {
                array_push($dataItemNo, $ItemNo);
            }
            $dataItemInsert = array_diff($dataItemInsert, $dataItemNo);
        }
        foreach ($dataItemInsert as $key => $valueinsert) {

            $sql = "SELECT MAX(RegimenItemId) as M FROM t_regimenitems ";
            $qr = mysql_query($sql);
            $r = mysql_fetch_object($qr);
            $RegimenItemId = $r->M;
            $RegimenItemId++;

            $sql = mysql_query('INSERT INTO t_regimenitems(RegimenItemId, RegimenId, ItemNo, OptionId, CountryId, PatientPercentage)
	                            VALUES ("' . $RegimenItemId . '", "' . $RegimenId . '", "' . $valueinsert . '", "' . $OptionId . '", "' . $CountryId . '", "' . $PatientPercentage . '")');
        }
    } else {
        $dataItemInsert = array();
        $dataItemNo = array();

        foreach ($RegimenItemListItem as $key => $value) {
            $Id = $value['Id'];
            $ItemNo = $value['ItemNo'];

            if ($Id === 0) {
                array_push($dataItemInsert, $ItemNo);
            } else {
                array_push($dataItemNo, $ItemNo);
            }
        }
        foreach ($dataItemNo as $key => $valuedelete) {
            $sql = mysql_query(" DELETE FROM t_regimenitems WHERE ItemNo = " . $valuedelete . " AND RegimenId = " . $RegimenId . "  AND OptionId = " . $OptionId . " AND CountryId = " . $CountryId . " ");
        }
        foreach ($dataItemInsert as $key => $valueinsert) {

            $sql = "SELECT MAX(RegimenItemId) as M FROM t_regimenitems ";
            $qr = mysql_query($sql);
            $r = mysql_fetch_object($qr);
            $RegimenItemId = $r->M;
            $RegimenItemId++;

            $sql = mysql_query('INSERT INTO t_regimenitems(RegimenItemId, RegimenId, ItemNo, OptionId, CountryId, PatientPercentage)
	                            VALUES ("' . $RegimenItemId . '", "' . $RegimenId . '", "' . $valueinsert . '", "' . $OptionId . '", "' . $CountryId . '", "' . $PatientPercentage . '")');
        }
    }
    if ($sql)
        $error = 1;
    else
        $error = 0;
    echo $error;
}

function deleteRegimenItemList($conn) {

    $RegimenItemId = $_POST['RegimenItemId'];

    if ($RegimenItemId != '') {

        $sql = " DELETE FROM t_regimenitems 
                 WHERE RegimenItemId = " . $RegimenItemId . " ";

        if (mysql_query($sql)) {
            $error = 1;
        }
        else
            $error = 0;
        echo $error;
    }
}

function getTextBox($v) {
    $r = $v;
    $r = ($r == '') ? '' : $r;
    $x = "<input type='text' class='datacell' value='" . $r . "'/>";
    return $x;
}

function getCombinationData($conn) {

    $RegimenId = $_POST['RegimenId'];
    $CountryId = $_POST['Country'];

    $sql = "SELECT DISTINCT OptionId, PatientPercentage FROM t_regimenitems WHERE RegimenId='" . $RegimenId . "'  AND CountryId = '" . $CountryId . "' ";

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

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {
        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . "<span class='ComName'>Combination" . "-" . $aRow['OptionId'] . "</span>" . '",';
        $sOutput .= '"' . getTextBox($aRow['PatientPercentage']) . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function updateCombinationPer($conn) {

    $ComOptionId = json_decode($_POST['ComOptionId'], TRUE);
    $ComPercentage = json_decode($_POST['ComPercentage'], TRUE);
    $RegimenId = $_POST['RegimenId'];
    $CountryId = $_POST['Country'];

    for ($i = 0; $i < count($ComOptionId); $i++) {

        $sql = ' UPDATE
                 t_regimenitems SET
                 PatientPercentage = ' . $ComPercentage[$i] . '
                 WHERE RegimenId = ' . $RegimenId . '
                 AND CountryId = ' . $CountryId . '
                 AND OptionId = ' . $ComOptionId[$i] . ' ';
        mysql_query($sql);
    }

    if ($sql)
        $error = 1;
    else
        $error = 0;
    echo $error;
}

?>