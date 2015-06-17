<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());


$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 


function getMonthsBtnTwoDate($firstDate, $lastDate) {
	$diff = abs(strtotime($lastDate) - strtotime($firstDate));
	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	return $months;
}
	
	 $CountryId = $_GET['CountryId'];
     $months = $_GET['MonthNumber'];
	 $StartMonthId = $_GET['StartMonthId'];
     $EndMonthId = $_GET['EndMonthId'];
     $StartYearId= $_GET['StartYearId'];
     $EndYearId= $_GET['EndYearId'];
	 $CountryName=$_GET['CountryName']; 
	 $MonthName=$_GET['MonthName'];
	 
	 // $ownerTypeId = $_REQUEST['OwnerTypeId'];
    // $OwnerTypeName = $_REQUEST['OwnerTypeName']; 
	 
	  $lan = $_GET['lan'];
	 if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
			$lblMOSTypeName='MOS Type Name';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
			$lblMOSTypeName='Type MSD Nom';
        } 
		
	 
	if($_GET['MonthNumber'] != 0){
        $months = $_GET['MonthNumber'];
        $monthIndex = date("n");
        $yearIndex = date("Y");
            if ($monthIndex == 1){
            $monthIndex = 12;                
            $yearIndex = $yearIndex - 1;                
            }else{
            $monthIndex = $monthIndex - 1;
            
            $endDate = $yearIndex."-".$monthIndex."-"."01";    
            $startDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($endDate)) . "+".-($months-1)." month"));      
    }
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";    
        $endDate = $EndYearId."-".$EndMonthId."-"."01";    
        $months = getMonthsBtnTwoDate($startDate, $endDate)+1;          
        $monthIndex = $EndMonthId;
        $yearIndex = $EndYearId;   
    }   
    settype($yearIndex, "integer");    
    $month_name = array();
    $Tdetails = array();  
    $sumRiskCount = array();  
    $sumTR = 0;
        
   	for ($i = 1; $i <= $months; $i++){
	$sql = " SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, a.MOS,(SELECT MosTypeId FROM t_mostype x WHERE  a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
				     WHERE a.MOS IS NOT NULL AND a.MonthId = ".$monthIndex. " AND Year = ".$yearIndex." AND (CountryId = ".$CountryId." OR ".$CountryId." = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId"; 
        mysql_query("SET character_set_results=utf8");                  
        $result = mysql_query($sql);
        $total = mysql_num_rows($result); 
        $Pdetails = array();  
        
        if($total>0){      
    		while ($aRow = mysql_fetch_array($result)) {
                $Pdetails['MosTypeId'] = $aRow['MosTypeId'];
                $Pdetails['MonthIndex'] = $monthIndex;
                $Pdetails['MosTypeName'] = $aRow['MosTypeName'];
                $Pdetails['RiskCount'] = $aRow['RiskCount'];
                array_push($Tdetails, $Pdetails);  
       	    }
            $mn = date("M", mktime(0,0,0,$monthIndex,1,0));
            $mn = $mn." ".$yearIndex;
            array_push($month_name, $mn);  
        }                          
   	    $monthIndex--;
		if ($monthIndex == 0){
			$monthIndex = 12;   				
			$yearIndex = $yearIndex - 1;			
		}
    }
    $veryHighRisk = array();
    $highRisk = array();
    $mediumRisk = array();
    $lowRisk = array();
    $noRisk = array();
    $areaName = array();
    
    $rmonth_name = array_reverse($month_name);
    $RTdetails = array_reverse($Tdetails);
    
    foreach($RTdetails as $key => $value){
         $MosTypeId = $value['MosTypeId'];
         $MonthIndex = $value['MonthIndex'];
         $MosTypeName = $value['MosTypeName'];
         $RiskCount = $value['RiskCount'];  
         
         if($MosTypeId == 1){
            array_push($veryHighRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);  
         }else if($MosTypeId == 2){
            array_push($highRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);
         }else if($MosTypeId == 3){
            array_push($mediumRisk, $RiskCount);
            array_push($areaName, $MosTypeName); 
         }else if($MosTypeId == 4){
            array_push($lowRisk, $RiskCount);
            array_push($areaName, $MosTypeName); 
         }else if($MosTypeId == 5){
            array_push($noRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);
         }                               		            
    }      
    
    $vhr = array();
    $hr = array();
    $mr = array();
    $lr = array();
    $nr = array();
    
    for($i = 0; $i<count($veryHighRisk); $i++){                                     
        $sumOfRiskCount = $veryHighRisk[$i] + $highRisk[$i] + $mediumRisk[$i] + $lowRisk[$i] + $noRisk[$i];   
        if($sumOfRiskCount==0)$sumOfRiskCount = 1;   
        $newPercentVHR = number_format($veryHighRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentHR = number_format($highRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentMR = number_format($mediumRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentLR = number_format($lowRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentNR = number_format($noRisk[$i]*100/$sumOfRiskCount, 1);
        
        array_push($vhr, $newPercentVHR."%");
        array_push($hr, $newPercentHR."%");
        array_push($mr, $newPercentMR."%");
        array_push($lr, $newPercentLR."%");
        array_push($nr, $newPercentNR."%");
    }
    $unique = array_reverse(array_unique($areaName));     
    array_unshift($vhr, "1", $unique[0]);
    array_unshift($hr, "2", $unique[1]);
    array_unshift($mr, "3", $unique[2]);
    array_unshift($lr, "4", $unique[3]);
    array_unshift($nr, "5", $unique[4]);
   
    
    //$str = ',"COLUMNS":[{"sTitle": "SL", "sWidth":"5%"}, {"sTitle": "MOS Type Name", "sClass" : "PatientType"}, ';	
    $f=0;
	$td='<tr><th>SL</th><th>'.$gTEXT['MOS Type Name'].'</th>';
    foreach($rmonth_name as $mon){
        if($f++) $str.=', ';
        
		$td.='<th>'.$mon.'</th>';                         
    }
    $td.='</tr>';
           
   
	
	
	$td.='<tr>';
	for($i=0;$i<count($vhr); $i++)
	{
		$td.='<td>'.$vhr[$i].'</td>';
	}
	$td.='<tr>';
	
	
	$td.='<tr>';
	for($i=0;$i<count($hr); $i++)
	{
		$td.='<td>'.$hr[$i].'</td>';
	}
	$td.='<tr>'; 
	
	$td.='<tr>';
	for($i=0;$i<count($mr); $i++)
	{
		$td.='<td>'.$mr[$i].'</td>';
	}
	$td.='<tr>'; 
	    
    $td.='<tr>';
	for($i=0;$i<count($lr); $i++)
	{
		$td.='<td>'.$lr[$i].'</td>';
	}
	$td.='<tr>'; 
	
	$td.='<tr>';
	for($i=0;$i<count($nr); $i++)
	{
		$td.='<td>'.$nr[$i].'</td>';
	}
	$td.='<tr>'; 
	                      
	
	$i=1;	
    
	if($total>0){
		
		echo '<!DOCTYPE html>
			 <html>
			 <head>
			 <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
			 <base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
			 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
			 <meta name="generator" content="Joomla! - Open Source Content Management" />
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
                			<h3 style="text-align:center;">'.$gTEXT['Stockout Trend'].' <h3>
                			<h4 style="text-align:center;">'.$gTEXT['Country'].' : '.$CountryName.' ,  '.$gTEXT['Month'].' : From '.date('M,Y', strtotime($startDate)).' to '.date('M,Y', strtotime($endDate)).'<h4>
                			</div>	
            			<table class="table table-striped display" id="gridDataCountry">
            				<thead>
            				</thead>
            				<tbody>
            					<tr>
            					</tr>';
                                
                               
                                	echo $td;
                                    
                                 
	 echo '</tbody></table></div></div></div><br/>';
     
	 echo'</tbody>';
	 echo $tbody;
	 echo'</tbody>    				
    			</table>
            </div>
		</div>  
     </div>';
		

echo '</body>
      </html>';	
    		
	 }	else{
			$error = "No record found.";	
			echo $error;
	}
	 
	  

?>