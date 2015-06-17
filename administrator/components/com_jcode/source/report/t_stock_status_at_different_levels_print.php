<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');
$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 
   
    $Month=$_GET['MonthId']; 
    $Year=$_GET['Year']; 
    $CountryId=$_GET['CountryId'];
    $ItemGroupId=$_GET['ItemGroupId'];
    $ownnerTypeId = $_GET['OwnnerTypeId']; //echo $ownnerTypeId;
    $CountryName=$_GET['CountryName'];   
    $MonthName = $_GET['MonthName'];
    $ItemGroupName = $_GET['ItemGroupName'];
    $OwnnerTypeName = $_GET['OwnnerTypeName']; 
    $lan = $_REQUEST['lan'];
    
    if($lan == 'en-GB'){ 
        $fLevelName = 'FLevelName';
    }else{
		 $fLevelName = 'FLevelNameFrench';
    } 
	
    if($CountryId){
		$CountryId = " AND a.CountryId = ".$CountryId." ";
	}
	
	$columnList = array(); 
 
	//$output = array('aaData' => array());
	$aData = array();
	//$output2 = array();
	
	if($ownnerTypeId==1 || $ownnerTypeId == 2){
	$sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
			, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            FROM t_cfm_stockstatus a 
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
            INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."
			AND d.OwnerTypeId  = ".$ownnerTypeId."
            GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
            HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0
			order by ItemName,f.FLevelId;";
	}
	else{
	$sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
				, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
				FROM t_cfm_stockstatus a 
				INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
				INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
				INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
				INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = ".$ItemGroupId."
				INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
				WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."
				AND d.AgentType = ".$ownnerTypeId."
				GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
				HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0
				order by ItemName,f.FLevelId;";
	}	
	//echo $sQuery;
	$rResult = mysql_query($sQuery);
	$total = mysql_num_rows($rResult);
	$tmpItemName = '';
	
	$sl = 1;
	$count = 0;
	$preItemName='';
	
	//echo 'Rubel';
	$data = array();
	$headerList = array();
	while ($row = mysql_fetch_assoc($rResult)) {
		$data[] = $row;
	}
	
	foreach($data as $row){
		////Duplicate value not push in array
		//if (!in_array($row['FLevelName'], $headerList)) {
		//	$headerList[] = $row['FLevelName'];
		//}
		$headerList[$row['FLevelId']] = $row['FLevelName'];
	}
	//array_push($headerList,'National');
	$headerList[999] = 'National'; 
	
	foreach($headerList as $key => $value){
		$columnList[] = $value;//.' Level AMC';
		$columnList[] = $value;//.' Level SOH';
		$columnList[] = $value;//.' Level MOS';
	}
	$fetchDataList = array();
	
	foreach($data as $row){
		if ($tmpItemName != $row['ItemName']) {
		
			if ($count > 0) {
				$fetchDataList['999'.'2'] =  number_format($fetchDataList['999'.'2']);
				$fetchDataList['999'.'3'] =  number_format($fetchDataList['999'.'3'],1);
				array_unshift($fetchDataList,$sl,$preItemName);
				$aData[] = $fetchDataList;
				$sl++;
			 }
			 $count++;	
			 
			 $preItemName	=  $row['ItemName'];
			 
			 unset($fetchDataList);
			 foreach($headerList as $key => $value){
				 $fetchDataList[$key.'1'] = NULL; 
				 $fetchDataList[$key.'2'] = NULL; 
				 $fetchDataList[$key.'3'] = NULL; 
			 }			 
			$tmpItemName = $row['ItemName'];
		}
		
		$fLevelId = $row['FLevelId'];
		
		$fetchDataList[$fLevelId.'1'] = number_format($row['FacilityAMC']);
		$fetchDataList[$fLevelId.'2'] = number_format($row['FacilitySOH']);
		$fetchDataList[$fLevelId.'3'] = number_format($row['MOS'],1);
		 
		if($fetchDataList['999'.'1'] < $row['FacilityAMC']){
			$fetchDataList['999'.'1'] =  number_format($row['FacilityAMC']);
		}
		
		$fetchDataList['999'.'2']+=  $row['FacilitySOH'];
		$fetchDataList['999'.'3']+=  $row['MOS'];
			
	}
	
	$fetchDataList['999'.'2'] =  number_format($fetchDataList['999'.'2']);
	$fetchDataList['999'.'3'] =  number_format($fetchDataList['999'.'3'],1);
	array_unshift($fetchDataList,$sl,$preItemName);
	$aData[] = $fetchDataList;
    //print_r($columnList);
