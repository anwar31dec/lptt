<?php
//require_once('firephp.php');
/* Legend
 * getTable_**_YearForLastMonth($year, $monthId);
 * (**) means following
 * PO: yyyy_patientoverview
 * RP: yyyy_regimenpatient
 * SD: yyyy_stockdata
 * */
include ("universal_function_lib_ext.php");
//include ("joomla_user_info.php");

include ("function_lib.php");

$task = '';
if (isset($_REQUEST['action'])) {
	$task = $_REQUEST['action'];
}

$facilityId = isset($_POST['pFacilityId']) ? $_POST['pFacilityId'] : '';

$fLevelId = isset($_POST['pFLevelId']) ? $_POST['pFLevelId'] : '';

$monthId = isset($_POST['pMonthId']) ? $_POST['pMonthId'] : '';

$year = isset($_POST['pYearId']) ? $_POST['pYearId'] : '';

$countryId = isset($_POST['pCountryId']) ? $_POST['pCountryId'] : '';

//$itemGroupId = isset($_POST['pItemGroupId']) ? $_POST['pItemGroupId'] : '';

$ownerTypeId = isset($_POST['pOwnerTypeId']) ? $_POST['pOwnerTypeId'] : '';

$modified_data = isset($_POST['data']) ? $_POST['data'] : '';
//$formulationId = checkNumber($_POST['pFormulationId']);
$formulationId = isset($_POST['pFormulationId']) ? $_POST['pFormulationId'] : '';
//$reportId = !isset($_POST['pReportId']) ? "undefine" : checkNumber($_POST['pReportId']);

$reportId = isset($_POST['pReportId']) ? $_POST['pReportId'] : '';

$lastMonthDispensed = "(SELECT DispenseQty FROM t_cfm_stockstatus WHERE  `Year` = '" . getYearForLastMonth($year, $monthId) . "' AND MonthId = " . getLastMonth($year, $monthId) . "  AND CountryId = 1 AND FacilityId = $facilityId AND ItemNo = a.ItemNo)";

$beforeLastMonthDispensed = "(SELECT DispenseQty FROM t_cfm_stockstatus WHERE  `Year` = '" . getBeforeLastMonth($year, $monthId) . "' AND MonthId = " . getYearForLast2Month($year, $monthId) . "  AND CountryId = 1 AND FacilityId = $facilityId AND ItemNo = a.ItemNo)";

$userId = isset($_POST['pUserId']) ? $_POST['pUserId'] : '';

$language = isset($_POST['lang']) ? $_POST['lang'] : '';

switch($task) {	
	case "getFacilityWithMonthStatus" :
		getFacilityWithMonthStatus();
		break;	
	case "getPatientOverview" :
		getPatientOverview();
		break;
	case "getRegimens" :
		getRegimens();
		break;
	case "getMasterStockData" :
		getMasterStockData();
		break;
	case "getStockData" :
		getStockData();
		break;
	case "getFacilityRecordOfThisMonth" :
		getFacilityRecordOfThisMonth();
		break;
	case "getFacilityRecordOfPrevMonth" :
		getFacilityRecordOfPrevMonth();
		break;
	case "updatePatientOverview" :
		updatePatientOverview();
		break;
	case "updateRegimenPatient" :
		updateRegimenPatient();
		break;
	case "insertIntoStockData" :
		insertIntoStockData();
		break;
	// case "insertIntoStockData_For_Test" :
	// insertIntoStockData_For_Test();
	// break;
	case "updateStockData" :
		updateStockData();
		break;
	case "updateStockDataAll" :
		updateStockDataAll();
		break;
	case "delete_data_from_yyyy" :
		delete_data_from_yyyy();
		break;
	case "getLmisStartMonthYear" :
		getLmisStartMonthYear();
		break;
	case "changeBsubmittedInMaster" :
		changeBsubmittedInMaster();
		break;
	case "makeUnpublished" :
		makeUnpublished();
		break;
	default :
		echo "{failure:true}";
		break;
}

function getFacilityWithMonthStatus() {
	$monthId = $_REQUEST['pMonthId'];
	$year = $_REQUEST['pYearId'];
	$countryId = $_REQUEST['pCountryId'];
	$regionId = $_REQUEST['pRegionId'];
	$districtId = $_REQUEST['pDistrictId'];
	$ownerTypeId = $_REQUEST['pOwnerTypeId'];
	//$itemGroupId = $_REQUEST['pItemGroupId'];
	$jUserId = $_REQUEST['jUserId'];

	$query = "SELECT
			  a.FacilityId,
			  a.FacilityName,
			  a.FLevelId,
			  a.DistrictName,
			  a.StartMonthId,
			  a.StartYearId,
			  a.SupplyFrom,
			  a.FacilityCount,
			  b.CFMStockId,
			  b.StatusId
			FROM (SELECT
			        t_facility.FacilityId,
			        t_facility.FacilityName,
			        t_facility.FLevelId,
			        t_districts.DistrictName,
			        t_facility_group_map.StartMonthId,
			        t_facility_group_map.StartYearId,
			        t_facility_group_map.SupplyFrom,
			        (SELECT COUNT(*) FROM t_facility_group_map WHERE (SupplyFrom = t_facility.FacilityId))   FacilityCount
			      FROM (SELECT DISTINCT
				              FacilityId,
				              1 AS ItemGroupId,
				              StartMonthId,
				              StartYearId,
				              SupplyFrom
				            FROM t_facility_group_map) t_facility_group_map
			        INNER JOIN t_facility
			          ON (t_facility_group_map.FacilityId = t_facility.FacilityId)
			        INNER JOIN t_country
			          ON (t_facility.CountryId = t_country.CountryId)
			        INNER JOIN t_owner_type
			          ON (t_facility.OwnerTypeId = t_owner_type.OwnerTypeId)
			        INNER JOIN t_region
			          ON (t_facility.RegionId = t_region.RegionId)
			        INNER JOIN t_itemgroup
			          ON (t_facility_group_map.ItemGroupId = t_itemgroup.ItemGroupId)
			        INNER JOIN t_user_itemgroup_map
			          ON (t_user_itemgroup_map.ItemGroupId = t_itemgroup.ItemGroupId)
			        INNER JOIN t_user_country_map
			          ON (t_user_country_map.CountryId = t_country.CountryId)
			        INNER JOIN t_user_owner_type_map
			          ON (t_user_owner_type_map.OwnerTypeId = t_owner_type.OwnerTypeId)
			            AND (t_region.CountryId = t_country.CountryId)
			        INNER JOIN t_user_region_map
			          ON (t_user_region_map.RegionId = t_region.RegionId)
			        INNER JOIN t_districts
			          ON (t_districts.CountryId = t_country.CountryId)
			            AND (t_districts.RegionId = t_region.RegionId)
			            AND (t_facility.DistrictId = t_districts.DistrictId)
			      WHERE (t_facility_group_map.ItemGroupId = 1
			             AND t_user_itemgroup_map.UserId = '$jUserId'
			             AND t_user_country_map.UserId = '$jUserId'
			             AND t_user_country_map.CountryId = $countryId
			             AND t_user_owner_type_map.UserId = '$jUserId'
			             AND t_user_owner_type_map.OwnerTypeId = $ownerTypeId
			             AND t_user_region_map.UserId = '$jUserId'
			             AND t_user_region_map.RegionId = $regionId
						 AND t_districts.DistrictId = $districtId)) a
			  INNER JOIN (SELECT
			               CFMStockId,
			               FacilityId,
			               StatusId
			             FROM t_cfm_masterstockstatus
			             WHERE t_cfm_masterstockstatus.Year = '$year'
			                 AND t_cfm_masterstockstatus.MonthId = $monthId
			                 AND t_cfm_masterstockstatus.CountryId = $countryId
							 AND t_cfm_masterstockstatus.StatusId = 5) b
			    ON (a.FacilityId = b.FacilityId)";

	 //echo $query;

	//getJsonAll($query);

	if (isset($_POST['query'])) {
		$query .= " WHERE (a.FacilityName LIKE '%" . $_POST['query'] . "%' OR a.DistrictName LIKE '%" . $_POST['query'] . "%')";
	}

	// echo $query;

	//getJsonAll($query);

	$result = mysql_query($query);
	$nbrows = mysql_num_rows($result);
	$start = (integer)(isset($_POST['start']) ? $_POST['start'] : $_GET['start']);
	$end = (integer)(isset($_POST['limit']) ? $_POST['limit'] : $_GET['limit']);
	$limit = $query . " LIMIT " . $start . "," . $end;
	$result = mysql_query($limit);

	//echo $limit;

	$output = array();

	if ($nbrows > 0) {
		while ($row = mysql_fetch_array($result)) {
			$output[] = $row;
		}
		echo '({"total":"' . $nbrows . '","results":' . json_encode($output) . '})';
	} else {
		echo '({"total":"0", "results":""})';
	}

}

function getPatientOverview() {
	global $facilityId, $monthId, $year, $reportId;
	$query = "SELECT 	b.CFMPOId,
						a.FormulationName PatientTypeName, 
						b.RefillPatient, 
						b.NewPatient, 
						b.TotalPatient
						FROM t_formulation a INNER JOIN t_cfm_patientoverview b ON a.FormulationId = b.FormulationId ";
	$query .= "WHERE b.CFMStockId = $reportId and  FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'];
	$query .= " ORDER BY b.CFMPOId desc";

	//echo $query;

	getJsonAll($query);
}

