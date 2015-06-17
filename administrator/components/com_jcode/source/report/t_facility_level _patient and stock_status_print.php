<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
include ("../universal_function_lib_ext.php");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');


$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 

$CountryId = $_GET['CountryId'];	
$monthId = $_GET['MonthId'];		
$year = $_GET['Year'];	
$ItemGroupId = $_GET['ItemGroupId'];
$CountryName = $_GET['CountryName'];	
$GroupName = $_GET['ItemGroupName'];	
$MonthName = $_GET['MonthName'];
$facilityId = $_GET['FacilityId'];	
$reportId = $_GET['reportId'];


//**********************************************************************Create/Accept*************************************** 
	
	$query_3 = "SELECT CFMStockId, FacilityId, MonthId, Year, ItemGroupId, 
			(SELECT b.name FROM  j323_users b WHERE b.id = a.CreatedBy) CreatedBy, DATE_FORMAT(CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,
			(SELECT b.name FROM  j323_users b WHERE b.id = a.LastUpdateBy)  LastUpdateBy,	
			(SELECT b.name FROM  j323_users b WHERE b.id = a.LastSubmittedBy) LastSubmittedBy ,
			c.StatusId, c.StatusName,
			DATE_FORMAT(LastSubmittedDt, '%d-%b-%Y %h:%i %p') LastSubmittedDt,	
			DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,	
			DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt 	
			FROM t_cfm_masterstockstatus a LEFT JOIN t_status c ON a.StatusId = c.StatusId ";
			
	$query_3 .= " WHERE FacilityId =  $facilityId  and MonthId =  $monthId and Year = '$year' and ItemGroupId =  $ItemGroupId AND CountryId = $CountryId";
			
	 mysql_query("SET character_set_results=utf8");    	
	$r_3= mysql_query($query_3) ;
	
	$i=1;
	
	echo'<!DOCTYPE html>
        <html>
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
		<base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="Joomla! - Open Source Content Management" />
        <link rel="stylesheet" href="'.$jBaseUrl.'/templates/protostar/css/template.css" type="text/css"/>		  
        <link href="'.$jBaseUrl.'/templates/protostar/endless/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/font-awesome.min.css" rel="stylesheet">
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/pace.css" rel="stylesheet">	
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/colorbox/colorbox.css" rel="stylesheet">
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/morris.css" rel="stylesheet"/> 	
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/endless.min.css" rel="stylesheet"> 
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/endless-skin.css" rel="stylesheet">	
        <link href="'.$jBaseUrl.'/administrator/components/com_jcode/source/css/custom.css" rel="stylesheet"/>
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
		echo'<div class="row"> 
            <div class="panel panel-default table-responsive" id="grid_country">
            	<div class="padding-md clearfix">
            		<div class="panel-heading">
            			<h3 style="text-align:center;">'.$gTEXT['Facility Level Patient And Stock Status'].'<h3>
            			<h4 style="text-align:center;">'.$gTEXT['Country Name'].':'.$CountryName.' , '.$gTEXT['Product Group'].':'.$GroupName.' <h4>	
            			<h4 style="text-align:center;">'.$gTEXT['Month'].':'.$MonthName.'   ,   '.$gTEXT['Year'].':'.$year.'<h4>           						
            			</div>	
					 <table class="table table-striped display" id="gridDataCountry">
	                    <thead>
	                    </thead>
	                    <tbody>
	                        <tr>
	                            <th style="text-align: center;">'.$gTEXT['Report Id'].'</th>
	                            <th style="text-align: center;">'.$gTEXT['Status'].'</th>
	                        	<th style="text-align: center;">'.$gTEXT['Created Date'].'</th>
						 		<th style="text-align: center;">'.$gTEXT['Accepted Date'].'</th>
	                            <th style="text-align: center;">'.$gTEXT['Submitted Date'].'</th>
	                            <th style="text-align: center;">'.$gTEXT['Published Date'].'</th>
	                           </tr> ';
	                           while($rec_3=mysql_fetch_array($r_3)){
	                        		echo'<tr>
	                        	       	 <td style="text-align: center;">'.$rec_3['CFMStockId'].'</td>	  
	                        	 	     <td style="text-align: center;">'.$rec_3['StatusName'].'</td>	                                     
	                                     <td style="text-align: center;">'.$rec_3['CreatedDt'].'</td>	                                     
	                                     <td style="text-align: center;">'.$rec_3['AcceptedDt'].'</td>
	      								 <td style="text-align: center;">'.$rec_3['LastSubmittedDt'].'</td>
	   									 <td style="text-align: center;">'.$rec_3['PublishedDt'].'</td>
										 
										</tr>';
									$i++;
					 			}
					 
					 echo '</tbody></table></div></div></div></br>';
 
 
 
 
  
//***************************************************************Patient Overview***********************************************	 
     
	
    $query = "SELECT 	b.CFMPOId,
						a.FormulationName, 
						b.RefillPatient, 
						b.NewPatient, 
						b.TotalPatient
						FROM t_formulation a INNER JOIN t_cfm_patientoverview b ON a.FormulationId = b.FormulationId  ";	
	$query .= " AND FacilityId =  $facilityId  and MonthId =  $monthId  and Year =  '$year' AND CountryId = $CountryId AND b.ItemGroupId = $ItemGroupId";
	
	$query .= "  ORDER BY b.CFMPOId,FormulationName  ";
	 mysql_query("SET character_set_results=utf8");                    
	$r= mysql_query($query) ;
	
	$i=1;
    echo'<!DOCTYPE html>
        <html>
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
		<base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="'.$jBaseUrl.'/templates/protostar/css/template.css" type="text/css"/>		  
        <link href="'.$jBaseUrl.'/templates/protostar/endless/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/font-awesome.min.css" rel="stylesheet">
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/pace.css" rel="stylesheet">	
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/colorbox/colorbox.css" rel="stylesheet">
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/morris.css" rel="stylesheet"/> 	
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/endless.min.css" rel="stylesheet"> 
        <link href="'.$jBaseUrl.'/templates/protostar/endless/css/endless-skin.css" rel="stylesheet">	
        <link href="'.$jBaseUrl.'/administrator/components/com_jcode/source/css/custom.css" rel="stylesheet"/>
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
            
    echo'<div class="row"> 
            <div class="panel panel-default table-responsive" id="grid_country">
            	<div class="padding-md clearfix">
            		<div class="panel-heading">
            			<h3 style="text-align:left;">'.$gTEXT['Patient Overview'].'<h3>
            		</div>	
            		<table class="table table-striped display" id="gridDataCountry">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="text-align: center;">SL#</th>
                            <th style="text-align: left;">'.$gTEXT['Patient type'].'</th>
                            <th style="text-align: right;">'.$gTEXT['Cases'].'</th>
                            </tr>';
                                
                        while($rec=mysql_fetch_array($r)){
                        		if($rec['RefillPatient']==0)$rec['RefillPatient']='';
                        		// if($rec['NewPatient']==0)$rec['NewPatient']='';
                        	    // if($rec['TotalPatient']==0)$rec['TotalPatient']='';
                        	echo'<tr>
                                     <td style="text-align: center;">'.$i.'</td>                                 
                                     <td style="text-align: left;">'.$rec['FormulationName'].'</td>
                                     <td style="text-align: right;">'.$rec['RefillPatient'].'</td>                                                            
                           </td></tr>';
                        $i++; 
                        }
            echo'</tbody>
            </table>
            </div>
            </div>
            </div>
            <br/>';
	 
//********************************************************************Patient By Regimen ************************************************
 	$query_1 = "SELECT b.CFMPatientStatusId, a.RegimenName, c.FormulationName, b.RefillPatient, b.NewPatient, b.TotalPatient 
			FROM t_regimen a 
			INNER JOIN t_cfm_regimenpatient b ON a.RegimenId = b.RegimenId  
			INNER JOIN t_formulation c ON a.FormulationId = c.FormulationId  ";
	$query_1 .= "and FacilityId =  $facilityId  and MonthId =  $monthId and Year = '$year' AND CountryId = $CountryId AND b.ItemGroupId = $ItemGroupId";
	$query_1 .= " order by FormulationName,RegimenName, b.CFMPatientStatusId desc";
	$r_1 = mysql_query($query_1);
 mysql_query("SET character_set_results=utf8");    
        echo'<div class="row"> 
            <div class="panel panel-default table-responsive" id="grid_country">
                <div class="padding-md clearfix">
                <div class="panel-heading">
                    <h3 style="text-align:left;">'.$gTEXT['Patient By Regimen'].'<h3>
                </div>	
                <table class="table table-striped display" id="gridDataCountry">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <th style="text-align: center;">SL#</th>
                    <th style="text-align: left;">'.$gTEXT['Regimens'].'</th>
                    <th style="text-align: right;">'.$gTEXT['Cases'].'</th>
                    
                </tr>';	//<th style="text-align: right;">'.$gTEXT['New Patients'].'</th>
                    //<th style="text-align: right;">'.$gTEXT['Total Patients'].'</th>
        $j=1;
        $ItempGroupId='';
    	while ($rec_1 = mysql_fetch_array($r_1)){
    	   
         if($tempGroupId!=$rec_1['FormulationName']) 
		   {
		   	 	echo'<tr >
                     <td style="background-color:#DAEF62;border-radius:2px;  align:center; font-size:14px;" colspan="5">'.$rec_1['FormulationName'].'</td>
                   </tr>'; 
			   $tempGroupId=$rec_1['FormulationName'];
			   }
                if($rec_1['RefillPatient']==0)$rec_1['RefillPatient']='';
				// if($rec_1['NewPatient']==0)$rec_1['NewPatient']='';
        	    // if($rec_1['TotalPatient']==0)$rec_1['TotalPatient']='';
    	  echo'<tr>
                    <td style="text-align: center;">'.$j.'</td>
                    <td style="text-align: left;">'.$rec_1['RegimenName'].'</td>
                    <td style="text-align: right;">'.($rec_1['RefillPatient']==''? '':number_format($rec_1['RefillPatient'])).'</td>
                    
    	       </tr>';//<td style="text-align: right;">'.($rec_1['NewPatient']==''? '':number_format($rec_1['NewPatient'])).'</td>
                     //<td style="text-align: right;">'.($rec_1['TotalPatient']==''? '':number_format($rec_1['TotalPatient'])).'</td>
        $j++; 
        }
    echo'</thead>
    </table>
    </div>
    </div>  
    </div><br/>';
		
//*******************************************************************************Stock Status**********************************************************
    
    $query_2 = "SELECT a.CFMStockStatusId, a.FacilityId, a.MonthId, a.Year, a.ItemNo, b.ItemName, a.OpStock OpStock_A, 0 OpStock_C, a.ReceiveQty, a.DispenseQty, 
                a.AdjustQty, a.AdjustId AdjustReason,a.StockoutDays, a.ClStock ClStock_A, 0 ClStock_C, a.ClStockSourceId, a.MOS, a.AMC, a.AmcChangeReasonId, a.MaxQty, 
                a.OrderQty, a.ActualQty, a.UserId, a.LastEditTime, c.ProductSubGroupName FormulationName, SourceName
                FROM t_cfm_stockstatus a
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo 
                INNER JOIN t_product_subgroup c ON b.ProductSubGroupId = c.ProductSubGroupId 
                LEFT JOIN t_clstock_source d ON a.ClStockSourceId = d.ClStockSourceId
                WHERE a.CFMStockId = $reportId 
                AND a.FacilityId = $facilityId 
                AND MonthId = $monthId 
                AND Year = '$year' 
                AND a.ItemGroupId = $ItemGroupId
                AND CountryId = $CountryId ORDER BY c.ProductSubGroupName, b.ItemName ASC"; 
	 mysql_query("SET character_set_results=utf8");    
	$r_2 = mysql_query($query_2);
	
        echo'<div class="row"> 
	       <div class="panel panel-default table-responsive" id="grid_country">
                <div class="padding-md clearfix">
       	            <div class="panel-heading">
                        <h3 style="text-align:left;">'.$gTEXT['Stock Status'].'<h3>
                    </div>	
                    <table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
    					    <th style="text-align: center;">SL#</th>
    					    <th style="text-align: left;">'.$gTEXT['Item Name'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['OBL'].'</th>
    					    <th style="text-align: left;">'.$gTEXT['Received'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['Dispensed'].'</th>
    					    <th style="text-align: left;">'.$gTEXT['Adjusted'].'</th>
    					    <th style="text-align: left;">'.$gTEXT['Adjust Reason'].'</th>
    					    <th style="text-align: left;">'.$gTEXT['Stock Out Days'].'</th> 
    					    <th style="text-align: right;">'.$gTEXT['Closing Balance'].'</th>
                            <th style="text-align: left;">'.$gTEXT['CL Stock Source'].'</th>
                            <th style="text-align: right;">'.$gTEXT['AMC'].'</th>
                            <th style="text-align: left;">'.$gTEXT['AMC Change Reason'].'</th>                             
                            <th style="text-align: right;">'.$gTEXT['MOS'].'</th>
                            <th style="text-align: right;">'.$gTEXT['Max Qty'].'</th>
                            <th style="text-align: right;">'.$gTEXT['Order Qty'].'</th>                             
                            <th style="text-align: right;">'.$gTEXT['Actual Order Qty'].'</th>
    	                </tr>';
        $k=1;
        $ItempGroupId1='';
        while($rec_2 = mysql_fetch_array($r_2)){
            if($rec_2['OpStock_A']==0)$rec_2['OpStock_A']='';
			if($rec_2['ClStock_A']==0)$rec_2['ClStock_A']='';
            if($rec_2['MOS']==0)$rec_2['MOS']='';
               
        	echo'<tr>
                    <td style="text-align: center;">'.$k.'</td>
                    <td style="text-align: left;">'.$rec_2['ItemName'].'</td>
                    <td style="text-align: right;">'.($rec_2['OpStock_A']==''? '':number_format($rec_2['OpStock_A'])).'</td>
                    <td style="text-align: center;">'.($rec_2['ReceiveQty']==''? '':number_format($rec_2['ReceiveQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['DispenseQty']==''? '':number_format($rec_2['DispenseQty'])).'</td>
                    <td style="text-align: left;">'.$rec_2['AdjustQty'].'</td>
                    <td style="text-align: left;">'.$rec_2['AdjustId AdjustReason'].'</td>
                    <td style="text-align: left;">'.$rec_2['StockoutDays'].'</td>                  
                    <td style="text-align: right;">'.$rec_2['ClStock_A'].'</td>                 
                    <td style="text-align:center;">'.$rec_2['SourceName'].'</td>
                    <td style="text-align: right;">'.($rec_2['AMC']==''? '':number_format($rec_2['AMC'])).'</td>
                    <td style="text-align: left;">'.$rec_2['AmcChangeReasonId'].'</td>
                    <td style="text-align: right;">'.($rec_2['MOS']==''? '':number_format($rec_2['MOS'],1)).'</td>
                    <td style="text-align: right;">'.($rec_2['MaxQty']==''? '':number_format($rec_2['MaxQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['OrderQty']==''? '':number_format($rec_2['OrderQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['ActualQty']==''? '':number_format($rec_2['ActualQty'])).'</td>
                </tr>';
        	$k++; 
	   }
    echo'</thead>
    </table>
    </div>
    </div>  
    </div><br/>';	
    
    echo'</body></html>';

  

?>