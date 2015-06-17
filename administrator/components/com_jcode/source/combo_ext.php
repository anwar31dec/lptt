<?php
include ("universal_function_lib_ext.php");

$joomladb = JOOMLA_DBNAME;



function getItemGroupList() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT ItemGroupId, upc_name ItemGroupName FROM itemgroup");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}
function getUserItemGroupList() {
	 global $joomladb;
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT distinct(a.ItemGroupId), a.upc_name ItemGroupName
	 FROM itemgroup a,itemgroupvsusergroup b, ".$joomladb.".lmis_user_usergroup_map c,".$joomladb.".lmis_users d 
	 where b.Id=c.group_id  and a.ItemGroupId=b.ItemGroupId and c.user_id=d.id and c.user_id=".$_COOKIE['auth_userID']."");
	// echo "SELECT distinct(a.ItemGroupId), a.upc_name ItemGroupName
	 // FROM itemgroup a,itemgroupvsusergroup b, ".$joomladb.".lmis_user_usergroup_map c,".$joomladb.".lmis_users d 
	 // where b.Id=c.group_id  and a.ItemGroupId=b.ItemGroupId and c.user_id=d.id and c.user_id=".$_COOKIE['auth_userID']."";
	
	if(mysql_num_rows($result) > 0) {
		
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}
function getItemGroupListAll() {
	$result = mysql_query("SELECT 0 ItemGroupId, 'All' ItemGroupName  FROM DUAL
							UNION 
							SELECT ItemGroupId, upc_name ItemGroupName  FROM itemgroup");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getFormulationList() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT FormulationId, FormulationName FROM formulationtype WHERE ItemGroupId=1 ");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getLabFormulationList() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT FormulationId,FormulationName FROM formulationtype WHERE ItemGroupId=2");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getRegimen() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT RegimenId, RegimenName FROM regimens ");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getItem() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT ItemNo, ItemName FROM itemlist ");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getSubPortal() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT SubPortalId, SubPortalName FROM subportal");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getPatientOverviewMaster() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT POMasterId, POMasterName FROM pomaster");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getFacilityList() {
	mysql_query('SET CHARACTER SET utf8');
	$CountryId = checkNumber($_POST['pCountryId']);
	$ItemGroupId = checkNumber($_POST['pItemGroupId']);	
	
	$query = "SELECT a.FacilityId, a.FacilityName, a.RegionId, IFNULL(a.FacilityCount,0) FacilityCount FROM t_facility as a, t_facility_group_map as b 
		WHERE a.FacilityId=b.FacilityId and CountryId = $CountryId AND b.ItemGroupId = $ItemGroupId";
		
	//echo $query;
	
	$result = mysql_query($query);	
	
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}
function getFacilityTypeList() {
	mysql_query('SET CHARACTER SET utf8');
	
	$result = mysql_query("SELECT FacilityTypeId, FacilityTypeName FROM facility_type");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}