function getRegimens_new() {

	global $facilityId, $monthId, $year, $formulationId, $reportId;

	//$metaData;

	//$columns = array();

	$columns[] = array('name' => 'Column1', 'type' => 'int', 'mapping' => 'Column1');
	$columns[] = array('name' => 'Column2', 'type' => 'int', 'mapping' => 'Column1');
	$columns[] = array('name' => 'Column3', 'type' => 'int', 'mapping' => 'Column1');

	$records[] = array('Column1' => 100, 'Column2' => 200, 'Column3' => 300);
	$records[] = array('Column1' => 500, 'Column2' => 400, 'Column3' => 600);
	$records[] = array('Column1' => 700, 'Column2' => 500, 'Column3' => 700);

	$metaData['metaData'] = array('totalProperty' => 'totalCount', 'root' => 'records', 'successProperty' => 'success', 'fields' => $columns);

	$metaData['success'] = TRUE;
	$metaData['totalCount'] = 3;
	$metaData['records'] = $records;

	echo json_encode($metaData);

	// echo '{
	// "metaData": {
	// "totalProperty": "totalCount",
	// "root": "records",
	// "successProperty": "success",
	// "fields": [
	// {
	// "name": "Column1",
	// "type": "string"
	// }, {
	// "name": "Column2",
	// "type": "string"
	// }, {
	// "name": "Column3",
	// "type": "string"
	// }
	// ]
	// },
	// "success": true,
	// "totalCount": 5,
	// "records": [
	// {
	// "Column1": "Number of New Adults",
	// "Column2": "200",
	// "Column3": "300"
	// }
	// ]
	// }';

	//
	// echo "{\"metaData\" : {
	// totalProperty : 'totalCount',
	// root : 'records',
	// successProperty : \"success\",fields : [{name: 'Patient_Overview', type : 'string'},{name: 'JAN_2013', type : 'int'},{name: 'JUN_2013', type : 'int'},{name: 'JUL_2013', type : 'int'},{name: 'Total_', type : 'int'}]},
	// \"success\": true,
	// \"totalCount\" : \"5\",\"records\" : [{Patient_Overview: 'Number of New Adults',JAN_2012: '726',FEB_2012: '991',MAR_2012: '1168',APR_2012: '11',MAY_2012: '0',JAN_2013: '0',JUN_2013: '185',JUL_2013: '0', Total_: '3081'},{Patient_Overview: 'Number of Refill Adults',JAN_2012: '27034',FEB_2012: '32914',MAR_2012: '34165',APR_2012: '23',MAY_2012: '0',JAN_2013: '0',JUN_2013: '6685',JUL_2013: '0', Total_: '100821'},{Patient_Overview: 'Number of New Paeds',JAN_2012: '69',FEB_2012: '203',MAR_2012: '105',APR_2012: '0',MAY_2012: '0',JAN_2013: '0',JUN_2013: '110',JUL_2013: '0', Total_: '487'},{Patient_Overview: 'Number of Refill Paeds',JAN_2012: '2577',FEB_2012: '2437',MAR_2012: '3468',APR_2012: '0',MAY_2012: '0',JAN_2013: '0',JUN_2013: '504',JUL_2013: '0', Total_: '8986'},{Patient_Overview: 'Number of Pre-ART Patients',JAN_2012: '1360',FEB_2012: '1959',MAR_2012: '3223',APR_2012: '0',MAY_2012: '0',JAN_2013: '0',JUN_2013: '3131',JUL_2013: '0', Total_: '9673'},{Patient_Overview: 'TOTAL',JAN_2012:'31766',FEB_2012:'38504',MAR_2012:'42129',APR_2012:'34',MAY_2012:'0',JAN_2013:'0',JUN_2013:'10615',JUL_2013:'0',Total_:'123048'}]}";

	// echo '{
	// "metaData": {
	// "totalProperty": "totalCount",
	// "root": "records",
	// "successProperty": "success",
	// "fields": [
	// {
	// "name": "Patient_Overview",
	// "type": "string"
	// }, {
	// "name": "JAN_2013",
	// "type": "int"
	// }, {
	// "name": "JUN_2013",
	// "type": "int"
	// }, {
	// "name": "JUL_2013",
	// "type": "int"
	// }, {
	// "name": "Total_",
	// "type": "int"
	// }
	// ]
	// },
	// "success": true,
	// "totalCount": "5",
	// "records": [
	// {
	// "Patient_Overview": "Number of New Adults",
	// "JAN_2012": "726",
	// "FEB_2012": "991",
	// "MAR_2012": "1168",
	// "APR_2012": "11",
	// "MAY_2012": "0",
	// "JAN_2013": "0",
	// "JUN_2013": "185",
	// "JUL_2013": "0",
	// "Total_": "3081"
	// }, {
	// "Patient_Overview": "Number of Refill Adults",
	// "JAN_2012": "27034",
	// "FEB_2012": "32914",
	// "MAR_2012": "34165",
	// "APR_2012": "23",
	// "MAY_2012": "0",
	// "JAN_2013": "0",
	// "JUN_2013": "6685",
	// "JUL_2013": "0",
	// "Total_": "100821"
	// }, {
	// "Patient_Overview": "Number of New Paeds",
	// "JAN_2012": "69",
	// "FEB_2012": "203",
	// "MAR_2012": "105",
	// "APR_2012": "0",
	// "MAY_2012": "0",
	// "JAN_2013": "0",
	// "JUN_2013": "110",
	// "JUL_2013": "0",
	// "Total_": "487"
	// }, {
	// "Patient_Overview": "Number of Refill Paeds",
	// "JAN_2012": "2577",
	// "FEB_2012": "2437",
	// "MAR_2012": "3468",
	// "APR_2012": "0",
	// "MAY_2012": "0",
	// "JAN_2013": "0",
	// "JUN_2013": "504",
	// "JUL_2013": "0",
	// "Total_": "8986"
	// }, {
	// "Patient_Overview": "Number of Pre-ART Patients",
	// "JAN_2012": "1360",
	// "FEB_2012": "1959",
	// "MAR_2012": "3223",
	// "APR_2012": "0",
	// "MAY_2012": "0",
	// "JAN_2013": "0",
	// "JUN_2013": "3131",
	// "JUL_2013": "0",
	// "Total_": "9673"
	// }, {
	// "Patient_Overview": "TOTAL",
	// "JAN_2012": "31766",
	// "FEB_2012": "38504",
	// "MAR_2012": "42129",
	// "APR_2012": "34",
	// "MAY_2012": "0",
	// "JAN_2013": "0",
	// "JUN_2013": "10615",
	// "JUL_2013": "0",
	// "Total_": "123048"
	// }
	// ]
	// }';

	//
	// $columns = "fields : [";
	//
	// $records = "\"records\" : [";
	// $record = "{";
	//
	// $query = "SELECT b.CFMPatientStatusId, a.RegimenName, c.FormulationName, b.RefillPatient, b.NewPatient, b.TotalPatient
	// FROM t_regimen a
	// INNER JOIN  t_cfm_regimenpatient b ON a.RegimenId = b.RegimenId
	// INNER JOIN t_formulation c ON a.FormulationId = c.FormulationId ";
	// $query .= "WHERE b.CFMStockId = $reportId and FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'] . " AND b.ItemGroupId = " . $_POST['pItemGroupId'];
	//
	// $query .= " order by b.CFMPatientStatusId desc";
	//
	// //echo $query;
	//
	// getJsonAll($query);
}

function getRegimens_old() {

	global $facilityId, $monthId, $year, $formulationId, $reportId;

	$query = "SELECT b.CFMPatientStatusId, a.RegimenName, c.FormulationName, b.RefillPatient, b.NewPatient, b.TotalPatient 
			FROM t_regimen a 
			INNER JOIN  t_cfm_regimenpatient b ON a.RegimenId = b.RegimenId 
			INNER JOIN t_formulation c ON a.FormulationId = c.FormulationId ";
	$query .= "WHERE b.CFMStockId = $reportId and FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'] . " AND b.ItemGroupId = " . $_POST['pItemGroupId'];

	$query .= " order by b.CFMPatientStatusId desc";

	//echo $query;

	getJsonAll($query);
}

function getRegimens() {

	global $facilityId, $monthId, $year, $formulationId, $reportId, $countryId;

	$query = "SELECT
			  b.CFMPatientStatusId,
			  c.FormulationId,
			  c.FormulationName,
			  b.RegimenId,
			  d.RegimenName RegimenMasterName,
			  a.GenderTypeId,
			  b.RefillPatient,
			  b.NewPatient,
			  b.TotalPatient
			FROM t_regimen a
			  INNER JOIN t_cfm_regimenpatient b
			    ON a.RegimenId = b.RegimenId
			  INNER JOIN t_formulation c
			    ON a.FormulationId = c.FormulationId
			INNER JOIN t_regimen_master d
			    ON a.RegMasterId = d.RegMasterId
			WHERE b.CFMStockId = $reportId
			    AND FacilityId = $facilityId
			    AND MonthId = $monthId
			    AND `Year` = '$year'
			    AND CountryId = $countryId
			ORDER BY c.FormulationName, d.RegimenName, a.GenderTypeId";

	$result = mysql_query($query);

	$rows = array();

	while ($row = mysql_fetch_assoc($result)) {
		$rows[] = $row;
	}

	$formulationIds = array();

	foreach ($rows as $row) {
		foreach ($row as $key => $value) {
			if ($key == 'FormulationId')
				$formulationIds[$value] = $value;
		}
	}

	$data = array();
	foreach ($formulationIds as $formulationId) {
		$datum = array();
		foreach ($rows as $row) {
			if ($formulationId == $row['FormulationId']) {
				$datum['FormulationId'] = $row['FormulationId'];
				$datum['FormulationName'] = $row['FormulationName'];
				if ($row['RegimenMasterName'] == '(0-4 Years)' && $row['GenderTypeId'] == 'M') {
					$datum['C0to4M'] = $row['RefillPatient'];
					$datum['C0to4M_Id'] = $row['CFMPatientStatusId'];
				} else if ($row['RegimenMasterName'] == '(0-4 Years)' && $row['GenderTypeId'] == 'F') {
					$datum['C0to4F'] = $row['RefillPatient'];
					$datum['C0to4F_Id'] = $row['CFMPatientStatusId'];
				} else if ($row['RegimenMasterName'] == '(5-14 Years)' && $row['GenderTypeId'] == 'M') {
					$datum['C5to14M'] = $row['RefillPatient'];
					$datum['C5to14M_Id'] = $row['CFMPatientStatusId'];
				} else if ($row['RegimenMasterName'] == '(5-14 Years)' && $row['GenderTypeId'] == 'F') {
					$datum['C5to14F'] = $row['RefillPatient'];
					$datum['C5to14F_Id'] = $row['CFMPatientStatusId'];
				} else if ($row['RegimenMasterName'] == '(15+ Years)' && $row['GenderTypeId'] == 'M') {
					$datum['C15PlusM'] = $row['RefillPatient'];
					$datum['C15PlusM_Id'] = $row['CFMPatientStatusId'];
				} else if ($row['RegimenMasterName'] == '(15+ Years)' && $row['GenderTypeId'] == 'F') {
					$datum['C15PlusF'] = $row['RefillPatient'];
					$datum['C15PlusF_Id'] = $row['CFMPatientStatusId'];
				} else if ($row['RegimenMasterName'] == 'Pregnant women' && $row['GenderTypeId'] == 'F') {
					$datum['PregnantWomen'] = $row['RefillPatient'];
					$datum['PregnantWomen_Id'] = $row['CFMPatientStatusId'];
				}
			}
		}
		$data[] = $datum;
	}

	$output['total'] = 8;
	$output['results'] = $data;

	echo json_encode($output);
}

