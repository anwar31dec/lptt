<?php
//require_once('fb.php');
/* Legend
 * getTable_**_YearForLastMonth($year, $monthId);
 * (**) means following
 * PO: yyyy_patientoverview
 * RP: yyyy_regimenpatient
 * SD: yyyy_stockdata
 * */
include ("universal_function_lib_ext.php");
//include ("joomla_user_info.php");

$task = '';
if(isset($_POST['action'])) {
	$task = $_POST['action'];
}


$facilityId = $_POST['pFacilityId'];

$monthId = checkNumber($_POST['pMonthId']);
$year = checkNumber($_POST['pYearId']);


$modified_data = $_POST['data'];
$formulationId = checkNumber($_POST['pFormulationId']);
//$reportId = !isset($_POST['pReportId']) ? "undefine" : checkNumber($_POST['pReportId']);

$reportId = checkNumber($_POST['pReportId']);


$lastMonthDispensed = " IFNULL((SELECT DispenseQty ";
$lastMonthDispensed .= "FROM "."t_cnm_stockstatus";
$lastMonthDispensed .= " WHERE MonthId = " .getLastMonth($year, $monthId). " and Year = '" . getYearForLastMonth($year, $monthId) 
."' and ItemNo = a.ItemNo  and a.ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = ". $_POST['pCountryId'] . "),0) ";


$beforeLastMonthDispensed = " IFNULL((SELECT DispenseQty ";
$beforeLastMonthDispensed .= "FROM " . getTable_SD_YearForLast2Month($year, $monthId);
$beforeLastMonthDispensed .= " WHERE MonthId = " . getBeforeLastMonth($year, $monthId). " and Year = '" . getYearForLast2Month($year, $monthId)
."' and ItemNo = a.ItemNo  and a.ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = " . $_POST['pCountryId'] . "),0) ";


$userId  = checkNumber($_POST['pUserId']) ;

$joomladb = JOOMLA_DBNAME;

switch($task) {
	case "getPatientOverview" :
		getPatientOverview();
		break;
	case "getRegimens" :
		getRegimens();
		break;	
	case "getMasterStockData" :
		getMasterStockData();
	break;			
	case "getArvData" :
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
	default :
		echo "{failure:true}";
		break;
}

function getPatientOverview() {
	global 	$facilityId, $monthId, $year;
	$query = "SELECT 	b.CNMPOId,
						a.FormulationName, 
						b.RefillPatient, 
						b.NewPatient, 
						b.TotalPatient
						FROM t_formulation a INNER JOIN t_cnm_patientoverview b ON a.FormulationId = b.FormulationId ";	
	$query .= " AND MonthId = " . $monthId . " and Year = '" . $year ."' AND CountryId = " .$_POST['pCountryId'] . " AND b.ItemGroupId = " .$_POST['pItemGroupId'];
	// Here we check if we have a query parameter :
	if(isset($_POST['query'])) {
		$query .= " AND (a.FormulationName LIKE '%" . $_POST['query'] . "%') ";
	}	
	$query .= " ORDER BY b.CNMPOId desc";	
	
	//echo $query;
	
	getJsonAll($query);
}

function getRegimens() {
	
	global 	$facilityId, $monthId, $year, $formulationId;
		
	$query = "SELECT b.CNMPatientStatusId, a.RegimenName, c.FormulationName, b.RefillPatient, b.NewPatient, b.TotalPatient 
			FROM t_regimen a 
			INNER JOIN "."t_cnm_regimenpatient"." b ON a.RegimenId = b.RegimenId 
			INNER JOIN t_formulation c ON a.FormulationId = c.FormulationId ";
	$query .= "and MonthId = " . $monthId. " and Year = '" . $year ."' AND CountryId = " .$_POST['pCountryId']. " AND b.ItemGroupId = " .$_POST['pItemGroupId'];
	//$query .= "and a.FormulationId =".$formulationId;	
	
	// Here we check if we have a query parameter :
	if(isset($_POST['query'])) {
		$query .= " AND (a.RegimenName LIKE '%" . $_POST['query'] . "%')";
	}	
	$query .= " order by b.CNMPatientStatusId desc";		
		
	getJsonAll($query);
}


