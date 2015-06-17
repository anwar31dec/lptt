<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());


	$gTEXT = $TEXT;
	$jBaseUrl = $_GET['jBaseUrl']; 
    $StartMonthId = $_GET['StartMonthId']; 
    $StartYearId = $_GET['StartYearId']; 
    $EndMonthId = $_GET['EndMonthId']; 
    $EndYearId = $_GET['EndYearId'];    
	$countryId = $_GET['CountryId'];
	$itemGroupId = $_GET['ItemGroupId'];
    $months = $_GET['MonthNumber'];
	$CountryName=$_GET['CountryName']; 
    $ItemGroupName=$_GET['ItemGroupName']; 

    $frequencyId = 1;// $_POST['FrequencyId'];
		
	if($_GET['MonthNumber'] != 0){
        $months = $_GET['MonthNumber'];
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
	$output = array('Categories' => array(), 'Series' => array(), 'Colors' => array());
	$output2 = array('name' => '', 'data' => array());
		
		if($frequencyId == 1)
			$monthQuarterList = $monthListShort;
		else 
			$monthQuarterList = $quarterList;
 	
	$month_list = array();
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
 	$lan = $_REQUEST['lan'];
	if($lan == 'en-GB'){
            $serviceTypeName = 'ServiceTypeName';
        }else{
            $serviceTypeName = 'ServiceTypeNameFrench';
        }     
	 
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
	//echo $sQuery;
	
    mysql_query("SET character_set_results=utf8");
	$rResult = mysql_query($sQuery);
	$total = mysql_num_rows($rResult);
	$tmpServiceTypeId = -1;
	$countServiceType = 1;
	$count = 1;
	$preServiceTypeName='';
	
	if($total==0) return;
	//echo 'Rubel';
	while ($row = mysql_fetch_assoc($rResult)) {
		
		if(!is_null($row['TotalPatient']))	
			settype($row['TotalPatient'], "integer");

		if ($tmpServiceTypeId != $row['ServiceTypeId']) {
			
			if ($count > 1) {
				array_unshift($output2,$countServiceType,$preServiceTypeName);
								
				$aData[] = $output2;
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
	$aData[] = $output2;
	 
	 if($lan=='en-GB'){
        $TypeLang='Patient Type';
    }
    else{
        $TypeLang='Type de Patient';
    }
	
	 $str = ',"COLUMNS":[{"sTitle": "SL", "sWidth":"5%"}, {"sTitle": "'.$TypeLang.'", "sClass" : "' . 'PatientType' . '"}, ';	
    $f=0;
	 $td='<tr><th>SL</th><th>'.$TypeLang.'</th>';
    foreach($month_list as $mon){
        if($f++) $str.=', ';
        $str.= '{"sTitle": "'.$mon.'", "sClass" : "MonthName"}';   
		$td.='<th>'.$mon.'</th>';                          
    }
	
    
    $td.='</tr>'; 
	
	 
	for($p=0;$p<count($aData);$p++)
	{
			$td.='<tr>';
			for($i=0;$i<count($aData[$p]); $i++)
			{
				$td.='<td>'.$aData[$p][$i].'</td>';
			}
			$td.='</tr>';  
	}
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
                			<h3 style="text-align:center;">'.$gTEXT['Patient Trend Time Series'].'<h3>
                			<h4 style="text-align:center;">'.$gTEXT['Country'].' : '.$CountryName.' , '.$gTEXT['Product Group'].' : '.$ItemGroupName.'<h4>
                			<h4 style="text-align:center;">'.$gTEXT['Month'].' : From '.date('M,Y', strtotime($StartYearMonth)).' to '.date('M,Y', strtotime($EndYearMonth)).'<h4>
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
			echo "No record found.";	
			
	}
	 
	  

?>