<?php

include_once ('database_conn.php');
include_once ("function_lib.php");

$task = '';
if (isset($_REQUEST['operation'])) {
    $task = $_REQUEST['operation'];
}

switch ($task) {
    case "getCountryProfileParams" :
        getCountryProfileParams();
        break;
    case "getCountryProfileParams1" :
        getCountryProfileParams1();
        break;
    case "getCountryProfileParams2" :
        getCountryProfileParams2();
        break;
    case "getCountryList" :
        getCountryList();
        break;
    default :
        echo "{failure:true}";
        break;
}

function getCountryProfileParams() {
    $CountryId = $_REQUEST['CountryId'];
    $Year = $_POST['Year'];
    $sEcho = isset($_POST['sEcho']) ? $_POST['sEcho'] : '';
    if ($_REQUEST['lan'] == 'fr-FR') {
        $aColumns = 'p.ParamNameFrench';
    } else {
        $aColumns = 'p.ParamName';
    }
    /* if (empty($_POST['CountryId'])&&empty($_POST['ParamId'])){	
      $sql = "SELECT YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, $aColumns, SUM(YCValue) total
      FROM `t_ycprofile` p
      INNER JOIN t_cprofileparams c ON c.ParamId=p.ParamId
      WHERE c.BShow=1 AND Year='".$Year."'
      GROUP BY p.ParamId;";
      }else{
      $sql = "SELECT YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, $aColumns, YCValue
      FROM `t_ycprofile` p
      INNER JOIN t_cprofileparams c ON c.ParamId=p.ParamId
      WHERE c.ParamId NOT IN (5,7) AND Year='".$Year."'
      AND p.CountryId='" . $CountryId . "' AND p.ParamId='" . $ParamId . "'";
      } */

    $sql = "SELECT SQL_CALC_FOUND_ROWS d.ItemGroupId,d.GroupName, $aColumns ParamName, c.YCValue 
		FROM t_cprofileparams p
		INNER JOIN t_itemgroup d ON d.ItemGroupId=p.ItemGroupId
		LEFT JOIN t_ycprofile c ON (c.ParamId=p.ParamId AND c.Year = '" . $Year . "' AND c.CountryId = $CountryId)
		WHERE p.BShow=1 AND p.ItemGroupId = 1
		ORDER BY p.ItemGroupId,p.ShortBy;";

    $rs = safe_query($sql);
    $sql = "SELECT FOUND_ROWS();";
    $qr = safe_query($sql);
    $r = mysql_fetch_array($qr);
    $trecords = $r[0];
    echo '{ "sEcho": ' . intval($sEcho) . ', "iTotalRecords": "' . $trecords . '" , "iTotalDisplayRecords": "' . $trecords . '", "aaData":[';
    $f = 0;
    $serial = isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart'] : 0;
    $serial = $serial + 1;
    while ($r = mysql_fetch_object($rs)) {
        if ($f++)
            echo ',';
        /* if (empty($_POST['CountryId'])&&empty($_POST['ParamId'])){		
          $YCValue=$r -> total;
          $ParamName='Total '.$r -> ParamName;
          }else{
          $YCValue=$r -> YCValue;
          $ParamName=$r -> ParamName;
          } */
        $YCValue = $r->YCValue;
        $ParamName = $r->ParamName;


        echo '["' . $serial++ . '","' . $ParamName . '", "' . $YCValue . '"]';
    }
    echo ']}';
}