//    print_r('</br>');
//    print_r($headerList);
   
	if($total > 0){	
	echo '<!DOCTYPE html>
			<html>
			<head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			 <link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css" /> 
			 <link href="'.$jBaseUrl.'templates/protostar/endless/bootstrap/css/bootstrap.min.css" rel="stylesheet">
			 <link href="'.$jBaseUrl.'templates/protostar/endless/css/font-awesome.min.css" rel="stylesheet">
			 <link href="'.$jBaseUrl.'templates/protostar/endless/css/pace.css" rel="stylesheet">	
			 <link href="'.$jBaseUrl.'templates/protostar/endless/css/colorbox/colorbox.css" rel="stylesheet">
			 <link href="'.$jBaseUrl.'templates/protostar/endless/css/morris.css" rel="stylesheet"/> 	
             <link href="'.$jBaseUrl.'templates/protostar/endless/css/endless.min.css" rel="stylesheet"> 
	        <link href="'.$jBaseUrl.'templates/protostar/endless/css/endless-skin.css" rel="stylesheet">
			<link href="'.$jBaseUrl.'administrator/components/com_jcode/source/css/custom.css" rel="stylesheet"/>
			<style>
			   table.display tr.even.row_selected td {
    			background-color: #4DD4FD;
			    }    
			    table.display tr.odd.row_selected td {
			    	background-color: #4DD4FD;
			    }
			    .SL{
			        text-align: center !important;
			    }
			    td.Countries{
			        cursor: pointer;
			    }   
			</style>
			</head>
			<body>';
     echo '<div class="row"> 
	        <div class="panel panel-default table-responsive" id="grid_country">
          	<div class="padding-md clearfix">
           	<div class="panel-heading">
              <h3 style="text-align:center;">'.$gTEXT['Stock Status at Different Level Data List'].'<h3>
                   <h4 style="text-align:center;">'.$gTEXT['Country Name'].': '. $CountryName.'   ,   '.$gTEXT['Product Group'].': '. $ItemGroupName.' <h4>
			       <h4 style="text-align:center;">'.$gTEXT['Month'].': '. $MonthName.'   ,   '.$gTEXT['Year'].': '. $Year.'<h4>
			       <h5 style="text-align:center;">'.$gTEXT['Owner Type'].': '. $OwnnerTypeName.'<h5>
            </div>
              
	      		<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				';
	   
       
        	$col='';
        	$col.=' <tr><th rowspan="2" style="text-align:center; width:5%;"><b>SL</b></th>
        		  <th rowspan="2" style="text-align:center; width:10%;"><b>'.$gTEXT['Product Name'].'</b></th>';
         
            $Header = '-1';
        	for($i=0;$i<count($columnList);$i++)	
        	{
        		  if($Header != $columnList[$i]){
            		  $col.='<th colspan="3" style="text-align:center;"width:10%;><b>'.$columnList[$i].'</b></th>';                     
            		  $Header = $columnList[$i];
        	       }   
                           	
            }  
            $index = 0;
        	$col.= '</tr><tr>';
        	for ($i=0; $i<count($columnList); $i++) {
        	   $index++;
        		if($index == 1)
        			$col.= '<th  style="text-align:left;" >AMC</th>';
        		else if($index == 2)
        			$col.= '<th  style="text-align:left;" >SOH</th>';
        		else if($index == 3)
        			$col.= '<th  style="text-align:left;">'.$gTEXT['MOS'].'</th>';
        		
        		if($index == 3)
        			$index = 0;                 
            }  
                
        	$col.='</tr>';
        
        
        
        $data = '';
        for($p=0;$p<count($aData);$p++)
    	{
    			$data.='<tr>';
    			for($i=0;$i<count($aData[$p]); $i++)
    			{
    				$data.='<td>'.$aData[$p][$i].'</td>';
    			}
    			$data.='</tr>';  
    	}
         //echo $data;
         //echo '</tbody></table></div></div></div><br/>';
         
    	 //echo'</tbody>';
    	
    	 echo''.$col.'</thead><tbody>'.$data.'</tbody></table></div></div></div>';    	
         echo '</body></html>';	          
 
    }else{
        echo 'No Recoe Found.';
     }
 	 		


 


?>