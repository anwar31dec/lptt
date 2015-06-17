<?php

require ("define.inc");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_connection=utf8");

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {
    case 'getCountryName':
        getCountryName($conn);
        break;
    case 'getYear':
        getYear($conn);
        break;
    case "getMonthList" :
        getMonthList($conn);
        break;
    case 'getItemGroup':
        getItemGroup($conn);
        break;
    case 'getItemList':
        getItemList($conn);
        break;
    case 'getShipmentStatus':
        getShipmentStatus($conn);
        break;
    case "getFundingSource" :
        getFundingSource($conn);
        break;
    case "getYearList" :
        getYearList($conn);
        break;
    case "getUnit" :
        getUnit($conn);
        break;
    case "getDosesForm" :
        getDosesForm($conn);
        break;
    case "getFormulation" :
        getFormulation($conn);
        break;
    case "getFacilityType" :
        getFacilityType($conn);
        break;
    case "getFacilityLevel" :
        getFacilityLevel($conn);
        break;
    case "getRegionList" :
        getRegionList($conn);
        break;
    case "getFormulationByGroup" :
        getFormulationByGroup($conn);
        break;
    case "getServiceType" :
        getServiceType($conn);
        break;
    case "getProductSubGroup" :
        getProductSubGroup($conn);
        break;
    case "getFrequencyList" :
        getFrequencyList($conn);
        break;
    case "getQuadMonthList" :
        getQuadMonthList($conn);
        break;
    case "getItemListByGroup" :
        getItemListByGroup($conn);
        break;
    case "getCasseTypeName" :
        getCasseTypeName($conn);
        break;
    case "getGenderList" :
        getGenderList($conn);
        break;

    default :
        echo "{failure:true}";
        break;
}

function DMYtoYMD($rdateId) {
    $hold = explode('-', $rdateId);
    return $hold[2] . "-" . $hold[1] . "-" . $hold[0];
}

function YMDtoDMY($rdateId) {
    $hold = explode('-', $rdateId);
    return $hold[2] . "-" . $hold[1] . "-" . $hold[0];
}

/* * ********************************************************Combo Data******************************************************** */

function getCountryName($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT CountryId, CountryName 
             FROM t_country 
             ORDER BY CountryName ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getYear($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT YearID, YearName, DefaultYear FROM t_year ORDER BY YearName ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getMonthList($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT MonthId, MonthName FROM t_month ORDER BY MonthId";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getItemGroup($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT ItemGroupId, GroupName FROM t_itemgroup ORDER BY GroupName ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getItemList($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT ItemNo, ItemName FROM t_itemlist ORDER BY ItemName ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getShipmentStatus($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT ShipmentStatusId, ShipmentStatusDesc FROM t_shipmentstatus ORDER BY ShipmentStatusDesc ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getFundingSource($conn) {

    $ItemGroupId = $_REQUEST['ItemGroup'];

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT FundingSourceId, FundingSourceName FROM t_fundingsource where ItemGroupId='" . $ItemGroupId . "' ORDER BY FundingSourceName ";
//echo $sql;
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getCasseTypeName($conn) {

    $FormulationId = $_REQUEST['FormulationId'];
    //echo $FormulationId;
    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT RegMasterId, RegimenName FROM t_regimen_master WHERE t_regimen_master.ItemGroupId = 
(SELECT ItemGroupId FROM t_formulation WHERE FormulationId='".$FormulationId."') ORDER BY RegimenName ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getYearList($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT YearID, YearName FROM t_year ORDER BY YearName ";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getUnit($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT UnitId, UnitName FROM t_unitofmeas ORDER BY  UnitName";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getDosesForm($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT DosesFormId, DosesFormName FROM t_dosesform ORDER BY  DosesFormName";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getFormulation($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT FormulationId, FormulationName, ItemGroupId FROM t_formulation ORDER BY  FormulationName";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getFacilityType($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT FTypeId, FTypeName FROM t_facility_type ORDER BY FTypeName";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getFacilityLevel($conn) {
    
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $FLevelName = 'FLevelName';
        }else{
            $FLevelName = 'FLevelNameFrench';
        }
	
    mysql_query('SET CHARACTER SET utf8');
    
	$sql = " SELECT FLevelId,$FLevelName FLevelName FROM t_facility_level ORDER BY  $FLevelName";

	$result = mysql_query($sql, $conn);
	$total = mysql_num_rows($result);
	while ($r = mysql_fetch_array($result)) {
		$arr[] = $r;
	}
	if ($total == 0)
		echo '[]';
	else
		echo json_encode($arr, JSON_HEX_APOS);
}

function getRegionList($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $CountryId = $_GET['CountryId'];
    //echo $CountryId;

    $sql = " SELECT RegionId, RegionName FROM t_region WHERE CountryId = " . $CountryId . " ORDER BY RegionName";
   // echo $sql;

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getFormulationByGroup($conn) {

    mysql_query('SET CHARACTER SET utf8');
    $ItemGroupId = $_GET['ItemGroupId'];

    $sql = " SELECT FormulationId, FormulationName, ItemGroupId FROM t_formulation WHERE ItemGroupId = " . $ItemGroupId . " ORDER BY  FormulationId asc";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getServiceType($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT ServiceTypeId, ServiceTypeName FROM t_servicetype ORDER BY ServiceTypeName";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getProductSubGroup($conn) {

    mysql_query('SET CHARACTER SET utf8');
    $ItemGroupId = $_REQUEST['ItemGroupId'];

 $sql = " SELECT ProductSubGroupId, ProductSubGroupName FROM t_product_subgroup WHERE ItemGroupId='".$ItemGroupId."' ORDER BY ProductSubGroupName";
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

//22 07 2014 rubel

function getFrequencyList($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $sql = " SELECT FrequencyId, FrequencyName FROM t_frequency ORDER BY FrequencyId asc";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getQuadMonthList($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $FrequencyId = '';
    if (isset($_POST['FrequencyId'])) {
        $FrequencyId = $_POST['FrequencyId'];
    } else if (isset($_GET['FrequencyId'])) {
        $FrequencyId = $_GET['FrequencyId'];
    }
    $sql = "";
    if ($FrequencyId == 1) {
        $sql = " SELECT MonthId, MonthName FROM t_month ORDER BY MonthId";
    } else if ($FrequencyId == 2) {
        $sql = " SELECT MonthId, MonthName FROM t_quarter ORDER BY MonthId";
    }
//echo $sql;

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

//22 07 2014


function getItemListByGroup($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $ItemGroupId = '';
    if (isset($_POST['ItemGroupId'])) {
        $ItemGroupId = $_POST['ItemGroupId'];
    } else if (isset($_GET['ItemGroupId'])) {
        $ItemGroupId = $_GET['ItemGroupId'];
    }

    $sql = " SELECT ItemNo, ItemName FROM t_itemlist where ItemGroupId =" . $ItemGroupId . " ORDER BY ItemName ";
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

function getGenderList($conn) {

    mysql_query('SET CHARACTER SET utf8');

    $GenderTypeId = $_GET['GenderTypeId'];

    $sql = " SELECT GenderTypeId, GenderType FROM t_gendertype WHERE GenderTypeId = " . $GenderTypeId . " ORDER BY GenderType";

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    while ($r = mysql_fetch_array($result)) {
        $arr[] = $r;
    }
    if ($total == 0)
        echo '[]';
    else
        echo json_encode($arr, JSON_HEX_APOS);
}

?>