function getMasterStockData() {
	global $facilityId, $monthId, $year, $reportId;

	$query = "SELECT CFMStockId, FacilityId, MonthId, Year, 
	(SELECT b.name FROM  ykx9st_users b WHERE b.username = a.CreatedBy) CreatedBy, DATE_FORMAT(CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,
	(SELECT b.name FROM  ykx9st_users b WHERE b.username = a.LastUpdateBy)  LastUpdateBy,	
	(SELECT b.name FROM ykx9st_users b WHERE b.username = a.LastSubmittedBy) LastSubmittedBy ,
	c.StatusId, c.StatusName,
	DATE_FORMAT(LastUpdateDt, '%d-%b-%Y %h:%i %p') LastUpdateDt,	
	DATE_FORMAT(LastSubmittedDt, '%d-%b-%Y %h:%i %p') LastSubmittedDt,	
	DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,	
	(SELECT b.name FROM ykx9st_users b WHERE b.username = a.PublishedBy) PublishedBy ,
	DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt 	
	FROM t_cfm_masterstockstatus a LEFT JOIN t_status c ON a.StatusId = c.StatusId ";
	$query .= " WHERE FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'];

	//echo $query;
	getJsonAll($query);

}

function getStockData() {
	global $facilityId, $monthId, $year, $beforeLastMonthDispensed, $lastMonthDispensed, $reportId;
	$query = "SELECT a.CFMStockStatusId, a.FacilityId, a.MonthId, a.Year, a.ItemGroupId, b.ItemSL, a.ItemNo, b.ItemName, OpStock OpStock_A, a.OpStock_C, a.ReceiveQty, a.DispenseQty, 
	IFNULL($beforeLastMonthDispensed,0) BeforeLastMonthDispensed, IFNULL($lastMonthDispensed,0) LastMonthDispensed, a.AdjustQty, a.AdjustId AdjustReason";
	$query .= ",a.StockoutDays, a.StockOutReasonId, a.ClStock ClStock_A, a.ClStock_C, a.ClStockSourceId, a.MOS, a.AMC, a.AMC_C
	, a.AmcChangeReasonId, a.MaxQty, a.OrderQty, a.ActualQty, a.OUReasonId, 
	 a.UserId, a.LastEditTime, c.ProductSubGroupName FormulationName FROM t_cfm_stockstatus a, t_itemlist b, t_product_subgroup c ";
	$query .= " WHERE a.CFMStockId = $reportId
			    AND `YEAR` = '$year'
			    AND MonthId = $monthId
			    AND CountryId = 1
			    AND a.FacilityId =  $facilityId
			    AND a.ItemNo = b.ItemNo
			    AND b.ProductSubGroupId = c.ProductSubGroupId ";
	$query .= " ORDER BY b.ItemSL asc";

	//echo $query;

	getJson($query);
}

function getFacilityRecordOfThisMonth() {
	global $facilityId, $monthId, $year, $reportId;

	$query = "SELECT COUNT(*) as totalrec ";
	$query .= "FROM " . getTable_MSD_YearForCurrentMonth($year) . " a";
	$query .= " WHERE a.CFMStockId = " . $reportId . " AND FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' and ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = " . $_POST['pCountryId'];

	//echo $query;

	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result))
		$total = $row['totalrec'];
	echo(isset($total) || empty($total)) ? '{totalrec =' . $total . '}' : '{totalrec = 0}';
}

function getFacilityRecordOfPrevMonth() {
	global $facilityId, $monthId, $year, $prevMonth, $prevYear, $reportId;

	$query = "SELECT COUNT(*) as prev_month_report_unpub ";
	$query .= "FROM " . getTable_MSD_YearForLastMonth($year, $monthId) . " a ";
	$query .= " WHERE FacilityId = " . $facilityId . " and MonthId = " . getLastMonth($year, $monthId) . " and Year = '" . getYearForLastMonth($year, $monthId) . "' and a.StatusId < 5 " . " AND CountryId = " . $_POST['pCountryId'];
	//echo $query;
	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result))
		$total = $row['prev_month_report_unpub'];

	echo(isset($total) || empty($total)) ? '{prev_month_report_unpub =' . $total . '}' : '{prev_month_report_unpub = 0}';

	$query1 = "SELECT COUNT(*) as prev_month_report ";
	$query1 .= "FROM " . getTable_MSD_YearForLastMonth($year, $monthId) . " a ";
	$query1 .= " WHERE FacilityId = " . $facilityId . " and MonthId = " . getLastMonth($year, $monthId) . " and Year = '" . getYearForLastMonth($year, $monthId) . "' " . " AND CountryId = " . $_POST['pCountryId'];

	$result1 = mysql_query($query1);

	while ($row = mysql_fetch_assoc($result1))
		$total1 = $row['prev_month_report'];

	echo(isset($total1) || empty($total1)) ? '{prev_month_report =' . $total1 . '}' : '{prev_month_report = 0}';

}

function insertIntoStockData_For_Test() {
	// global 	$facilityId, $monthId, $year, $beforeLastMonthDispensed, $reportId, $userId;
	//
	//
	// $childCount	= 0;
	//
	// $sql3 = "INSERT INTO ".getTable_MSD_YearForCurrentMonth($year)
	// ." (FacilityId, CountryId, MonthId, Year, ItemGroupId, CreatedBy, CreatedDt, LastUpdateBy, LastUpdateDt, bSubmitted, StatusId ) "
	// ."VALUES ($facilityId, ". $_POST['pCountryId'] .", $monthId, '$year', ".$_POST['pItemGroupId'].", '" . $userId ."',Now(), '" . $userId ."',Now(), 0, 1)";
	//
	// echo $sql3;
	//
	// $sql1 = "INSERT INTO t_cfm_patientoverview (CFMStockId, FormulationId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient, ItemGroupId) "
	// . "SELECT $reportId, a.FormulationId, " . $facilityId . ", ".$_POST['pCountryId'].", " . $monthId.",'" . $year ."',". " 0, 0, 0, ItemGroupId FROM t_formulation a WHERE ItemGroupId = " . $_POST['pItemGroupId'];
	//
	// echo $sql1 ;
	//
	//
	// $sql2 = "INSERT INTO ".getTable_RP_YearForCurrentMonth($year)." (CFMStockId, RegimenId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient, ItemGroupId) "
	// . "SELECT $reportId, RegimenId, " . $facilityId . ", CountryId, " . $monthId.",'" . $year ."',". " 0, 0, 0, ItemGroupId FROM t_country_regimen WHERE CountryId = " . $_POST['pCountryId'] . " AND a.ItemGroupId = " . $_POST['pItemGroupId'];
	//
	// echo $sql2;
	//
	//
	//
	// $sql4 = "INSERT INTO ".getTable_SD_YearForCurrentMonth($year)." (CFMStockId, FacilityId, CountryId, MonthId, Year, ItemNo, ItemGroupId, OpStock,
	// ReceiveQty, DispenseQty, "
	// . "AdjustQty, AdjustId, StockoutDays, ClStock, MOS, AMC, MaxQty, OrderQty, ActualQty, UserId, LastEditTime) "
	// . "SELECT $reportId," . $facilityId . "," . $_POST['pCountryId'] . ", " . $monthId.",'" . $year ."', a.ItemNo, " . $_POST['pItemGroupId'] .", ClStock, NULL, NULL, NULL, '', NULL, 0, "
	// ."FORMAT(ClStock/FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2),2)
	// , FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2)
	// , FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2)*3
	// , FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2)*3 - ClStock
	// ,FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2)*3 - ClStock, 0, now() "
	// . "FROM t_country_product a LEFT JOIN ".getTable_SD_YearForLastMonth($year, $monthId)." b ON a.ItemNo = b.ItemNo and b.FacilityId = "
	// . $facilityId . " and MonthId = " . getLastMonth($year, $monthId) . " and Year = '"
	// . getYearForLastMonth($year, $monthId) ."' AND a.CountryId = b.CountryId AND a.ItemGroupId = b.ItemGroupId "
	// . " WHERE a.CountryId = " .$_POST['pCountryId']. " AND a.ItemGroupId = " . $_POST['pItemGroupId'];
	//
	// echo $sql4;

}

//"SELECT $reportId, a.FormulationId, " . $facilityId . ", ".$_POST['pCountryId'].", " . $monthId.",'" . $year ."',". " 0, 0, 0, ItemGroupId FROM t_formulation a WHERE ItemGroupId = " . $_POST['pItemGroupId'];

function insertIntoStockData() {
	global $facilityId, $monthId, $year, $beforeLastMonthDispensed, $reportId, $userId, $language;

	$query1 = "INSERT INTO  t_cfm_masterstockstatus " . " (FacilityId, CountryId, MonthId, Year, CreatedBy, CreatedDt, LastUpdateBy, LastUpdateDt, bSubmitted, StatusId ) " . "VALUES ($facilityId, " . $_POST['pCountryId'] . ", $monthId, '$year', '" . $userId . "',Now(), '" . $userId . "',Now(), 0, 1)";

	$aQuery1 = array('command' => 'INSERT', 'query' => $query1, 'sTable' => 't_cfm_masterstockstatus', 'pks' => array('CFMStockId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);

	$query2 = "INSERT INTO t_cfm_patientoverview (CFMStockId, FormulationId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient, ItemGroupId) " . "SELECT [LastInsertedId], a.FormulationId, " . $facilityId . ", " . $_POST['pCountryId'] . ", " . $monthId . ",'" . $year . "'," . " 0, 0, 0, ItemGroupId FROM t_formulation a WHERE ItemGroupId = 1";

	$aQuery2 = array('command' => 'INSERT', 'query' => $query2, 'sTable' => 't_cfm_patientoverview', 'pks' => array('CFMPOId'), 'pk_values' => array(), 'bUseInsetId' => FALSE);

	$query3 = "INSERT INTO t_cfm_regimenpatient (CFMStockId, RegimenId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient, ItemGroupId) " . "SELECT [LastInsertedId], RegimenId, " . $facilityId . ", CountryId, " . $monthId . ",'" . $year . "'," . " 0, 0, 0, ItemGroupId FROM t_country_regimen WHERE CountryId = " . $_POST['pCountryId'] . " AND ItemGroupId = 1";

	$aQuery3 = array('command' => 'INSERT', 'query' => $query3, 'sTable' => 't_cfm_regimenpatient', 'pks' => array('CFMPatientStatusId'), 'pk_values' => array(), 'bUseInsetId' => FALSE);

	$query4 = "INSERT INTO t_cfm_stockstatus (CFMStockId, FacilityId, CountryId, MonthId, Year, ItemNo, ItemGroupId,
		 OpStock_C, OpStock, ReceiveQty, DispenseQty, " . "AdjustQty, AdjustId, StockoutDays, ClStock, MOS, AMC, MaxQty, OrderQty, ActualQty, UserId, LastEditTime) " . "SELECT [LastInsertedId]," . $facilityId . "," . $_POST['pCountryId'] . ", " . $monthId . ",'" . $year . "', a.ItemNo, a.ItemGroupId, ClStock, ClStock, NULL, NULL, NULL, NULL, NULL, NULL, " . "NULL
		, NULL
		, NULL
		, NULL
		,NULL, NULL, now() " . "FROM t_country_product a LEFT JOIN t_cfm_stockstatus b ON a.ItemNo = b.ItemNo and b.FacilityId = " . $facilityId . " and MonthId = " . getLastMonth($year, $monthId) . " and Year = '" . getYearForLastMonth($year, $monthId) . "' AND a.CountryId = b.CountryId AND a.ItemGroupId = b.ItemGroupId " . " WHERE a.CountryId = " . $_POST['pCountryId'] . " AND a.bActive = 1";

	$aQuery4 = array('command' => 'INSERT', 'query' => $query4, 'sTable' => 't_cfm_stockstatus', 'pks' => array('CFMStockStatusId'), 'pk_values' => array(), 'bUseInsetId' => FALSE);

	//$aQuerys = array($aQuery1, $aQuery2, $aQuery3, $aQuery4);
	$aQuerys = array($aQuery1, $aQuery2, $aQuery3, $aQuery4);

	//print_r($aQuerys);

	$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);

	//print_r($msg);

	if ($msg['msgType'] == 'success') {
		update_parent_amc_mos($facilityId, $monthId, $year);

		echo '{success = 1; reportId = ' . $msg['CFMStockId'] . '}';
	} else
		echo '{success = 0; error = "Error"}';

	// }
	// catch (Exception $e)
	// {
	// mysql_query('ROLLBACK;');
	// echo '{ success = 0; error = "' . $e->getMessage() .'" }';
	// }
}