function getMasterStockData() {	
	global 	$facilityId, $monthId, $year, $reportId,$joomladb;				
	//$query = "SELECT ReportId, FacilityId, MonthId, Year, ItemGroupId, CreatedBy, CreatedDt, LastUpdateBy, LastUpdateDt, bSubmitted, LastSubmittedBy, LastSubmittedDt FROM "."t_cnm_stockstatus";	
	$query = "SELECT CNMStockId, MonthId, Year, ItemGroupId, 
	(SELECT b.name FROM ".$joomladb.". j323_users b WHERE b.id = a.CreatedBy) CreatedBy, DATE_FORMAT(CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,
	(SELECT b.name FROM ".$joomladb.". j323_users b WHERE b.id = a.LastUpdateBy)  LastUpdateBy,	
	(SELECT b.name FROM ".$joomladb.". j323_users b WHERE b.id = a.LastSubmittedBy) LastSubmittedBy ,
	c.StatusId, c.StatusName,
	DATE_FORMAT(LastSubmittedDt, '%d-%b-%Y %h:%i %p') LastSubmittedDt,	
	DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,	
	DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt 	
	FROM "."t_cnm_masterstockstatus" . " a LEFT JOIN t_status c ON a.StatusId = c.StatusId ";
	$query .= " WHERE MonthId = " . $monthId. " and Year = '" . $year ."' and ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = ". $_POST['pCountryId'];
	
	getJsonAll($query);
	
}

function getStockData() {	
	global 	$facilityId, $monthId, $year, $beforeLastMonthDispensed, $lastMonthDispensed, $reportId;			
	$query = "SELECT a.CNMStockStatusId, a.MonthId, a.Year, a.ItemNo, b.ItemName, a.OpStock OpStock_A, 0 OpStock_C, a.ReceiveQty, a.DispenseQty, 
	IFNULL(0,0) BeforeLastMonthDispensed, IFNULL(0,0) LastMonthDispensed, a.AdjustQty, a.AdjustId AdjustReason";
	$query .= ",a.StockoutDays, a.ClStock ClStock_A, 0 ClStock_C, a.ClStockSourceId, a.MOS, a.AMC, a.AmcChangeReasonId, a.MaxQty, a.OrderQty, a.ActualQty, a.UserId, a.LastEditTime,  c.ProductSubGroupName FormulationName FROM "."t_cnm_stockstatus"." a, t_itemlist b, t_product_subgroup c ";
	//$query .= " WHERE a.ItemNo = b.ItemNo and b.FormulationId = c.FormulationId and a.CNMStockId = " . $reportId ." and a.ItemGroupId = 1";
	$query .= " WHERE a.ItemNo = b.ItemNo and b.ProductSubGroupId = c.ProductSubGroupId and a.CNMStockId = " . $reportId . " AND MonthId = " . $monthId. " and Year = '" . $year 
	."' and a.ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = ". $_POST['pCountryId'];
	
	// Here we check if we have a query parameter :
	if(isset($_POST['query'])) {
		//$query .= " AND (a.RegimenName LIKE '%" . $_POST['query'] . "%')";
	}	
	$query .= " ORDER BY c.ProductSubGroupName, b.ItemName ASC";
	
	// echo $query;
		
	getJsonAll($query);
	
}

function  getFacilityRecordOfThisMonth() {	
	global 	$facilityId, $monthId, $year, $reportId;		
	
	$query = "SELECT COUNT(*) as totalrec ";
	$query .= "FROM "."t_cnm_masterstockstatus"." a";
	$query .= " WHERE a.CNMStockId = " . $reportId . " AND  MonthId = " . $monthId. " and Year = '" . $year 
	."' and ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = ". $_POST['pCountryId'];
	
	//echo $query;
	
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result))
		$total= $row['totalrec'];		
	echo  (isset($total) || empty($total))?  '{totalrec ='.$total.'}' : '{totalrec = 0}';
}

