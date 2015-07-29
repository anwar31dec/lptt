<?php
include_once ('database_conn.php');
include_once ("function_lib.php");

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');

mysql_query('SET CHARACTER SET utf8');

$gTEXT = $TEXT;

$task = '';
if (isset($_POST['action'])) {
    $task = $_POST['action'];
} else if (isset($_GET['action'])) {
    $task = $_GET['action'];
}

switch ($task) {   
    case 'getDiffLevelTableData' :
        getDiffLevelTableData();
        break;

    default :
        echo "{failure:true}";
        break;
}

function getFacilitySummaryChart() {
    $Year = $_POST['Year'];
    $ItemGroupId = $_POST['ItemGroup'];
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];
    $ownnerTypeId = $_POST['OwnnerTypeId'];
    $lan = $_POST['lan'];

    if ($lan == 'en-GB') {
        $MonthList = array('1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        $fLevelName = 'FLevelName';
    } else {
        $MonthList = array('1' => 'Janvier', '2' => 'Février', '3' => 'Mars', '4' => 'Avril', '5' => 'Mai', '6' => 'Juin', '7' => 'Juillet', '8' => 'Août', '9' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
        $fLevelName = 'FLevelNameFrench';
    }


    if ($CountryId) {
        $CountryId = " AND a.CountryId = " . $CountryId . " ";
    }

    if ($ownnerTypeId == 1 || $ownnerTypeId == 2) {
        $sql = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, SUM(ClStock) FacilitySOH, SUM(AMC) FacilityAMC
	, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            FROM t_cfm_stockstatus a 
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5
            INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
            INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
            WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "
			AND d.OwnerTypeId = " . $ownnerTypeId . "
            GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
            /*HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0*/
			order by f.FLevelId;";
    } else {
        $sql = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, SUM(ClStock) FacilitySOH, SUM(AMC) FacilityAMC
	, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            FROM t_cfm_stockstatus a 
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 
            INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
            INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
            WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "
			AND d.AgentType = " . $ownnerTypeId . "
            GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
            /*HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0*/
			order by f.FLevelId;";
    }
    //echo $sql;            
    $result = mysql_query($sql);

    $dataList = array();
    $facilityList = array();
    $facilityColorList = array();
    $itemList = array();
    $dataValue = array();

    while ($row = mysql_fetch_assoc($result)) {
        $dataList[] = $row;
    }

    //Create facility, item and color list
    foreach ($dataList as $r) {
        $facilityList[$r['FLevelName']] = $r['FLevelName'];
        $facilityColorList[] = $r['ColorCode'];
        $itemList[$r['ItemName']] = $r['ItemName'];
    }
    $facilityList = array_unique($facilityList);
    $facilityColorList = array_unique($facilityColorList);
    $itemList = array_unique($itemList);


    //Create data list
    $tmpFacilityName = '';
    $tmpData = array();
    $count = 0;
    foreach ($dataList as $itemData) {

        if ($tmpFacilityName != $itemData['FLevelName']) {
            if ($count > 0)
                $dataValue[] = getArrayAllocate($tmpData);
            unset($tmpData);
            $tmpFacilityName = $itemData['FLevelName'];

            foreach ($itemList as $key => $value) {
                $tmpData[$key] = null;
            }
        }
        $count++;

        $mos = str_replace(',', '', (number_format($itemData['MOS'], 1)));
        settype($mos, "float");
        $tmpData[$itemData['ItemName']] = $mos;
    }
    $dataValue[] = getArrayAllocate($tmpData);

    $data = array();
    $data['item_name'] = getArrayAllocate($itemList);
    $data['level_name'] = getArrayAllocate($facilityList);
    $data['dataValue'] = $dataValue;
    $data['barcolor'] = getArrayAllocate($facilityColorList);
    $data['name'] = $MonthList[$Month] . ', ' . $Year;
    echo json_encode($data);
}

function getArrayAllocate($tmpData) {
    $dataArray = array();
    foreach ($tmpData as $key => $value) {
        $dataArray[] = $value;
    }
    return $dataArray;
}

//for table
function getDiffLevelTableData_01() {
    $Year = $_POST['Year'];
    $ItemGroupId = $_POST['ItemGroup'];
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];
    $ownnerTypeId = $_POST['OwnnerTypeId'];
    $lan = $_REQUEST['lan'];

    if ($lan == 'en-GB') {
        $fLevelName = 'FLevelName';
    } else {
        $fLevelName = 'FLevelNameFrench';
    }

    if ($CountryId) {
        $CountryId = " AND a.CountryId = " . $CountryId . " ";
    }

    $columnList = array();
    $productName = 'Product Name';

    $output = array('aaData' => array());
    $aData = array();
    $output2 = array();

    /* if ($ownnerTypeId == 1 || $ownnerTypeId == 2) {
        $sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
			, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            FROM t_cfm_stockstatus a 
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 
            INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
            INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
            WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "
			AND d.OwnerTypeId  = " . $ownnerTypeId . "
            GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
           
			order by ItemName,f.FLevelId;";
    } else {
        $sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
				, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
				FROM t_cfm_stockstatus a 
				INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = " . $ItemGroupId . "
				INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5
				INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
				INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = " . $ItemGroupId . "
				INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
				WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "
				AND d.AgentType = " . $ownnerTypeId . "
				GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
				
				order by ItemName,f.FLevelId;";
    } */
	
	 $sQuery = "SELECT ProcessId, ProcessName, ProcessOrder
				FROM t_process_list
				ORDER BY ProcessOrder;";
	
	
    //echo $sQuery;
    $rResult = safe_query($sQuery);
    $total = mysql_num_rows($rResult);
    $tmpItemName = '';

    $sl = 1;
    $count = 0;
    $preItemName = '';

    //echo 'Rubel';
    $data = array();
    $headerList = array();
    while ($row = mysql_fetch_assoc($rResult)) {
        $data[] = $row;
    }

    foreach ($data as $row) {
        ////Duplicate value not push in array
        //if (!in_array($row['FLevelName'], $headerList)) {
        //	$headerList[] = $row['FLevelName'];
        //}
        $headerList[$row['ProcessOrder']] = $row['ProcessName'];
    }
    //array_push($headerList,'National');
    //$headerList[999] = 'National';

    foreach ($headerList as $key => $value) {
        $columnList[] = $value; //.' Level AMC';
        $columnList[] = $value; //.' Level SOH';
        $columnList[] = $value; //.' Level MOS';
    }
	
	//$columnList[] = 'Total Time';
	
    $fetchDataList = array();

    foreach ($data as $row) {
        if ($tmpItemName != $row['ItemName']) {

            if ($count > 0) {
                $fetchDataList['999' . '2'] = number_format($fetchDataList['999' . '2']);
                $fetchDataList['999' . '3'] = number_format($fetchDataList['999' . '3'], 1);
                array_unshift($fetchDataList, $sl, $preItemName);
                $aData[] = $fetchDataList;
                $sl++;
            }
            $count++;

            $preItemName = $row['ItemName'];

            unset($fetchDataList);
            foreach ($headerList as $key => $value) {
                $fetchDataList[$key . '1'] = NULL;
                $fetchDataList[$key . '2'] = NULL;
                $fetchDataList[$key . '3'] = NULL;
            }
            $tmpItemName = $row['ItemName'];
        }

        $fLevelId = $row['FLevelId'];

        $fetchDataList[$fLevelId . '1'] = number_format($row['FacilityAMC']);
        $fetchDataList[$fLevelId . '2'] = number_format($row['FacilitySOH']);
        $fetchDataList[$fLevelId . '3'] = number_format($row['MOS'], 1);

        if ($fetchDataList['999' . '1'] < $row['FacilityAMC']) {
            $fetchDataList['999' . '1'] = number_format($row['FacilityAMC']);
        }

        $fetchDataList['999' . '2']+= $row['FacilitySOH'];
        $fetchDataList['999' . '3']+= $row['MOS'];
    }

    $fetchDataList['9992'] = 0.0;
    $fetchDataList['9993'] = 0.0;

    $fetchDataList['999' . '2'] = number_format($fetchDataList['999' . '2']);
    $fetchDataList['999' . '3'] = number_format($fetchDataList['999' . '3'], 1);

    array_unshift($fetchDataList, $sl, $preItemName);
    $aData[] = $fetchDataList;
    
    $sEcho = isset($_POST['sEcho'])? $_POST['sEcho'] : '';


    if ($total == 0) {
        echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[]';
        echo ',"COLUMNS":' . json_encode($columnList) . '}';
    } else {
        echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '';
        echo ',"COLUMNS":' . json_encode($columnList) . '}';
    }
}
function getDiffLevelTableData_0099() {
    $Year = $_POST['Year'];
    $ItemGroupId = $_POST['ItemGroup'];
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];
    $ownnerTypeId = $_POST['OwnnerTypeId'];
    $lan = $_REQUEST['lan'];

    if ($lan == 'en-GB') {
        $fLevelName = 'FLevelName';
    } else {
        $fLevelName = 'FLevelNameFrench';
    }

    if ($CountryId) {
        $CountryId = " AND a.CountryId = " . $CountryId . " ";
    }

    $columnList = array();
    $productName = 'Product Name';

    $output = array('aaData' => array());
    $aData = array();
    $output2 = array();

    /* if ($ownnerTypeId == 1 || $ownnerTypeId == 2) {
        $sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
			, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            FROM t_cfm_stockstatus a 
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 
            INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
            INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = " . $ItemGroupId . "
            INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
            WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "
			AND d.OwnerTypeId  = " . $ownnerTypeId . "
            GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
           
			order by ItemName,f.FLevelId;";
    } else {
        $sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
				, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
				FROM t_cfm_stockstatus a 
				INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = " . $ItemGroupId . "
				INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5
				INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
				INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = " . $ItemGroupId . "
				INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
				WHERE a.MonthId = " . $Month . " AND a.Year = '" . $Year . "' " . $CountryId . "
				AND d.AgentType = " . $ownnerTypeId . "
				GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
				
				order by ItemName,f.FLevelId;";
    } */
	
	 $sQuery = "SELECT 
				  t_process_tracking.TrackingNo, t_process_tracking.ProcessId, t_process_list.ProcessName, t_process_list.ProcessOrder, t_process_tracking.InTime, t_process_tracking.OutTime 
				FROM
				  t_process_tracking 
				  INNER JOIN t_process_list 
					ON t_process_tracking.ProcessId = t_process_list.ProcessId 
				WHERE EntryDate >= '2014-01-01' 
				  AND EntryDate <= '2015-06-31' 
				ORDER BY t_process_tracking.TrackingNo, t_process_list.ProcessOrder;";
	
	
    //echo $sQuery;
    $rResult = safe_query($sQuery);
    $total = mysql_num_rows($rResult);
    $tmpItemName = '';

    $sl = 1;
    $count = 0;
    $preItemName = '';

    //echo 'Rubel';
    $data = array();
    $headerList = array();
    while ($row = mysql_fetch_assoc($rResult)) {
        $data[] = $row;
    }

    foreach ($data as $row) {
        ////Duplicate value not push in array
        //if (!in_array($row['FLevelName'], $headerList)) {
        //	$headerList[] = $row['FLevelName'];
        //}
        $headerList[$row['ProcessOrder']] = $row['ProcessName'];
    }
    //array_push($headerList,'National');
    //$headerList[999] = 'National';

    foreach ($headerList as $key => $value) {
        $columnList[] = $value; //.' Level AMC';
        $columnList[] = $value; //.' Level SOH';
        $columnList[] = $value; //.' Level MOS';
    }
	
	//$columnList[] = 'Total Time';
	
    $fetchDataList = array();

    foreach ($data as $row) {
        if ($tmpItemName != $row['TrackingNo']) {

            if ($count > 0) {
                $fetchDataList['999' . '2'] = number_format($fetchDataList['999' . '2']);
                $fetchDataList['999' . '3'] = number_format($fetchDataList['999' . '3'], 1);
                array_unshift($fetchDataList, $sl, $preItemName);
                $aData[] = $fetchDataList;
                $sl++;
            }
            $count++;

            $preItemName = $row['TrackingNo'];

            unset($fetchDataList);
            foreach ($headerList as $key => $value) {
                $fetchDataList[$key . '1'] = NULL;
                $fetchDataList[$key . '2'] = NULL;
                $fetchDataList[$key . '3'] = NULL;
            }
            $tmpItemName = $row['TrackingNo'];
        }

        $fLevelId = $row['FLevelId'];

        $fetchDataList[$fLevelId . '1'] = ($row['InTime']);
        $fetchDataList[$fLevelId . '2'] = ($row['OutTime']);
        $fetchDataList[$fLevelId . '3'] = 0;

        // if ($fetchDataList['999' . '1'] < $row['FacilityAMC']) {
            // $fetchDataList['999' . '1'] = number_format($row['FacilityAMC']);
        // }

       // $fetchDataList['999' . '2']+= $row['FacilitySOH'];
       // $fetchDataList['999' . '3']+= $row['MOS'];
    }

    //$fetchDataList['9992'] = 0.0;
    //$fetchDataList['9993'] = 0.0;

    //$fetchDataList['999' . '2'] = number_format($fetchDataList['999' . '2']);
   // $fetchDataList['999' . '3'] = number_format($fetchDataList['999' . '3'], 1);

    array_unshift($fetchDataList, $sl, $preItemName);
    $aData[] = $fetchDataList;
    
    $sEcho = isset($_POST['sEcho'])? $_POST['sEcho'] : '';


    if ($total == 0) {
        echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":[]';
        echo ',"COLUMNS":' . json_encode($columnList) . '}';
    } else {
        echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aData) . '';
        echo ',"COLUMNS":' . json_encode($columnList) . '}';
    }
}