function getRegimenList() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query("SELECT RegimenId, RegimenName FROM regimens");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function UserGroupNameList() {
	$result = mysql_query('SELECT GroupId, GroupName FROM usergroups ORDER BY GroupName asc');
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

// / ORDER BY RegionId asc
function getRegionList() {
	$result = mysql_query("SELECT RegionId, RegionName FROM t_region ORDER BY RegionId asc");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getRegionByCId() {
	$pCountryId = $_POST['pCountryId'];
	$lang = $_POST['lang'];
	
	if($lang == 'fr-FR')
		$RegionName = 'RegionName';
	else if($lang == 'fr-FR')
		$RegionName = 'RegionName';
   
   $query = "SELECT RegionId, RegionName FROM t_region WHERE CountryId = $pCountryId ORDER BY RegionId asc"; 
	
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getAdjustList() {
	$query = "SELECT '0' AdjustId, 'None' AdjustReason FROM dual UNION SELECT AdjustId, AdjustReason FROM t_adjust_reason ORDER BY AdjustId, AdjustReason";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getClStockSource() {
	$query = "SELECT 0 SortId, '0' ClStockSourceId, 'None' SourceName FROM dual UNION SELECT 1 SortId, ClStockSourceId, SourceName FROM t_clstock_source ORDER BY SortId, SourceName";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getAmcChangeReason() {
	$query = "SELECT 0 SortId, '0' AmcChangeReasonId, 'None' AmcChangeReasonName FROM dual UNION SELECT 1 SortId, AmcChangeReasonId, AmcChangeReasonName FROM t_amc_change_reason ORDER BY SortId, AmcChangeReasonName";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}


function getStockOutReason() {
	$query = "SELECT 0 SortId, '0' StockOutReasonId, 'None' StockOutReasonName FROM dual UNION SELECT 1 SortId, StockOutReasonId, StockOutReasonName FROM  t_stock_out_reason ORDER BY SortId, StockOutReasonName";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}


function getServiceArea() {
	$query = "SELECT 0 SortId, '0' ServiceAreaId, 'None' ServiceAreaName FROM dual UNION SELECT 1 SortId, ServiceAreaId, ServiceAreaName FROM t_service_area ORDER BY SortId, ServiceAreaName";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getOwnerType() {
	$query = "SELECT 0 SortId, '0' OwnerTypeId, 'None' OwnerTypeName FROM dual UNION SELECT 1 SortId, OwnerTypeId, OwnerTypeName FROM t_owner_type ORDER BY SortId, OwnerTypeName";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getRegionListAll() {
	$result = mysql_query("SELECT 0 RegionId, 'All' RegionName FROM DUAL
							UNION 
							SELECT RegionId, RegionName FROM region");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getgroup() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT ItemGroupId, upc_name FROM itemgroup WHERE leafnode = '1'");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getgroupMobile() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT ItemGroupId, upc_name FROM itemgroup WHERE leafnode = '1'");
        $nbrows = mysql_num_rows($result);
	if($nbrows > 0) {
		while($rec = mysql_fetch_array($result)) {                    
                    $arr[] = $rec;
		}
		$jsonresult = JEncode($arr);
		echo '{"total":"' . $nbrows . '","results":' . $jsonresult . '}';
	} else {
		echo '{"total":"0", "results":""}';
	}
}


function getgroupFortool() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT ItemGroupId, upc_name FROM itemgroup WHERE leafnode = '1' UNION  SELECT '0', '( All Groups)'");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getunit() {
	$result = mysql_query('SELECT UnitId, UnitName FROM unitofmeas');
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function RouteofAdminNamelist() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT RouteofAdminId, RouteofAdminName FROM routeofadmin");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getDosageForm() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT DosesFormId, DosesFormName FROM dosesform");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getMonth($lang) {
	mysql_query('SET CHARACTER SET utf8');
	
	$monthName = '';
	if($lang == EN_GB)
		$monthName = 'MonthName';
	else if($lang == FR_FR)
		$monthName = 'MonthNameFrench';
	
	$result = mysql_query(" SELECT MonthId, $monthName MonthName FROM t_month Order By MonthId");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}


function getMonthByFrequencyId() {
	$frequencyId = $_POST['FrequencyId'];
	 
	mysql_query('SET CHARACTER SET utf8');
	
	$query = '';
	
	if($frequencyId==1)
		$query = "SELECT MonthId, MonthName FROM t_month Order By MonthId";
	else if($frequencyId==2)
		$query = "SELECT MonthId, MonthName FROM t_quarter Order By MonthId";
	
	$result = mysql_query($query);

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}


function getMonthMobile() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT MonthId, MonthName FROM tmonth Order By MonthId");
        $nbrows = mysql_num_rows($result);
	if($nbrows > 0) {
		while($rec = mysql_fetch_array($result)) {                    
                    $arr[] = $rec;
		}
		$jsonresult = JEncode($arr);
		echo '{"total":"' . $nbrows . '","results":' . $jsonresult . '}';
	} else {
		echo '{"total":"0", "results":""}';
	}
}

function getYear() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT YearName YearId,YearName FROM t_year Order By YearId");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getYearMobile() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT YearName YearId,YearName FROM tyear Order By YearId");
        $nbrows = mysql_num_rows($result);
	if($nbrows > 0) {
		while($rec = mysql_fetch_array($result)) {                    
                    $arr[] = $rec;
		}
		$jsonresult = JEncode($arr);
		echo '{"total":"' . $nbrows . '","results":' . $jsonresult . '}';
	} else {
		echo '{"total":"0", "results":""}';
	}
}

function getItemlistwithstrength() {
	mysql_query('SET CHARACTER SET utf8');
	$result = mysql_query(" SELECT ItemNo,ItemName FROM itemlist WHERE bStrength = 1 Order By ItemName");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

function getKeyItemlistWithStrength() {
	
	mysql_query('SET CHARACTER SET utf8');
	$ItemGroupId = checkNumber($_POST['pItemGroupId']);
	$bStrength = $itemGroupId == 1? 1 : 'NULL';
	$result = mysql_query(" SELECT ItemNo,ItemName FROM itemlist WHERE ItemGroupId=$ItemGroupId  AND (bStrength= $bStrength OR $bStrength is NULL) AND bKeyItem = 1 Order By ItemName");

	if(mysql_num_rows($result) > 0) {

		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;

		}

	}

	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}


function getOrderQtyChangeReason() {
	$query = "SELECT 0 SortId, 0 OUReasonId, 'None' OUReason FROM dual UNION SELECT 1 SortId, OUReasonId, OUReason FROM `t_oderqty_update_reason`  ORDER BY SortId, OUReason";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}
// / ORDER BY RegionId asc
function getItemGroupFrequency() {
	$countryId = $_POST['pCountryId'];
	$result = mysql_query("SELECT
			    t_itemgroup.ItemGroupId
			    , t_itemgroup.GroupName
			    , t_itemgroup.GroupNameFrench
			    , t_reporting_frequency.CountryId
			    , t_reporting_frequency.FrequencyId
			    , t_reporting_frequency.StartMonthId
			    , t_reporting_frequency.StartYearId    
			FROM
			    t_reporting_frequency
			    INNER JOIN t_itemgroup 
			        ON (t_reporting_frequency.ItemGroupId = t_itemgroup.ItemGroupId)
			WHERE (t_reporting_frequency.CountryId = $countryId);");
	if(mysql_num_rows($result) > 0) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}


function getDistrictsByCR() {	
	$countryId = $_POST['pCountryId'];
	$regionId = $_POST['pRegionId'];	

	$query = "SELECT
				    DistrictId
				    , DistrictName
				FROM
				    t_districts
				WHERE (CountryId = $countryId
				    AND RegionId = $regionId);";
					
	$result = mysql_query($query);

	if( mysql_num_rows($result) > 0 ) {
		while($obj = mysql_fetch_object($result)) {
			$arr[] = $obj;
		}
	}
	echo '{rows:' . json_encode($arr, JSON_HEX_APOS) . '}';
}

$task = '';
if(isset($_POST['action'])) {
	$task = $_POST['action'];
}
else if(isset($_GET['action'])) {
	$task = $_GET['action'];
}

$lang = isset($_REQUEST['lang'])? $_REQUEST['lang'] : '';

switch($task) {	
	case "getItemGroupList" :
		getItemGroupList();
		break;
        
	case "getUserItemGroupList" :
		getUserItemGroupList();
		break;
	case "getItemGroupListAll" :
		getItemGroupListAll();
		break;
	case "getFormulation" :
		getFormulationList();
		break;
	case "getLabFormulation" :
		getLabFormulationList();
		break;
	case "getRegimens" :
		getRegimen();
		break;
	case "getItemList" :
		getItem();
		break;
	case "SubportalFetch" :
		getSubPortal();
		break;
	case "getPOMaster" :
		getPatientOverviewMaster();
		break;
	case "getFacility" :
		getFacilityList();
		break;
    case "getFacilityType" :
		getFacilityTypeList();
		break;
	case "getRegimens" :
		getRegimenList();
		break;
	case "UserGroupNameFetch" :
		UserGroupNameList();
		break;
	case "getRegion" :
		getRegionList();
		break;
	case "getAdjust" :
		getAdjustList();
		break;
	case "getRegionAll" :
		getRegionListAll();
		break;
	case "getgroup" :
		getgroup();
		break;
    case "getgroupMobile" :
        getgroupMobile();
        break;
	case "getgroupFortool" :
		getgroupFortool();
		break;
	case "getunit" :
		getunit();
		break;
	case "getdosageformFetch" :
		getDosageForm();
		break;
	case "getRouteofAdminName" :
		RouteofAdminNamelist();
		break;
	case "getMonth" :
		getMonth($lang);
		break;
	case "getMonthByFrequencyId" :
		getMonthByFrequencyId();
		break;		
    case "getMonthMobile" :
        getMonthMobile();
        break;
	case "getYear" :
		getYear();
		break;
    case "getYearMobile" :
        getYearMobile();
        break;
	case "getItemlistwithstrength" :
		getItemlistwithstrength();
		break;
	case "getKeyItemlistWithStrength" :
		getKeyItemlistWithStrength();
		break;            
    case "getClStockSource" :
		getClStockSource();
		break;
	 case "getAmcChangeReason" :
		getAmcChangeReason();
		break;
	case "getServiceArea" :
		getServiceArea();
		break;
	case "getOwnerType" :
		getOwnerType();
		break;
	case "getItemGroupFrequency" :
		getItemGroupFrequency();
		break;
	case "getRegionByCId" :
		getRegionByCId();
		break;
	case "getStockOutReason" :
		getStockOutReason();
		break;
	case "getOrderQtyChangeReason" :
		getOrderQtyChangeReason();
		break;
	default :
		echo "{failure:true}";
		break;
	case "getDistrictsByCR" : // get District list by Country and Region
		getDistrictsByCR();
		break;
	default :
		echo "{failure:true}";
		break;
}
?>

