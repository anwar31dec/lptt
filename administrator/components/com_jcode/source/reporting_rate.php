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
	case "getPercentage" :
		getPercentage();
		break;
	default :
		echo "{failure:true}";
		break;
}

function getPercentage(){
    $CountryId = $_POST['CountryId'];
	$ItemGroupId = $_POST['ItemGroupId'];
	$OwnerTypeId = $_POST['OwnerTypeId'];
	$RegionId = isset($_POST['RegionId'])? $_POST['RegionId'] : 0;
	$DistrictId = isset($_POST['DistrictId'])? $_POST['DistrictId'] : 0;
	$Year = $_POST['Year'];   
    $Month = $_POST['Month'];
    $lan = $_POST['lan'];
	

	if(empty($CountryId))
		$CountryId = 0;
	if(empty($ItemGroupId))
		$ItemGroupId = 0;
	if(empty($OwnerTypeId))
		$OwnerTypeId = 0;
	if(empty($RegionId))
		$RegionId = 0;
	if(empty($DistrictId))
		$DistrictId = 0;
	
	
    if($lan == 'en-GB'){
            $FLevelName = 'q.FLevelName';
        }else{
            $FLevelName = 'q.FLevelNameFrench';
        }
		
	$innerWhere = '';
	$outerWhere = '';
	/*
	if($ItemGroupId > 3){
	//common baskate
	}
	else{
	 $innerWhere.= ' AND (r.ItemGroupId = '.$ItemGroupId.' OR '.$ItemGroupId.' = 0) ';
	 $outerWhere.= ' AND (b.ItemGroupId = '.$ItemGroupId.' OR '.$ItemGroupId.'=0) ';
	}
	*/
	if($OwnerTypeId == 3){
		$innerWhere.= ' AND (p.AgentType = '.$OwnerTypeId.' OR 0 = '.$OwnerTypeId.')';
		$outerWhere.= ' AND (c.AgentType = '.$OwnerTypeId.' OR 0 = '.$OwnerTypeId.')';
	}
	else{
		$innerWhere.= ' AND (p.OwnerTypeId = '.$OwnerTypeId.' OR 0 = '.$OwnerTypeId.') ';
		$outerWhere.= ' AND (c.OwnerTypeId = '.$OwnerTypeId.' OR 0 = '.$OwnerTypeId.')';
	}
	
    $sql = "SELECT m.FLevelId, m.FLevelName, m.fCount, n.invCount, FORMAT((n.invCount*100)/m.fCount,2) percent
			FROM
			 (SELECT p.FLevelId,$FLevelName FLevelName, COUNT(p.FacilityId) fCount
			 FROM t_facility p
			 INNER JOIN t_facility_level q ON p.FLevelId = q.FLevelId
			 WHERE (p.CountryId = $CountryId  OR $CountryId = 0)			
			 $innerWhere
			 AND (p.RegionId = $RegionId  OR $RegionId = 0)
			 AND (p.DistrictId = $DistrictId  OR $DistrictId = 0)
			 GROUP BY p.FLevelId,$FLevelName) m LEFT JOIN 

			(SELECT c.FLevelId, COUNT(DISTINCT b.FacilityId) invCount
			 FROM t_cfm_masterstockstatus a
			 INNER JOIN t_cfm_stockstatus b ON a.CFMStockId = b.CFMStockId
			 INNER JOIN t_facility c ON b.FacilityId = c.FacilityId
			 WHERE a.StatusId = 5
			 AND (b.CountryId = $CountryId OR $CountryId=0)
			 AND b.Year = '$Year'
			 AND b.MonthId = $Month			
			 $outerWhere
			 AND (c.RegionId = $RegionId  OR $RegionId = 0)
			 AND (c.DistrictId = $DistrictId  OR $DistrictId = 0)
			 GROUP BY c.FLevelId) n ON m.FLevelId = n.FLevelId
			 ORDER BY m.FLevelId DESC;"; 
			//echo $sql;
     
	 
    $result = mysql_query($sql);
	$sOutput = array("HealthFaclilities"=>0, "DistrictWarehouse"=>0, "RegionalWarhouse"=>0, "CentralWarehouse"=>0,"Total"=>0);
	
	$totalFacility=0;
	$totalInvGenerateFacility=0;
	$total=0;
   	while ($aRow = mysql_fetch_object($result)) {
		/*if($aRow->FLevelId == 99)
   	      $sOutput['HealthFaclilities'] = $aRow->FLevelName.'  ' . $aRow->percent.' %';
		else if($aRow->FLevelId == 3)
   	      $sOutput['DistrictWarehouse'] = $aRow->FLevelName.'  ' . $aRow->percent.' %';
		else if($aRow->FLevelId == 2)
   	      $sOutput['RegionalWarhouse'] = $aRow->FLevelName.'  ' . $aRow->percent.' %';
		else if($aRow->FLevelId == 1)
   	      $sOutput['CentralWarehouse'] = $aRow->FLevelName.'  ' . $aRow->percent.' %';
		  */
		 if($aRow->FLevelId == 99)
   	      $sOutput['HealthFaclilities'] = ($aRow->percent == null ? 0 : $aRow->percent);
		else if($aRow->FLevelId == 3)
   	      $sOutput['DistrictWarehouse'] = ($aRow->percent == null ? 0 : $aRow->percent);
		else if($aRow->FLevelId == 2)
   	      $sOutput['RegionalWarhouse'] = ($aRow->percent == null ? 0 : $aRow->percent);
		else if($aRow->FLevelId == 1)
   	      $sOutput['CentralWarehouse'] = ($aRow->percent == null ? 0 : $aRow->percent);
		$total+=$total+$aRow->percent;
		
		$totalFacility+= $aRow->fCount;
		$totalInvGenerateFacility+= $aRow->invCount;
    }
         if($totalFacility>0){
             $sOutput['Total'] = number_format(($totalInvGenerateFacility*100)/$totalFacility,2);
         }else{
             $sOutput['Total'] = "0.00";
         }
	
	
	echo json_encode($sOutput);
	
}
	
	
?>