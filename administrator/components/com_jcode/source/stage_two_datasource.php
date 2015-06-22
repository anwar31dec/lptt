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
    case "getFormulationData" :
        getFormulationData($conn);
        break;
    case "insertUpdateFormulationData" :
        insertUpdateFormulationData($conn);
        break;
    case "deleteFormulationData" :
        deleteFormulationData($conn);
        break;
    case "getRegionData" :
        getRegionData($conn);
        break;
    case "insertUpdateRegionData" :
        insertUpdateRegionData($conn);
        break;
    case "deleteRegionData" :
        deleteRegionData($conn);
        break;
    case "getYcProfileData" :
        getYcProfileData($conn);
        break;
    case "getYcProfileFundingSourceAssign" :
        getYcProfileFundingSourceAssign($conn);
        break;
    case "getYcFundingSource" :
        getYcFundingSource($conn);
        break;
    case "getYcRegimenPatient" :
        getYcRegimenPatient($conn);
        break;
    case "getPledgedFundingData" :
        getPledgedFundingData($conn);
        break;
    case "updateYcProfileData" :
        updateYcProfileData($conn);
        break;
    case "updateYcRegimenData" :
        updateYcRegimenData($conn);
        break;
    case "updateFundingRequirementData" :
        updateFundingRequirementData($conn);
        break;
    case "updateYcProfileMultipleData" :
        updateYcProfileMultipleData($conn);
        break;
    case "updatePledgedFundingData" :
        updatePledgedFundingData($conn);
        break;
    case "clearCountryProfileData" :
        clearCountryProfileData($conn);
        break;
    case "getItemListData" :
        getItemListData($conn);
        break;
	case "getWaitingProcessList" :
		getWaitingProcessList($conn);
	break;
    case "insertUpdateProcessTracking" :
        insertUpdateProcessTracking($conn);
        break;
    case "deleteItemList" :
        deleteItemList($conn);
        break;
    case "getAgencyShipment" :
        getAgencyShipment($conn);
        break;
    case "insertUpdateAgencyShipment" :
        insertUpdateAgencyShipment($conn);
        break;
    case "deleteAgencyShipment" :
        deleteAgencyShipment($conn);
        break;
    case "getProductSubGroupData" :
        getProductSubGroupData($conn);
        break;
    case "insertUpdateProductSubGroupData" :
        insertUpdateProductSubGroupData($conn);
        break;
    case "deleteProductSubGroupData" :
        deleteProductSubGroupData($conn);
        break;
    case "getFundingReqData" :
        getFundingReqData($conn);
        break;
    case "insertUpdateFundingReqData" :
        insertUpdateFundingReqData($conn);
        break;
    case "deleteFundingReqData" :
        deleteFundingReqData($conn);
        break;

    default :
        echo "{failure:true}";
        break;
}



/* * ***********************************************************Others Function*********************************************************** */

function getTextBox($v, $class) {
    $r = $v;
    $r = ($r == '') ? '' : $r;
    $x = "<input type='text' class='datacell " . $class . "' value='" . $r . "'/>";
    //$x = $r;
    return $x;
}

function getTextBox1($v, $class, $id) {
    $r = $v;
    $r = ($r == '') ? '' : $r;
    $x = "<input  type='text' class='datacell " . $class . "' value='" . $r . "' id='" . $id . "'/>";
    //$x = $r;
    return $x;
}

function getMultiSelectBox($v, $class, $ProfileId, $conn) {
    $r = $v;
    $r = ($r == '') ? '' : $r;

    $values = explode(',', $v);
    //print_r($values);
    $sql = "select * from t_fundingsource order by FundingSourceName asc;";
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    $x = "<table width='100%' border='0' id='multiselect'>";
    if ($total > 0) {
        while ($row = mysql_fetch_object($result)) {
            $match = 0;
            for ($k = 0; $k < count($values); $k++) {
                if (trim($values[$k]) == $row->FundingSourceName) {
                    $match = 1;
                    break;
                }
            }
            if ($match == 1)
                $x.="<tr><td width='25px'><input type='checkbox' checked='true' value='" . $row->FundingSourceId . "' id='" . $ProfileId . "' class='items' name='multiselectitems[]'></td><td>" . $row->FundingSourceName . "</td></tr>";
            else
                $x.="<tr><td width='25px'><input type='checkbox' value='" . $row->FundingSourceId . "' id='" . $ProfileId . "' class='items' name='multiselectitems[]'></td><td>" . $row->FundingSourceName . "</td></tr>";
        }
    }
    $x.="
</table>";
    $x = str_replace("\n", '', $x);
    $x = str_replace("\r", '', $x);
    return $x;
}

function getMultiSelectBoxForFundingAssign($v, $class, $ProfileId, $conn) {
    $r = $v;
    $r = ($r == '') ? '' : $r;

    $values = explode(',', $v);
    //print_r($values);
    $sql = "select * from t_fundingsource order by FundingSourceName asc;";
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    $x = "<table width='100%' border='0' id='multiselect'>";
    if ($total > 0) {
        while ($row = mysql_fetch_object($result)) {
            $match = 0;
            for ($k = 0; $k < count($values); $k++) {
                if (trim($values[$k]) == $row->FundingSourceName) {
                    $match = 1;
                    break;
                }
            }
            if ($match == 1)
                $x.="<tr><td width='25px'><input type='checkbox' checked='true' value='" . $row->FundingSourceId . "' id='" . $ProfileId . "' class='items' name='multiselectitems[]'></td><td>" . $row->FundingSourceName . "</td></tr>";
            else
                $x.="<tr><td width='25px'><input type='checkbox' value='" . $row->FundingSourceId . "' id='" . $ProfileId . "' class='items' name='multiselectitems[]'></td><td>" . $row->FundingSourceName . "</td></tr>";
        }
    }
    $x.="
</table>";
    $x = str_replace("\n", '', $x);
    $x = str_replace("\r", '', $x);
    return $x;
}

//function getCheckBox($id, $class, $Name, $fromId){
// if($id=='')
//$x.="<tr><td width='50px'><input type='checkbox' id='".$id."' fromId='".$fromId."' class='items' name='multiselectitems[]'></td><td>".$Name."</td></tr>";
//else
//$x.="<tr><td width='50px'><input type='checkbox' checked='true' id='".$id."' fromId='".$fromId."' class='items' name='multiselectitems[]'></td><td>".$Name."</td></tr>";
//return $x;
//}

