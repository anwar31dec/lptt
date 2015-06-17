<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case "getShipmentReportData" :
		getShipmentReportData();
		break;	
	default :
		echo "{failure:true}";
		break;
}


function getMonthsBtnTwoDate($firstDate, $lastDate) {
	$diff = abs(strtotime($lastDate) - strtotime($firstDate));
	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	return $months;
}

function getShipmentReportData() {
	
	$lan = $_POST['lan'];
	 if($lan == 'en-GB'){
            $CountryName = 'CountryName';
		   
        }else{
            $CountryName = 'CountryNameFrench';
			
        }
	
	
	
    $StartMonthId = $_POST['StartMonthId']; 
    $StartYearId = $_POST['StartYearId']; 
    $EndMonthId = $_POST['EndMonthId']; 
    $EndYearId = $_POST['EndYearId'];
	
	if($_POST['MonthNumber'] != 0){
        $months = $_POST['MonthNumber'];
        $monthIndex = date("m");
        $yearIndex = date("Y");
		 settype($yearIndex, "integer");  
		
		$startDate = $yearIndex."-".$monthIndex."-"."01";	
		$startDate = date('Y-m-d', strtotime($startDate));	
		$months--;
		$endDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($startDate)) . "+".$months." month"));  		
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";	
		$startDate = date('Y-m-d', strtotime($startDate));	
		
		$d=cal_days_in_month(CAL_GREGORIAN,$EndMonthId,$EndYearId);
    	$endDate = $EndYearId."-".$EndMonthId."-".$d;	
		$endDate = date('Y-m-d', strtotime($endDate));	    	
    }  
	
//////////////////
  
    $CountryId = $_POST['ACountryId'];
    $AFundingSourceId = $_POST['AFundingSourceId'];
    $ASStatusId = $_POST['ASStatusId'];
	$ItemGroup = $_POST['ItemGroup']; 
    $OwnerTypeId = $_POST['OwnerType']; 
    
    if($AFundingSourceId){
		$AFundingSourceId = " AND a.FundingSourceId = '".$AFundingSourceId."' ";
	}   
    if($ASStatusId){
		$ASStatusId = " AND a.ShipmentStatusId = '".$ASStatusId."' ";
	}
	 if($ItemGroup){
		$ItemGroup = " AND e.ItemGroupId = '".$ItemGroup."' ";
	}
     if($OwnerTypeId){
		$OwnerTypeId = " AND f.OwnerTypeId = '".$OwnerTypeId."' ";
	}
       
	$sLimit = "";
	if (isset($_POST['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_POST['iDisplayStart']) . ", " . mysql_real_escape_string($_POST['iDisplayLength']);
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
		$sWhere = "  AND (a.ItemNo LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'  OR " .
				     " e.ItemName LIKE '%".mysql_real_escape_string( $_POST['sSearch'] )."%' OR ".
				     " c.ShipmentStatusDesc LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%')  ";
	}

    $sql = "SELECT SQL_CALC_FOUND_ROWS AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.$CountryName CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty, a.OwnerTypeId, f.OwnerTypeName 
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo
            INNER JOIN t_owner_type f ON a.OwnerTypeId = f.OwnerTypeId 
            WHERE CAST(a.ShipmentDate AS DATETIME) BETWEEN CAST('$startDate' AS DATETIME) AND CAST('$endDate' AS DATETIME) 
            AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 
            ".$AFundingSourceId." ".$ASStatusId. " " .$ItemGroup. " " .$OwnerTypeId."
			$sWhere $sOrder $sLimit ";

	$result = mysql_query($sql);
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

	//$y = "<a class='task-del itmEdit' href='javascript:void(0);'><span class='label label-info'>".$gTEXT['Edit']."</span></a>";
	//$z = "<a class='task-del itmDrop' style='margin-left:4px' href='javascript:void(0);'><span class='label label-danger'>".$gTEXT['Delete']."</span></a>";

	$f = 0;
	$GrandtotalQty=0;
	$SubtotalQty=0;
	$OldCountry=' ';
	$NewCountry=' ';
	$rcount = 0;
	while ($aRow = mysql_fetch_array($result)) {

        $ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));
        $date = strtotime($aRow['ShipmentDate']);
        $newdate = date( 'd/m/Y', $date );
        
		/////////////////
		if($OldCountry==' ')
			$OldCountry=addslashes($aRow['CountryName']);
		
		$NewCountry = addslashes($aRow['CountryName']);		
		if($OldCountry != $NewCountry){			
				$sOutput.=',';
			$sOutput.="[";
			$sOutput.='"Sub Total",';
			$sOutput.='"",';
			$sOutput.='"",';
			$sOutput.='"",';
			$sOutput.='"",';
			$sOutput.='"'.number_format($SubtotalQty).'",';	
			$sOutput.='""';
			$sOutput.="]";
		
			$OldCountry=$NewCountry;			
			$SubtotalQty=$aRow['Qty'];
		}
		else
			$SubtotalQty+=$aRow['Qty'];
		//////////////////
		if ($f++)
			$sOutput .= ',';
		$sOutput .= "[";
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . $ItemName . '",';
        $sOutput .= '"' . addslashes($aRow['FundingSourceName']) . '",';
        $sOutput .= '"' . addslashes($aRow['ShipmentStatusDesc']) . '",';
 	    $sOutput .= '"' . $newdate . '",';
		$sOutput .= '"' . number_format(addslashes($aRow['Qty'])) . '",';       
        $sOutput .= '"' . addslashes($aRow['CountryName']) . '"';
		$sOutput .= "]";
		
		
		$GrandtotalQty+=$aRow['Qty'];
	}
	////
	if($total>0){
		 $sOutput.=',';
		$sOutput.="[";
		$sOutput.='"Sub Total",';
		$sOutput.='"",';
		$sOutput.='"",';
		$sOutput.='"",';
		$sOutput.='"",';
		$sOutput.='"'.number_format($SubtotalQty).'",';	
		$sOutput.='""';
		$sOutput.="]";
	}
	
	
	if($total>0){
			$sOutput.=',';
		$sOutput.="[";
		$sOutput.='"Grand Total",';
		$sOutput.='"",';
		$sOutput.='"",';
		$sOutput.='"",';
		$sOutput.='"",';
		$sOutput.='"'.number_format($GrandtotalQty).'",';	
		$sOutput.='""';
		$sOutput.="]";
	}
	////
	
	$sOutput .= '] }';
	echo $sOutput;
    	    
}

function fnColumnToField_agencyShipment($i) {
	if ($i == 1)
		return "ItemName ";
  	else if ($i == 2)
		return "FundingSourceName ";
  	else if ($i == 3)
		return "ShipmentStatusDesc ";
    else if ($i == 4)
    	return "ShipmentDate ";
    else if ($i == 5)
    	return "Qty ";
    else if ($i == 6)
        return "CountryName ";
}








