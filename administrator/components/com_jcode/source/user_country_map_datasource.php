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
    case 'getJoomlaLabUsers':
        getJoomlaLabUsers();
        break;
    case 'getCountryList':
        getCountryList();
        break;
    case 'insertAllorOneMapping':
        insertAllorOneMapping();
        break;
    case 'getItemGroupList':
        getItemGroupList();
        break;
    case 'insertAllorOneMappingItemGroup' :
        insertAllorOneMappingItemGroup();
        break;
    case 'getOwnerTypeList':
        getOwnerTypeList();
        break;
    case 'insertAllorOneMappingOwner' :
        insertAllorOneMappingOwner();
        break;
    case 'getRegionList':
        getRegionList();
        break;
    case 'insertAllorOneMappingRegion' :
        insertAllorOneMappingRegion();
        break;
    default :
        echo "{failure:true}";
        break;
}

/* * ***************************************************lab user authentication***************************************** */

function getJoomlaLabUsers() {

    mysql_query('SET CHARACTER SET utf8');

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND (name like '%" . mysql_real_escape_string($_POST['sSearch']) . "%')";
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

    /* $sql = " SELECT SQL_CALC_FOUND_ROWS a.id, name, username, title
      FROM j323_users a
      INNER JOIN j323_user_usergroup_map b ON a.id = b.user_id
      INNER JOIN j323_usergroups c ON b.group_id = c.id
      WHERE b.group_id IN(3, 10, 11, 12, 13, 14, 15)
      ".$sWhere." ".$sOrder." ".$sLimit."";
     */
    $sql = "SELECT SQL_CALC_FOUND_ROWS a.id, name, username, GROUP_CONCAT(title SEPARATOR ', ') title
             FROM ykx9st_users a
             INNER JOIN ykx9st_user_usergroup_map b ON a.id = b.user_id 
             INNER JOIN ykx9st_usergroups c ON b.group_id = c.id           
             WHERE b.group_id IN(3, 10, 11, 12, 13, 14, 15)  " . $sWhere . " GROUP BY a.id, name, username
			 " . $sOrder . " " . $sLimit . "";

    $pacrs = mysql_query($sql);
    $sql = "SELECT FOUND_ROWS()";
    $rs = mysql_query($sql);
    $r = mysql_fetch_array($rs);
    $total = $r[0];
    echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
    $f = 0;
    $serial = $_POST['iDisplayStart'] + 1;

    while ($row = @mysql_fetch_object($pacrs)) {
        $id = $row->id;
        $name = $row->name;
        $username = $row->username;
        $title = $row->title;
        if ($f++)
            echo ",";

        echo '["' . $id . '", "' . $serial . '", "' . $name . '", "' . $username . '", "' . $title . '"]';
        $serial++;
    }
    echo ']}';
}

function fnColumnToGetUsers($i) {
    if ($i == 2)
        return "name ";
    else if ($i == 4)
        return "title ";
}

function getcheckBox($v) {
    if ($v == "true") {
        $x = "<input type='checkbox' checked class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    } else {
        $x = "<input type='checkbox' class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    }
    return $x;
}

function getCountryList() {

     $lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $CountryName = 'CountryName';
        }else{
            $CountryName = 'CountryNameFrench';
        }


    mysql_query('SET CHARACTER SET utf8');
    $username = $_POST['userName'];
    $mode = $_POST['mode'];

    $sWhere = "";
   /* if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE (CountryName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";
    }*/

    $sLimit = "";
    // if (isset($_POST['iDisplayStart'])) { 
    // $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    // }

    if ($mode == 'edit') {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.MapId, a.UserId, b.CountryId, IF(a.MapId is Null,'false','true') chkValue, $CountryName CountryName
                 FROM t_user_country_map a 
                 RIGHT JOIN t_country b ON (a.CountryId = b.CountryId AND a.UserId = '" . $username . "')
                 ORDER BY $CountryName " . $sLimit . "";
    } else {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.MapId, a.UserId, b.CountryId, IF(a.MapId is Null,'false','true') chkValue, $CountryName CountryName
                 FROM t_user_country_map a 
                 INNER JOIN t_country b ON (a.CountryId = b.CountryId AND a.UserId = '" . $username . "')
                 ORDER BY $CountryName " . $sLimit . "";
    }

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
        $chkValue = $row->chkValue;
        $MapId = $row->MapId;

        if ($f++)
            echo ",";

        echo '["' . $MapId . '", "' . getcheckBox($chkValue) . " " . $CountryName . '", "' . $CountryId . '"]';

        $serial++;
    }
    echo ']}';
}

