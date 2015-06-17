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
    $Year = $_GET['Year'];   
    $Month = $_GET['Month'];
    $CountryId = $_GET['CountryId'];
    $ServiceType = $_GET['ServiceType'];
	$CountryName=$_GET['CountryName'];   
	$MonthName = $_GET['MonthName'];
	$ServiceTypeName = $_GET['ServiceTypeName'];  
    if($CountryId){
		$CountryId = " AND a.CountryId = ".$CountryId." ";
	}
    
	$sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " AND (FacilityName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                         OR NewPatient LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                         OR TotalPatient LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') ";
	}
      	    
    $sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99	
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = ".$ServiceType."
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId." $sWhere  
            GROUP BY a.FacilityId, FacilityName
           	$sOrder $sLimit ";
    mysql_query("SET character_set_results=utf8");       	   
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99	
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = ".$ServiceType."
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."  
            GROUP BY a.FacilityId, FacilityName";
     mysql_query("SET character_set_results=utf8");               
    $result = mysql_query($sql);
    $totalPatient = 0;
   	while ($aRow = mysql_fetch_object($result)) {
   	    $totalPatient = $totalPatient + $aRow->TotalPatient;
    }
	$i=1;	
	if ($total>0){
		echo '<!DOCTYPE html>
			<html>
			<head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />	
			    <base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
			    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
			    <meta name="generator" content="Joomla! - Open Source Content Management" />
			 	<link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css"/>
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
			 	<div class="col-md-7"> 
           		<div class="panel panel-default table-responsive" id="grid_country">
            	<div class="padding-md clearfix">
           			<h3 style="text-align:center;">'.$gTEXT['Facility Service indicators'].'<h3>
            		</div>	
            		<div class="clearfix">
	            		<h4 style="text-align:center;">'.$gTEXT['Country Name'].':'.$CountryName.'   ,   '.$gTEXT['Service Type'].':'.$ServiceTypeName.' <h4>
						<h4 style="text-align:center;">'.$gTEXT['Month'].':'.$MonthName.'   ,   '.$gTEXT['Year'].':'.$Year.'<h4>
						<h4 style="text-align:center;">'.$gTEXT['Total Patient'].' is '.(number_format($totalPatient)).'<h4>
					 </div> 
    				<table class="table table-striped display" id="gridDataCountry">
    			<thead>
    			<tr>
    			    <th style="text-align: center;">SL</th> 
		            <th>'.$gTEXT['Name of Facility'].'</th> 
					<th style="text-align: right;">'.$gTEXT['Number of Total Patients'].'</th> 
					</tr>';
					//<th style="text-align: right;">'.$gTEXT['Number of New Patients'].'</th>
	while($rec=mysql_fetch_array($r)){
		echo '<tr>
				   <td style="text-align: center;">'.$i.'</td>
				   <td>'.$rec['FacilityName'].'</td>
				   <td style="text-align: right;">'.number_format($rec['TotalPatient']).'</td>
				  
			 </tr>';
				// <td style="text-align: right;">'.$rec['NewPatient'].'</td>					 
		$i++; 
	} 			
    echo'</thead>	
    	 </table>
         </div>
		 </div>  
         </div>
		 </div>';
   echo '</body></html>';	
   } else{
			$error = "No record found.";	
			echo $error;
   }


?>