function insertIntoStockData_Old_23_11_2014() {
	global $facilityId, $monthId, $year, $beforeLastMonthDispensed, $reportId, $userId;

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		$childCount = 0;

		$sql3 = "INSERT INTO  t_cfm_masterstockstatus " . " (FacilityId, CountryId, MonthId, Year, ItemGroupId, CreatedBy, CreatedDt, LastUpdateBy, LastUpdateDt, bSubmitted, StatusId ) " . "VALUES ($facilityId, " . $_POST['pCountryId'] . ", $monthId, '$year', " . $_POST['pItemGroupId'] . ", '" . $userId . "',Now(), '" . $userId . "',Now(), 0, 1)";

		//fb($sql3);

		$reportId = 0;

		$result3 = mysql_query($sql3);
		if ($result3) {
			$reportId = mysql_insert_id();
		}
		$error .= mysql_error() . "</br>" . $sql3;

		$sql1 = "INSERT INTO t_cfm_patientoverview (CFMStockId, FormulationId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient, ItemGroupId) " . "SELECT $reportId, a.FormulationId, " . $facilityId . ", " . $_POST['pCountryId'] . ", " . $monthId . ",'" . $year . "'," . " 0, 0, 0, ItemGroupId FROM t_formulation a WHERE ItemGroupId = " . $_POST['pItemGroupId'];

		//echo ($sql1);

		$result1 = mysql_query($sql1);
		$error = mysql_error() . "</br>" . $sql1;

		$sql2 = "INSERT INTO t_cfm_regimenpatient (CFMStockId, RegimenId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient, ItemGroupId) " . "SELECT $reportId, RegimenId, " . $facilityId . ", CountryId, " . $monthId . ",'" . $year . "'," . " 0, 0, 0, ItemGroupId FROM t_country_regimen WHERE CountryId = " . $_POST['pCountryId'] . " AND ItemGroupId = " . $_POST['pItemGroupId'];

		//fb($sql2);

		$result2 = mysql_query($sql2);
		$error .= mysql_error() . "</br>" . $sql2;

		$sql4 = "INSERT INTO " . getTable_SD_YearForCurrentMonth($year) . " (CFMStockId, FacilityId, CountryId, MonthId, Year, ItemNo, ItemGroupId,
		 OpStock_C, OpStock, ReceiveQty, DispenseQty, " . "AdjustQty, AdjustId, StockoutDays, ClStock, MOS, AMC, MaxQty, OrderQty, ActualQty, UserId, LastEditTime) " . "SELECT $reportId," . $facilityId . "," . $_POST['pCountryId'] . ", " . $monthId . ",'" . $year . "', a.ItemNo, " . $_POST['pItemGroupId'] . ", ClStock, ClStock, NULL, NULL, NULL, NULL, NULL, NULL, " . "NULL
		, NULL
		, NULL
		, NULL
		,NULL, NULL, now() " . "FROM t_country_product a LEFT JOIN " . getTable_SD_YearForLastMonth($year, $monthId) . " b ON a.ItemNo = b.ItemNo and b.FacilityId = " . $facilityId . " and MonthId = " . getLastMonth($year, $monthId) . " and Year = '" . getYearForLastMonth($year, $monthId) . "' AND a.CountryId = b.CountryId AND a.ItemGroupId = b.ItemGroupId " . " WHERE a.CountryId = " . $_POST['pCountryId'] . " AND a.ItemGroupId = " . $_POST['pItemGroupId'];

		//fb($sql4);

		$result4 = mysql_query($sql4);
		$error .= mysql_error() . $sql4;

		$bResult = $result1 && $result2 && $result3 && $result4;

		if (!$bResult)
			throw new Exception("Query error:</br>" . mysql_real_escape_string($error));

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

		///getSumOfChildOfPatient($facilityId, $monthId, $year);
		//getSumOfChildOfRegimen($facilityId, $monthId, $year);
		//getSumOfChildOfStockStatus($facilityId, $monthId, $year);

		echo '{success = 1; reportId =' . $reportId . '}';
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
		echo '{ success = 0; error = "' . $e -> getMessage() . '" }';
	}
}

function DeleteNationalStockStatus() {
	global $facilityId, $monthId, $year, $reportId, $userId, $countryId;

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		$sql1 = "DELETE FROM t_cnm_patientoverview WHERE CountryId = $countryId AND MonthId = $monthId AND Year = '$year' AND ItemGroupId = $itemGroupId";

		$result1 = mysql_query($sql1);
		$error = mysql_error() . "</br>";

		$sql2 = "DELETE FROM t_cnm_regimenpatient WHERE CountryId = $countryId AND MonthId = $monthId AND Year = '$year' AND ItemGroupId = $itemGroupId";

		$result2 = mysql_query($sql2);
		$error = mysql_error() . "</br>";

		$sql3 = "DELETE FROM t_cnm_stockstatus WHERE CountryId = $countryId AND MonthId = $monthId AND Year = '$year' AND ItemGroupId = $itemGroupId";

		$result3 = mysql_query($sql3);
		$error = mysql_error() . "</br>";

		$sql4 = "DELETE FROM t_cnm_masterstockstatus WHERE CountryId = $countryId AND MonthId = $monthId AND Year = '$year' AND ItemGroupId = $itemGroupId";

		$result4 = mysql_query($sql4);
		$error = mysql_error() . "</br>";

		$bResult = $result1 && $result2 && $result3 && $result4;

		if (!$bResult)
			throw new Exception("Query error:</br>" . mysql_real_escape_string($error));

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
		//print_r($e->getLine());
		echo '{ success = 0; error = "' . $e -> getMessage() . '" }';
	}
}

