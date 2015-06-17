<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$task = '';
if (isset($_REQUEST['action'])) {
	$task = $_REQUEST['action'];
}

switch($task) {
	case "getstockoutstatuschart" :
		getstockoutstatuschart();
		break;
 	case "getstockoutstatustable" :
		getstockoutstatustable();
		break;	
	default :
		echo "{failure:true}";
		break;
}

function getstockoutstatuschart() {

$lan = $_REQUEST['lan'];
$countryId = $_POST['Country'];	
$ItemGroupId = $_POST['ItemGroupId'];
$year = $_POST['Year'];
$MonthId = $_POST['Month'];	
$OwnerTypeId = $_POST['OwnerType'];	
$RegionId = $_POST['RegionId'];	
$DistrictId = $_POST['DistrictId'];

if($lan = 'en-GB')
	 $presentation = 'Presentations';
else
	$presentation = 'présentations';

	$sq2 = "SELECT  0 StockoutItemCount, COUNT(p.FacilityId) AS facilityCount FROM
	(SELECT b.FacilityId, COUNT(a.ItemNo) AS StockoutItemCount
	FROM t_itemlist a
	INNER JOIN t_cfm_stockstatus b ON a.ItemNo = b.ItemNo
	INNER JOIN t_cfm_masterstockstatus c ON b.CFMStockId = c.CFMStockId AND c.StatusId = 5
	INNER JOIN t_facility d ON b.FacilityId = d.FacilityId AND d.FLevelId = 99
	WHERE a.ItemNo IN (32,34,35,36)
	AND (b.CountryId=$countryId OR $countryId=0) 
	AND (d.RegionId=$RegionId OR $RegionId=0) 
	AND (d.DistrictId=$DistrictId OR $DistrictId=0) 
	AND (d.OwnerTypeId = $OwnerTypeId OR $OwnerTypeId=0) 
	AND b.Year = '$year'
	AND b.MonthId=$MonthId
	AND IFNULL(b.ClStock,0) > 0
	GROUP BY b.FacilityId
	HAVING StockoutItemCount = 5) p
	GROUP BY p.StockoutItemCount

	UNION ALL

	SELECT  p.StockoutItemCount, COUNT(p.FacilityId) AS facilityCount FROM
	(SELECT b.FacilityId, COUNT(a.ItemNo) AS StockoutItemCount
	FROM t_itemlist a
	INNER JOIN t_cfm_stockstatus b ON a.ItemNo = b.ItemNo
	INNER JOIN t_cfm_masterstockstatus c ON b.CFMStockId = c.CFMStockId AND c.StatusId = 5
	INNER JOIN t_facility d ON b.FacilityId = d.FacilityId AND d.FLevelId = 99
	WHERE a.ItemNo IN (32,34,35,36)
	AND (b.CountryId=$countryId OR $countryId=0) 
	AND (d.RegionId=$RegionId OR $RegionId=0)  
	AND (d.DistrictId=$DistrictId OR $DistrictId=0) 
	AND (d.OwnerTypeId = $OwnerTypeId OR $OwnerTypeId=0) 
	AND b.Year = '$year'
	AND b.MonthId=$MonthId
	AND IFNULL(b.ClStock,0) = 0
	GROUP BY b.FacilityId) p
	GROUP BY p.StockoutItemCount;";
   
   
 $rResult2 = mysql_query($sq2);	
 $totalFacilityCount = 0;
 $dataList = Array();
  while ($row1 = mysql_fetch_assoc($rResult2)) {
	  $dataList[$row1['StockoutItemCount']] = $row1['facilityCount'];
	   
	  if(!is_null($row1['facilityCount']))	
			 settype($row1['facilityCount'], "integer");
		 
	   $totalFacilityCount+= $row1['facilityCount'];
  }
  //echo $totalFacilityCount;
  //I need all presentations 0,1,2,3,4=presentation
  $needArrayIndex = Array(0,1,2,3,4);
  foreach($needArrayIndex as $value){
	  
		if (!array_key_exists($value,$dataList))
		{
			$dataList[$value]=0;
		}
	   
   }
   //array sort by index
   ksort($dataList);
   
 $output = array();
 $output1 = array('name' => '', 'y' => '');

//$key => $value....here key=stockoutstatus and value=facility count
	 foreach($dataList as $key => $value){
	 
			if(!is_null($value))	
			 settype($value, "integer"); 
		     $findPercentage = ($value*100)/(($totalFacilityCount==0)?1:$totalFacilityCount);
			 $preLevel='';
			 if($key==0)
				 $preLevel = 4;
			 else if($key==1)
				 $preLevel = 3;
			 else if($key==2)
				 $preLevel = 2;
			 else if($key==3)
				 $preLevel = 1;
			 else if($key==4)
				 $preLevel = 'No';
			 
			 $output1['name'] = $preLevel.' '.$presentation; //, '.$row2['facilityCount'];	
			 $output1['y'] = $findPercentage;
			 $output['data'][] = $output1; 
	 }	
	echo json_encode($output);
}