function  getFacilityRecordOfPrevMonth() {	
	global 	$facilityId, $monthId, $year, $prevMonth,$prevYear, $reportId;		
	
	$query = "SELECT COUNT(*) as totalsubmitted ";
	$query .= "FROM "."t_cnm_masterstockstatus"." a ";
	$query .= " WHERE MonthId = " . getLastMonth($year, $monthId). " and Year = '" . getYearForLastMonth($year, $monthId) ."' and a.StatusId < 5 AND ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = ". $_POST['pCountryId'];
	//echo $query;
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result))
		$total= $row['totalsubmitted'];		
	
	echo  (isset($total) || empty($total))?  '{totalsubmitted ='.$total.'}' : '{totalsubmitted = 0}'; ;
	
	$query1 = "SELECT COUNT(*) as totalprevnonreported ";
	$query1.= "FROM "."t_cnm_masterstockstatus"." a ";
	$query1 .= " WHERE MonthId = " . getLastMonth($year, $monthId). " and Year = '" . getYearForLastMonth($year, $monthId) ."' and ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = ". $_POST['pCountryId'];
	
	$result1 = mysql_query($query1);

	while($row = mysql_fetch_assoc($result1))
		$total1= $row['totalprevnonreported'];	
	
	echo  (isset($total1) || empty($total1))?  '{totalprevnonreported ='.$total1.'}' : '{totalprevnonreported = 0}'; 

	$query2 = "SELECT COUNT(*) as totalrec ";
	$query2 .= "FROM "."t_cnm_masterstockstatus"." a ";
	$query2 .= " WHERE MonthId = " . $monthId. " and Year = '" . $year ."'  and ItemGroupId = " . $_POST['pItemGroupId'] . " AND CountryId = ". $_POST['pCountryId'];
	
	$result2 = mysql_query($query2);


	while($row = mysql_fetch_assoc($result2))
		$total2= $row['totalrec'];		
	
	echo  (isset($total2) || empty($total2))?  '{totalrec ='.$total2.'}' : '{totalrec = 0}'; 

}

// function insertIntoStockData(){
	// mysql_query('CREATE TABLE `test` (
	               // `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
	               // `Name` varchar(255) NOT NULL,
	                // PRIMARY KEY (`ID`)
	             // ) ENGINE=InnoDB') or die(mysql_error());
	// mysql_query('SET AUTOCOMMIT=0') or die(mysql_error());
	// mysql_query('START TRANSACTION') or die(mysql_error());
	// mysql_query("INSERT INTO test VALUES (NULL, 'Martin')") or die(mysql_error());
	// echo mysql_insert_id().'<br />';
	// mysql_query("INSERT INTO test VALUES (NULL, 'Dani')") or die(mysql_error());
	// echo mysql_insert_id().'<br />';
	// mysql_query("INSERT INTO test VALUES (NULL, 'Pesho')") or die(mysql_error());
	// echo mysql_insert_id().'<br />';
	// mysql_query('COMMIT') or die(mysql_error());
	// mysql_query('SET AUTOCOMMIT=1') or die(mysql_error());
// }


