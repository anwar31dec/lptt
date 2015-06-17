<?php

include_once ('database_conn.php');
include_once ("function_lib.php");

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {
    case 'getCountryList':
        getCountryList();
        break;
    case 'getRegimenList':
        getRegimenList();
        break;
    case 'insertAllorOneMapping':
        insertAllorOneMapping();
        break;
    default :
        echo "{failure:true}";
        break;
}
/* * ***************************************************lab user authentication***************************************** */

function getCountryList() {

    $lan = $_POST['lan'];
    if ($lan == 'en-GB') {
        $CountryName = 'CountryName';
    } else {
        $CountryName = 'CountryNameFrench';
    }


    mysql_query('SET CHARACTER SET utf8');
    $userName = $_POST['userName'];

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " and ($CountryName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%')";
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToGetUsers(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sql = "SELECT a.CountryId, CountryCode, $CountryName CountryName 	
            FROM t_country a	
            INNER JOIN t_user_country_map b ON a.CountryId = b.CountryId
            WHERE b.UserId = '" . $userName . "' 
            " . $sWhere . " " . $sOrder . " " . $sLimit . " ";
    // echo $sql;
    $pacrs = mysql_query($sql);
    $sql = "SELECT FOUND_ROWS()";
    $rs = mysql_query($sql);
    $r = mysql_fetch_array($rs);
    $total = $r[0];
    echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
    $f = 0;
    $serial = $_POST['iDisplayStart'] + 1;

    while ($row = @mysql_fetch_object($pacrs)) {
        $CountryId = $row->CountryId;
        $CountryName = $row->CountryName;

        if ($f++)
            echo ",";

        echo '["' . $CountryId . '", "' . $serial . '", "' . $CountryName . '"]';
        $serial++;
    }
    echo ']}';
}

function fnColumnToGetUsers($i) {
    if ($i == 2)
        return "CountryName ";
}

function getcheckBox($v) {
    if ($v == "true") {
        $x = "<input type='checkbox' checked class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    } else {
        $x = "<input type='checkbox' class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    }
    return $x;
}

function getRegimenList() {

    $lan = $_POST['lan'];
    if ($lan == 'en-GB') {
        $FormulationName = 'FormulationName';
    } else {
        $FormulationName = 'FormulationNameFrench';
    }

    //$refimenName= trim(preg_replace('/\s+/', ' ', addslashes(RegimenName)));
    mysql_query('SET CHARACTER SET utf8');
    $CountryId = $_POST['SelCountryId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $mode = $_POST['mode'];

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE (
                $FormulationName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' or 
                 RegimenName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
                    
                )";
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    if ($mode == 'edit') {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.CountryRegimenId, a.CountryId, b.RegimenId, IF(a.CountryRegimenId is Null,'false','true') chkValue, RegimenName,$FormulationName FormulationName  	 	
                 FROM  t_country_regimen a 
                 RIGHT JOIN t_regimen b ON (a.RegimenId = b.RegimenId AND a.CountryId = '" . $CountryId . "')
                 INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId AND c.ItemGroupId = '" . $ItemGroupId . "' 
                 " . $sWhere . " ORDER BY FormulationName, RegimenName " . $sLimit . "";
    } else {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.CountryRegimenId, a.CountryId, b.RegimenId, IF(a.CountryRegimenId is Null,'false','true') chkValue, RegimenName,$FormulationName FormulationName
                 FROM  t_country_regimen a 
                 INNER JOIN t_regimen b ON (a.RegimenId = b.RegimenId AND a.CountryId = '" . $CountryId . "')
                 INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId AND c.ItemGroupId = '" . $ItemGroupId . "' 
                 " . $sWhere . " ORDER BY FormulationName, RegimenName " . $sLimit . "";
    }

    // echo $sql;

    $pacrs = mysql_query($sql);
    $sql = "SELECT FOUND_ROWS()";
    $rs = mysql_query($sql);
    $r = mysql_fetch_array($rs);
    $total = $r[0];
    echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
    $f = 0;
    $serial = $_POST['iDisplayStart'] + 1;

    while ($row = @mysql_fetch_object($pacrs)) {
        $CountryRegimenId = $row->CountryRegimenId;
        $CountryId = $row->CountryId;
        $RegimenId = $row->RegimenId;
        $RegimenName = trim(preg_replace('/\s+/', ' ', addslashes($row->RegimenName)));
        $FormulationName = trim(preg_replace('/\s+/', ' ', addslashes($row->FormulationName)));
        $chkValue = $row->chkValue;


        if ($f++)
            echo ",";

        echo '["' . $CountryRegimenId . '", "' . getcheckBox($chkValue) . " " . $RegimenName . '", "' . $FormulationName . '", "' . $RegimenId . '"]';

        $serial++;
    }
    echo ']}';
}

function insertAllorOneMapping() {

    $CountryRegimenId = $_POST['CountryRegimenId'];
    $CountryId = $_POST['SelCountryId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $RegimenId = $_POST['RegimenId'];
    $checkVal = $_POST['checkVal'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($checkVal == "true") {
        $sql = "INSERT INTO  t_country_regimen (CountryId, RegimenId, ItemGroupId) 
                VALUES ('" . $CountryId . "', '" . $RegimenId . "', '" . $ItemGroupId . "') ";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_country_regimen', 'pks' => array('CountryRegimenId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
        
          echo json_encode(exec_query($aQuerys, $jUserId, $language, TRUE, FALSE, TRUE));
    } else {
        $sql = "DELETE FROM  t_country_regimen WHERE CountryRegimenId = '" . $CountryRegimenId . "' ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_country_regimen', 'pks' => array('CountryRegimenId'), 'pk_values' => array($CountryRegimenId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
        
          echo json_encode(exec_query($aQuerys, $jUserId, $language));
    }
  
}

?>