function getstockoutstatustable(){
    
$lan = $_REQUEST['lan'];
$countryId = $_POST['Country'];	
$ItemGroupId = $_POST['ItemGroupId'];
$year = $_POST['Year'];
$MonthId = $_POST['Month'];	
$OwnerTypeId = $_POST['OwnerType'];	
$RegionId = $_POST['RegionId'];	
$DistrictId = $_POST['DistrictId'];

if($lan = 'en-GB')
	 $presentation = 'Presentations';
else
	$presentation = 'présentations';


	$sq2 = "SELECT SQL_CALC_FOUND_ROWS 0 StockoutItemCount, COUNT(p.FacilityId) AS facilityCount FROM
	(SELECT b.FacilityId, COUNT(a.ItemNo) AS StockoutItemCount
	FROM t_itemlist a
	INNER JOIN t_cfm_stockstatus b ON a.ItemNo = b.ItemNo
	INNER JOIN t_cfm_masterstockstatus c ON b.CFMStockId = c.CFMStockId AND c.StatusId = 5
	INNER JOIN t_facility d ON b.FacilityId = d.FacilityId AND d.FLevelId = 99
	WHERE a.ItemNo IN (32,34,35,36)
	AND (b.CountryId=$countryId OR $countryId=0) 
	AND (d.RegionId=$RegionId OR $RegionId=0)  
	AND (d.DistrictId=$DistrictId OR $DistrictId=0) 
	AND (d.OwnerTypeId = $OwnerTypeId OR $OwnerTypeId=0) 
	AND b.Year = '$year'
	AND b.MonthId=$MonthId
	AND IFNULL(b.ClStock,0) > 0
	GROUP BY b.FacilityId
	HAVING StockoutItemCount = 5) p
	GROUP BY p.StockoutItemCount

	UNION ALL

	SELECT  p.StockoutItemCount, COUNT(p.FacilityId) AS facilityCount FROM
	(SELECT b.FacilityId, COUNT(a.ItemNo) AS StockoutItemCount
	FROM t_itemlist a
	INNER JOIN t_cfm_stockstatus b ON a.ItemNo = b.ItemNo
	INNER JOIN t_cfm_masterstockstatus c ON b.CFMStockId = c.CFMStockId AND c.StatusId = 5
	INNER JOIN t_facility d ON b.FacilityId = d.FacilityId AND d.FLevelId = 99
	WHERE a.ItemNo IN (32,34,35,36)
	AND (b.CountryId=$countryId OR $countryId=0) 
	AND (d.RegionId=$RegionId OR $RegionId=0)  
	AND (d.DistrictId=$DistrictId OR $DistrictId=0) 
	AND (d.OwnerTypeId = $OwnerTypeId OR $OwnerTypeId=0) 
	AND b.Year = '$year'
	AND b.MonthId=$MonthId
	AND IFNULL(b.ClStock,0) = 0
	GROUP BY b.FacilityId) p
	GROUP BY p.StockoutItemCount;";
  // echo $sq2;
   
$result3 = mysql_query($sq2);
$total = mysql_num_rows($result3);
$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysql_query($sQuery);
$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

 $totalFacilityCount = 0;
 $dataList = Array();
  while ($row1 = mysql_fetch_assoc($result3)) {
	   $dataList[$row1['StockoutItemCount']] = $row1['facilityCount'];
	   
	  if(!is_null($row1['facilityCount']))	
			 settype($row1['facilityCount'], "integer");
		 
	   $totalFacilityCount+= $row1['facilityCount'];
  }
  
  //I need all presentations 0,1,2,3,4=presentation
  $needArrayIndex = Array(0,1,2,3,4);
  foreach($needArrayIndex as $value){
	  
		if (!array_key_exists($value,$dataList))
		{
			$dataList[$value]=0;
		}
	   
   }
   //array sort by index
   ksort($dataList);
  
	$sOutput = '{';
	$sOutput .= '"sEcho": ' . intval($_POST['sEcho']) . ', ';
	$sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
	$sOutput .= '"aaData": [ ';
	$serial = $_POST['iDisplayStart'] + 1;
	$f = 0;
	$gTotalFacilityCount=0;
	$TotalfindPercentage=0;
	//$key => $value....here key=stockoutstatus and value=facility count
	//echo $totalFacilityCount;
	//echo 'i m here';
	 foreach($dataList as $key => $value){
								//echo $value;
								//echo 'hi';
								
			if(!is_null($value))	
			 settype($value, "integer"); 
			 $findPercentage = ($value*100)/(($totalFacilityCount==0)?1:$totalFacilityCount);
			 $preLevel='';
			 if($key==0)
				 $preLevel = 4;
			 else if($key==1)
				 $preLevel = 3;
			 else if($key==2)
				 $preLevel = 2;
			 else if($key==3)
				 $preLevel = 1;
	//		 else if($aRow['StockoutItemCount']==4)
		//		 $preLevel = 1;
			 else if($key==4)
				 $preLevel = 'No';
			
			 
			if ($f++)
				$sOutput .= ',';
			$sOutput .= "[";	
			$sOutput .= '"' . $preLevel.' '.$presentation . '",';	
			$sOutput .= '"' . $value.'/'. $totalFacilityCount . '",';
			$sOutput .= '"' . number_format($findPercentage,1). '%' . '"';
			$sOutput .= "]";
			
			$TotalfindPercentage+=$findPercentage;
			$gTotalFacilityCount+=$value;
	}
	
	////////////For Toatal
	if($TotalfindPercentage > 0){
		$sOutput .= ",";
		$sOutput .= "[";	
		$sOutput .= '" Total ",';
		$sOutput .= '"' . $gTotalFacilityCount.'/'.$totalFacilityCount . '",';
		$sOutput .= '"' . number_format($TotalfindPercentage). '%' . '"';
		$sOutput .= "]";
	}

	$sOutput .= ']}';

	echo $sOutput;
}
  

?>