function insertIntoStockData() {
	global 	$facilityId, $monthId, $year, $beforeLastMonthDispensed, $reportId, $userId;	
	
	try 
	{
		$error = '';
		
		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');	
		
		$sql3 = "INSERT INTO "."t_cnm_masterstockstatus"
				." (CountryId, MonthId, Year, ItemGroupId, CreatedBy, CreatedDt, LastUpdateBy, LastUpdateDt, bSubmitted, StatusId ) "
				."VALUES (". $_POST['pCountryId'] .", $monthId, '$year', ".$_POST['pItemGroupId'].", '" . $userId ."',Now(), '" . $userId ."',Now(), 0, 1)";
		
				
		$reportId = 0;
	
		$result3 = mysql_query($sql3);
		if($result3){
			$reportId = mysql_insert_id();
		}
		$error .= mysql_error()."</br>";
		
	
		$sql1 = "INSERT INTO t_cnm_patientoverview (CNMStockId, FormulationId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient) "
			. "SELECT $reportId, a.FormulationId, ".$_POST['pCountryId'].", " . $monthId.",'" . $year ."',". " 0, 0, 0 FROM t_formulation a WHERE ItemGroupId = " . $_POST['pItemGroupId'];	
	
		$result1 = mysql_query($sql1);
		$error = mysql_error()."</br>";
	
		$sql2 = "INSERT INTO "."t_cnm_regimenpatient"." (CNMStockId, RegimenId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient) "
			. "SELECT $reportId, RegimenId, " . " CountryId, " . $monthId.",'" . $year ."',". " 0, 0, 0 FROM t_country_regimen WHERE CountryId = " . $_POST['pCountryId'];
		
		
		//echo $sql2;
	
		$result2 = mysql_query($sql2);
		$error .=mysql_error()."</br>$sql2";
			
		$sql4 = "INSERT INTO "."t_cnm_stockstatus"." (CNMStockId, CountryId, MonthId, Year, ItemNo, ItemGroupId, OpStock,
		 ReceiveQty, DispenseQty, "
		. "AdjustQty, AdjustId, StockoutDays, ClStock, MOS, AMC, MaxQty, OrderQty, ActualQty, UserId, LastEditTime) "
		. "SELECT $reportId," . $_POST['pCountryId'] . ", " . $monthId.",'" . $year ."', a.ItemNo, " . $_POST['pItemGroupId'] .", ClStock, NULL, NULL, NULL, '', NULL, 0, "
		."FORMAT(ClStock/FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2),2)
		, FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2)
		, FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2)*3
		, FORMAT(($beforeLastMonthDispensed+DispenseQty)/3,2)*3 - ClStock
		,FORMAT(($beforeLastMonthDispesnsed+DispenseQty)/3,2)*3 - ClStock, 0, now() "
		. "FROM t_country_product a LEFT JOIN "."t_cnm_stockstatus"." b ON a.ItemNo = b.ItemNo and MonthId = "
		. getLastMonth($year, $monthId) . " and Year = '" 
		. getYearForLastMonth($year, $monthId) ."' AND a.CountryId = b.CountryId AND a.ItemGroupId = b.ItemGroupId "
		. " WHERE a.CountryId = " .$_POST['pCountryId']. " AND a.ItemGroupId = " . $_POST['pItemGroupId'];
		
		//echo $sql4;
	
		$result4 = mysql_query($sql4);
		$error .= mysql_error();
		
		$bResult = $result1 && $result2 && $result3 && $result4;		
		
		if (!$bResult)
			throw new Exception("Query error:</br>".mysql_real_escape_string($error));
		
		mysql_query('COMMIT;');
		
		mysql_query('SET autocommit = 1;');
		
		echo '{success = 1; reportId ='.$reportId.'}';
	} 
	catch (Exception $e) 
	{
		mysql_query('ROLLBACK;');
		//print_r($e->getLine());
		echo '{ success = 0; error = "' . $e->getMessage() .'" }';				
	}	
}

