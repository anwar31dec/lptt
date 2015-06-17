<?php
include("../define.inc");
include_once ("../function_lib.php");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');
mysql_query("SET character_set_results=utf8");

$sql='';
$sqlResult='';
$totalRec=0;
$useSl = 1;
$sl=1;
$datalist = array();
$month_list = array();

$lan = $_POST['lan'];	
$dataType = $_POST['dataType'];
$groupBySqlIndex = $_POST['groupBySqlIndex'];
$alignment = $_POST['alignment'];
$tableHeaderList = $_POST['tableHeaderList'];
$tableHeaderWidth = $_POST['tableHeaderWidth'];
$sqlParameterList = $_POST['sqlParameterList'];

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case "getPatientTrendTimeSeriesData" :
		getPatientTrendTimeSeriesData();
		break;
	case "getStockOutTrendData" :
		getStockOutTrendData();
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

function getPatientTrendTimeSeriesData() {
	global $sqlParameterList;
	global $lan;

	 $StartMonthId = $sqlParameterList[0]; 
     $StartYearId = $sqlParameterList[1];
     $EndMonthId = $sqlParameterList[2];
     $EndYearId = $sqlParameterList[3];
	 $countryId = $sqlParameterList[4];
	 $itemGroupId = $sqlParameterList[5];
	 $frequencyId = $sqlParameterList[6];
	 $months = $sqlParameterList[7];

	if($lan == 'en-GB'){
            $serviceTypeName = 'ServiceTypeName';
        }else{
            $serviceTypeName = 'ServiceTypeNameFrench';
        }     
	
	if($months != 0){
       
        $monthIndex = date("m");
        $yearIndex = date("Y");
		 settype($yearIndex, "integer");    
		if ($monthIndex == 1){
			$monthIndex = 12;				
			$yearIndex = $yearIndex - 1;				
		}else{
			$monthIndex = $monthIndex - 1;
		}
		$months = $months - 1;  
			   
		$d=cal_days_in_month(CAL_GREGORIAN,$monthIndex,$yearIndex);
		$EndYearMonth = $yearIndex."-".str_pad($monthIndex,2,"0",STR_PAD_LEFT)."-".$d; 
		$EndYearMonth = date('Y-m-d', strtotime($EndYearMonth));	
		
		$StartYearMonth = $yearIndex."-".str_pad($monthIndex,2,"0",STR_PAD_LEFT)."-"."01"; 
		$StartYearMonth = date('Y-m-d', strtotime($StartYearMonth));	
		$StartYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartYearMonth)) . "-".$months." month"));
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";	
		$StartYearMonth = date('Y-m-d', strtotime($startDate));	
		
		$d=cal_days_in_month(CAL_GREGORIAN,$EndMonthId,$EndYearId);
    	$endDate = $EndYearId."-".$EndMonthId."-".$d;	
		$EndYearMonth = date('Y-m-d', strtotime($endDate));	    	
    }
	
	$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
	$quarterList = array(3 => 'Jan-Mar', 6 => 'Apr-Jun', 9 => 'Jul-Sep', 12 => 'Oct-Dec');
	
	$output = array('aaData' => array());
	global $datalist;// = array();
	$output2 = array();		
		if($frequencyId == 1)
			$monthQuarterList = $monthListShort;
		else 
			$monthQuarterList = $quarterList; 	

	$startDate = strtotime($StartYearMonth);
	$endDate   = strtotime($EndYearMonth);
	$index=0;
	
	while ($endDate >= $startDate) {
			if($frequencyId == 1){
					$monthid=date('m',$startDate);
					settype($monthid,"integer");
					$ym= $monthListShort[$monthid].' '.date('Y',$startDate);				
					$month_list[$index] = $ym;
					$output['Categories'][] = $ym;	
					$index++;
					}				
				else{
					$monthid=date('m',$startDate);
					settype($monthid,"integer");
					if($monthid==3 || $monthid==6 || $monthid==9 || $monthid==12){
						$ym=$quarterList[$monthid].' '.date('Y',$startDate);
						$month_list[$index] = $ym;
						$output['Categories'][] = $ym;	
						$index++;
						}
					}				
		
	    $startDate = strtotime( date('Y/m/d',$startDate).' 1 month');
	}
	// //////////////////
 
	$sQuery = "SELECT a.ServiceTypeId, IFNULL(SUM(c.TotalPatient),0) TotalPatient
			, $serviceTypeName ServiceTypeName, a.STL_Color,c.Year,c.MonthId
                FROM t_servicetype a
                INNER JOIN t_formulation b ON a.ServiceTypeId = b.ServiceTypeId
                Inner JOIN t_cnm_patientoverview c 	
					ON (c.FormulationId = b.FormulationId 
						and STR_TO_DATE(concat(year,'/',monthid,'/02'), '%Y/%m/%d') 
						between '".$StartYearMonth."' and '".$EndYearMonth."'
                		AND (c.CountryId = ".$countryId." OR ".$countryId." = 0)
						AND (c.ItemGroupId = ".$itemGroupId." OR ".$itemGroupId." = 0))  		                       
                GROUP BY a.ServiceTypeId, $serviceTypeName, a.STL_Color
				, c.Year, c.MonthId
				HAVING TotalPatient > 0
		        ORDER BY a.ServiceTypeId asc,c.Year asc, c.MonthId asc;";
	//return $sQuery;
	$rResult = safe_query($sQuery);
	$total = mysql_num_rows($rResult);
	$tmpServiceTypeId = -1;
	$countServiceType = 1;
	$count = 1;
	$preServiceTypeName='';
	
	if($total==0) return;
	
	while ($row = mysql_fetch_assoc($rResult)) {
		
		if(!is_null($row['TotalPatient']))	
			settype($row['TotalPatient'], "integer");

		if ($tmpServiceTypeId != $row['ServiceTypeId']) {
			
			if ($count > 1) {
				array_unshift($output2,$countServiceType,$preServiceTypeName);
								
				$datalist[] = $output2;
				unset($output2);
				$countServiceType++;
			 }
			$count++;		
			
			$preServiceTypeName	=  $row['ServiceTypeName'];	
			$count = 0;
			while( $count < count($month_list)){				
				$output2[] = null;
				$count++;
			}

			$dataMonthYear = $monthQuarterList[$row['MonthId']].' '.$row['Year']; 
			$count = 0;
			while( $count < count($month_list)){
				if($month_list[$count] == $dataMonthYear){
					$output2[$count] = $row['TotalPatient'];
				}				
				$count++;
			}
			$tmpServiceTypeId = $row['ServiceTypeId'];
		} 
		else {
				$dataMonthYear = $monthQuarterList[$row['MonthId']].' '.$row['Year']; 
				$count = 0;
				while( $count < count($month_list)){
					if($month_list[$count] == $dataMonthYear){
						$output2[$count] = $row['TotalPatient'];
					}				
					$count++;
				}
			$tmpServiceTypeId = $row['ServiceTypeId'];
		}   
	}
	
	array_unshift($output2,$countServiceType,$preServiceTypeName);
	$datalist[] = $output2;
	//print_r($month_list);
	
}


