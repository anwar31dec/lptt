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
 	case "getstockoutstatustable" :
		getstockoutstatustable();
		break;	
	default :
		echo "{failure:true}";
		break;
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

if($lan = 'en-GB'){
	 $MosTypeName = 'MosTypeName';
}
else{
	$MosTypeName = 'MosTypeNameFrench';
}

	$sq = "SELECT $MosTypeName AS MosTypeName FROM t_mostype_facility WHERE CountryId = 1 AND FLevelId = 99 order by MosTypeId asc;";
	$result = mysql_query($sq);
	$MOSTypeMasterList=Array();
	//Get mostype list value is not fact but index is usable
	while($row = mysql_fetch_assoc($result)) {
		$MOSTypeMasterList[$row['MosTypeName']] = -1;
	}


	$sq1 = "SELECT a.ItemNo, COUNT(a.FacilityId) AS rptFacilityCount 
	FROM t_cfm_stockstatus a 
	INNER JOIN t_cfm_masterstockstatus b ON a.CFMStockId = b.CFMStockId AND b.StatusId = 5
	INNER JOIN t_facility c ON a.FacilityId = c.FacilityId  AND c.FLevelId = 99
	WHERE a.MonthId = 1 AND a.Year = '2014' AND a.ItemGroupId = 1
	AND (a.CountryId = 1)
	AND a.MOS IS NOT NULL
	GROUP BY a.ItemNo;";
	$result1 = mysql_query($sq1);
	$TotalReportedFacilityCount = Array();
    while($row1 = mysql_fetch_assoc($result1)) {
	  $TotalReportedFacilityCount[$row1['ItemNo']] = $row1['rptFacilityCount'];
    }
//print_r($TotalReportedFacilityCount);
	$sq2 = "SELECT p.ItemNo,p.ShortName,p.MosTypeName, COUNT(p.FacilityId) AS StockOutFacilityCount FROM(			
	SELECT a.ItemNo, b.ShortName, a.FacilityId, IFNULL(a.MOS,0) AS MOS
	,(SELECT $MosTypeName FROM t_mostype_facility x WHERE x.FLevelId = 99 AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos) MosTypeName

	FROM t_cfm_stockstatus a 
	INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = 1
	INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId AND c.StatusId = 5
	INNER JOIN t_facility d ON a.FacilityId = d.FacilityId  AND d.FLevelId = 99
	WHERE a.MonthId = 1 AND a.Year = '2014'
	AND (a.CountryId = 1)
	AND a.MOS IS NOT NULL) p
	GROUP BY p.ShortName, p.ItemNo, p.MosTypeName;";

$result2 = mysql_query($sq2);
$total = mysql_num_rows($result2);
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
	
	$tmpMostypeName = '';
	$preItemName = '';
	$preTotalrptFacilityCount = 0;
	$flagIndex = 0;
	
	  while ($row2 = mysql_fetch_assoc($result2)) {
		  
		  if(!is_null($row2['StockOutFacilityCount']))	
			 settype($value, "integer"); 
			 $findPercentage = ($row2['StockOutFacilityCount']*100)/(($TotalReportedFacilityCount[$row2['ItemNo']]==0) ? 1 : $TotalReportedFacilityCount[$row2['ItemNo']]);
		  
		  if($preItemName != $row2['ShortName'] && $tmpMostypeName != $row2['MosTypeName']){
			  
			  if($flagIndex > 0){
				  if ($f++)
					$sOutput .= ',';
					$sOutput .= "[";
					$sOutput .= '"' . $preItemName . '",';
					  foreach($MOSTypeMasterList as $key=>$value){							
						$sOutput .= '"' . $value . '",';						
						}
					$sOutput .= '"' . $preTotalrptFacilityCount . '"';
					$sOutput .= "]";
			  }
			  
			  
			  foreach($MOSTypeMasterList as $key=>$value){
					$MOSTypeMasterList[$key] = '0% (0/'.$TotalReportedFacilityCount[$row2['ItemNo']].')' ; // reassign the array's value
				}
				
			$flagIndex++;
			$preItemName = $row2['ShortName'];//.$row2['ItemNo'];
			$tmpMostypeName = $row2['MosTypeName'];
			$preTotalrptFacilityCount = $TotalReportedFacilityCount[$row2['ItemNo']];
			$MOSTypeMasterList[$row2['MosTypeName']] = number_format($findPercentage,2).'% ('.$row2['StockOutFacilityCount'].'/'.$TotalReportedFacilityCount[$row2['ItemNo']].')';
		  }
		  else{
			$MOSTypeMasterList[$row2['MosTypeName']] = number_format($findPercentage,2).'% ('.$row2['StockOutFacilityCount'].'/'.$TotalReportedFacilityCount[$row2['ItemNo']].')';
			$preItemName = $row2['ShortName'];
			$tmpMostypeName != $row2['MosTypeName'];
			$preTotalrptFacilityCount = $TotalReportedFacilityCount[$row2['ItemNo']];
		  }		  		
	}
	
	 if($flagIndex > 0){
		 
		  if ($f++)
			$sOutput .= ',';
			$sOutput .= "[";
			$sOutput .= '"' . $preItemName . '",';
			  foreach($MOSTypeMasterList as $key=>$value){							
				$sOutput .= '"' . $value . '",';						
				}
			$sOutput .= '"' . $preTotalrptFacilityCount . '"';
			$sOutput .= "]]}";
		  }
		 