function updateStockData() {
	global $monthId, $year, $reportId, $userId;		
	try 
	{
		$error = '';
		
		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');		
		
		$sql = "UPDATE "."t_cnm_stockstatus".' SET';	
		$sql .= " OpStock = ".$_POST['pOpStock_A'];	
		$sql .= ", ReceiveQty = ".checkNumber($_POST['pReceiveQty']);
		$sql .= ", DispenseQty = ".checkNumber($_POST['pDispenseQty']);
		$sql .= ", AdjustQty = ".checkNumber($_POST['pAdjustQty']);
		$sql .= ", AdjustId = ".checkString($_POST['pAdjustReason']);
		$sql .= ", StockoutDays = ".checkNumber($_POST['pStockoutDays']);
		$sql .= ", ClStock = ".checkNumber($_POST['pClStock_A']);
		//$sql .= ", ClStock_C = ".checkNumber($_POST['pClStock_C']);
		$sql .= ", ClStockSourceId = ".checkNumber($_POST['pClStockSourceId']);
		$sql .= ", MOS = ".checkNumber($_POST['pMOS']);	 
		$sql .= ", AMC = ".checkNumber($_POST['pAMC']);
		$sql .= ", AmcChangeReasonId = ".checkNumber($_POST['pAmcChangeReasonId']);
		$sql .= ", MaxQty = ".checkNumber($_POST['pMaxQty']);
		$sql .= ", OrderQty = ".checkNumber($_POST['pOrderQty']);
		$sql .= ", ActualQty = ".checkNumber($_POST['pActualQty']);
		$sql .= ", LastEditTime = now()";
		$sql .= " WHERE CNMStockStatusId = ".checkNumber($_POST['pARVDataId']); 	
		
		$result1 = mysql_query($sql);
		$error = mysql_error()."</br>";
		
		$sql1 = "UPDATE "."t_cnm_masterstockstatus".' SET';	
		$sql1 .= " LastUpdateBy = '" . $userId;
		$sql1 .= "', LastUpdateDt = now()";
		$sql1 .= " WHERE CNMStockId = ".$reportId . " AND Year = '$year'";		 	
						
		$result2 = mysql_query($sql1);						
			
		$bResult = $result1 && $result2;		
		
		if (!$bResult)
			throw new Exception("Query error:</br>".mysql_real_escape_string($error));
		
		mysql_query('COMMIT;');
		
		mysql_query('SET autocommit = 1;');
		
		echo '{success = 1; reportId ='.$reportId.'}';
	} 
	catch (Exception $e) 
	{
		mysql_query('ROLLBACK;');
		echo '{ success = 0; error = "' . $e->getMessage() .'" }';				
	}		
}

function updateStockDataAll() {
	global $monthId, $year, $modified_data, $reportId, $userId;	
	
	
	try 
	{
		$error = '';
		
		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');	
		
		$data = json_decode(stripslashes($modified_data));	
		
		$result1 = True;	
		
		foreach ($data as $arvdata) {		  		
			$sql = "UPDATE t_cnm_stockstatus SET";	
			$sql .= " OpStock = ".$arvdata->OpStock_A;	
			$sql .= ", ReceiveQty = ".checkNumber($arvdata->ReceiveQty);
			$sql .= ", DispenseQty = ".checkNumber($arvdata->DispenseQty);
			$sql .= ", AdjustQty = ".checkNumber($arvdata->AdjustQty);
			$sql .= ", AdjustId = ".checkString($arvdata->AdjustId);
			$sql .= ", StockoutDays = ".checkNumber($arvdata->StockoutDays);
			$sql .= ", ClStock = ".checkNumber($arvdata->ClStock_A);	
			$sql .= ", MOS = ".checkNumber($arvdata->MOS);	 
			$sql .= ", AMC = ".checkNumber($arvdata->AMC);
			$sql .= ", MaxQty = ".checkNumber($arvdata->MaxQty);
			$sql .= ", OrderQty = ".checkNumber($arvdata->OrderQty);
			$sql .= ", LastEditTime = now()";
			$sql .= " WHERE CNMStockStatusId = ".checkNumber($arvdata->ARVDataId); 

			$result1 = $result1 && mysql_query($sql);
			$error = mysql_error()."</br>";
		}	
				
		$sql1 = "UPDATE t_cnm_masterstockstatus SET";	
		$sql1 .= " LastUpdateBy = '" . $userId;
		$sql1 .= "', LastUpdateDt = now()";
		$sql1 .= " WHERE CNMStockId = ".$reportId . " AND Year = '$year'";		 	
						
		$result2 = mysql_query($sql1);						
			
		$bResult = $result1 && $result2;		
		
		if (!$bResult)
			throw new Exception("Query error:</br>".mysql_real_escape_string($error));
		
		mysql_query('COMMIT;');
		
		mysql_query('SET autocommit = 1;');
		
		echo '{success = 1; error = "No Error"}';
	} 
	catch (Exception $e) 
	{
		mysql_query('ROLLBACK;');
		echo '{ success = 0; error = "' . $e->getMessage() .'" }';				
	}
		
}

// function updateStockDataAll() {
	// global $monthId, $year, $modified_data, $reportId, $userId;	
// 	
	// $success = 0;
	// $failure = 0;