function getCountryProfileParams1() {
    $CountryId = $_REQUEST['CountryId'];
    $Year = $_POST['Year'];
    $sEcho = isset($_POST['sEcho']) ? $_POST['sEcho'] : '';
    if ($_REQUEST['lan'] == 'fr-FR') {
        $aColumns = 'p.ParamNameFrench';
    } else {
        $aColumns = 'p.ParamName';
    }
    /* if (empty($_POST['CountryId'])&&empty($_POST['ParamId'])){	
      $sql = "SELECT YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, $aColumns, SUM(YCValue) total
      FROM `t_ycprofile` p
      INNER JOIN t_cprofileparams c ON c.ParamId=p.ParamId
      WHERE c.BShow=1 AND Year='".$Year."'
      GROUP BY p.ParamId;";
      }else{
      $sql = "SELECT YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, $aColumns, YCValue
      FROM `t_ycprofile` p
      INNER JOIN t_cprofileparams c ON c.ParamId=p.ParamId
      WHERE c.ParamId NOT IN (5,7) AND Year='".$Year."'
      AND p.CountryId='" . $CountryId . "' AND p.ParamId='" . $ParamId . "'";
      } */

    $sql = "SELECT SQL_CALC_FOUND_ROWS d.ItemGroupId,d.GroupName, $aColumns ParamName, c.YCValue 
		FROM t_cprofileparams p
		INNER JOIN t_itemgroup d ON d.ItemGroupId=p.ItemGroupId
		LEFT JOIN t_ycprofile c ON (c.ParamId=p.ParamId AND c.Year = '" . $Year . "' AND c.CountryId = $CountryId)
		WHERE p.BShow=1 AND p.ItemGroupId = 2
		ORDER BY p.ItemGroupId,p.ShortBy;";

    $rs = safe_query($sql);
    $sql = "SELECT FOUND_ROWS();";
    $qr = safe_query($sql);
    $r = mysql_fetch_array($qr);
    $trecords = $r[0];
    echo '{ "sEcho": ' . intval($sEcho) . ', "iTotalRecords": "' . $trecords . '" , "iTotalDisplayRecords": "' . $trecords . '", "aaData":[';
    $f = 0;
    $serial = isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart'] : 0;
    $serial = $serial + 1;
    while ($r = mysql_fetch_object($rs)) {
        if ($f++)
            echo ',';
        /* if (empty($_POST['CountryId'])&&empty($_POST['ParamId'])){		
          $YCValue=$r -> total;
          $ParamName='Total '.$r -> ParamName;
          }else{
          $YCValue=$r -> YCValue;
          $ParamName=$r -> ParamName;
          } */
        $YCValue = $r->YCValue;
        $ParamName = $r->ParamName;


        echo '["' . $serial++ . '","' . $ParamName . '", "' . $YCValue . '"]';
    }
    echo ']}';
}

function getCountryProfileParams2() {
    $CountryId = $_REQUEST['CountryId'];
    $Year = $_POST['Year'];
    $sEcho = isset($_POST['sEcho']) ? $_POST['sEcho'] : '';

    if ($_REQUEST['lan'] == 'fr-FR') {
        $aColumns = 'p.ParamNameFrench';
    } else {
        $aColumns = 'p.ParamName';
    }
    /* if (empty($_POST['CountryId'])&&empty($_POST['ParamId'])){	
      $sql = "SELECT YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, $aColumns, SUM(YCValue) total
      FROM `t_ycprofile` p
      INNER JOIN t_cprofileparams c ON c.ParamId=p.ParamId
      WHERE c.BShow=1 AND Year='".$Year."'
      GROUP BY p.ParamId;";
      }else{
      $sql = "SELECT YCProfileId, p.CountryId, p.ParamId, p.Year, BShow, $aColumns, YCValue
      FROM `t_ycprofile` p
      INNER JOIN t_cprofileparams c ON c.ParamId=p.ParamId
      WHERE c.ParamId NOT IN (5,7) AND Year='".$Year."'
      AND p.CountryId='" . $CountryId . "' AND p.ParamId='" . $ParamId . "'";
      } */

    $sql = "SELECT SQL_CALC_FOUND_ROWS d.ItemGroupId,d.GroupName, $aColumns ParamName, c.YCValue 
		FROM t_cprofileparams p
		INNER JOIN t_itemgroup d ON d.ItemGroupId=p.ItemGroupId
		LEFT JOIN t_ycprofile c ON (c.ParamId=p.ParamId AND c.Year = '" . $Year . "' AND c.CountryId = $CountryId)
		WHERE p.BShow=1 AND p.ItemGroupId = 3
		ORDER BY p.ItemGroupId,p.ShortBy;";

    $rs = safe_query($sql);
    $sql = "SELECT FOUND_ROWS();";
    $qr = safe_query($sql);
    $r = mysql_fetch_array($qr);
    $trecords = $r[0];
    echo '{ "sEcho": ' . intval($sEcho) . ', "iTotalRecords": "' . $trecords . '" , "iTotalDisplayRecords": "' . $trecords . '", "aaData":[';
    $f = 0;
    $serial = isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart'] : 0;
    $serial = $serial + 1;
    while ($r = mysql_fetch_object($rs)) {
        if ($f++)
            echo ',';
        /* if (empty($_POST['CountryId'])&&empty($_POST['ParamId'])){		
          $YCValue=$r -> total;
          $ParamName='Total '.$r -> ParamName;
          }else{
          $YCValue=$r -> YCValue;
          $ParamName=$r -> ParamName;
          } */
        $YCValue = $r->YCValue;
        $ParamName = $r->ParamName;


        echo '["' . $serial++ . '","' . $ParamName . '", "' . $YCValue . '"]';
    }
    echo ']}';
}

function getCountryList() {
    $sql = "SELECT * from t_country order by CountryName";
    $result = safe_query($sql);
    while ($row = mysql_fetch_object($result)) {
        $data[] = $row;
    }
    echo json_encode($data);
}
