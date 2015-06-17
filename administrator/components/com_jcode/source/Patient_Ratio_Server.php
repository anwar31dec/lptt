<?php
include ("define.inc");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

include('language/lang_en.php');
include('language/lang_fr.php');
include('language/lang_switcher_report.php');
include_once ("function_lib.php");
$gTEXT = $TEXT;

//$jBaseUrl = $_GET['jBaseUrl']; 
$jBaseUrl=isset($_GET['jBaseUrl'])? $_GET['jBaseUrl'] : '';

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case "getPatientRatioPieChart" :
		getPatientRatioPieChart();
		break;
	case "getPatientRatioPieChartTable" :
		getPatientRatioPieChartTable();
		break;	
	default :
		echo "{failure:true}";
		break;
}


function getPatientRatioPieChart() {
$lan = $_REQUEST['lan'];
$countryId = $_POST['Country'];	
$ItemGroupId = $_POST['ItemGroupId'];
$year = $_POST['YearId'];
$MonthId = $_POST['MonthId'];	


if($lan == 'en-GB'){
		$formulationName = 'FormulationName';
	}else{
		$formulationName = 'FormulationNameFrench';
	} 

	
$output = array('Year' => '', 'Series1' => array(), 'Series2' => array(),'Series1Color' => array());
$output['Year'][] = $year;	

 $output1 = array('name' => '', 'y' => '', 'drilldown'=> '');
 $series1GroupTotal = array();	
 
 $sql1 = "SELECT SQL_CALC_FOUND_ROWS t_regimen.FormulationId,$formulationName FormulationName, ColorCode
  ,SUM(IFNULL(TotalPatient,0)) TotalPatient
   FROM t_cnm_regimenpatient
   INNER JOIN t_regimen ON t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId
   INNER JOIN t_formulation ON t_regimen.FormulationId  = t_formulation.FormulationId 
   
   where (t_cnm_regimenpatient.CountryId = ".$countryId." OR ".$countryId." = 0)
	 AND (t_cnm_regimenpatient.Year = '".$year."') 
	 AND (t_cnm_regimenpatient.MonthId = ".$MonthId.") 
	 AND (t_cnm_regimenpatient.ItemGroupId = ".$ItemGroupId.")
	 AND t_formulation.bMajore = 1	 
   GROUP BY t_regimen.FormulationId,$formulationName,ColorCode
   ORDER BY t_regimen.FormulationId,t_cnm_regimenpatient.RegimenId;";
  
 $rResult1 = safe_query($sql1);	
 $gTotal = 0;
 $index = 0;
 
 while ($row = mysql_fetch_assoc($rResult1)) {
	 if(!is_null($row['TotalPatient'])){
		 settype($row['TotalPatient'], "integer");
		 $gTotal+= $row['TotalPatient'];
	}
	 $series1GroupTotal[$index] =($row['TotalPatient'] ==null ? 0 : $row['TotalPatient']);
	 $index++;
	 
	 $colorCode = mysql_real_escape_string($row['ColorCode']);
	 $output['Series1Color'][] = $colorCode;	 
 }
 $gTotal = ($gTotal ==0 ? 1 : $gTotal);
 
 $rResult11 = safe_query($sql1);
$series1array = array(); 
 while ($row = mysql_fetch_assoc($rResult11)) {
 		
		 if(!is_null($row['TotalPatient']))	
			 settype($row['TotalPatient'], "integer"); 		
		
			 $groupTotal = number_format(($row["TotalPatient"]*100)/$gTotal,1);
			 settype($groupTotal, "float");
			 		
			 $output1['name'] = $row['FormulationName'];	
			 $output1['y'] = $groupTotal;
			 $output1['drilldown'] = $row['FormulationName'];	

			$series1array[] = $output1;
	 }
	 
$output['Series1'][] = json_encode($series1array);

   $sq2 = "SELECT SQL_CALC_FOUND_ROWS t_regimen.FormulationId,$formulationName FormulationName,
   t_cnm_regimenpatient.RegimenId,t_regimen.RegimenName
  ,SUM(IFNULL(TotalPatient,0)) TotalPatient
   FROM t_cnm_regimenpatient
   INNER JOIN t_regimen ON t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId
   INNER JOIN t_formulation ON t_regimen.FormulationId  = t_formulation.FormulationId 
   
   where (t_cnm_regimenpatient.CountryId = ".$countryId." OR ".$countryId." = 0)
	 AND (t_cnm_regimenpatient.Year = '".$year."') 
	 AND (t_cnm_regimenpatient.MonthId = ".$MonthId.") 
	 AND (t_cnm_regimenpatient.ItemGroupId = ".$ItemGroupId.") 
	 AND t_formulation.bMajore = 1	 
   GROUP BY t_regimen.FormulationId,$formulationName,t_cnm_regimenpatient.RegimenId,t_regimen.RegimenName
   ORDER BY t_regimen.FormulationId,t_cnm_regimenpatient.RegimenId;";
   
   
 $rResult2 = safe_query($sq2);	
 $gTotal2 = 0;
 
 $output2 = array('id' => '', 'data' => array());
 $output3 = array('name' => '', 'y' => '');
 $tmpServiceTypeId = -1;
 $index = -1;
 $count = 0;
$series2array = array();

 while ($row2 = mysql_fetch_assoc($rResult2)) {
 		
		 if(!is_null($row2['TotalPatient']))	
			 settype($row2['TotalPatient'], "integer"); 		
		
		if ($tmpServiceTypeId != $row2['FormulationId']) {
			 if ($count > 0) {			 
				 $series2array[] = $output2;				 
				 unset($output2['data']);
				 unset($output2);
				 unset($output3);				 
			  }	
			 
			 $groupTotal = $series1GroupTotal[++$index];
			 $subgroupTotal = number_format((($row2['TotalPatient']*100)/($groupTotal == 0 ? 1 : $groupTotal)),1);			
			 settype($subgroupTotal, "float");
			 
			 $output2['id'] = $row2['FormulationName'];
			 $output3['name'] = $row2['RegimenName'];	
			 $output3['y'] =$subgroupTotal;
			 $output2['data'][] = $output3;
			 $count++;

			 $tmpServiceTypeId = $row2['FormulationId'];				 
		 } 
		 else {
			 unset($output3);
			  $groupTotal = $series1GroupTotal[$index];
			 $subgroupTotal = number_format((($row2['TotalPatient']*100)/($groupTotal == 0 ? 1 : $groupTotal)),2);			 
			 settype($subgroupTotal, "float");
			 
			 $output3['name'] = $row2['RegimenName'];	
			 $output3['y'] = $subgroupTotal;
			 $output2['data'][] = $output3;
		 }  
		 
	 }
	 $series2array[] = $output2;
	
	$output['Series2'][] = json_encode($series2array);
	
	echo json_encode($output);
}