// 	
	// $sql1 = "UPDATE "."t_cnm_masterstockstatus".' SET';	
	// $sql1 .= " LastUpdateBy = '" . $userId;
	// $sql1 .= "', LastUpdateDt = now()";
	// $sql1 .= " WHERE CNMStockId = ".$reportId . " AND Year = '$year'";	
	// //echo $sql1; 	 	
	// $result1 = mysql_query($sql1);
// 			
	// $data = json_decode(stripslashes($modified_data));	
	// //echo $data[0][0];
	// foreach ($data as $arvdata) {
		// //echo $arvdata->ReceiveQty;   		
		// $sql = "UPDATE "."t_cnm_stockstatus".' SET';	
		// $sql .= " OpStock_A = ".$arvdata->OpStock_A;	
		// $sql .= ", ReceiveQty = ".checkNumber($arvdata->ReceiveQty);
		// $sql .= ", DispenseQty = ".checkNumber($arvdata->DispenseQty);
		// $sql .= ", AdjustQty = ".checkNumber($arvdata->AdjustQty);
		// $sql .= ", AdjustReason = ".checkString($arvdata->AdjustReason);
		// $sql .= ", StockoutDays = ".checkNumber($arvdata->StockoutDays);
		// $sql .= ", ClStock_A = ".checkNumber($arvdata->ClStock_A);	
		// $sql .= ", MOS = ".checkNumber($arvdata->MOS);	 
		// $sql .= ", AMC = ".checkNumber($arvdata->AMC);
		// $sql .= ", MaxQty = ".checkNumber($arvdata->MaxQty);
		// $sql .= ", OrderQty = ".checkNumber($arvdata->OrderQty);
		// //$sql .= ", ActualQty = ".checkNumber($arvdata->ActualQty);
		// $sql .= ", LastEditTime = now()";
		// $sql .= " WHERE ARVDataId = ".checkNumber($arvdata->ARVDataId); 
		// //runSql($sql);	
		// $result = mysql_query($sql);
		// if($result) {
		  // $success += 1;
		// } else {
			// $failure += 1;			
		// }
	// }
	// if($success>0)
		// echo '{success = 1; error = "No Error"}';
	// if($failure>0)
		// echo '{success = 0; error = "Invalid query: ' . mysql_error() . ', SQL: ' . $sql . '"}';
// 			
// }


function changeBsubmittedInMaster() {
	global $monthId, $year, $reportId, $userId;		
	$sql = "UPDATE "."t_cnm_masterstockstatus".' SET';	
	$sql .= " StatusId = " . $_POST['pStatusId'];	
		
	if($_POST['pStatusId'] == 2){
		$sql .= ", LastSubmittedBy = ".$userId;
		$sql .= ", LastSubmittedDt = now()";
	}
	else if($_POST['pStatusId'] == 3)
		$sql .= ", AcceptedDt = now()";
	else if($_POST['pStatusId'] == 5)
		$sql .= ", PublishedDt = now()";
	
	$sql .= " WHERE CNMStockId = ".$reportId . " AND Year = '$year'";	 	 	
	$result = mysql_query($sql);
	if($result) {		
		getMasterStockData();
	} else {
		echo '{success = 0; error = "Invalid query: ' . mysql_error() . ', SQL: ' . $sql . '"}';
	}
}

// function insertPatientOverview() {	
	// global 	$facilityId, $monthId, $year, $prevYear, $prevMonth;		
	// $sql="INSERT INTO t_cnm_patientoverview (FormulationId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient) "
		// . "SELECT a.FormulationId, " . $facilityId . ", ".$_POST['pCountryId'].", " . $monthId.",'" . $year ."',". " 0, 0, 0 FROM t_formulation a WHERE ItemGroupId = " . $_POST['pItemGroupId'];	
	// //echo $sql;
	// runSql($sql);		
// }

function updatePatientOverview() {
	global 	$facilityId, $monthId, $year;	
	$sql = "UPDATE "."t_cnm_patientoverview"." SET ";	
	$sql .= " RefillPatient = ".$_POST['refillPatient'];	
	$sql .= ", NewPatient = ".$_POST['newPatient'];	
	$sql .= ", TotalPatient = ".$_POST['totalPatient'];	
	$sql .= " WHERE CNMPOId = ".$_POST['cNMPOId'];	
	runSql($sql);		
}

