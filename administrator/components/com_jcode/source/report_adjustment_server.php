<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case "getServiceIndicators" :
		getServiceIndicators();
		break;
 	case "getTotalPatient" :
		getTotalPatient();
		break;	
	default :
		echo "{failure:true}";
		break;
}

function getServiceIndicators() {
    
	mysql_query('SET CHARACTER SET utf8');    
    $Year = $_POST['Year'];   
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];  
    $itemgroupId = $_POST['ItemGroupId'];   
     
   
    $regionId = $_POST['RegionId'];
    $districtId = $_POST['DistrictId'];
    $ownertypeId = $_POST['OwnerType'];
     
	    
   	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " AND (FacilityName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                         OR AdjustReason LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
                         OR ItemName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%') ";
	}
      	    
    $sql = "SELECT a.FacilityId,a.FacilityName,b.ItemNo,d.ItemName,

            CASE WHEN b.AdjustQty>0 THEN b.AdjustQty ELSE 0 END AdjustQtyPlus,
            CASE WHEN b.AdjustQty<0 THEN b.AdjustQty ELSE 0 END AdjustQtyMinus, b.AdjustId, c.AdjustReason
            
            FROM t_facility a
            INNER JOIN t_cfm_stockstatus b ON a.FacilityId = b.FacilityId
            INNER JOIN t_adjust_reason c ON b.AdjustId = c.AdjustId
            INNER JOIN t_itemlist d ON b.ItemNo = d.ItemNo
            INNER JOIN t_cfm_masterstockstatus e ON b.CFMStockId = e.CFMStockId
            
            WHERE b.CountryId = $CountryId
			AND (a.RegionId = $regionId OR $regionId=0)
            AND (a.DistrictId = $districtId OR $districtId=0)
            AND (a.OwnerTypeId = $ownertypeId OR $ownertypeId=0)
            AND e.StatusId = 5
            AND b.Year = '$Year'
            AND b.MonthId = $Month
            AND (b.ItemGroupId = $itemgroupId OR $itemgroupId=0) $sWhere
            HAVING AdjustQtyPlus != 0 OR AdjustQtyMinus != 0
            $sOrder $sLimit";  //
			//echo $sql; 
                  
   	$result = mysql_query($sql);
      if($result){
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
       
		if ($f++) $sOutput .= ',';
		$sOutput .= "[";
		$sOutput .= '"' . $serial++ . '",';
        $sOutput .= '"' . addslashes($aRow['FacilityName']) . '",';
		$sOutput .= '"' . $aRow['ItemName'] . '",';
 	    $sOutput .= '"' . number_format($aRow['AdjustQtyPlus']) . '",';
        $sOutput .= '"' . abs($aRow['AdjustQtyMinus']) . '",';
        $sOutput .= '"' . $aRow['AdjustReason'] . '"';
        //$sOutput .= '"' . $totalPatient . '"';
		$sOutput .= "]";
	}
	$sOutput .= '] }';
	echo $sOutput;
    }

}

function fnColumnToField($i) {
	if ($i == 1)
		return "FacilityName ";
  	else if ($i == 2)
		return "ItemName ";
	else if ($i == 5)
		return "AdjustReason ";
}

function getTotalPatient(){
    
    $Year = $_POST['Year'];   
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];  
    $ServiceType = $_POST['ServiceType'];   
    $regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];
     
    if($CountryId){
		$CountryId = " AND a.CountryId = ".$CountryId." ";
	}
    
    $sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99
            AND (b.RegionId = ".$regionId." OR ".$regionId." = 0)  
            AND (b.DistrictId = ".$districtId." OR ".$districtId." = 0)	
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = ".$ServiceType."
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."  
            GROUP BY a.FacilityId, FacilityName"; //
			//echo $sql;
                
    $result = mysql_query($sql);
    $totalPatient = 0;
   	while ($aRow = mysql_fetch_object($result)) {
   	    $totalPatient = $totalPatient + $aRow->TotalPatient;
    }

    echo number_format($totalPatient);	
}
  

?>