function updateNationalStockStatus($facilityId, $monthId, $year) {
	//fb('updateNationalStockStatus');

	$query = "SELECT a.CountryId, a.ItemGroupId, a.ItemNo, SUM(a.OpStock) OpStock_A, SUM(a.ReceiveQty) ReceiveQty, SUM(a.DispenseQty) DispenseQty
	, SUM(a.AdjustQty) AdjustQty, SUM(a.ClStock) ClStock_A, SUM(a.MOS) MOS, SUM(a.AMC) AMC
	, SUM(a.MaxQty) MaxQty, SUM(a.OrderQty) OrderQty, SUM(a.ActualQty) ActualQty FROM t_cfm_stockstatus a, t_facility b, t_cfm_masterstockstatus c
	 WHERE a.FacilityId = b.FacilityId AND a.MonthId = $monthId AND a.`Year` = $year 
	 AND a.ItemGroupId = " . $_POST['pItemGroupId'] . " AND a.CountryId = " . $_POST['pCountryId'] . " AND a.CFMStockId = c.CFMStockId AND c.StatusId >= 5" . " GROUP BY a.CountryId, a.ItemGroupId, a.ItemNo";

	//fb($query);

	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cnm_stockstatus SET OpStock = " . (is_null($rec['OpStock_A']) ? "NULL" : $rec['OpStock_A']) . ", AdjustQty = " . (is_null($rec['AdjustQty']) ? "NULL" : $rec['AdjustQty']) . ", ClStock = " . (is_null($rec['ClStock_A']) ? "NULL" : $rec['ClStock_A']) . " WHERE ItemGroupId = " . $rec['ItemGroupId'] . " AND ItemNo = " . $rec['ItemNo'] . " AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";
			//echo $sql;
			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

function updateNationalReceive($facilityId, $monthId, $year) {
	//fb('updateNationalReceive');

	$query = "SELECT a.CountryId, a.ItemGroupId, a.ItemNo, a.ReceiveQty, a.AMC
	 FROM t_cfm_stockstatus a, t_facility b, t_cfm_masterstockstatus c
	 WHERE a.FacilityId = b.FacilityId AND a.MonthId = $monthId AND a.`Year` = $year 
	 AND a.ItemGroupId = " . $_POST['pItemGroupId'] . " AND a.CountryId = " . $_POST['pCountryId'] . " AND a.CFMStockId = c.CFMStockId AND c.StatusId >= 5 AND b.FLevelId = 1";
	//fb($query);
	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cnm_stockstatus SET ReceiveQty = " . (is_null($rec['ReceiveQty']) ? "NULL" : $rec['ReceiveQty']) . ", AMC = " . (is_null($rec['AMC']) ? "NULL" : $rec['AMC']) . " WHERE ItemGroupId = " . $rec['ItemGroupId'] . " AND ItemNo = " . $rec['ItemNo'] . " AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";
			//echo $sql;
			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

function updateNationalDispensed($facilityId, $monthId, $year) {
	//fb('updateNationalDispensed');

	$query = "SELECT a.CountryId, a.ItemGroupId, a.ItemNo, SUM(a.OpStock) OpStock_A, SUM(a.ReceiveQty) ReceiveQty, SUM(a.DispenseQty) DispenseQty
	, SUM(a.AdjustQty) AdjustQty, SUM(a.ClStock) ClStock_A, SUM(a.MOS) MOS, SUM(a.AMC) AMC
	, SUM(a.MaxQty) MaxQty, SUM(a.OrderQty) OrderQty, SUM(a.ActualQty) ActualQty FROM t_cfm_stockstatus a, t_facility b, t_cfm_masterstockstatus c
	 WHERE a.FacilityId = b.FacilityId AND b.FLevelId = 99 AND a.MonthId = $monthId AND a.`Year` = $year 
	 AND a.ItemGroupId = " . $_POST['pItemGroupId'] . " AND a.CountryId = " . $_POST['pCountryId'] . " AND a.CFMStockId = c.CFMStockId AND c.StatusId >= 5" . " GROUP BY a.CountryId, a.ItemGroupId, a.ItemNo";
	//fb($query);
	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cnm_stockstatus SET DispenseQty = " . (is_null($rec['DispenseQty']) ? "NULL" : $rec['DispenseQty']) . " WHERE ItemGroupId = " . $rec['ItemGroupId'] . " AND ItemNo = " . $rec['ItemNo'] . " AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";
			//echo $sql;
			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

/**
 * Update Parent AMC and MOS from its child facilities
 * @param   int  $facilityId   ( This is parent facility id )
 * @param   int  $monthId  ( Month Id )
 * @param   string  $year  ( Year Id )
 */
function update_parent_amc_mos($facilityId, $monthId, $year) {

	global $userId, $language;

	$aQuerys = array();

	$query = "SELECT
				  t_cfm_stockstatus.ItemGroupId,
				  t_cfm_stockstatus.ItemNo,
				  SUM(t_cfm_stockstatus.AMC) AMC
				FROM t_cfm_stockstatus
				  INNER JOIN t_cfm_masterstockstatus
				    ON (t_cfm_stockstatus.CFMStockId = t_cfm_masterstockstatus.CFMStockId)
				  INNER JOIN t_facility
				    ON (t_cfm_masterstockstatus.FacilityId = t_facility.FacilityId)
				      AND (t_cfm_stockstatus.FacilityId = t_facility.FacilityId)
				  INNER JOIN t_facility_group_map
				    ON (t_facility_group_map.FacilityId = t_facility.FacilityId
				     AND t_facility_group_map.ItemGroupId = 1)
				WHERE (t_facility_group_map.SupplyFrom = $facilityId
				       AND t_cfm_masterstockstatus.Year = '$year'
				       AND t_cfm_masterstockstatus.MonthId = $monthId
				       AND t_facility_group_map.ItemGroupId = 1
				       AND t_cfm_masterstockstatus.StatusId = 5)
				GROUP BY t_cfm_stockstatus.ItemGroupId, t_cfm_stockstatus.ItemNo;";

	//echo $query;

	$rResult = safe_query($query);

	while ($rec = mysql_fetch_assoc($rResult)) {
		$query = "UPDATE t_cfm_stockstatus SET AMC = " . (is_null($rec['AMC']) ? "NULL" : $rec['AMC']) . ", AMC_C = " . (is_null($rec['AMC']) ? "NULL" : $rec['AMC']) . ", MOS = " . (is_null($rec['AMC']) ? "NULL" : ("FORMAT((ClStock/" . $rec['AMC'] . "), 1)")) . " WHERE ItemGroupId = " . $rec['ItemGroupId'] . " AND ItemNo = " . $rec['ItemNo'] . " AND FacilityId = $facilityId AND MonthId = $monthId AND `Year` = '$year'
			AND CountryId = " . $_POST['pCountryId'] . ";";

		$aQuery = array('command' => 'UPDATE', 'query' => $query, 'sTable' => 't_cfm_stockstatus', 'pks' => array('ItemGroupId', 'ItemNo', 'FacilityId', 'MonthId', 'Year'), 'pk_values' => array($rec['ItemGroupId'], $rec['ItemNo'], $facilityId, $monthId, "'" . $year . "'"), 'bUseInsetId' => FALSE);

		$aQuerys[] = $aQuery;
	}
	if (count($aQuerys) > 0)
		exec_query($aQuerys, $userId, $language, TRUE, FALSE);
}

function getSumOfChildOfStockStatus_old($facilityId, $monthId, $year) {
	//global 	$facilityId, $monthId, $year;

	$query = "SELECT a.CountryId, a.ItemGroupId, a.ItemNo, SUM(a.OpStock) OpStock_A, SUM(a.ReceiveQty) ReceiveQty, SUM(a.DispenseQty) DispenseQty
	, SUM(a.AdjustQty) AdjustQty, SUM(a.ClStock) ClStock_A, SUM(a.MOS) MOS, SUM(a.AMC) AMC
	, SUM(a.MaxQty) MaxQty, SUM(a.OrderQty) OrderQty, SUM(a.ActualQty) ActualQty FROM t_cfm_stockstatus a, t_facility b, t_cfm_masterstockstatus c
	 WHERE a.FacilityId = b.FacilityId AND b.ParentFacilityId = $facilityId AND a.MonthId = $monthId AND a.`Year` = $year 
	 AND a.ItemGroupId = " . $_POST['pItemGroupId'] . " AND a.CountryId = " . $_POST['pCountryId'] . " AND a.CFMStockId = c.CFMStockId AND c.StatusId = 5" . " GROUP BY a.CountryId, a.ItemGroupId, a.ItemNo";

	//echo $query;

	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cfm_stockstatus SET AMC = " . (is_null($rec['AMC']) ? "NULL" : $rec['AMC']) . ", MOS = " . (is_null($rec['AMC']) ? "NULL" : ("FORMAT((ClStock/" . $rec['AMC'] . "), 1)")) . " WHERE ItemGroupId = " . $rec['ItemGroupId'] . " AND ItemNo = " . $rec['ItemNo'] . " AND FacilityId = $facilityId AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";
			//echo $sql;
			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

/**
 * Recalculate parent AMC and MOS of a child facility
 * @param   int  $facilityId   ( This is child facility id )
 * @param   int  $monthId  ( Month Id )
 * @param   string  $year  ( Year Id )
 * This is a recursive call to its parent, SupplyFrom is parent id of a facility
 */
function recalParentAMC($facilityId, $monthId, $year) {	
	update_parent_amc_mos($facilityId, $monthId, $year);

	$query = "SELECT
				    t_facility_group_map.SupplyFrom
				FROM
				    t_facility_group_map
				    INNER JOIN t_facility 
				        ON (t_facility_group_map.FacilityId = t_facility.FacilityId)
				WHERE (t_facility_group_map.ItemGroupId = 1
    					AND t_facility.FacilityId = $facilityId);";

	$rResult = safe_query($query);

	while ($rec = mysql_fetch_assoc($rResult)) {
		$facilityId = $rec['SupplyFrom'];
	}

	$facilityId = is_null($facilityId) ? '0' : $facilityId;

	$facilityId = intval($facilityId);

	//var_dump($facilityId);

	if ($facilityId != 0)
		recalParentAMC($facilityId, $monthId, $year);
}

function updateNationalPatient($facilityId, $monthId, $year) {
	//global 	$facilityId, $monthId, $year;

	$query = "SELECT a.CountryId, a.FormulationId, SUM(a.RefillPatient) RefillPatient, SUM(a.NewPatient) NewPatient, 
	SUM(a.TotalPatient) TotalPatient
	FROM t_cfm_patientoverview a, t_facility b
	 WHERE a.FacilityId = b.FacilityId AND MonthId = $monthId AND `Year` = $year
	 AND a.CountryId = " . $_POST['pCountryId'] . " GROUP BY a.CountryId, a.FormulationId";

	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cnm_patientoverview SET RefillPatient = " . $rec['RefillPatient'] . ", NewPatient = " . $rec['NewPatient'] . ', TotalPatient = ' . $rec['TotalPatient'] . " WHERE FormulationId = " . $rec['FormulationId'] . " AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";

			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

function getSumOfChildOfPatient($facilityId, $monthId, $year) {
	//global 	$facilityId, $monthId, $year;

	$query = "SELECT a.CountryId, a.FormulationId, SUM(a.RefillPatient) RefillPatient, SUM(a.NewPatient) NewPatient, 
	SUM(a.TotalPatient) TotalPatient
	FROM t_cfm_patientoverview a, t_facility b
	 WHERE a.FacilityId = b.FacilityId AND b.ParentFacilityId = $facilityId AND MonthId = $monthId AND `Year` = $year
	 AND a.CountryId = " . $_POST['pCountryId'] . " GROUP BY a.CountryId, a.FormulationId";

	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cfm_patientoverview SET RefillPatient = " . $rec['RefillPatient'] . ", NewPatient = " . $rec['NewPatient'] . ', TotalPatient = ' . $rec['TotalPatient'] . " WHERE FormulationId = " . $rec['FormulationId'] . " AND FacilityId = $facilityId AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";

			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

function updateNationalRegimen($facilityId, $monthId, $year) {

	//global 	$facilityId, $monthId, $year;

	$query = "SELECT a.CountryId, a.RegimenId, SUM(a.RefillPatient) RefillPatient, SUM(a.NewPatient) NewPatient, 
	SUM(a.TotalPatient) TotalPatient
	FROM t_cfm_regimenpatient a, t_facility b
	 WHERE a.FacilityId = b.FacilityId AND MonthId = $monthId AND `Year` = $year
	 AND a.CountryId = " . $_POST['pCountryId'] . " GROUP BY a.CountryId, a.RegimenId";

	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cnm_regimenpatient SET RefillPatient = " . $rec['RefillPatient'] . ", NewPatient = " . $rec['NewPatient'] . ', TotalPatient = ' . $rec['TotalPatient'] . " WHERE RegimenId = " . $rec['RegimenId'] . " AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";

			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

function getSumOfChildOfRegimen($facilityId, $monthId, $year) {

	//global 	$facilityId, $monthId, $year;

	$query = "SELECT a.CountryId, a.RegimenId, SUM(a.RefillPatient) RefillPatient, SUM(a.NewPatient) NewPatient, 
	SUM(a.TotalPatient) TotalPatient
	FROM t_cfm_regimenpatient a, t_facility b
	 WHERE a.FacilityId = b.FacilityId AND b.ParentFacilityId = $facilityId AND MonthId = $monthId AND `Year` = $year
	 AND a.CountryId = " . $_POST['pCountryId'] . " GROUP BY a.CountryId, a.RegimenId";

	$rResult = safe_query($query);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		while ($rec = mysql_fetch_assoc($rResult)) {
			$sql = "UPDATE t_cfm_regimenpatient SET RefillPatient = " . (empty($rec['RefillPatient']) ? 'NULL' : $rec['RefillPatient']) . ", NewPatient = " . (empty($rec['NewPatient']) ? 'NULL' : $rec['NewPatient']) . ', TotalPatient = ' . (empty($rec['TotalPatient']) ? 'NULL' : $rec['TotalPatient']) . " WHERE RegimenId = " . $rec['RegimenId'] . " AND FacilityId = $facilityId AND MonthId = $monthId AND `Year` = $year
				AND CountryId = " . $_POST['pCountryId'] . ";";

			$result = mysql_query($sql);
			$bResult = $result;
			$error .= mysql_error();
			if (!$bResult)
				throw new Exception("Query error:</br>" . mysql_real_escape_string($error));
		}

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
	}
}

function updateStockData() {
	global $monthId, $year, $reportId, $userId, $language;

	//var_dump($_POST);

	$sql = "UPDATE t_cfm_stockstatus SET ";
	$sql .= " OpStock_C = " . ($_POST['pOpStock_C'] === '' ? "NULL" : $_POST['pOpStock_C']);
	$sql .= ", OpStock = " . ($_POST['pOpStock_A'] === '' ? "NULL" : $_POST['pOpStock_A']);
	$sql .= ", ReceiveQty = " . ($_POST['pReceiveQty'] === '' ? "NULL" : $_POST['pReceiveQty']);
	$sql .= ", DispenseQty = " . ($_POST['pDispenseQty'] === '' ? "NULL" : $_POST['pDispenseQty']);
	$sql .= ", AdjustQty = " . ($_POST['pAdjustQty'] == '' ? "NULL" : $_POST['pAdjustQty']);
	$sql .= ", AdjustId = " . zeroEmptyUnsetToNull($_POST['pAdjustReason']);
	$sql .= ", StockoutDays = " . ($_POST['pStockoutDays'] == '' ? "NULL" : $_POST['pStockoutDays']);
	$sql .= ", StockOutReasonId = " . zeroEmptyUnsetToNull($_POST['pStockOutReasonId']);
	$sql .= ", ClStock_C = " . ($_POST['pClStock_C'] === '' ? "NULL" : $_POST['pClStock_C']);
	$sql .= ", ClStock = " . ($_POST['pClStock_A'] === '' ? "NULL" : $_POST['pClStock_A']);
	$sql .= ", ClStockSourceId = " . zeroEmptyUnsetToNull($_POST['pClStockSourceId']);
	$sql .= ", MOS = " . ($_POST['pMOS'] == '' ? "NULL" : $_POST['pMOS']);
	$sql .= ", AMC_C = " . ($_POST['pAMC_C'] == '' ? "NULL" : $_POST['pAMC_C']);
	$sql .= ", AMC = " . ($_POST['pAMC'] == '' ? "NULL" : $_POST['pAMC']);
	$sql .= ", AmcChangeReasonId = " . zeroEmptyUnsetToNull($_POST['pAmcChangeReasonId']);
	$sql .= ", MaxQty = " . ($_POST['pMaxQty'] == '' ? "NULL" : $_POST['pMaxQty']);
	$sql .= ", OrderQty = " . ($_POST['pOrderQty'] == '' ? "NULL" : $_POST['pOrderQty']);
	$sql .= ", ActualQty = " . ($_POST['pActualQty'] == '' ? "NULL" : $_POST['pActualQty']);
	$sql .= ", OUReasonId = " . zeroEmptyUnsetToNull($_POST['pOUReasonId']);
	//".($_POST['pOUReasonId']  == '0'? "NULL": $_POST['pOUReasonId']);
	$sql .= ", LastEditTime = now()";
	$sql .= " WHERE CFMStockStatusId = " . zeroEmptyUnsetToNull($_POST['pARVDataId']);

	$aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_cfm_stockstatus', 'pks' => array('CFMStockStatusId'), 'pk_values' => array(zeroEmptyUnsetToNull($_POST['pARVDataId'])), 'bUseInsetId' => FALSE);

	$sql1 = "UPDATE t_cfm_masterstockstatus SET";
	$sql1 .= " LastUpdateBy = '" . $userId;
	$sql1 .= "', LastUpdateDt = now()";
	$sql1 .= " WHERE CFMStockId = " . $reportId . " AND Year = '$year'";

	$aQuery2 = array('command' => 'UPDATE', 'query' => $sql1, 'sTable' => 't_cfm_masterstockstatus', 'pks' => array('CFMStockId'), 'pk_values' => array($reportId), 'bUseInsetId' => FALSE);

	$aQuerys = array($aQuery1, $aQuery2);

	$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);

	if ($msg['msgType'] == 'success')
		echo '{success = 1; reportId =' . $reportId . '}';
	else
		echo '{success = 0; error = "Error"}';

	//echo '{success = 1; reportId ='.$reportId.'}';
	// }
	// catch (Exception $e)
	// {
	// mysql_query('ROLLBACK;');
	// echo '{ success = 0; error = "' . $e->getMessage() .'" }';
	// }
}

function updateStockData_Old_23_11_2014() {
	global $monthId, $year, $reportId, $userId;

	//var_dump($_POST['pClStock_A']);

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		//var_dump($_POST['pClStock_C']);

		//var_dump($_POST['pAdjustReason']);

		$sql = "UPDATE t_cfm_stockstatus SET ";
		$sql .= " OpStock_C = " . ($_POST['pOpStock_C'] === '' ? "NULL" : $_POST['pOpStock_C']);
		$sql .= ", OpStock = " . ($_POST['pOpStock_A'] === '' ? "NULL" : $_POST['pOpStock_A']);
		$sql .= ", ReceiveQty = " . ($_POST['pReceiveQty'] === '' ? "NULL" : $_POST['pReceiveQty']);
		$sql .= ", DispenseQty = " . ($_POST['pDispenseQty'] === '' ? "NULL" : $_POST['pDispenseQty']);
		$sql .= ", AdjustQty = " . ($_POST['pAdjustQty'] == '' ? "NULL" : $_POST['pAdjustQty']);
		$sql .= ", AdjustId = " . zeroEmptyUnsetToNull($_POST['pAdjustReason']);
		$sql .= ", StockoutDays = " . ($_POST['pStockoutDays'] == '' ? "NULL" : $_POST['pStockoutDays']);
		$sql .= ", StockOutReasonId = " . zeroEmptyUnsetToNull($_POST['pStockOutReasonId']);
		$sql .= ", ClStock_C = " . ($_POST['pClStock_C'] === '' ? "NULL" : $_POST['pClStock_C']);
		$sql .= ", ClStock = " . ($_POST['pClStock_A'] === '' ? "NULL" : $_POST['pClStock_A']);
		$sql .= ", ClStockSourceId = " . zeroEmptyUnsetToNull($_POST['pClStockSourceId']);
		$sql .= ", MOS = " . ($_POST['pMOS'] == '' ? "NULL" : $_POST['pMOS']);
		$sql .= ", AMC_C = " . ($_POST['pAMC_C'] == '' ? "NULL" : $_POST['pAMC_C']);
		$sql .= ", AMC = " . ($_POST['pAMC'] == '' ? "NULL" : $_POST['pAMC']);
		$sql .= ", AmcChangeReasonId = " . zeroEmptyUnsetToNull($_POST['pAmcChangeReasonId']);
		$sql .= ", MaxQty = " . ($_POST['pMaxQty'] == '' ? "NULL" : $_POST['pMaxQty']);
		$sql .= ", OrderQty = " . ($_POST['pOrderQty'] == '' ? "NULL" : $_POST['pOrderQty']);
		$sql .= ", ActualQty = " . ($_POST['pActualQty'] == '' ? "NULL" : $_POST['pActualQty']);
		$sql .= ", OUReasonId = " . zeroEmptyUnsetToNull($_POST['pOUReasonId']);
		$sql .= ", LastEditTime = now()";
		$sql .= " WHERE CFMStockStatusId = " . zeroEmptyUnsetToNull($_POST['pARVDataId']);

		//echo $sql;

		$result1 = mysql_query($sql);
		$error = mysql_error() . "</br>";

		$sql1 = "UPDATE " . getTable_MSD_YearForCurrentMonth($year) . ' SET';
		$sql1 .= " LastUpdateBy = '" . $userId;
		$sql1 .= "', LastUpdateDt = now()";
		$sql1 .= " WHERE CFMStockId = " . $reportId . " AND Year = '$year'";

		$result2 = mysql_query($sql1);

		$bResult = $result1 && $result2;

		if (!$bResult)
			throw new Exception("Query error:</br>" . mysql_real_escape_string($error));

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

		echo '{success = 1; reportId =' . $reportId . '}';
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
		echo '{ success = 0; error = "' . $e -> getMessage() . '" }';
	}
}

function updateStockDataAll() {
	global $monthId, $year, $modified_data, $reportId, $userId;

	try {
		$error = '';

		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');

		$data = json_decode(stripslashes($modified_data));

		$result1 = True;

		foreach ($data as $arvdata) {
			$sql = "UPDATE t_cfm_stockstatus SET ";
			$sql .= " OpStock_C = " . ($arvdata -> OpStock_C === '' ? "NULL" : $arvdata -> OpStock_C);
			$sql .= ", OpStock = " . ($arvdata -> OpStock_A === '' ? "NULL" : $arvdata -> OpStock_A);
			$sql .= ", ReceiveQty = " . ($arvdata -> ReceiveQty === '' ? "NULL" : $arvdata -> ReceiveQty);
			$sql .= ", DispenseQty = " . ($arvdata -> DispenseQty === '' ? "NULL" : $arvdata -> DispenseQty);
			$sql .= ", AdjustQty = " . ($arvdata -> AdjustQty == '' ? "NULL" : $arvdata -> AdjustQty);
			$sql .= ", AdjustId = " . zeroEmptyUnsetToNull($arvdata -> AdjustId);
			$sql .= ", StockoutDays = " . ($arvdata -> StockoutDays == '' ? "NULL" : $arvdata -> StockoutDays);
			$sql .= ", StockOutReasonId = " . zeroEmptyUnsetToNull($arvdata -> StockOutReasonId);
			$sql .= ", ClStock_C = " . ($arvdata -> ClStock_C === '' ? "NULL" : $arvdata -> ClStock_C);
			$sql .= ", ClStock = " . ($arvdata -> ClStock_A === '' ? "NULL" : $arvdata -> ClStock_A);

			$sql .= ", MOS = " . ($arvdata -> MOS == '' ? "NULL" : $arvdata -> MOS);
			$sql .= ", AMC_C = " . ($arvdata -> AMC_C == '' ? "NULL" : $arvdata -> AMC_C);
			$sql .= ", AMC = " . ($arvdata -> AMC == '' ? "NULL" : $arvdata -> AMC);

			$sql .= ", MaxQty = " . ($arvdata -> MaxQty == '' ? "NULL" : $arvdata -> MaxQty);
			$sql .= ", OrderQty = " . ($arvdata -> OrderQty == '' ? "NULL" : $arvdata -> OrderQty);
			$sql .= ", ActualQty = " . ($arvdata -> ActualQty == '' ? "NULL" : $arvdata -> ActualQty);
			$sql .= ", OUReasonId = " . zeroEmptyUnsetToNull($arvdata -> OUReasonId);
			$sql .= ", LastEditTime = now()";
			$sql .= " WHERE CFMStockStatusId = " . zeroEmptyUnsetToNull($arvdata -> ARVDataId);

			// $sql = "UPDATE " . getTable_SD_YearForCurrentMonth($year) . ' SET';
			// $sql .= " OpStock = " . $arvdata -> OpStock_A;
			// $sql .= ", ReceiveQty = " . checkNumber($arvdata -> ReceiveQty);
			// $sql .= ", DispenseQty = " . checkNumber($arvdata -> DispenseQty);
			// $sql .= ", AdjustQty = " . checkNumber($arvdata -> AdjustQty);
			// $sql .= ", AdjustId = " . checkString($arvdata -> AdjustId);
			// $sql .= ", StockoutDays = " . checkNumber($arvdata -> StockoutDays);
			// $sql .= ", ClStock = " . checkNumber($arvdata -> ClStock_A);
			// $sql .= ", MOS = " . checkNumber($arvdata -> MOS);
			// $sql .= ", AMC_C = " . checkNumber($arvdata -> AMC_C);
			// $sql .= ", AMC = " . checkNumber($arvdata -> AMC);
			// $sql .= ", MaxQty = " . checkNumber($arvdata -> MaxQty);
			// $sql .= ", OrderQty = " . checkNumber($arvdata -> OrderQty);
			// $sql .= ", ActualQty = " . checkNumber($_POST['pActualQty']);
			// $sql .= ", LastEditTime = now()";
			// $sql .= " WHERE CFMStockStatusId = " . checkNumber($arvdata -> ARVDataId);

			$result1 = $result1 && mysql_query($sql);
			$error = mysql_error() . "</br>";
		}

		$sql1 = "UPDATE " . getTable_MSD_YearForCurrentMonth($year) . ' SET';
		$sql1 .= " LastUpdateBy = '" . $userId;
		$sql1 .= "', LastUpdateDt = now()";
		$sql1 .= " WHERE CFMStockId = " . $reportId . " AND Year = '$year'";

		$result2 = mysql_query($sql1);

		$bResult = $result1 && $result2;

		if (!$bResult)
			throw new Exception("Query error:</br>" . mysql_real_escape_string($error));

		mysql_query('COMMIT;');

		mysql_query('SET autocommit = 1;');

		echo '{success = 1; error = "No Error"}';
	} catch (Exception $e) {
		mysql_query('ROLLBACK;');
		echo '{ success = 0; error = "' . $e -> getMessage() . '" }';
	}

}

function changeBsubmittedInMaster() {
	global $facilityId, $monthId, $year, $reportId, $userId, $countryId, $language;

	date_default_timezone_set("GMT");
	$curDateTime = date('Y-m-d h:i:s A');

	$sql = "UPDATE t_cfm_masterstockstatus SET";
	$sql .= " StatusId = " . $_POST['pStatusId'];

	if ($_POST['pStatusId'] == 2) {
		$sql .= ", LastSubmittedBy = '" . $userId;
		$sql .= "', LastSubmittedDt = '$curDateTime'";
	} else if ($_POST['pStatusId'] == 5) {
		$sql .= ", PublishedBy = '" . $userId;
		$sql .= "', PublishedDt = '$curDateTime'";
	}

	$sql .= " WHERE CFMStockId = " . $reportId . ";";

	//echo $sql;

	$aQuery = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_cfm_masterstockstatus', 'pks' => array('CFMStockId'), 'pk_values' => array($reportId), 'bUseInsetId' => FALSE);

	$aQuerys1[] = $aQuery;

	$msg = exec_query($aQuerys1, $userId, $language, TRUE, FALSE);

	//$aQuerys[] = $aQuery;

	//$msg2;

	// Deleting and creating National Report
	if ($msg['msgType'] == 'success' && $_POST['pStatusId'] == 5) {
		recalParentAMC($facilityId, $monthId, $year);
		createNationalReport();
	}

	//print_r($aQuerys);

	//$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);

	if ($msg['msgType'] == 'success') {
		getMasterStockData();
	} else {
		echo '{success = 0; error = "Invalid query", SQL: ""}';
	}
}

function createNationalReport() {

	global $facilityId, $monthId, $year, $reportId, $userId, $countryId, $ownerTypeId, $language;

	$query1 = "SELECT * FROM t_cnm_patientoverview WHERE CountryId = $countryId AND MonthId = $monthId AND Year = '$year';";
	//echo $query1;
	$result1 = mysql_query($query1);

	while ($row = mysql_fetch_assoc($result1)) {
		$cNMPOId = $row['CNMPOId'];
		$sql1 = "DELETE FROM t_cnm_patientoverview WHERE CNMPOId = $cNMPOId;";
		$aQuery2 = array('command' => 'DELETE', 'query' => $sql1, 'sTable' => 't_cnm_patientoverview', 'pks' => array('CNMPOId'), 'pk_values' => array($cNMPOId), 'bUseInsetId' => FALSE);
		$aQuerys[] = $aQuery2;
	}

	$query1 = "SELECT * FROM t_cnm_regimenpatient WHERE CountryId = $countryId AND MonthId = $monthId AND Year = '$year';";
	$result1 = mysql_query($query1);

	while ($row = mysql_fetch_assoc($result1)) {
		$cNMPatientStatusId = $row['CNMPatientStatusId'];
		$sql1 = "DELETE FROM t_cnm_regimenpatient WHERE CNMPatientStatusId = $cNMPatientStatusId;";
		$aQuery2 = array('command' => 'DELETE', 'query' => $sql1, 'sTable' => 't_cnm_regimenpatient', 'pks' => array('CNMPatientStatusId'), 'pk_values' => array($cNMPatientStatusId), 'bUseInsetId' => FALSE);
		$aQuerys[] = $aQuery2;
	}

	$query1 = "SELECT * FROM t_cnm_stockstatus WHERE CountryId = $countryId AND MonthId = $monthId AND Year = '$year';";
	$result1 = mysql_query($query1);

	while ($row = mysql_fetch_assoc($result1)) {
		$cNMStockStatusId = $row['CNMStockStatusId'];
		$sql1 = "DELETE FROM t_cnm_stockstatus WHERE CNMStockStatusId = $cNMStockStatusId;";
		$aQuery2 = array('command' => 'DELETE', 'query' => $sql1, 'sTable' => 't_cnm_stockstatus', 'pks' => array('CNMStockStatusId'), 'pk_values' => array($cNMStockStatusId), 'bUseInsetId' => FALSE);
		$aQuerys[] = $aQuery2;
	}

	$sql4 = "DELETE FROM t_cnm_masterstockstatus WHERE CountryId = $countryId AND MonthId = $monthId AND `Year` = '$year';";
	//echo $sql4;
	$aQuery1 = array('command' => 'DELETE', 'query' => $sql4, 'sTable' => 't_cnm_masterstockstatus', 'pks' => array('CountryId', 'MonthId', 'Year'), 'pk_values' => array($countryId, $monthId, "'" . $year . "'"), 'bUseInsetId' => FALSE);
	$aQuerys[] = $aQuery1;

	$sql1 = "INSERT INTO t_cnm_masterstockstatus
				(CountryId, MonthId, Year, OwnerTypeId, CreatedBy, CreatedDt, LastUpdateBy, LastUpdateDt, bSubmitted, StatusId, LastSubmittedDt, AcceptedDt, PublishedDt ) 
				VALUES ($countryId, $monthId, '$year', $ownerTypeId, '$userId',Now(), '$userId',Now(), 0, 5, Now(), Now(), Now());";

	//echo $sql1;

	$aQuery1 = array('command' => 'INSERT', 'query' => $sql1, 'sTable' => 't_cnm_masterstockstatus', 'pks' => array('CNMStockId'), 'pk_values' => array(), 'bUseInsetId' => TRUE);
	$aQuerys[] = $aQuery1;

	$query1 = "SELECT a.FormulationId, a.CountryId, a.ItemGroupId, SUM(a.RefillPatient) RefillPatient, SUM(a.NewPatient) NewPatient,SUM(a.TotalPatient) TotalPatient
				 FROM t_cfm_patientoverview a, t_facility b, t_cfm_masterstockstatus c 
				 WHERE a.FacilityId = b.FacilityId AND a.MonthId = $monthId AND a.`Year` = '$year'
				 AND a.CountryId = $countryId
				 AND a.CFMStockId = c.CFMStockId
				 AND c.StatusId = 5 
				 AND a.ItemGroupId = 1
				 AND b.FLevelId = 99
				 GROUP BY a.CountryId, a.FormulationId, a.ItemGroupId;";

	//echo $query1;

	$result1 = mysql_query($query1);

	while ($row = mysql_fetch_assoc($result1)) {
		$formulationId = $row['FormulationId'];
		$refillPatient = $row['RefillPatient'];
		$newPatient = $row['NewPatient'];
		$totalPatient = $row['TotalPatient'];

		$sql2 = "INSERT INTO t_cnm_patientoverview (CNMStockId, FormulationId, CountryId, ItemGroupId, MonthId, Year, RefillPatient, NewPatient, TotalPatient)
				 VALUES([LastInsertedId], $formulationId, $countryId, 1, $monthId, '$year', $refillPatient, $newPatient, $totalPatient)";

		$aQuery2 = array('command' => 'INSERT', 'query' => $sql2, 'sTable' => 't_cnm_patientoverview', 'pks' => array('CNMPOId'), 'pk_values' => array(), 'bUseInsetId' => FALSE);
		$aQuerys[] = $aQuery2;
	}

	$query1 = "SELECT a.RegimenId, SUM(a.RefillPatient) RefillPatient, SUM(a.NewPatient) NewPatient, 
				 SUM(a.TotalPatient) TotalPatient
				 FROM t_cfm_regimenpatient a, t_facility b, t_cfm_masterstockstatus c 
				 WHERE a.FacilityId = b.FacilityId AND a.MonthId = $monthId AND a.`Year` = $year
				 AND a.CountryId = $countryId
				 AND a.CFMStockId = c.CFMStockId
				 AND c.StatusId = 5
				 AND a.ItemGroupId = 1
				 AND b.FLevelId = 99
				 GROUP BY a.CountryId, a.RegimenId;";

	//echo $query1;

	$result1 = mysql_query($query1);

	while ($row = mysql_fetch_assoc($result1)) {
		$regimenId = $row['RegimenId'];
		$refillPatient = $row['RefillPatient'];
		$newPatient = $row['NewPatient'];
		$totalPatient = $row['TotalPatient'];

		$sql2 = "INSERT INTO t_cnm_regimenpatient (CNMStockId, RegimenId, CountryId, ItemGroupId, MonthId, Year, RefillPatient, NewPatient, TotalPatient)
				 VALUES([LastInsertedId], $regimenId, $countryId, 1, $monthId, '$year', $refillPatient, $newPatient, $totalPatient)";

		$aQuery2 = array('command' => 'INSERT', 'query' => $sql2, 'sTable' => 't_cnm_regimenpatient', 'pks' => array('CNMPatientStatusId'), 'pk_values' => array(), 'bUseInsetId' => FALSE);
		$aQuerys[] = $aQuery2;
	}

	$query1 = "SELECT z.ItemGroupId, z.ItemNo, SUM(z.OpStock) OpStock, SUM(z.AdjustQty) AdjustQty, SUM(z.ClStock) ClStock, 
		 SUM(z.ReceiveQty) ReceiveQty, SUM(z.AMC) AMC, SUM(z.DispenseQty) DispenseQty, SUM(z.ClStock)/SUM(z.AMC) MOS, 0, now()
			FROM (
			SELECT a.CountryId, a.ItemGroupId, a.ItemNo, 
			SUM(a.OpStock) OpStock,
			SUM(a.AdjustQty) AdjustQty,
			SUM(a.ClStock) ClStock,
			0 ReceiveQty, 0 AMC, 0 DispenseQty
			FROM t_cfm_stockstatus a, t_cfm_masterstockstatus c 
			WHERE a.MonthId = $monthId
			AND a.`Year` = $year 
			AND a.CountryId = $countryId
			AND a.CFMStockId = c.CFMStockId 
			AND c.StatusId = 5 
			GROUP BY a.CountryId, a.ItemGroupId, a.ItemNo
			
			UNION
			
			SELECT a.CountryId, a.ItemGroupId, a.ItemNo, 0 OpStock, 0 AdjustQty, 0 ClStock, a.ReceiveQty, 0 AMC, 0 DispenseQty
			FROM t_cfm_stockstatus a, t_facility b, t_cfm_masterstockstatus c 
			WHERE a.FacilityId = b.FacilityId 
			AND a.MonthId = $monthId 
			AND a.`Year` = $year 
			AND a.CountryId = $countryId
			AND a.CFMStockId = c.CFMStockId 
			AND c.StatusId = 5 
			AND b.FLevelId = 1
			
			UNION
			
			SELECT a.CountryId, a.ItemGroupId, a.ItemNo,
			0 OpStock, 0 AdjustQty, 0 ClStock, 0 ReceiveQty, SUM(a.AMC) AMC, SUM(a.DispenseQty) DispenseQty
			FROM t_cfm_stockstatus a, t_facility b, t_cfm_masterstockstatus c 
			WHERE a.FacilityId = b.FacilityId 
			AND b.FLevelId = 99 
			AND a.MonthId = $monthId 
			AND a.`Year` = $year 
			AND a.CountryId = $countryId 
			AND a.CFMStockId = c.CFMStockId 
			AND c.StatusId = 5 
			GROUP BY a.CountryId, a.ItemGroupId, a.ItemNo) z
			GROUP BY z.CountryId, z.ItemGroupId, z.ItemNo";

	//echo $query1;

	$result1 = mysql_query($query1);

	while ($row = mysql_fetch_assoc($result1)) {
		$itemGroupId = $row['ItemGroupId'];
		$itemNo = $row['ItemNo'];
		$opStock = is_null($row['OpStock']) ? 'NULL' : $row['OpStock'];
		$adjustQty = is_null($row['AdjustQty']) ? 'NULL' : $row['AdjustQty'];
		$clStock = is_null($row['ClStock']) ? 'NULL' : $row['ClStock'];
		$receiveQty = is_null($row['ReceiveQty']) ? 'NULL' : $row['ReceiveQty'];
		$amc = is_null($row['AMC']) ? 'NULL' : $row['AMC'];
		$row['AMC'];
		$dispenseQty = is_null($row['DispenseQty']) ? 'NULL' : $row['DispenseQty'];
		$mos = is_null($row['MOS']) ? 'NULL' : $row['MOS'];

		$sql2 = "INSERT INTO t_cnm_stockstatus (CNMStockId, CountryId, MonthId, Year, ItemGroupId, ItemNo, OpStock" . ", AdjustQty, ClStock, ReceiveQty, AMC, DispenseQty, MOS, UserId, LastEditTime)" . " VALUES([LastInsertedId], $countryId, $monthId, '$year', $itemGroupId, $itemNo, $opStock, $adjustQty, $clStock, $receiveQty, $amc, $dispenseQty, $mos, 0, now());";

		$aQuery2 = array('command' => 'INSERT', 'query' => $sql2, 'sTable' => 't_cnm_stockstatus', 'pks' => array('CNMStockStatusId'), 'pk_values' => array(), 'bUseInsetId' => FALSE);
		$aQuerys[] = $aQuery2;
	}
	//print_r($aQuerys);
	$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);
	//print_r($msg);
}

function updatePatientOverview() {
	global $facilityId, $monthId, $year, $userId, $language;
	$sql = "UPDATE t_cfm_patientoverview SET ";
	$sql .= " TotalPatient = " . (empty($_POST['totalPatient']) ? 'NULL' : $_POST['totalPatient']);
	$sql .= " WHERE CFMPOId = " . $_POST['cFMPOId'];

	//runSql($sql);

	$aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_cfm_patientoverview', 'pks' => array('CFMPOId'), 'pk_values' => array($_POST['cFMPOId']), 'bUseInsetId' => FALSE);
	$aQuerys = array($aQuery1);
	$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);

	if ($msg['msgType'] == 'success')
		echo '{success = 1; error = "No Error"}';
	else
		echo '{success = 0; error = "Error"}';
}

function updateRegimenPatient() {
	global $facilityId, $year, $userId, $language;

	//print_r($_POST);

	$sql = "UPDATE t_cfm_regimenpatient SET ";
	$sql .= " RefillPatient = " . (empty($_POST['pPatients']) ? 'NULL' : $_POST['pPatients']);
	$sql .= ", TotalPatient = " . (empty($_POST['pPatients']) ? 'NULL' : $_POST['pPatients']);
	$sql .= " WHERE CFMPatientStatusId = " . $_POST['pCFMPatientStatusId'];

	$aQuery1 = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_cfm_regimenpatient', 'pks' => array('CFMPatientStatusId'), 'pk_values' => array($_POST['pCFMPatientStatusId']), 'bUseInsetId' => FALSE);
	$aQuerys = array($aQuery1);
	$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);

	if ($msg['msgType'] == 'success')
		echo '{success = 1; error = "No Error"}';
	else
		echo '{success = 0; error = "Error"}';
}

function delete_data_from_yyyy() {
	global $facilityId, $monthId, $year, $userId, $facilityId, $language;
	$sql1 = "DELETE FROM t_cfm_patientoverview ";
	$sql1 .= " WHERE FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'] . ";";

	$aQuery1 = array('command' => 'DELETE', 'query' => $sql1, 'sTable' => 't_cfm_patientoverview', 'pks' => array('FacilityId', 'MonthId', 'Year', 'CountryId'), 'pk_values' => array($facilityId, $monthId, $year, $_POST['pCountryId']), 'bUseInsetId' => FALSE);

	$sql2 = "DELETE FROM t_cfm_regimenpatient ";
	$sql2 .= " WHERE FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'] . ";";

	$aQuery2 = array('command' => 'DELETE', 'query' => $sql2, 'sTable' => 't_cfm_regimenpatient', 'pks' => array('FacilityId', 'MonthId', 'Year', 'CountryId'), 'pk_values' => array($facilityId, $monthId, $year, $_POST['pCountryId']), 'bUseInsetId' => FALSE);

	$sql3 = "DELETE FROM t_cfm_stockstatus ";
	$sql3 .= " WHERE FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'] . ";";

	$aQuery3 = array('command' => 'DELETE', 'query' => $sql3, 'sTable' => 't_cfm_stockstatus', 'pks' => array('FacilityId', 'MonthId', 'Year', 'CountryId'), 'pk_values' => array($facilityId, $monthId, $year, $_POST['pCountryId']), 'bUseInsetId' => FALSE);

	$sql4 = "DELETE FROM t_cfm_masterstockstatus ";
	$sql4 .= " WHERE FacilityId = " . $facilityId . " and MonthId = " . $monthId . " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'] . ";";

	$aQuery4 = array('command' => 'DELETE', 'query' => $sql4, 'sTable' => 't_cfm_masterstockstatus', 'pks' => array('FacilityId', 'MonthId', 'Year', 'CountryId'), 'pk_values' => array($facilityId, $monthId, $year, $_POST['pCountryId']), 'bUseInsetId' => FALSE);

	//var_dump($aQuery4);

	$aQuerys = array($aQuery1, $aQuery2, $aQuery3, $aQuery4);
	$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);

	if ($msg['msgType'] == 'success') {
		recalParentAMC($facilityId, $monthId, $year);
		createNationalReport();
	}

	// if ($msg['msgType'] == 'success') {
	// getMasterStockData();
	// } else {
	// echo '{success = 0; error = "Invalid query", SQL: ""}';
	// }

	if ($msg['msgType'] == 'success')
		echo '{success = 1; error = "No Error"}';
	else
		echo '{success = 0; error = "Error"}';

}