// function insertRegimenPatient() {		
	// global 	$facilityId, $monthId, $year;
// 	
	// $sql = "INSERT INTO "."t_cnm_regimenpatient"." (RegimenId, FacilityId, CountryId, MonthId, Year, RefillPatient, NewPatient, TotalPatient) "
		// . "SELECT RegimenId, " . $facilityId . ", CountryId, " . $monthId.",'" . $year ."',". " 0, 0, 0 FROM t_country_regimen WHERE CountryId = " . $_POST['pCountryId'];		
// 	
	// //echo "$sql";
// 	
	 // runSql($sql);			
// }

function updateRegimenPatient() {
	global 	$facilityId, $year;	
	$sql = "UPDATE "."t_cnm_regimenpatient"." SET ";	
	$sql .= " RefillPatient = ".$_POST['RefillPatient'];
	$sql .= ", NewPatient = ".$_POST['NewPatient'];
	$sql .= ", TotalPatient = ".$_POST['TotalPatient'];	
	$sql .= " WHERE CNMPatientStatusId = ".$_POST['CNMPatientStatusId'];
	runSql($sql);		
}

function  delete_data_from_yyyy() {	
	global 	$facilityId, $monthId, $year;			
	$sql1 = "DELETE FROM "."t_cnm_patientoverview";	
	$sql1 .= " WHERE MonthId = " . $monthId. " and Year = '" . $year ."' AND CountryId = " . $_POST['pCountryId'];
			
	$sql2 = "DELETE FROM "."t_cnm_regimenpatient";	
	$sql2 .= " WHERE MonthId = " . $monthId. " and Year = '" . $year ."' AND CountryId = " . $_POST['pCountryId'];	
	
	$sql3 = "DELETE FROM "."t_cnm_stockstatus";	
	$sql3 .= " WHERE MonthId = " . $monthId. " and Year = '" . $year ."' AND CountryId = " . $_POST['pCountryId'] . " and ItemGroupId = " . $_POST['pItemGroupId'];
	
	$sql4 = "DELETE FROM "."t_cnm_masterstockstatus";	
	$sql4 .= " WHERE MonthId = " . $monthId. " and Year = '" . $year . "' AND CountryId = " . $_POST['pCountryId'] . " and ItemGroupId = " . $_POST['pItemGroupId'];
			
	try 
	{		
		$error = '';
		
		mysql_query('SET autocommit = 0;');
		mysql_query('START TRANSACTION;');	
			
		$result1 = mysql_query($sql1);
		$error = mysql_error()."</br>";
		
		$result2 = mysql_query($sql2);
		$error .=mysql_error()."</br>";
		
		$result3 = mysql_query($sql3);
		$error .= mysql_error()."</br>";
		
		$result4 = mysql_query($sql4);
		$error .= mysql_error();
		
		$bResult = $result1 && $result2 && $result3 && $result4;		
		
		if (!$bResult)
			throw new Exception("Query error:</br>".mysql_real_escape_string($error));
		
		mysql_query('COMMIT;');
		
		mysql_query('SET autocommit = 1;');
		
		echo '{ success = 1; error = "" }';	
	} 
	catch (Exception $e) 
	{
		mysql_query('ROLLBACK;');
		echo '{ success = 0; error = "' . $e->getMessage() .'" }';		
	}	
}


function  getLmisStartMonthYear() {
			
	$query = "SELECT LmisStartMonth, LmisStartYear FROM tsettings Where id = 1;";
		
	$result = mysql_query($query);
	if($result)
	while($row = mysql_fetch_assoc($result)){
		$lmisStartMonth = $row['LmisStartMonth'];
		$lmisStartYear = $row['LmisStartYear'];	
		echo '{lmisStartMonth='.$lmisStartMonth.'; lmisStartYear='.$lmisStartYear.';}';		
	}
	else 'Error : ' . mysql_error();
	
}

?> 