function getStockOutTrendData() {

	global $sqlParameterList;
	global $lan;
	 $StartMonthId = $sqlParameterList[0]; 
     $StartYearId = $sqlParameterList[1];
     $EndMonthId = $sqlParameterList[2];
     $EndYearId = $sqlParameterList[3];
	 $CountryId = $sqlParameterList[4];
	 $ItemGroupId = $sqlParameterList[5];
	 $OwnerTypeId = $sqlParameterList[6];
	 $months = $sqlParameterList[7];	 
	 	//echo $itemGroupId;

	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
			$lblMOSTypeName='MOS Type Name';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
			$lblMOSTypeName='Type MSD Nom';
        } 
		
    if($months != 0){
        $monthIndex = date("n");
        $yearIndex = date("Y");
		 if ($monthIndex == 1){
		$monthIndex = 12;				
		$yearIndex = $yearIndex - 1;				
		}else{
		    $monthIndex = $monthIndex - 1;
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
   	    
/*$sql = " SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
(SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
SELECT a.ItemNo, a.MOS,(SELECT MosTypeId FROM t_mostype x WHERE  a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
FROM t_cnm_stockstatus a
WHERE a.MOS IS NOT NULL AND a.MonthId = ".$monthIndex. " AND Year = ".$yearIndex." AND (CountryId = ".$CountryId." OR ".$CountryId." = 0)) p 
GROUP BY p.MosTypeId) u
RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
GROUP BY v.MosTypeId"; */
//$ItemGroupId = $_POST['ItemGroup'];
        if($ItemGroupId > 0){
    		$sql =" SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, a.MOS,(SELECT MosTypeId FROM t_mostype x WHERE  a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
                     INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo  
					 AND b.ItemGroupId = ".$ItemGroupId."
				     WHERE a.MOS IS NOT NULL 
					 AND a.MonthId = ".$monthIndex. " 
					 AND Year = ".$yearIndex."
					 AND a.OwnerTypeId = ".$OwnerTypeId." 
                     AND (CountryId = ".$CountryId." OR ".$CountryId." = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId";					
    	}else{
    		$sql =" SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, a.MOS,(SELECT MosTypeId FROM t_mostype x WHERE  a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
                     INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo  
					 AND b.bCommonBasket = 1
				     WHERE a.MOS IS NOT NULL 
					 AND a.MonthId = ".$monthIndex. " 
					 AND Year = ".$yearIndex."
					 AND a.OwnerTypeId = ".$OwnerTypeId." 					
                     AND (CountryId = ".$CountryId." OR ".$CountryId." = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId;";	 
    		
    	}   
		//echo $sql.' imhere   ok       '; 
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
	
	
	global $datalist;
	$datalist[] = $vhr;
	$datalist[] = $hr;
	$datalist[] = $mr;
	$datalist[] = $lr;
	$datalist[] = $nr;

}
//print_r($datalist);

//echo $sql;
//====================================Dynamic Design======================================

$tableFieldList = array();

$totalRowcount = count($datalist);
//echo $totalRowcount;
if ($totalRowcount>0){

	//Count column of first row
	$tableFieldCount = count($datalist[0]);
	
	$totalwidth=0;
	$index=0;
	for($index=0;$index<$tableFieldCount;$index++){
		$tableHeaderWidth[$index] = str_replace('px','',$tableHeaderWidth[$index]);
		$totalwidth+=$tableHeaderWidth[$index];
	}
	for($index=0;$index<$tableFieldCount;$index++){
		$tableHeaderWidth[$index] = floor(($tableHeaderWidth[$index]*100)/$totalwidth);
	}
	//Convert PX to Number for Percent
		echo '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;">    	
		<tbody><tr>';
		
		//Table Header			
		for($i=0;$i<$tableFieldCount;$i++){
		
			//if($i != $groupBySqlIndex){ //For Avoid Group Header
			//	if($i==0)
			//		echo '<th style="width:'.$tableHeaderWidth[$i].'%; text-align:'.$alignment["0"].';">'.$tableHeaderList[$i].'</th>';
			//	else
					echo '<th style="width:'.$tableHeaderWidth[$i].'%; text-align:'.$alignment[$dataType[$i]].';">'.$tableHeaderList[$i].'</th>';
			//}
		}
			echo '</tr>';
					
		$tempGroupId='';	
		foreach($datalist as $row){
		//print_r($row);
		
		/*
			if($tempGroupId != $row[$tableFieldList[0][$groupBySqlIndex]]){
				echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#000000>
						<td class="txtLeft"; colspan="'.$tableFieldCount.'">'.$row[$tableFieldList[0][$groupBySqlIndex]].'</td>
					 </tr>'; 
			$tempGroupId=$row[$tableFieldList[0][$groupBySqlIndex]];
			}
		*/
		
			echo '<tr>';
			for($i=0;$i<$tableFieldCount;$i++){
				//if($i != $groupBySqlIndex){ //For Avoid Group 
					if($dataType[$i] == 'html'){ //For Color Field
						echo '<td style="height:5px;width:'.$tableHeaderWidth[$i].'%;background-color:'.$row[$i].'"></td>';
					}
					else{
						//if($i==0)
						//	echo '<td style="width:'.$tableHeaderWidth[$i].'%; text-align: '.$alignment["0"].';">'.$sl.'</td>';
						//else
							echo '<td style="width:'.$tableHeaderWidth[$i].'%; text-align: '.$alignment[$dataType[$i]].';">'.getValueFormat($row[$i], $dataType[$i]).'</td>';
					}
				//}				
			}
			echo '</tr>';
					 
				$sl++; 
		}
			
		echo'</tbody>
			 </table>';
			 
     }
	 else{
   	    echo 'No record found.';
    }
	
	function getValueFormat($value, $dataType){
		//$parameterList['alignment'] = array("numeric"=>"right","string"=>"left",""=>"center");
		$retVal=0;
		if($dataType == $alignment[0]){
			$str_arr = explode('.',$value);
			$retVal = number_format($value,strlen($str_arr[1]));  // After the Decimal point
		}
		else{
			$retVal = $value;
		}
	return $retVal;
	}
		
?>