function getCountNationalReport() {
	global $monthId, $year, $reportId;

	$query = "SELECT COUNT(*) as TotalRec FROM t_cnm_masterstockstatus WHERE MonthId = " . $monthId . " and Year = '" . $year . "' and ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = " . $_POST['pCountryId'];
	$query .= "";

	$result = mysql_query($query);

	$total = 0;

	while ($row = mysql_fetch_assoc($result))
		$total = $row['TotalRec'];

	return $total;
}

function getLmisStartMonthYear() {

	$query = "SELECT LmisStartMonth, LmisStartYear FROM tsettings Where id = 1;";

	$result = mysql_query($query);
	if ($result)
		while ($row = mysql_fetch_assoc($result)) {
			$lmisStartMonth = $row['LmisStartMonth'];
			$lmisStartYear = $row['LmisStartYear'];
			echo '{lmisStartMonth=' . $lmisStartMonth . '; lmisStartYear=' . $lmisStartYear . ';}';
		}
	else
		'Error : ' . mysql_error();

}

function makeUnpublished() {
	global $monthId, $year, $reportId, $userId, $facilityId, $language;
	//global $facilityId, $monthId, $year, $reportId, $userId, $countryId, $language;
	// date_default_timezone_set("GMT");
	// $curDateTime = date('Y-m-d h:i:s A');

	$sql = "UPDATE t_cfm_masterstockstatus SET";
	$sql .= " StatusId = 1";

	$sql .= ", LastSubmittedBy = NULL";
	$sql .= ", LastSubmittedDt = NULL";

	$sql .= ", AcceptedDt = NULL";

	$sql .= ", PublishedDt = NULL";

	$sql .= " WHERE CFMStockId = " . $reportId;

	$aQuery = array('command' => 'UPDATE', 'query' => $sql, 'sTable' => 't_cfm_masterstockstatus', 'pks' => array('CFMStockId'), 'pk_values' => array($reportId), 'bUseInsetId' => FALSE);

	$aQuerys[] = $aQuery;

	//$result = mysql_query($sql);
	$msg = exec_query($aQuerys, $userId, $language, TRUE, FALSE);

	if ($msg['msgType'] == 'success') {
		recalParentAMC($facilityId, $monthId, $year);
		createNationalReport();
	}

	if ($msg['msgType'] == 'success') {
		getMasterStockData();
	} else {
		echo '{success = 0; error = "Invalid query", SQL: ""}';
	}
}
?>