function getPatientRatioPieChartTable() {
$countryId = $_POST['Country'];
$ItemGroupId = $_POST['ItemGroupId'];
$year = $_POST['YearId'];
$MonthId = $_POST['MonthId'];	
$FormulationType = $_POST['serviceType'];	
$lan = $_REQUEST['lan'];

if($lan == 'en-GB'){
		$formulationName = 'FormulationName';
	}else{
		$formulationName = 'FormulationNameFrench';
	} 
	
$sq2 = "SELECT SQL_CALC_FOUND_ROWS t_regimen.FormulationId,$formulationName FormulationName,
   t_cnm_regimenpatient.RegimenId,t_regimen.RegimenName
  ,SUM(IFNULL(TotalPatient,0)) TotalPatient
   FROM t_cnm_regimenpatient
   INNER JOIN t_regimen ON t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId
   INNER JOIN t_formulation ON t_regimen.FormulationId  = t_formulation.FormulationId 
   
   where (t_cnm_regimenpatient.CountryId = ".$countryId." OR ".$countryId." = 0)
	 AND (t_cnm_regimenpatient.Year = '".$year."') 
	 AND (t_cnm_regimenpatient.MonthId = ".$MonthId.") 
	 AND (t_cnm_regimenpatient.ItemGroupId = ".$ItemGroupId.")
	 AND ($formulationName = '".$FormulationType."' OR '".$FormulationType."' = '')
	 AND t_formulation.bMajore = 1	 
   GROUP BY t_regimen.FormulationId,$formulationName,t_cnm_regimenpatient.RegimenId,t_regimen.RegimenName
   ORDER BY t_regimen.FormulationId,t_cnm_regimenpatient.RegimenId;";

	 $rResult1 = safe_query($sq2);	
	 $gTotal = 0;
	 $groupTotal = 0;
	 $count = 1;

	 $series1GroupTotal = array();
	 $series1GroupName = array();

	 $preServiceTypeId = -1;
	 $preServiceTypeName = '';
	 
	 while ($row = mysql_fetch_assoc($rResult1)) {
	 	
		 if(!is_null($row['TotalPatient'])){
			 settype($row['TotalPatient'], "integer");
			 $gTotal+= $row['TotalPatient'];
		}
		
		if($count > 1){
			if($preServiceTypeId != $row['FormulationId']){
				 $series1GroupTotal[$preServiceTypeId] = $groupTotal;
				 $series1GroupName[$preServiceTypeId] = $preServiceTypeName;
				 $groupTotal = 0;
			 }
	 	}

		$preServiceTypeId = $row['FormulationId'];
		$preServiceTypeName = $row['FormulationName'];
		
		$groupTotal+= ($row['TotalPatient'] == null ? 0 : $row['TotalPatient']);
		 
		 $count++;
	 }
	 
	 $series1GroupTotal[$preServiceTypeId] = $groupTotal;
	 $series1GroupName[$preServiceTypeId] = $preServiceTypeName;
	 
	 $gTotal = ($gTotal == 0 ? 1 : $gTotal);


	$result3 = safe_query($sq2);
    $total = mysql_num_rows($result3);
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

	$tmpServiceTypeId = -1;
	$TotalPercent = 0;
	
		$f = 0;
		if($FormulationType == ''){
			while ($aRow = mysql_fetch_array($result3)) {
				
				if($tmpServiceTypeId != $aRow['FormulationId']){						
					$formulationName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['FormulationName'])));
					
					$s1groupTotal = $series1GroupTotal[$aRow['FormulationId']];
					$gPercent = number_format((($s1groupTotal*100)/($gTotal==0?1:$gTotal)),1).' %';
					$TotalPercent+=$gPercent;
					
					if ($f++)
						$sOutput .= ',';
					$sOutput .= "[";	
					$sOutput .= '"' . addslashes($aRow['FormulationId']) . '",';	
					$sOutput .= '"' . $serial++ . '",';
					$sOutput .= '"' . $formulationName . '",';
					$sOutput .= '"' . number_format($s1groupTotal) . '",';
					$sOutput .= '"' . $gPercent . '"';
					$sOutput .= "]";
			   }
			$tmpServiceTypeId = $aRow['FormulationId'];
			}
			
			////////////For Toatal
			if($total > 0){
				$sOutput .= ",";
				$sOutput .= "[";	
				$sOutput .= '"' . '' . '",';		
				$sOutput .= '"' . 'Total' . '",';
				$sOutput .= '"' . '' . '",';
				$sOutput .= '"' . number_format($gTotal) . '",';
				$sOutput .= '"' . $TotalPercent.' %'. '"';
				$sOutput .= "]";
			}
		
		}
		else{
			while ($aRow = mysql_fetch_array($result3)) {
				$regimenName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['RegimenName'])));  
		
				$s1groupTotal = $series1GroupTotal[$aRow['FormulationId']];
				$s1groupTotal1 = ($s1groupTotal == 0 ? 1 : $s1groupTotal);
				
				$totalPatient = $aRow['TotalPatient'];			 
					 settype($totalPatient, "float");		
				$fPercent = number_format((($totalPatient*100)/$s1groupTotal1),1).' %';
				$TotalPercent+=$fPercent;
				
				if ($f++)
					$sOutput .= ',';
				$sOutput .= "[";	
				$sOutput .= '"' . addslashes($aRow['FormulationId']) . '",';		
				$sOutput .= '"' . $serial++ . '",';
				$sOutput .= '"' . $regimenName . '",';
				$sOutput .= '"' . number_format(addslashes($aRow['TotalPatient'])) . '",';
				$sOutput .= '"' . $fPercent . '"';
				$sOutput .= "]";
			}
			
			////////////For Toatal
			if($total > 0){
				$sOutput .= ",";
				$sOutput .= "[";	
				$sOutput .= '"' . '' . '",';		
				$sOutput .= '"' . 'Total' . '",';
				$sOutput .= '"' . '' . '",';
				$sOutput .= '"' . number_format($s1groupTotal) . '",';
				$sOutput .= '"' . $TotalPercent.' %'. '"';
				$sOutput .= "]";	
			}		
		}

	$sOutput .= '] }';

	echo $sOutput;
	
	// $gname = 'Product Group';
	// $totalPatient = 'Patients';
	// $parcentage = 'Parcentage';
// 	
	// $str = '{"COLUMNS":[{"sTitle": "SL", "sWidth":"5%","sClass":"SL"}, {"sTitle": "'.$gname.'", "sClass" : "' . 'gname' . '"}, ';	
    // $str.= '{"sTitle": "'.$totalPatient.'", "sClass" : "totalPatient"},';  
	// $str.= '{"sTitle": "'.$parcentage.'", "sClass" : "parcentage"}';  
	// $str.= ']}';
// 	
		//echo $str;
	
	
	//echo json_encode($output);
}



?>