function getDiffLevelTableData() {
   
$StartDate = $_POST['dp1-start'];
$EndDate = $_POST['dp1-end'];

$query = "SELECT ProcessId, ProcessName, ProcessOrder
FROM t_process_list
ORDER BY ProcessOrder;";

//echo $query;

$result = mysql_query($query);

//$result = mysqli_get_result($query);

$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');

$aData = array();
//$aMonthYear = array();
$aTemplateValues = array();
$aColumns = array();

if ($result) {
	while ($rec = mysql_fetch_object($result)) {
		//get dynamic columns for the datatable
		$pia = $rec -> ProcessOrder . 'i'; // for InTime
		$pib = $rec -> ProcessOrder . 'o'; // for OutTime
		$pid = $rec -> ProcessOrder . 'd'; // for Duration
		
		$aColumns[] = $rec -> ProcessName; // for InTime
		$aColumns[] = $rec -> ProcessName; // for OutTime
		$aColumns[] = $rec -> ProcessName; // for Duration

		$aData[] = $rec;
		///get the initial value for each facility, if a facility have no value for a specific item then
		//the following array keep track the zero value for that position.
		$aTemplateValues[$pia] = '';
		$aTemplateValues[$pib] = '';
		$aTemplateValues[$pid] = '';
	}
}

$aTemplateValues['Total'] = 0;

//echo json_encode($aTemplateValues);
//exit;

$aaData = array();

$tmpFacilityCode = '';
$tmpFacilityName = '';
$tmpItemName = '';
$tmpUnitName = '';
$sl = 0;

$sQuery = "SELECT 
				  t_process_tracking.TrackingNo, t_process_tracking.RegNo, t_process_tracking.NoOfScann, t_process_tracking.ProcessId, t_process_list.ProcessName, t_process_list.ProcessOrder, t_process_tracking.InTime, t_process_tracking.OutTime,  Duration
				FROM
				  t_process_tracking 
				  INNER JOIN t_process_list 
					ON t_process_tracking.ProcessId = t_process_list.ProcessId 
				WHERE EntryDate >= '$StartDate' 
				  AND EntryDate <= '$EndDate' 
				ORDER BY t_process_tracking.TrackingNo, t_process_tracking.NoOfScann, t_process_list.ProcessOrder;";
	
	
$rResult = mysql_query($sQuery);

//$holidays = array("2015-06-18","2015-06-19");

if ($rResult) {
	while ($data = mysql_fetch_object($rResult)) {
		//print_r($data);
		$TrackNoAndNoOfScann = $data -> TrackingNo . $data -> NoOfScann;
		if ($TrackNoAndNoOfScann != $tmpFacilityCode) {

			// get each row to a array when it changes it state so in this case last row always skipped
			if (!is_null($row)) {
				$row['Total'] = is_null($row['Total'])? '' : convertToHoursMins($row['Total'],'%02d hours %02d minutes');
				$tmpRow = array_values($row);
				array_unshift($tmpRow, ++$sl, $tmpFacilityName);
				$aaData[] = $tmpRow;
			}
			// initialize the $row the zero values array sized with the number of facility.
			$row = $aTemplateValues;
			//$row['Total'] = 0;
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = is_null($data -> InTime)? '' : date('d/m/Y g:i A', strtotime($data -> InTime));
			$row[$data -> ProcessOrder.'o'] = is_null($data -> OutTime)? '' : date('d/m/Y g:i A', strtotime($data -> OutTime));
			$row[$data -> ProcessOrder.'d'] = is_null($data -> Duration)? '' : convertToHoursMins($data -> Duration,'%02d hours %02d minutes');
			
			//$row[$data -> ProcessOrder.'d'] = is_null($data -> InTime) || is_null($data -> OutTime)? '' : convertToHoursMins(getWorkingDays($data -> InTime,$data -> OutTime,$holidays) * 24 * 60, '%02d hours %02d minutes');
			
			$row['Total'] += is_null($data -> Duration)? 0 : $data -> Duration;
			
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> TrackingNo . $data -> NoOfScann;
			$tmpFacilityName = $data -> RegNo? $data -> RegNo : $data -> TrackingNo;
		} else {
			// collecting data for the facility
			$row[$data -> ProcessOrder.'i'] = is_null($data -> InTime)? '' : date('d/m/Y g:i A', strtotime($data -> InTime));
			$row[$data -> ProcessOrder.'o'] = is_null($data -> OutTime)? '' : date('d/m/Y g:i A', strtotime($data -> OutTime));
			$row[$data -> ProcessOrder.'d'] = is_null($data -> Duration)? '' : convertToHoursMins($data -> Duration,'%02d hours %02d minutes');
			
			//$row[$data -> ProcessOrder.'d'] = is_null($data -> InTime) || is_null($data -> OutTime)? '' : convertToHoursMins(getWorkingDays($data -> InTime,$data -> OutTime,$holidays) * 24 * 60, '%02d hours %02d minutes');
			
			$row['Total'] += is_null($data -> Duration)? 0 : $data -> Duration;
			// put the temp variable with the item code
			$tmpFacilityCode = $data -> TrackingNo . $data -> NoOfScann;
		}
		//print_r($row);
	}
}



$num_rows = mysql_num_rows($rResult);
//var_dump($num_rows);
if ($num_rows) {
	//print_r(array_values($row));
	// get the last row that is skipped in the above loop
	$row['Total'] = is_null($row['Total'])? '' : convertToHoursMins($row['Total'],'%02d hours %02d minutes');
	$tmpRow = array_values($row);
	array_unshift($tmpRow, ++$sl, $tmpFacilityName);
	$aaData[] = $tmpRow;
	//print_r($aaData);
}

//$clmMonthYear = array_values($aMonthYear);
//array_unshift($clmMonthYear, 'Warhouse Name');

//echo '{"sEcho": 0, "iTotalRecords":"10","iTotalDisplayRecords": "10","aaData":' . json_encode($aaData, JSON_NUMERIC_CHECK) . '}';

 echo '{"sEcho": ' . intval($sEcho) . ', "iTotalRecords":"10","iTotalDisplayRecords": "10", "aaData":' . json_encode($aaData) . '';
 echo ',"COLUMNS":' . json_encode($aColumns) . '}';
}

?>