//	echo $sOutput;
echo '{"sEcho": 29, "iTotalRecords": 45, "iTotalDisplayRecords": 45, "aaData": [ ["AL 1X6","13.04% (3/23)"
,"8.70% (2/23)","8.70% (2/23)","21.74% (5/23)","47.83% (11/23)","23"],["AL 1X6 dispersible","100.00%
 (1/1)","0% (0/1)","0% (0/1)","0% (0/1)","0% (0/1)","1"],["AL 2X6","16.67% (3/18)","0% (0/18)","5.56
% (1/18)","27.78% (5/18)","50.00% (9/18)","18"],["AL 4X6","20.00% (4/20)","5.00% (1/20)","10.00% (2/20
)","15.00% (3/20)","50.00% (10/20)","20"],["Arthemether 20mg inj","0% (0/1)","0% (0/1)","0% (0/1)","0
% (0/1)","100.00% (1/1)","1"],["LN ","15.00% (3/20)","5.00% (1/20)","25.00% (5/20)","5.00% (1/20)","50
.00% (10/20)","20"],["Quinie 400mg inj","0% (0/1)","0% (0/1)","0% (0/1)","100.00% (1/1)","0% (0/1)","1"
],["Quinine 200mg inj","0% (0/1)","0% (0/1)","100.00% (1/1)","0% (0/1)","0% (0/1)","1"],["Quinine tb"
,"12.50% (1/8)","0% (0/8)","25.00% (2/8)","0% (0/8)","62.50% (5/8)","8"],["SP B/1000","35.00% (7/20)"
,"5.00% (1/20)","0% (0/21)","15.00% (3/20)","45.00% (9/20)","20"]]",COLUMNS":[{"sTitle": "SL", "sWidth"
:"5%"}, {"sTitle": "Patient Type", "sClass" : "PatientType"}, {"sTitle": "Jan 2014", "sClass" : "MonthName"
}, {"sTitle": "Feb 2014", "sClass" : "MonthName"}, {"sTitle": "Mar 2014", "sClass" : "MonthName"}, {"sTitle"
: "Apr 2014", "sClass" : "MonthName"}, {"sTitle": "May 2014", "sClass" : "MonthName"}, {"sTitle": "Jun
 2014", "sClass" : "MonthName"}, {"sTitle": "Jul 2014", "sClass" : "MonthName"}, {"sTitle": "Aug 2014"
, "sClass" : "MonthName"}, {"sTitle": "Sep 2014", "sClass" : "MonthName"}, {"sTitle": "Oct 2014", "sClass"
 : "MonthName"}, {"sTitle": "Nov 2014", "sClass" : "MonthName"}, {"sTitle": "Dec 2014", "sClass" : "MonthName"
}, {"sTitle": "Jan 2015", "sClass" : "MonthName"}, {"sTitle": "Feb 2015", "sClass" : "MonthName"}, {"sTitle"
: "Mar 2015", "sClass" : "MonthName"}, {"sTitle": "Apr 2015", "sClass" : "MonthName"}]}';
	
}
  

?>