function getcheckBox($v) {
    if ($v == "true") {
        $x = "<input type='checkbox' checked class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    } else {
        $x = "<input type='checkbox' class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    }
    return $x;
}

/* * *********************************************************Formulatiuon Data*********************************************************** */

function getFormulationData($conn) {

    global $gTEXT;
    
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
		    $ServiceTypeName = 'ServiceTypeName';
        }else{
            $GroupName = 'GroupNameFrench';
			$ServiceTypeName = 'ServiceTypeNameFrench';
        }
	
	
    $condition = '';
    $sWhere = "";
    $itemGroupId = $_POST['itemGroupId'];
    if ($itemGroupId != 0) {
        $sWhere = ' WHERE ';
        $condition.=" a.ItemGroupId = '" . $itemGroupId . "' ";
    }
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_formulation(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }


    if ($_POST['sSearch'] != "") {

        if ($sWhere == '')
            $sWhere = " WHERE ";
        else
            $condition.=" and ";

        $condition.= "   (FormulationName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR $ServiceTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR $GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR ColorCode LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS FormulationId, FormulationName,FormulationNameFrench, a.ItemGroupId,$GroupName GroupName, a.ServiceTypeId,$ServiceTypeName ServiceTypeName, ColorCode
				FROM t_formulation a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                INNER JOIN t_servicetype c ON a.ServiceTypeId = c.ServiceTypeId
                $sWhere $condition $sOrder $sLimit ";
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
        $FormulationName = crnl2br($aRow['FormulationName']);
        $GroupName = crnl2br($aRow['GroupName']);
        $ServiceTypeName = crnl2br($aRow['ServiceTypeName']);
        $ColorCode = mysql_real_escape_string('<span style="width:30px;height:15px;display:block;align:center;background:' . $aRow['ColorCode'] . ';"></span>');


        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aRow['FormulationId'] . '",'; //addslashes
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $FormulationName . '",';   //******* $FormulationName. '",';
        $sOutput .= '"' . $aRow['FormulationNameFrench'] . '",';
        $sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $aRow['ServiceTypeName'] . '",';
        $sOutput .= '"' . $ColorCode . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['ItemGroupId'] . '",';
        $sOutput .= '"' . $aRow['ServiceTypeId'] . '",';
        $sOutput .= '"' . $aRow['ColorCode'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_formulation($i) {
    if ($i == 0)
        return "FormulationId ";
    else if ($i == 2)
        return "FormulationName ";
    else if ($i == 3)
        return "FormulationNameFrench";
    else if ($i == 4)
        return "ServiceTypeName ";
    else if ($i == 5)
        return "GroupName ";
}

function insertUpdateFormulationData($conn) {

    $RecordId = $_POST['RecordId'];
    $FormulationName = str_replace("'", "''", $_POST['FormulationName']);  //$_POST['FormulationName'];      //**********$_POST['FormulationName'];
    $FormulationNameFrench = str_replace("'", "''", $_POST['FormulationNameFrench']);  //$_POST['FormulationNameFrench'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $ServiceTypeId = $_POST['ServiceTypeId'];
    $ColorCode = str_replace("'", "''", $_POST['ColorCode']);  //$_POST['ColorCode'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_formulation(FormulationName,FormulationNameFrench, ItemGroupId, ServiceTypeId,ColorCode)
                 VALUES ('" . $FormulationName . "', '" . $FormulationNameFrench . "', '" . $ItemGroupId . "', '" . $ServiceTypeId . "', '" . $ColorCode . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_formulation', 'pks' => array('FormulationId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE
                 t_formulation SET
                 FormulationName = '" . $FormulationName . "',
                 FormulationNameFrench = '" . $FormulationNameFrench . "',
                 ItemGroupId = '" . $ItemGroupId . "',
                 ServiceTypeId = '" . $ServiceTypeId . "',
                 ColorCode = '" . $ColorCode . "'
                 WHERE FormulationId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_formulation', 'pks' => array('FormulationId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteFormulationData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_formulation WHERE FormulationId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_formulation', 'pks' => array('FormulationId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * **********************************************************Region Data****************************************************** */


function getRegionData($conn) {

    global $gTEXT; 
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $CountryName = 'CountryName';
        }else{
            $CountryName = 'CountryNameFrench';
        }
	
	
    $CountryId = $_POST['CountryId'];
    
    if($CountryId){
		$CountryId = " WHERE a.CountryId = '".$CountryId."' ";
	}
    
	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_region(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " and (RegionName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR $CountryName LIKE '%".mysql_real_escape_string( $_POST['sSearch'] )."%') ";
    }    
    $sql = "	SELECT SQL_CALC_FOUND_ROWS RegionId, RegionName, a.CountryId,$CountryName CountryName
				FROM t_region a
                INNER JOIN t_country b ON a.CountryId = b.CountryId ".$CountryId."
				$sWhere $sOrder $sLimit ";  
//echo  $sql ;
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

    $y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>".$gTEXT['Edit']."</span></a>";
	$z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>".$gTEXT['Delete']."</span></a>";

	$f = 0;
	while ($aRow = mysql_fetch_array($result)) {

		$RegionName = crnl2br($aRow['RegionName']);

		if ($f++)
			$sOutput .= ',';
		$sOutput .= "[";
		$sOutput .= '"' . $aRow['RegionId'] . '",';
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . $RegionName . '",';
		$sOutput .= '"' . $y.$z . '",';
		$sOutput .= '"' . $aRow['CountryName'] . '",';
 	    $sOutput .= '"' . $aRow['CountryId'] . '"';
		$sOutput .= "]";
	}
	$sOutput .= '] }';
	echo $sOutput;
}

function fnColumnToField_region($i) {
    if ($i == 2)
        return "RegionName ";
    else if ($i == 4)
        return "CountryName ";
}

function insertUpdateRegionData($conn) {

    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $RecordId = $_POST['RecordId'];
    $RegionName = str_replace("'", "''", $_POST['RegionName']);  //$_POST['RegionName'];
    $CountryId = $_POST['CountryId'];

    if ($RecordId == '') {

        $sql = "INSERT INTO t_region(RegionName, CountryId)
                 VALUES ('" . $RegionName . "', '" . $CountryId . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_region', 'pks' => array('RegionId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = " UPDATE
                 t_region SET
                 RegionName = '" . $RegionName . "',
                 CountryId = '" . $CountryId . "'
                 WHERE RegionId = " . $RecordId;
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_region', 'pks' => array('RegionId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteRegionData($conn) {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $RecordId = $_POST['RecordId'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_region WHERE RegionId = " . $RecordId . " ";
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_region', 'pks' => array('RegionId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ***********************************************************YC Profile Data****************************************************** */

function getYcProfileData($conn) {
    $CountryId = $_POST['country'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $Year = $_POST['year'];
    $lan = $_POST['lan'];
//echo $CountryId.'    '.$ItemGroupId. '  '.$Year.'  '.$lan.'   ';
    if (!empty($CountryId) && !empty($Year) && !empty($ItemGroupId)) {

        $sql_count = "SELECT COUNT(t_ycprofile.YCProfileId) as M
					FROM t_ycprofile
					INNER Join t_cprofileparams ON t_ycprofile.ParamId = t_cprofileparams.ParamId
					WHERE t_ycprofile.CountryId = '" . $CountryId . "' AND t_cprofileparams.ItemGroupId = '" . $ItemGroupId . "'
					AND t_ycprofile.Year = '" . $Year . "' ";
        //echo $sql_count;
        $qr_count = mysql_query($sql_count, $conn);
        $r_count = mysql_fetch_object($qr_count);
        $re_num = $r_count->M;
        if ($re_num == 0) {
            // $sql_paramlist = "SELECT ParamId, ParamName, ParamNameFrench 
            // FROM t_cprofileparams where ItemGroupId ='".$ItemGroupId."' order by ParamId";
            // $result_paramlist = mysql_query($sql_paramlist, $conn);
            // while ($row_paramlist = mysql_fetch_object($result_paramlist)) {

            $sql = "INSERT INTO t_ycprofile(YCProfileId, CountryId, ItemGroupId, ParamId, Year, YCValue, ShortBy)
				SELECT NULL, '" . $CountryId . "', ItemGroupId, ParamId, '" . $Year . "', '', ShortBy
					FROM t_cprofileparams 
					where ItemGroupId ='" . $ItemGroupId . "' order by t_cprofileparams.ShortBy;
				";
            //	echo $sql;
            mysql_query($sql, $conn);
            //}			
        }
    }
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    //$sOrder = "";
    //if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
    //	for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
    //		$sOrder .= fnColumnToField_profile(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
    //							" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
    //	}
    //	$sOrder = substr_replace($sOrder, "", -2);
    //}
    //$sWhere = "";
    //if ($_POST['sSearch'] != "") {
    //	$sWhere = " AND (ParamName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    //}


    $sql = "SELECT SQL_CALC_FOUND_ROWS a.YCProfileId, a.YCValue, Year, a.CountryId, a.ParamId, ParamName, ParamNameFrench
				FROM t_ycprofile a
                INNER JOIN t_country b ON a.CountryId = b.CountryId
                INNER JOIN t_cprofileparams c ON a.ParamId = c.ParamId
                WHERE a.CountryId = '" . $CountryId . "'
                AND a.Year = '" . $Year . "' AND c.ItemGroupId = '" . $ItemGroupId . "'
				order by c.ShortBy;";

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

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {
        if ($lan == 'en-GB') {
            $ParamName = crnl2br($aRow['ParamName']);
        } else {
            $ParamName = crnl2br($aRow['ParamNameFrench']);
        }
        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['YCProfileId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $ParamName . '",';
        //if($aRow['ParamId']==12){
        //	$sOutput .= '"' . getMultiSelectBox($aRow['YCValue'],'yc_'.$aRow['ParamId'],$aRow['YCProfileId'],$conn) . '",';
        //}else{
        //if($aRow['ParamId']==2||$aRow['ParamId']==11){
        $sOutput .= '"' . getTextBox($aRow['YCValue'], 'yc_' . $aRow['ParamId']) . '",';
        //}else{	
        //	$YCValue=$aRow['YCValue']=='' ? '' : number_format($aRow['YCValue']);
        //		$sOutput .= '"' . getTextBox($YCValue,'yc_'.$aRow['ParamId']) . '",';
        //}
        //}


        $sOutput .= '"' . $aRow['ParamId'] . '",';
        $sOutput .= '"' . $aRow['CountryId'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_profile($i) {
    if ($i == 4)
        return "a.ParamId ";
    else if ($i == 2)
        return "ParamName ";
}

function getYcProfileFundingSourceAssign($conn) {
    //mysql_query('SET CHARACTER SET utf8');
    $CountryId = $_POST['country'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $Year = $_POST['year'];
    $lan = $_POST['lan'];
    $sSearch = isset($_POST['sSearch'])? $_POST['sSearch'] : '';
    $sWhere = "";
    if ($sSearch != "") {
        $sWhere = " WHERE (GroupName like '%" . mysql_real_escape_string($sSearch) . "%' )";
    }

    $sLimit = "";

    $sql = "SELECT t_fundingsource.ItemGroupId, t_fundingsource.FundingSourceName
	,t_yearly_country_fundingsource.YearlyFundingSrcId,t_fundingsource.FundingSourceId
	,IF(t_yearly_country_fundingsource.YearlyFundingSrcId is Null,'false','true') chkValue
	FROM t_fundingsource
	LEFT JOIN t_yearly_country_fundingsource ON (t_yearly_country_fundingsource.FundingSourceId = t_fundingsource.FundingSourceId
			AND t_yearly_country_fundingsource.CountryId = $CountryId
			AND t_yearly_country_fundingsource.Year = $Year
			AND t_fundingsource.ItemGroupId = $ItemGroupId)
	WHERE t_fundingsource.ItemGroupId = $ItemGroupId;";
    //echo $sql;
    $pacrs = mysql_query($sql, $conn);
    $sql = "SELECT FOUND_ROWS()";
    $rs = mysql_query($sql, $conn);
    $r = mysql_fetch_array($rs);
    $total = $r[0];
    echo '{ "sEcho": ' . intval($_POST['sEcho']) . ', "iTotalRecords":' . $total . ', "iTotalDisplayRecords": ' . $total . ', "aaData":[';
    $f = 0;
    $serial = $_POST['iDisplayStart'] + 1;

    while ($row = @mysql_fetch_object($pacrs)) {
        $FundingSourceId = $row->FundingSourceId;
        $FundingSourceName = $row->FundingSourceName;
        $chkValue = $row->chkValue;
        $YearlyFundingSrcId = $row->YearlyFundingSrcId;
        if ($f++)
            echo ",";
        echo '["' . $YearlyFundingSrcId . '", "' . getcheckBox($chkValue) . " " . $FundingSourceName . '", "' . $FundingSourceId . '"]';
        $serial++;
    }
    echo ']}';
}
function getYcRegimenPatient($conn) {
			
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $FormulationName = 'FormulationName';
        }else{
            $FormulationName = 'FormulationNameFrench';
        }
    $CountryId = $_POST['country'];
    $Year = $_POST['year'];
    $ItemGroupId = $_POST['ItemGroupId'];

    if (!empty($CountryId) && !empty($Year)) {

        $sql_count = "SELECT COUNT(YearlyRegPatientId) as M						
						FROM t_yearly_country_regimen_patient 
						INNER JOIN t_regimen_master 
							ON t_regimen_master.RegMasterId = t_yearly_country_regimen_patient.RegMasterId
						WHERE t_yearly_country_regimen_patient.CountryId = " . $CountryId . "
						AND t_yearly_country_regimen_patient.Year = '" . $Year . "'	
						AND t_regimen_master.ItemGroupId = '" . $ItemGroupId . "'							
                        ";
        //echo $sql_count;				
        $qr_count = mysql_query($sql_count, $conn);
        $r_count = mysql_fetch_object($qr_count);
        $re_num = $r_count->M;

        if ($re_num == 0) {
            $sql_paramlist = "SELECT FormulationId,FormulationName,FormulationNameFrench FROM `t_formulation`
							WHERE ItemGroupId = $ItemGroupId
							order by FormulationId";
//echo $sql_paramlist;
            $result_paramlist = mysql_query($sql_paramlist, $conn);
            $total = mysql_num_rows($result_paramlist);

            while ($row_paramlist = mysql_fetch_object($result_paramlist)) {
                $FormulationId = $row_paramlist->FormulationId;

                $sql = "INSERT INTO `t_yearly_country_regimen_patient` 
				(`YearlyRegPatientId` ,`Year` ,`CountryId`,`ItemGroupId` ,`RegMasterId` ,`PatientCount` ,`FormulationId`)
				SELECT NULL, '" . $Year . "', '" . $CountryId . "',ItemGroupId, RegMasterId, '' ,'" . $FormulationId . "'
				FROM `t_regimen_master` WHERE  ItemGroupId = '" . $ItemGroupId . "';";

                //echo $sql;
                mysql_query($sql, $conn);
            }
        }
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_regimen2(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }
    $columnsName = "";
    $sql = "SELECT RegMasterId,RegimenName FROM `t_regimen_master`
				where ItemGroupId=" . $ItemGroupId . " Order By RegMasterId ASC;";


    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    if ($total > 0) {
        while ($row = mysql_fetch_object($result)) {
            $columnsName.=',{ "sTitle": "' . $row->RegimenName . '","sWidth":"12%"}';
        }
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS YearlyRegPatientId, t_regimen_master.RegimenName, PatientCount,
				t_yearly_country_regimen_patient.FormulationId,$FormulationName	FormulationName
				FROM t_yearly_country_regimen_patient 
				INNER JOIN t_regimen_master ON t_yearly_country_regimen_patient.RegMasterId = t_regimen_master.RegMasterId 
				INNER JOIN t_formulation ON t_yearly_country_regimen_patient.FormulationId = t_formulation.FormulationId 
				WHERE t_yearly_country_regimen_patient.CountryId = '" . $CountryId . "' AND t_yearly_country_regimen_patient.Year = '" . $Year . "'	 
				AND t_regimen_master.ItemGroupId = " . $ItemGroupId . "	 				
				Order BY t_formulation.FormulationId ASC, t_yearly_country_regimen_patient.RegMasterId ASC;";
    //	echo $sql;		
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
    $serial = $_POST['iDisplayStart'];

    $f = 0;
    $tmpFormulationId = -1;
    while ($aRow = mysql_fetch_array($result)) {

        if ($tmpFormulationId != $aRow['FormulationId']) {

            if ($serial > 0) //For Close Bracket
                $sOutput .= "]";

            if ($f++)
                $sOutput .= ',';

            $sOutput .= "[";
            $sOutput .= '"' . ++$serial . '"';
            $sOutput .= ',"' . $aRow['FormulationName'] . '"';
            $sOutput .= ',"' . getTextBox1($aRow['PatientCount'], 'yc_1', $aRow['YearlyRegPatientId']) . '"';

            $tmpFormulationId = $aRow['FormulationId'];
        }
        else {
            $sOutput .= ',"' . getTextBox1($aRow['PatientCount'], 'yc_1', $aRow['YearlyRegPatientId']) . '"';
            $tmpFormulationId = $aRow['FormulationId'];
        }
    }
    if ($serial > 0) //For Close Bracket
        $sOutput .= "]";

    //$sOutput .= '],';
    $sOutput .= '] }';
    echo $sOutput;
    // echo '{'.$sOutput.' "COLUMNS":[{ "sTitle": "SL","sWidth":"10%"},{ "sTitle": "Formulation","sWidth":"13%"}'.$columnsName;
    //	echo ']}';	
}

function fnColumnToField_regimen2($i) {
    if ($i == 1)
        return "RegimenName ";
    else if ($i == 2)
        return "RegimenName ";
    else if ($i == 3)
        return "FormulationName ";
}

function getYcFundingSource($conn) {
	
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $ServiceTypeName = 'ServiceTypeName';
            $FundingReqSourceName = 'FundingReqSourceName';
        }else{
            $ServiceTypeName = 'ServiceTypeNameFrench';
			$FundingReqSourceName = 'FundingReqSourceNameFrench';
        }

    $CountryId = $_POST['country'];
    $Year = $_POST['year'];
    $ItemGroupId = $_POST['ItemGroupId'];

    if (!empty($CountryId) && !empty($Year) && !empty($ItemGroupId)) {

        $sql_count = "SELECT COUNT(FundingReqId) as M
                        FROM t_yearly_funding_requirements
                        WHERE CountryId = '" . $CountryId . "' 
                        AND Year = '" . $Year . "'
						AND ItemGroupId = '" . $ItemGroupId . "';";

        //	echo $sql_count;
        $qr_count = mysql_query($sql_count, $conn);
        $r_count = mysql_fetch_object($qr_count);
        $re_num = $r_count->M;
        if ($re_num == 0) {

            $sql = "INSERT INTO t_yearly_funding_requirements(FundingReqId, CountryId,FundingReqSourceId,
				ItemGroupId, Year, TotalRequirements)
				SELECT NULL,'" . $CountryId . "',FundingReqSourceId,'" . $ItemGroupId . "','" . $Year . "',''
				FROM `t_fundingreqsources` WHERE ItemGroupId = '" . $ItemGroupId . "' 
				ORDER BY FundingReqSourceId ASC;";
           // echo $sql;
            mysql_query($sql, $conn);
            //}
        }
    }
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_fundingsource(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }
    $sql = "SELECT SQL_CALC_FOUND_ROWS a.FundingReqId, a.TotalRequirements, a.Year, Y1, Y2, Y3,
				d.$ServiceTypeName ServiceTypeName, a.CountryId, a.FormulationId, c.$FundingReqSourceName FundingReqSourceName
				FROM t_yearly_funding_requirements a
				INNER JOIN  t_fundingreqsources c ON c.FundingReqSourceId = a.FundingReqSourceId 
				INNER JOIN t_servicetype d ON d.ServiceTypeId = c.ServiceTypeId
                INNER JOIN t_itemgroup b ON c.ItemGroupId = b.ItemGroupId                
                WHERE a.CountryId = '" . $CountryId . "'
                AND a.Year = '" . $Year . "' AND a.ItemGroupId = '" . $ItemGroupId . "'
				ORDER BY a.FundingReqSourceId ASC
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

    $f = 0;
    while ($aRow = mysql_fetch_array($result)) {

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['FundingReqId'] . '",';
        $sOutput .= '"' . $aRow['ServiceTypeName'] . '",';
        $sOutput .= '"' . $aRow['FundingReqSourceName'] . '",';
        $sOutput .= '"' . getTextBox($aRow['Y1'], 'Y1') . '",';
        $sOutput .= '"' . getTextBox($aRow['Y2'], 'Y2') . '",';
        $sOutput .= '"' . getTextBox($aRow['Y3'], 'Y3') . '",';
        $sOutput .= '"<span class=TR-' . $aRow['FundingReqId'] . '>' . $aRow['TotalRequirements'] . '</span>"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_fundingsource($i) {
    if ($i == 1)
        return "FundingReqId ";
    else if ($i == 2)
        return "GroupName ";
}

////////////////new pledged funding///////////////
function getPledgedFundingData($conn) {
    
    $lan = $_POST['lan'];
	
	 if($lan == 'en-GB'){
            $ServiceTypeName = 'ServiceTypeName';
            $FundingReqSourceName = 'FundingReqSourceName';
        }else{
            $ServiceTypeName = 'ServiceTypeNameFrench';
			$FundingReqSourceName = 'FundingReqSourceNameFrench';
        }

    $CountryId = $_POST['country'];
    $Year = $_POST['year'];
    $RequirementYear = $_POST['RequirementYear'];
    $ItemGroupId = $_POST['ItemGroupId'];

    $columnsName = "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS t_yearly_country_fundingsource.FundingSourceId,FundingSourceName FROM t_yearly_country_fundingsource
		INNER JOIN t_fundingsource ON t_yearly_country_fundingsource.FundingSourceId=t_fundingsource.FundingSourceId
				where Year ='" . $Year . "' AND CountryId ='" . $CountryId . "' AND t_fundingsource.ItemGroupId = '" . $ItemGroupId . "'
				Order By FundingSourceId;";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
	
	
    if ($total > 0) {
    	if($lan == 'en-GB'){
    	$columnsName.= '{ "sTitle": "Service Type","sWidth":"100px"}';
        $columnsName.= ',{ "sTitle": "Category","sWidth":"100px"}';
        $columnsName.= ',{ "sTitle": "Total Requirements","sWidth":"100px"}';
    		
    	}
	  else{
			$columnsName.= '{ "sTitle": "Type de service","sWidth":"100px"}';
			$columnsName.= ',{ "sTitle": "catégorie","sWidth":"100px"}';
			$columnsName.= ',{ "sTitle": "total des besoins","sWidth":"100px"}';
		 }

        while ($row = mysql_fetch_object($result)) {
            $columnsName.=',{ "sTitle": "' . $row->FundingSourceName . '","sWidth":"100px"}';
        }
    if($lan == 'en-GB'){
	    $columnsName.= ',{ "sTitle": "Total","sWidth":"100px"}';
        $columnsName.= ',{ "sTitle": "Gap/Surplus","sWidth":"100px"}';
        }
	else{
		$columnsName.= ',{ "sTitle": "total","sWidth":"100px"}';
        $columnsName.= ',{ "sTitle": "Gap/Surplus","sWidth":"100px"}';
	   }
        	
    }
	else{
		if ($lan == 'en-GB') 
			echo '{"aaData": [ ], "COLUMNS":[{ "sTitle": "Service Type","sWidth":"100px"},{ "sTitle": "Category","sWidth":"100px"}, { "sTitle": "Total Requirements","sWidth":"100px"}, { "sTitle": "Total","sWidth":"100px"},{ "sTitle": "Gap/Surplus","sWidth":"100px"}]}';
		else
			echo '{"aaData": [ ], "COLUMNS":[{ "sTitle": "Type de service offert","sWidth":"100px"},{ "sTitle": "Category","sWidth":"100px"}, { "sTitle": "total des besoins","sWidth":"100px"}, { "sTitle": "total","sWidth":"100px"},{ "sTitle": "Gap/Surplus","sWidth":"100px"}]}';	
	
		return;
	}

    $sql = "SELECT FundingReqSourceId FROM t_fundingreqsources  WHERE ItemGroupId = '" . $ItemGroupId . "';";


    $result = mysql_query($sql, $conn);
    while ($row = mysql_fetch_object($result)) {
        $FundingReqSourceId = $row->FundingReqSourceId;


        $sqldel = "SELECT PledgedFundingId FROM t_yearly_pledged_funding
			 WHERE YEAR = '" . $Year . "' AND CountryId = " . $CountryId . " AND ItemGroupId = '" . $ItemGroupId . "'
			 AND FundingReqSourceId = " . $FundingReqSourceId . "			 
			 AND FundingSourceId NOT IN (			 
				SELECT FundingSourceId
				FROM t_yearly_country_fundingsource a
				WHERE a.Year = '" . $Year . "' AND a.CountryId = " . $CountryId . " AND a.ItemGroupId = '" . $ItemGroupId . "');";
        //echo $sqldel;
        $delResult = mysql_query($sqldel, $conn);
        while ($r = mysql_fetch_object($delResult)) {
            $sqldel1 = "DELETE FROM t_yearly_pledged_funding WHERE PledgedFundingId = " . $r->PledgedFundingId . ";";
            mysql_query($sqldel1, $conn);
        }

        $sql = "INSERT INTO t_yearly_pledged_funding 
						(`PledgedFundingId` ,`CountryId` ,`Year` ,`ItemGroupId` ,`FundingReqSourceId` ,`FundingSourceId` ,`TotalFund`)
						 SELECT NULL, a.CountryId, a.Year, a.ItemGroupId, '" . $FundingReqSourceId . "' ,a.FundingSourceId,0
							FROM t_yearly_country_fundingsource a
							WHERE a.Year = '" . $Year . "' AND a.CountryId = " . $CountryId . " AND a.ItemGroupId = '" . $ItemGroupId . "'
							AND FundingSourceId NOT IN (
							SELECT DISTINCT FundingSourceId FROM t_yearly_pledged_funding
							WHERE YEAR = '" . $Year . "' AND CountryId = " . $CountryId . " 
							AND ItemGroupId = '" . $ItemGroupId . "' AND FundingReqSourceId = " . $FundingReqSourceId . ");";
        mysql_query($sql, $conn);		
    }

    $YValue = 'a.Y' . $RequirementYear;
    $sql = "SELECT d.ServiceTypeId, d.$ServiceTypeName ServiceTypeName, d.ServiceTypeNameFrench,
			b.FundingReqSourceId, b.$FundingReqSourceName FundingReqSourceName, b.FundingReqSourceNameFrench, IFNULL($YValue,0) YReq
			FROM t_yearly_funding_requirements a
			INNER JOIN t_fundingreqsources b ON a.FundingReqSourceId = b.FundingReqSourceId
			INNER JOIN t_servicetype d ON b.ServiceTypeId = d.ServiceTypeId
						
			WHERE a.CountryId = " . $CountryId . "
			AND  a.ItemGroupId = " . $ItemGroupId . "
			AND a.Year = '" . $Year . "'
			ORDER BY b.ServiceTypeId, b.FundingReqSourceId;";


    $result = mysql_query($sql, $conn);
    $sOutput = '"aaData": [ ';	
    $f = 0;
    $ColumnClass = 0;
    $tmpServiceTypeId = -1;
    $tmpFundingReqSourceId = -1;
    $sl = 0;
    while ($aRow = mysql_fetch_array($result)) {

        $Total = 0;
        $YReq = $aRow['YReq'];
		$FundingReqSourceId = $aRow['FundingReqSourceId'];
        $PledgedFundingId = isset($aRow['PledgedFundingId'])? $aRow['PledgedFundingId'] : '';

        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aRow['ServiceTypeName'] . '",';
        $sOutput .= '"' . $aRow['FundingReqSourceName'] . '",';
        $sOutput .= '"' . getTextBox1($YReq, 'ycreq_' . $FundingReqSourceId, $FundingReqSourceId) . '",';


        $sql1 = "SELECT a.PledgedFundingId, b.FundingSourceId, b.FundingSourceName, IFNULL($YValue,0) YCurr	
					FROM t_yearly_pledged_funding a
					INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId								
					WHERE a.CountryId = " . $CountryId . "
					AND  a.ItemGroupId = " . $ItemGroupId . "
					AND a.Year = '" . $Year . "'
					AND a.FundingReqSourceId = " . $aRow['FundingReqSourceId'] . "
					ORDER BY b.FundingSourceId;";

        $sResult = mysql_query($sql1, $conn);
        while ($r = mysql_fetch_array($sResult)) {
            $sOutput .= '"' . getTextBox1($r['YCurr'], 'yccurr_' . $FundingReqSourceId, $r['PledgedFundingId']) . '",';
            $Total+= $r['YCurr'];
        }

        $sOutput .= '"' . getTextBox1(number_format($Total, 1), 'yctotal_' . $FundingReqSourceId, $PledgedFundingId) . '",';
        $sOutput .= '"' . getTextBox1(number_format(($YReq - $Total), 1), 'ycgaporsurplus_' . $FundingReqSourceId, $PledgedFundingId) . '"';

        $sOutput .= "]";
    }

    $sOutput .= '],';
    echo '{' . $sOutput . ' "COLUMNS":[' . $columnsName . ']}';
}

function updateYcProfileData($conn) {
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
	
    $ProfileId = $_POST['ProfileId'];
    $ParamValue = $_POST['Pvalue'];
	$msg = '';
    if ($ProfileId != '') {
        $ParamValue = str_replace(',', '', $ParamValue);
        //$ParamValue=str_replace("'", "''", $ParamValue); 
        $sql = " UPDATE
                 t_ycprofile SET
                 YCValue = '" . $ParamValue . "'
                 WHERE YCProfileId = " . $ProfileId;
				 
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_ycprofile', 'pks' => array('YCProfileId'), 'pk_values' => array($ProfileId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
        echo json_encode(exec_query($aQuerys, $jUserId, $language)); 
		
    }
}
function updateYcRegimenData($conn) {
	$jUserId = $_REQUEST['jUserId'];
	$language = $_REQUEST['language'];	
    $YearlyRegPatientId = $_POST['YearlyRegPatientId'];
    $ParamValue = $_POST['Pvalue'];

    if ($YearlyRegPatientId != '') {
        $ParamValue = str_replace(',', '', $ParamValue);
        $sql = "UPDATE t_yearly_country_regimen_patient SET
                 PatientCount  = '" . $ParamValue . "'
                 WHERE YearlyRegPatientId = " . $YearlyRegPatientId;
				 
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_yearly_country_regimen_patient', 'pks' => array('YearlyRegPatientId'), 'pk_values' => array($YearlyRegPatientId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
        echo json_encode(exec_query($aQuerys, $jUserId, $language)); 		
    }
}

function updateFundingRequirementData($conn) {
	$jUserId = $_REQUEST['jUserId'];
	$language = $_REQUEST['language'];	
	//echo $jUserId . ' nazim ' . $language . ' nazim ma';	
    $FundingReqId = $_POST['FundingReqId'];
    $ParamValue = $_POST['Pvalue'];
    $currentY = $_POST['currentY'];

    if ($FundingReqId != '') {
        $ParamValue = str_replace(',', '', $ParamValue);
        if ($currentY == 'Y1')
            $currentY = 'Y1="' . $ParamValue . '" ';
        else if ($currentY == 'Y2')
            $currentY = 'Y2="' . $ParamValue . '" ';
        else if ($currentY == 'Y3')
            $currentY = 'Y3="' . $ParamValue . '" ';
        $sql = " UPDATE
                 t_yearly_funding_requirements SET
                 " . $currentY . ", TotalRequirements=Y1+Y2+Y3
                 WHERE FundingReqId = " . $FundingReqId;
				 
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_yearly_funding_requirements', 'pks' => array('FundingReqId'), 'pk_values' => array($FundingReqId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
        echo json_encode(exec_query($aQuerys, $jUserId, $language));		 
				 
    }
    if (mysql_query($sql, $conn)) {
        $sql = "select Y1+Y2+Y3 total from t_yearly_funding_requirements where FundingReqId='" . $FundingReqId . "'";
        $result = mysql_query($sql, $conn);
        $row = mysql_fetch_object($result);
        $error = "1*" . $row->total;
    }
}

function updateYcProfileMultipleData($conn) {

	$jUserId = $_REQUEST['jUserId'];
	$language = $_REQUEST['language'];	
	//echo $jUserId . ' nazim ' . $language . ' nazim ma';
    $YearlyFundingSrcId = $_POST['YearlyFundingSrcId'];
    // $ItemGroupId = $_POST['ItemGroupId'];
    $FundingSourceId = $_POST['FundingSourceId'];
    $checkVal = $_POST['checkVal'];
    $Year = str_replace("'", "''", $_POST['year']);  //$_POST['year'];
    $CountryId = $_POST['country'];
    $ItemGroupId = $_POST['ItemGroupId'];


    if ($checkVal == "true") {
	$sql = "INSERT INTO t_yearly_country_fundingsource(Year, CountryId, ItemGroupId, FundingSourceId)
                 VALUES ('".$Year."', ".$CountryId.", ".$ItemGroupId.", " .$FundingSourceId.")";
				 
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_yearly_country_fundingsource', 'pks' => array('YearlyFundingSrcId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
		echo json_encode(exec_query($aQuerys, $jUserId, $language));		
    } else {
		$sql = "DELETE FROM t_yearly_country_fundingsource WHERE YearlyFundingSrcId = " . $YearlyFundingSrcId;
		
        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_yearly_country_fundingsource', 'pks' => array('YearlyFundingSrcId'), 'pk_values' => array($YearlyFundingSrcId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);

        echo json_encode(exec_query($aQuerys, $jUserId, $language));
    }
}
function updatePledgedFundingData($conn) {

	$jUserId = $_REQUEST['jUserId'];
	$language = $_REQUEST['language'];	
    $pPledgedFundingId = $_POST['pPledgedFundingId'];
    $pRequirementYear = $_POST['pRequirementYear'];
    $pValue = $_POST['pValue'];
    $Y = 'Y' . $pRequirementYear;
    if (!empty($pPledgedFundingId) && !empty($pRequirementYear) && !empty($pValue)) {
        $sql = "UPDATE t_yearly_pledged_funding SET $Y = '" . $pValue . "' 
		WHERE PledgedFundingId=" . $pPledgedFundingId ;
		
        $aQuery = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_yearly_pledged_funding', 'pks' => array('PledgedFundingId'), 'pk_values' => array($pPledgedFundingId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery);
        echo json_encode(exec_query($aQuerys, $jUserId, $language));
    }
}

function clearCountryProfileData($conn) {
    $CountryId = $_POST['country'];
    $Year = $_POST['year'];
    $CountryProfileType = $_POST['CountryProfileType'];
    $ItemGroupId = $_POST['ItemGroupId'];

    if (!empty($CountryId) && !empty($Year)) {
        if ($CountryProfileType == 1) {
            $sql = "Delete from t_ycprofile where CountryId='" . $CountryId . "' and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "' ;";
            mysql_query($sql, $conn);
            $sql = "Delete from t_yearly_pledged_funding where CountryId='" . $CountryId . "' and Year='" . $Year . "'and ItemGroupId='" . $ItemGroupId . "' ;";
            mysql_query($sql, $conn);
            $sql = "Delete from t_yearly_country_fundingsource where CountryId='" . $CountryId . "' and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "' ;";
            mysql_query($sql, $conn);
        } else if ($CountryProfileType == 3) {
            $sql = "Delete from t_yearly_country_regimen_patient where CountryId='" . $CountryId . "' and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "';";
            mysql_query($sql, $conn);
        } else if ($CountryProfileType == 4) {
            $sql = "Delete from t_yearly_funding_requirements where CountryId='" . $CountryId . "' and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "' ;";
            mysql_query($sql, $conn);
        } else if ($CountryProfileType == 5) {
            $sql = "Delete from t_yearly_pledged_funding where CountryId='" . $CountryId . "' and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "';";
            mysql_query($sql, $conn);
        }
        echo 1;
    } else {
        echo 0;
    }
    // CountryProfileType=1, Basic Information
    // CountryProfileType=2, Regimens
    // CountryProfileType=3, Funding Requirements
}

/* * *********************************************************Itemlist Data*********************************************************** */

function getItemListData($conn) {

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
            $sOrder .= fnColumnToField_itemlist(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
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
					WHERE t_process_tracking.ProcessId = $ProcessId AND t_process_tracking.OutTime IS NULL
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

function fnColumnToField_itemlist($i) {
    if ($i == 0)
        return "ProTrackId";
    elseif ($i == 2)
        return "ItemCode";
    elseif ($i == 3)
        return "ItemName";
    elseif ($i == 4)
        return "ShortName";
      elseif ($i == 5)
        return "bKeyItem";
         elseif ($i == 6)
        return "ProductSubGroupName";
         elseif ($i == 7)
        return "bCommonBasket";
    elseif ($i == 9)
        return "GroupName";
}

function convertToHoursMins($time, $format = '%d:%d') {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
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
            $sOrder .= fnColumnToField_itemlist(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
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
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
    $TrackingNo = $_POST['TrackingNo'];
	$RegNo = $_POST['RegNo'];
	$hTrackingNo = $_POST['hTrackingNo'];
	$ProcessId = $_POST['ProcessId'];
	$ProcessOrder = $_POST['ProcessOrder'];
	$PrevProcessOrder = $_POST['ProcessOrder']-1;
	$ReadyForProOrder = $ProcessOrder + 1;
	$ParentProcessId = $_POST['ParentProcessId'];
	$bNewNo = $_POST['bNewNo'];
	
	
	//echo $RegNo ;
	//exit;
	
	$pTrackingNo = '';
	$pOutTime = '';
	//$result = '';
	
	 $sql = "SELECT TrackingNo, RegNo, OutTime FROM t_process_tracking WHERE TrackingNo = '$TrackingNo' AND ProcessId = $ProcessId;";
	 
	 $result = mysql_query($sql);
	 
	 //echo $result;
	 //exit;
	
	if($result)
		$aData = mysql_fetch_assoc($result);
	
	//var_dump($aData);
	
	if($aData){
		$pTrackingNo = $aData['TrackingNo'];
		$pOutTime = $aData['OutTime'];
	}
	 
	//var_dump($pOutTime);
	 
	//exit;
	
	$sql2 = "SELECT 
	  t_process_tracking.ProTrackId 
	FROM
	  t_process_tracking 
	  INNER JOIN t_process_list 
		ON t_process_tracking.ProcessId = t_process_list.ProcessId		
	WHERE 
		t_process_tracking.TrackingNo = '$TrackingNo' 
		AND t_process_list.ProcessOrder = $PrevProcessOrder;";
  
 // exit;
  
  $result2 = mysql_query($sql2);
  if($result2)
		$aData2 = mysql_fetch_assoc($result2);
	
 //echo $aData2['ProTrackId'];
 //exit;
	$ProTrackId = '';
	if($aData2){
		$ProTrackId = $aData2['ProTrackId'];
		//var_dump($ProTrackId);
	}
 
 
	
	
	
	
    if ($pTrackingNo == '') {
		
		if($ProTrackId != ''){
			$sql2 = "UPDATE t_process_tracking SET OutTime = NOW() WHERE TrackingNo = '$TrackingNo' AND ProTrackId = $ProTrackId;";
			//echo $sql2;
			//exit;
				
			$aQuery2 = array('command' => 'UPDATE', 'query' => $sql2, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo'), 'pk_values' => array("'".$TrackingNo."'"), 'bUseInsetId' => FALSE);
		   
			$aQuerys[] = $aQuery2;
		}
		
        $sql = "INSERT INTO t_process_tracking
            (TrackingNo, RegNo, ProcessId, InTime, EntryDate)
			VALUES ('$TrackingNo', '$RegNo', $ProcessId, NOW(), Now());";
			
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
        $aQuerys[] = $aQuery1;
		
		
		$sql3 = "UPDATE t_process_tracking
				SET RegNo = '$RegNo'
				WHERE TrackingNo = '$TrackingNo';";
			
        $aQuery3 = array('command' => 'INSERT', 'query' => $sql3, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'" . $TrackingNo . "'", $ProcessId), 'bUseInsetId' => TRUE);
        $aQuerys[] = $aQuery3;
		
		echo json_encode(exec_query($aQuerys, $jUserId, $language));
    } else if($pOutTime == '') {
        $sql = "UPDATE t_process_tracking
				SET OutTime = NOW(), ReadyForProOrder = $ReadyForProOrder
				WHERE TrackingNo = '$TrackingNo' AND ProcessId = $ProcessId;";
			
        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_process_tracking', 'pks' => array('TrackingNo', 'ProcessId'), 'pk_values' => array("'".$TrackingNo."'", $ProcessId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
		echo json_encode(exec_query($aQuerys, $jUserId, $language));
    }else if($pTrackingNo != '' && $pOutTime != ''){
		echo json_encode(array('msgType' => 'success', 'msg' => 'This tracking no is already completed.'));	
	
	}   
}

function deleteItemList($conn) {
    $ItemNo = $_POST['ItemNo'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($ItemNo != '') {
        $sql = " DELETE FROM t_itemlist WHERE ItemNo = " . $ItemNo . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_itemlist', 'pks' => array('ItemNo'), 'pk_values' => array($ItemNo), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * ******************************************************Shipment Entry************************************************ */

function getAgencyShipment($conn) {

    global $gTEXT;
	
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $GroupName = 'GroupName';
		    $OwnerTypeName = 'OwnerTypeName';
        }else{
            $GroupName = 'GroupNameFrench';
			$OwnerTypeName = 'OwnerTypeNameFrench';
        }

    $CountryId = $_POST['ACountryId'];
    $AFundingSourceId = $_POST['AFundingSourceId'];
    $ASStatusId = $_POST['ASStatusId'];
    $ItemGroup = $_POST['ItemGroup'];
    $OwnerTypeId = $_POST['OwnerType'];

    if ($CountryId) {
        $CountryId = " WHERE a.CountryId = '" . $CountryId . "' ";
    }
    if ($AFundingSourceId) {
        $AFundingSourceId = " AND a.FundingSourceId = '" . $AFundingSourceId . "' ";
    }
    if ($ASStatusId) {
        $ASStatusId = " AND a.ShipmentStatusId = '" . $ASStatusId . "' ";
    }
    if ($ItemGroup) {
        $ItemGroup = " AND a.ItemGroupId = '" . $ItemGroup . "' ";
    }
    if ($OwnerTypeId) {
        $OwnerTypeId = " AND a.OwnerTypeId = '" . $OwnerTypeId . "' ";
    }
    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_agencyShipment(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " AND (a.ShipmentDate LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
                "a.Qty LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                "$GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                "d.FundingSourceName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                "ItemName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                "ShipmentStatusDesc LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR " .
                "g.$OwnerTypeName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }



    $sql = "SELECT SQL_CALC_FOUND_ROWS AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty, f.$GroupName GroupName,a.ItemGroupId,a.OwnerTypeId, g.$OwnerTypeName OwnerTypeName 
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo 
			INNER JOIN t_itemgroup f ON a.ItemGroupId = f.ItemGroupId 
            INNER JOIN t_owner_type g ON a.OwnerTypeId = g.OwnerTypeId
            " . $CountryId . " " . $AFundingSourceId . " " . $ASStatusId . " " . $ItemGroup . " " . $OwnerTypeId . "
            
			$sWhere $sOrder $sLimit "; //ORDER BY d.FundingSourceName asc  
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

        $ItemName = crnl2br($aRow['ItemName']);
        $OwnerTypeName = crnl2br($aRow['OwnerTypeName']);
        $date = strtotime($aRow['ShipmentDate']);
        $newdate = date('d/m/Y', $date);

        if ($f++)
            $sOutput .= ',';

        $sOutput .= "[";
        $sOutput .= '"' . $aRow['AgencyShipmentId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $ItemName . '",';
        $sOutput .= '"' . $aRow['ShipmentStatusDesc'] . '",';
        $sOutput .= '"' . $newdate . '",';
        $sOutput .= '"' . $aRow['OwnerTypeName'] . '",';
        $sOutput .= '"' . number_format($aRow['Qty']) . '",';
        //$sOutput .= '"' . addslashes($aRow['OwnerTypeName']) . '",';
        $sOutput .= '"' . $y . $z . " " . '",';
        $sOutput .= '"' . $aRow['FundingSourceName'] . '",';
        $sOutput .= '"' . $aRow['FundingSourceId'] . '",';
        $sOutput .= '"' . $aRow['ShipmentStatusId'] . '",';
        $sOutput .= '"' . $aRow['CountryId'] . '",';
        $sOutput .= '"' . $aRow['ItemNo'] . '",';
		$sOutput .= '"' . $aRow['ItemGroupId'] . '",';
		$sOutput .= '"' . $aRow['OwnerTypeId'] . '"';
        
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_agencyShipment($i) {
    if ($i == 2)
        return "f.GroupName ";
    else if ($i == 3)
        return "ItemName ";
    else if ($i == 4)
        return "c.ShipmentStatusDesc ";
    else if ($i == 5)
        return "ShipmentDate ";
    else if ($i == 6)
        return "g.OwnerTypeName ";
    else if ($i == 7)
        return "Qty ";
    else if ($i == 9)
        return "d.FundingSourceName ";
}

function DMYtoYMD($rdateId) {
    $hold = explode('/', $rdateId);
    return $hold[2] . "-" . $hold[1] . "-" . $hold[0];
}

function insertUpdateAgencyShipment($conn) {

    $RecordId = $_POST['RecordId'];
    $CountryId = $_POST['CountryId'];
    $FundingSourceId = $_POST['FundingSourceId'];
    $ItemNo = $_POST['ItemNo'];
    $ShipmentStatusId = $_POST['ShipmentStatusId'];
    $ShipmentDate = DMYtoYMD($_POST['ShipmentDate']);
    $Qty = $_POST['Qty'];
    $OwnerTypeId = $_POST['OwnerTypeId'];
    $ItemGroup = $_POST['ItemGroup'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];
 
    if ($RecordId == '') {

        $sql = "INSERT INTO t_agencyshipment
        ( FundingSourceId, 
        ShipmentStatusId, 
        CountryId, 
        ItemNo, 
        ShipmentDate, 
        Qty,
        ItemGroupId,
        OwnerTypeId)
        VALUES ( '" . $FundingSourceId . "', '" . $ShipmentStatusId . "', '" . $CountryId . "', '" . $ItemNo . "', '" . $ShipmentDate . "', '" . $Qty . "', '" . $ItemGroup . "', '" . $OwnerTypeId . "')";

        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_agencyshipment', 'pks' => array('AgencyShipmentId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {
        $sql = "UPDATE
                 t_agencyshipment SET
                 FundingSourceId = '" . $FundingSourceId . "',
                 ShipmentStatusId = '" . $ShipmentStatusId . "',
                 CountryId = '" . $CountryId . "',
				 ItemGroupId = '" . $ItemGroup . "',
                 ItemNo = '" . $ItemNo . "',
                 ShipmentDate = '" . $ShipmentDate . "',
                 Qty = '" . $Qty . "',
                 OwnerTypeId = '" . $OwnerTypeId . "'
                 WHERE AgencyShipmentId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_agencyshipment', 'pks' => array('AgencyShipmentId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteAgencyShipment($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {
        $sql = " DELETE FROM t_agencyshipment WHERE AgencyShipmentId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_agencyshipment', 'pks' => array('AgencyShipmentId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }
    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * **********************************************************Product Subgroup************************************************* */

function getProductSubGroupData($conn) {

    global $gTEXT;

    $lan = $_POST['lan'];
    if ($lan == 'en-GB') {
        $GroupName = 'GroupName';
    } else {
        $GroupName = 'GroupNameFrench';
    }

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_ProductSubGroup(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }

    $sWhere = "";
    if ($_POST['sSearch'] != "") {
        $sWhere = " WHERE (ProductSubGroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                    OR  $GroupName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }

    $sql = "	SELECT SQL_CALC_FOUND_ROWS ProductSubGroupId, ProductSubGroupName, a.ItemGroupId, $GroupName GroupName 
				FROM t_product_subgroup a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId 
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

        $ProductSubGroupName = crnl2br($aRow['ProductSubGroupName']);

        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aRow['ProductSubGroupId'] . '",';
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $ProductSubGroupName . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $aRow['ItemGroupId'] . '"';
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_ProductSubGroup($i) {
    if ($i == 2)
        return "ProductSubGroupName ";
    else if ($i == 4)
        return "GroupName ";
}

function insertUpdateProductSubGroupData($conn) {

    $RecordId = $_POST['RecordId'];
    $ProductSubGroupName = str_replace("'", "''", $_POST['ProductSubGroupName']);  //$_POST['ProductSubGroupName'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId == '') {
        $sql = "INSERT INTO t_product_subgroup(ProductSubGroupName, ItemGroupId)
                 VALUES ('" . $ProductSubGroupName . "', '" . $ItemGroupId . "')";
        $aQuery1 = array('command' => 'INSERT', 'query' => $sql, 'sTable' => 't_product_subgroup', 'pks' => array('ProductSubGroupId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
        $aQuerys = array($aQuery1);
    } else {

        $sql = "UPDATE t_product_subgroup 
                 SET ProductSubGroupName = '" . $ProductSubGroupName . "',
                 ItemGroupId = '" . $ItemGroupId . "'
                 WHERE ProductSubGroupId = " . $RecordId;

        $aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_product_subgroup', 'pks' => array('ProductSubGroupId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

function deleteProductSubGroupData($conn) {

    $RecordId = $_POST['RecordId'];
    $jUserId = $_REQUEST['jUserId'];
    $language = $_REQUEST['language'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_product_subgroup WHERE ProductSubGroupId = " . $RecordId . " ";

        $aQuery1 = array('command' => 'DELETE', 'query' => $sql, 'sTable' => 't_product_subgroup', 'pks' => array('ProductSubGroupId'), 'pk_values' => array($RecordId), 'bUseInsetId' => FALSE);
        $aQuerys = array($aQuery1);
    }

    echo json_encode(exec_query($aQuerys, $jUserId, $language));
}

/* * *********************************************************Funding Req Data*********************************************************** */

function getFundingReqData($conn) {

    global $gTEXT;
    //$condition='';
//	$sWhere = "";
    $itemGroupId = $_POST['itemGroupId'];
    $serviceTypeId = $_POST['ServiceTypeId'];  //echo $serviceTypeId;
    /* if($itemGroupId!=0){
      $sWhere=" WHERE a.ItemGroupId = '".$itemGroupId."'";
      //	$condition.="  "; //AND a.ServiceTypeId = '".$serviceTypeId."'
      } */

    $sLimit = "";
    if (isset($_POST['iDisplayStart'])) {
        $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_POST['iSortCol_0'])) {
        $sOrder = " ORDER BY  ";
        for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
            $sOrder .= fnColumnToField_fundingreq(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
    }


    $sWhere = "";
    if ($_POST['sSearch'] != "") {

        $sWhere.= "   WHERE (FundingReqSourceName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                        OR FundingReqSourceNameFrench LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
    }
    /* $sWhere = "";
      if ($_POST['sSearch'] != "") {
      $sWhere .= " AND (FundingReqSourceName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
      OR FundingReqSourceNameFrench LIKE '%".mysql_real_escape_string( $_POST['sSearch'] )."%') ";
      } */

    $sql = "SELECT SQL_CALC_FOUND_ROWS FundingReqSourceId, FundingReqSourceName,FundingReqSourceNameFrench, a.ItemGroupId, a.ServiceTypeId
				FROM  t_fundingreqsources a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                INNER JOIN t_servicetype c ON a.ServiceTypeId = c.ServiceTypeId
                AND a.ItemGroupId = " . $itemGroupId . " OR " . $itemGroupId . " = 0
                AND (a.ServiceTypeId = " . $serviceTypeId . " OR " . $serviceTypeId . " = 0)
                $sWhere $sOrder $sLimit "; //echo $sql;

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
        $FundingName = crnl2br($aRow['FundingReqSourceName']);
        //	$GroupName = crnl2br($aRow['GroupName']) ;
        //	$ServiceTypeName = crnl2br($aRow['ServiceTypeName']) ;
        //  $ColorCode = mysql_real_escape_string( '<span style="width:30px;height:15px;display:block;align:center;background:'.$aRow['ColorCode'].';"></span>');


        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $aRow['FundingReqSourceId'] . '",'; //addslashes
        $sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . $FundingName . '",';   //******* $FormulationName. '",';
        $sOutput .= '"' . $aRow['FundingReqSourceNameFrench'] . '",';
        //$sOutput .= '"' . $aRow['GroupName'] . '",';  
        //$sOutput .= '"' . $aRow['ServiceTypeName'] . '",';
        //$sOutput .= '"' . $ColorCode . '",';
        $sOutput .= '"' . $y . $z . '",';
        $sOutput .= '"' . $aRow['ItemGroupId'] . '",';
        $sOutput .= '"' . $aRow['ServiceTypeId'] . '"';
        // $sOutput .= '"' . $aRow['ColorCode'] . '"';	
        $sOutput .= "]";
    }
    $sOutput .= '] }';
    echo $sOutput;
}

function fnColumnToField_fundingreq($i) {
    if ($i == 0)
        return "FundingReqSourceId ";
    else if ($i == 2)
        return "FundingReqSourceName ";
    else if ($i == 3)
        return "FundingReqSourceNameFrench";
}

function insertUpdateFundingReqData($conn) {

    $RecordId = $_POST['RecordId'];
    $FundingName = $_POST['FundingReqSourceName'];
    $FundingNameFrench = $_POST['FundingReqSourceNameFrench'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $ServiceTypeId = $_POST['ServiceTypeId'];

    if ($RecordId == '') {

        $sql = "SELECT MAX(FundingReqSourceId) as M FROM t_fundingreqsources ";
        $qr = mysql_query($sql);
        $r = mysql_fetch_object($qr);
        $Id = $r->M;
        $Id++;
        $sql = ' INSERT INTO t_fundingreqsources(FundingReqSourceId, FundingReqSourceName,FundingReqSourceNameFrench, ItemGroupId, ServiceTypeId)
                 VALUES ("' . $Id . '", "' . $FundingName . '", "' . $FundingNameFrench . '", "' . $ItemGroupId . '", "' . $ServiceTypeId . '")';
    } else {
        $sql = ' UPDATE
                 t_fundingreqsources SET
                 FundingReqSourceName = "' . $FundingName . '",
                 FundingReqSourceNameFrench = "' . $FundingNameFrench . '",
                 ItemGroupId = "' . $ItemGroupId . '",
                 ServiceTypeId = "' . $ServiceTypeId . '"
                 WHERE FundingReqSourceId = ' . $RecordId;
    }

    if (mysql_query($sql, $conn))
        $error = 1;
    else
        $error = 0;

    echo $error;
}

function deleteFundingReqData($conn) {

    $RecordId = $_POST['RecordId'];

    if ($RecordId != '') {

        $sql = " DELETE FROM t_fundingreqsources WHERE FundingReqSourceId = " . $RecordId . " ";

        if (mysql_query($sql)) {
            $error = 1;
        }
        else
            $error = 0;

        echo $error;
    }
}

?>