function insertAllorOneMapping() {

    $MapId = $_POST['MapId'];
    $CountryId = $_POST['CountryId'];
    $userName = str_replace("'", "''", $_POST['userName']);  //$_POST['userName'];
    $checkVal = $_POST['checkVal'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($checkVal == "true") {
        $sql = "INSERT INTO t_user_country_map (UserId, CountryId) 
                VALUES ('" . $userName . "', '" . $CountryId . "') ";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_user_country_map', 'pks' => array('MapId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "DELETE FROM t_user_country_map WHERE MapId = '" . $MapId . "' ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_user_country_map', 'pks' => array('MapId'), 'pk_values' => array($MapId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function getItemGroupList() {
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
        }else{
            $GroupName = 'GroupNameFrench';
        }

    mysql_query('SET CHARACTER SET utf8');
    $username = $_POST['userName'];
    $mode = $_POST['mode'];

    /*$sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE ($GroupName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";
    }*/

    $sLimit = "";
    // if (isset($_POST['iDisplayStart'])) { 
    // $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    // }

    if ($mode == 'edit') {
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.ItemGroupMapId, a.UserId, b.ItemGroupId, IF(a.ItemGroupMapId is Null,'false','true') chkValue,$GroupName GroupName
             FROM t_user_itemgroup_map a 
             RIGHT JOIN t_itemgroup b ON (a.ItemGroupId = b.ItemGroupId AND a.UserId = '" . $username . "')
             ORDER BY $GroupName " . $sLimit . "";
    } else {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.ItemGroupMapId, a.UserId, b.ItemGroupId, IF(a.ItemGroupMapId is Null,'false','true') chkValue,$GroupName GroupName
			 FROM t_user_itemgroup_map a 
			 INNER JOIN t_itemgroup b ON (a.ItemGroupId = b.ItemGroupId AND a.UserId = '" . $username . "')
			 ORDER BY $GroupName " . $sLimit . "";
    }

    $pacrs = mysql_query($sql);
    $sql = "SELECT FOUND_ROWS()";
    $rs = mysql_query($sql);
    $r = mysql_fetch_array($rs);
    $total = $r[0];
    echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
    $f = 0;
    $serial = $_POST['iDisplayStart'] + 1;

    while ($row = @mysql_fetch_object($pacrs)) {
        $ItemGroupId = $row->ItemGroupId;
        $GroupName = $row->GroupName;
        $chkValue = $row->chkValue;
        $ItemGroupMapId = $row->ItemGroupMapId;

        if ($f++)
            echo ",";

        echo '["' . $ItemGroupMapId . '", "' . getcheckBox($chkValue) . " " . $GroupName . '", "' . $ItemGroupId . '"]';

        $serial++;
    }
    echo ']}';
}

function insertAllorOneMappingItemGroup() {

    $ItemGroupMapId = $_POST['ItemGroupMapId'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $userName = str_replace("'", "''", $_POST['userName']);  //$_POST['userName'];
    $checkVal = $_POST['checkVal'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($checkVal == "true") {
        $sql = "INSERT INTO t_user_itemgroup_map (UserId, ItemGroupId) 
                VALUES ('" . $userName . "', '" . $ItemGroupId . "');";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_user_itemgroup_map', 'pks' => array('ItemGroupMapId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "DELETE FROM t_user_itemgroup_map WHERE ItemGroupMapId = '" . $ItemGroupMapId . "';";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_user_itemgroup_map', 'pks' => array('ItemGroupMapId'), 'pk_values' => array($ItemGroupMapId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function getOwnerTypeList() {
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $OwnerTypeName = 'OwnerTypeName';
        }else{
            $OwnerTypeName = 'OwnerTypeNameFrench';
        }

    mysql_query('SET CHARACTER SET utf8');
    $username = $_POST['userName'];
    $mode = $_POST['mode'];

    /*$sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE ($OwnerTypeName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";
    }*/

    $sLimit = "";
    // if (isset($_POST['iDisplayStart'])) { 
    // $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    // }

    if ($mode == 'edit') {
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.OwnerTypeMapId, a.UserId, b.OwnerTypeId, IF(a.OwnerTypeMapId is Null,'false','true') chkValue, $OwnerTypeName OwnerTypeName
             FROM t_user_owner_type_map a 
             RIGHT JOIN t_owner_type b ON (a.OwnerTypeId = b.OwnerTypeId AND a.UserId = '" . $username . "')
             ORDER BY $OwnerTypeName " . $sLimit . "";
    } else {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.OwnerTypeMapId, a.UserId, b.OwnerTypeId, IF(a.OwnerTypeMapId is Null,'false','true') chkValue,$OwnerTypeName OwnerTypeName
			 FROM t_user_owner_type_map a 
			 INNER JOIN t_owner_type b ON (a.OwnerTypeId = b.OwnerTypeId AND a.UserId = '" . $username . "')
			 ORDER BY $OwnerTypeName " . $sLimit . "";
    }

    $pacrs = mysql_query($sql);
    $sql = "SELECT FOUND_ROWS()";
    $rs = mysql_query($sql);
    $r = mysql_fetch_array($rs);
    $total = $r[0];
    echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
    $f = 0;
    $serial = $_POST['iDisplayStart'] + 1;

    while ($row = @mysql_fetch_object($pacrs)) {
        $OwnerTypeId = $row->OwnerTypeId;
        $OwnerTypeName = $row->OwnerTypeName;
        $chkValue = $row->chkValue;
        $OwnerTypeMapId = $row->OwnerTypeMapId;

        if ($f++)
            echo ",";


        echo '["' . $OwnerTypeMapId . '", "' . getcheckBox($chkValue) . " " . $OwnerTypeName . '", "' . $OwnerTypeId . '"]';

        $serial++;
    }
    echo ']}';
}

function insertAllorOneMappingOwner() {
    $OwnerTypeMapId = $_POST['OwnerTypeMapId'];
    $OwnerTypeId = $_POST['OwnerTypeId'];
    $userName = str_replace("'", "''", $_POST['userName']);  //$_POST['userName'];
    $checkVal = $_POST['checkVal'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($checkVal == "true") {
        $sql = "INSERT INTO t_user_owner_type_map(UserId, OwnerTypeId) 
                VALUES ('" . $userName . "', '" . $OwnerTypeId . "');";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_user_owner_type_map', 'pks' => array('OwnerTypeMapId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "DELETE FROM t_user_owner_type_map WHERE OwnerTypeMapId = '" . $OwnerTypeMapId . "';";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_user_owner_type_map', 'pks' => array('OwnerTypeMapId'), 'pk_values' => array($OwnerTypeMapId), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function getRegionList() {

    mysql_query('SET CHARACTER SET utf8');
    $username = $_POST['userName'];
    $mode = $_POST['mode'];

    /*$sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE (RegionName like '%" . mysql_real_escape_string($_POST['sSearch']) . "%' )";
    }*/

    $sLimit = "";
    // if (isset($_POST['iDisplayStart'])) { 
    // $sLimit = "limit " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    // }

    if ($mode == 'edit') {
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.RegionMapId, a.UserId, b.RegionId, IF(a.RegionMapId is Null,'false','true') chkValue, RegionName
             FROM t_user_region_map a 
             RIGHT JOIN t_region b ON (a.RegionId = b.RegionId AND a.UserId = '" . $username . "')
             ORDER BY RegionName " . $sLimit . "";
    } else {
        $sql = " SELECT SQL_CALC_FOUND_ROWS a.RegionMapId, a.UserId, b.RegionId, IF(a.RegionMapId is Null,'false','true') chkValue, RegionName
			 FROM t_user_region_map a 
			 INNER JOIN t_region b ON (a.RegionId = b.RegionId AND a.UserId = '" . $username . "')
			 ORDER BY RegionName " . $sLimit . "";
    }
//echo $sql;
    $pacrs = mysql_query($sql);
    $sql = "SELECT FOUND_ROWS()";
    $rs = mysql_query($sql);
    $r = mysql_fetch_array($rs);
    $total = $r[0];
    echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
    $f = 0;
    $serial = $_POST['iDisplayStart'] + 1;

    while ($row = @mysql_fetch_object($pacrs)) {
        $RegionId = $row->RegionId;
        $RegionName = $row->RegionName;
        $chkValue = $row->chkValue;
        $RegionMapId = $row->RegionMapId;

        if ($f++)
            echo ",";


        echo '["' . $RegionMapId . '", "' . getcheckBox($chkValue) . " " . $RegionName . '", "' . $RegionId . '"]';

        $serial++;
    }
    echo ']}';
}

function insertAllorOneMappingRegion() {
    $RegionMapId = $_POST['RegionMapId'];
    $RegionId = $_POST['RegionId'];
    $userName = str_replace("'", "''", $_POST['userName']);  //$_POST['userName'];
    $checkVal = $_POST['checkVal'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    //echo $checkVal;

    if ($checkVal == "true") {
        $sql = "INSERT INTO t_user_region_map (UserId, RegionId) 
                VALUES ('" . $userName . "', '" . $RegionId . "');";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_user_region_map', 'pks' => array('RegionMapId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "DELETE FROM t_user_region_map WHERE RegionMapId = '" . $RegionMapId . "';";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_user_region_map', 'pks' => array('RegionMapId'), 'pk_values' => array($RegionMapId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
  
}

?>