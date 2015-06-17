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

$lastMonthDispensed = " IFNULL((SELECT DispenseQty ";
$lastMonthDispensed .= "FROM "."t_cnm_stockstatus";
$lastMonthDispensed .= " WHERE MonthId = " .getLastMonth($year, $monthId). " and Year = '" . getYearForLastMonth($year, $monthId) 
."' and ItemNo = a.ItemNo  and a.ItemGroupId = $itemGroupId AND CountryId = $countryId),0) ";

$beforeLastMonthDispensed = " IFNULL((SELECT DispenseQty ";
$beforeLastMonthDispensed .= "FROM " . getTable_SD_YearForLast2Month($year, $monthId);
$beforeLastMonthDispensed .= " WHERE MonthId = " . getBeforeLastMonth($year, $monthId). " and Year = '" . getYearForLast2Month($year, $monthId)
."' and ItemNo = a.ItemNo  and a.ItemGroupId = $itemGroupId AND CountryId = $countryId),0) ";

/***********************************************Create/Accept********************************************************************************************/

$created = $_GET['CreatedDt'];	
$accepted = $_GET['AcceptedDt'];		
$submitted = $_GET['LastSubmittedDt'];	
$published = $_GET['PublishedDt'];
	
$countryId = $_GET['CountryId'];	
$monthId = $_GET['MonthId'];		
$year = $_GET['Year'];	
$itemGroupId = $_GET['ItemGroupId'];

$CountryName = $_GET['CountryName'];	
$GroupName = $_GET['ItemGroupName'];	
$MonthName = $_GET['MonthName'];
$reportId = $_GET['ReportId'];

	$query3 = "SELECT CNMStockId, MonthId, Year, ItemGroupId,
				(SELECT b.name FROM  j323_users b WHERE b.id = a.CreatedBy) CreatedBy, DATE_FORMAT(CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,
				(SELECT b.name FROM  j323_users b WHERE b.id = a.LastUpdateBy)  LastUpdateBy,	
				(SELECT b.name FROM  j323_users b WHERE b.id = a.LastSubmittedBy) LastSubmittedBy ,
				c.StatusId, c.StatusName,
				DATE_FORMAT(LastSubmittedDt, '%d-%b-%Y %h:%i %p') LastSubmittedDt,	
				DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,	
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt 	
				FROM t_cnm_masterstockstatus a 
				LEFT JOIN t_status c ON a.StatusId = c.StatusId ";
	$query3.= " WHERE MonthId = $monthId and Year = '$year' AND CountryId = $countryId AND ItemGroupId = $itemGroupId";  
	
	$r_1 = mysql_query($query3) ;
	
	$l=1;
	
	echo'<!DOCTYPE html>
        <html>
        <head>
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
            		<div class="clearfix">
            			<h3 style="text-align:center;">'.$gTEXT['National Level Patient And Stock Status'].'<h3>
            		</div>	
            		<div class="clearfix">
	            		<h4 style="text-align:center;">'.$gTEXT['Country Name'].':'.$CountryName.'   ,   '.$gTEXT['Product Group'].':'.$GroupName.' <h4>
						<h4 style="text-align:center;">'.$gTEXT['Month'].':'.$MonthName.'   ,   '.$gTEXT['Year'].':'.$year.'<h4>
					 </div> 
					 <table class="table table-striped display" id="gridDataCountry">
	                    <thead>
	                    </thead>
	                    <tbody>
	                        <tr>
	                            <th style="text-align: left;">'.$gTEXT['Report Id'].'</th>
                                <th style="text-align: left;">'.$gTEXT['Status'].'</th>
	                        	<th style="text-align: left;">'.$gTEXT['Created Date'].'</th>
						 		<th style="text-align: left;">'.$gTEXT['Accepted Date'].'</th>
	                            <th style="text-align: left;">'.$gTEXT['Submitted Date'].'</th>
	                            <th style="text-align: left;">'.$gTEXT['Published Date'].'</th>
	                           </tr> ';
	                           while($rec_1 = mysql_fetch_array($r_1)){
	                        		echo'<tr>
	                        		     <td style="text-align: left;">'.$rec_1['CNMStockId'].'</td>
                                         <td style="text-align: left;">'.$rec_1['StatusName'].'</td>                                        
	                                     <td style="text-align: left;">'.$rec_1['CreatedDt'].'</td>	                                     
	                                     <td style="text-align: left;">'.$rec_1['AcceptedDt'].'</td> 
										 <td style="text-align: left;">'.$rec_1['LastSubmittedDt'].'</td>										 
										 <td style="text-align: left;">'.$rec_1['PublishedDt'].'</td>
										</tr>';
									$l++;
					 			}
					 
					 echo '</tbody></table></div></div></div>';

//***************************************************************Patient Overview*************************************************************************************/	 
    
    $query = "SELECT 	b.CNMPOId,
        				a.FormulationName, 
        				b.RefillPatient, 
        				b.NewPatient, 
        				b.TotalPatient
        				FROM t_formulation a INNER JOIN t_cnm_patientoverview b ON a.FormulationId = b.FormulationId ";
    
    $query .= " AND MonthId = $monthId and Year = '$year' AND CountryId = $countryId AND b.ItemGroupId = $itemGroupId";
    
    $query .= " ORDER BY b.CNMPOId asc";
                
	$r= mysql_query($query) ;
	
	$i=1;
    
            
    echo'<div class="row"> 
            <div class="panel panel-default table-responsive" id="grid_country">
            	<div class="padding-md clearfix">
            		<div class="clearfix">
            			<h3 style="text-align:center;">'.$gTEXT['Patient Overview'].'<h3>
            		</div>	
            		<table class="table table-striped display" id="gridDataCountry">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="text-align: center;">SL#</th>
                            <th style="text-align: left;">'.$gTEXT['Patient type'].'</th>
                            <th style="text-align: right;">'.$gTEXT['Refill Patients'].'</th>
                            <th style="text-align: right;">'.$gTEXT['New Patients'].'</th>
                            <th style="text-align: right;">'.$gTEXT['Total Patients'].'</th>
                        </tr>';
                                
                        while($rec=mysql_fetch_array($r)){
                        	echo'<tr>
                                    <td style="text-align: center;">'.$i.'</td>
                                    <td style="text-align: left;">'.$rec['FormulationName'].'</td>
                                    <td style="text-align: right;">'.($rec['RefillPatient']==0? '':number_format($rec['RefillPatient'])).'</td>
                                    <td style="text-align: right;">'.($rec['NewPatient']==0? '':number_format($rec['NewPatient'])).'</td>
                                    <td style="text-align: right;">'.($rec['TotalPatient']==0? '':number_format($rec['TotalPatient'])).'</td>                                                                                     
                                </tr>';
                        $i++; 
                        }
            echo'</tbody>
            </table>
            </div>
            </div>
            </div>';
//********************************************************************Patient By Regimen *******************************************************************************/
        $query1 = "SELECT b.CNMPatientStatusId, a.RegimenName, c.FormulationName, b.RefillPatient, b.NewPatient, b.TotalPatient 
        			FROM t_regimen a 
        			INNER JOIN "."t_cnm_regimenpatient"." b ON a.RegimenId = b.RegimenId 
        			INNER JOIN t_formulation c ON a.FormulationId = c.FormulationId ";
         
        $query1 .= " AND MonthId = $monthId and Year = '$year' AND CountryId = $countryId AND b.ItemGroupId = $itemGroupId";
        
        $query1 .= " ORDER BY c.FormulationName, b.CNMPatientStatusId";
        
    	$r1 = mysql_query($query1);
		
        echo'<div class="row"> 
            <div class="panel panel-default table-responsive" id="grid_country">
                <div class="padding-md clearfix">
                <div class="clearfix">
                    <h3 style="text-align:center;">'.$gTEXT['Patient By Regimen'].'<h3>
                </div>	
                <table class="table table-striped display" id="gridDataCountry">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <th style="text-align: center;">SL#</th>
                    <th style="text-align: left;">'.$gTEXT['Regimens'].'</th>
                    <th style="text-align: right;">'.$gTEXT['Refill Patients'].'</th>
                    <th style="text-align: right;">'.$gTEXT['New Patients'].'</th>
                    <th style="text-align: right;">'.$gTEXT['Total Patients'].'</th>
                </tr>';
                
        
    	
        $j=1;
        $tempGroupId='';
    	while ($rec_1 = mysql_fetch_array($r1)){
    	   
          if($tempGroupId!=$rec_1['FormulationName']){
                echo'<tr>
                    <td style="background-color:#DAEF62;border-radius:2px;  align:center;" colspan="5">'.$rec_1['FormulationName'].'</td>
                </tr>'; 
    		    $tempGroupId=$rec_1['FormulationName'];
    	    }
    	  echo'<tr>
                    <td style="text-align: center;">'.$j.'</td>
                    <td style="text-align: left;">'.$rec_1['RegimenName'].'</td>
                    <td style="text-align: right;">'.($rec_1['RefillPatient']==0? '':number_format($rec_1['RefillPatient'])).'</td>
                    <td style="text-align: right;">'.($rec_1['NewPatient']==0? '':number_format($rec_1['NewPatient'])).'</td>
                    <td style="text-align: right;">'.($rec_1['TotalPatient']==0? '':number_format($rec_1['TotalPatient'])).'</td>
    	       </tr>';
        $j++; 
        }
    echo'</thead>
    </table>
    </div>
    </div>  
    </div>';
		
//*******************************************************************************Stock Status***************************************************************************
		
		$query2 = " SELECT a.CNMStockStatusId, a.MonthId, a.Year, a.ItemNo, b.ItemName, a.OpStock OpStock_A, 0 OpStock_C, a.ReceiveQty, a.DispenseQty, 
                    a.AdjustQty, a.AdjustId AdjustReason, a.StockoutDays, a.ClStock ClStock_A, 0 ClStock_C, a.ClStockSourceId, a.MOS, a.AMC, a.AmcChangeReasonId, 
                    a.MaxQty, a.OrderQty, a.ActualQty, a.UserId, a.LastEditTime, c.ProductSubGroupName FormulationName, SourceName
                    FROM t_cnm_stockstatus a 
                    INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo 
                    INNER JOIN t_product_subgroup c ON b.ProductSubGroupId = c.ProductSubGroupId 
                    LEFT JOIN t_clstock_source d ON a.ClStockSourceId = d.ClStockSourceId
                    WHERE a.CNMStockId = $reportId 
                    AND MonthId = $monthId 
                    AND Year = '$year' 
                    AND a.ItemGroupId = $itemGroupId 
                    AND CountryId = $countryId
                    ORDER BY c.ProductSubGroupName, b.ItemName ASC ";
		
		$r2 = mysql_query($query2);
		
        echo'<div class="row"> 
	       <div class="panel panel-default table-responsive" id="grid_country">
                <div class="padding-md clearfix">
       	            <div class="clearfix">
                        <h3 style="text-align:center;">'.$gTEXT['Stock Status'].'<h3>
                    </div>	
                    <table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
    					    <th style="text-align: center;">SL#</th>
    					    <th style="text-align: left;">'.$gTEXT['Item Name'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['OBL'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['Received'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['Dispensed'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['Adjusted'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['Adjust Reason'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['Stock Out Days'].'</th>
    					    <th style="text-align: right;">'.$gTEXT['Closing Balance'].'</th>
                            <th style="text-align: left;">'.$gTEXT['CL Stock Source'].'</th>
                            <th style="text-align: right;">'.$gTEXT['AMC'].'</th>
                            <th style="text-align: left;">'.$gTEXT['AMC Change Reason'].'</th>
                            <th style="text-align: right;">'.$gTEXT['MOS'].'</th>
    	                </tr>';
        $k=1;
		
        while($rec_2 = mysql_fetch_array($r2)){
        	
        	echo'<tr>
                    <td style="text-align: center;">'.$k.'</td>
                    <td style="text-align: left;">'.$rec_2['ItemName'].'</td>
                    <td style="text-align: right;">'.($rec_2['OpStock_A']==0? '':number_format($rec_2['OpStock_A'])).'</td>
                    <td style="text-align: right;">'.($rec_2['ReceiveQty']==0? '':number_format($rec_2['ReceiveQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['DispenseQty']==0? '':number_format($rec_2['DispenseQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['AdjustQty']==0? '':number_format($rec_2['DispenseQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['AdjustId']==''? '':number_format($rec_2['DispenseQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['StockoutDays']==0? '':number_format($rec_2['DispenseQty'])).'</td>
                    <td style="text-align: right;">'.($rec_2['ClStock_A']==0? '':number_format($rec_2['ClStock_A'])).'</td>
                    <td style="text-align: left;">'.$rec_2['SourceName'].'</td>
                    <td style="text-align: right;">'.($rec_2['AMC']==0? '':number_format($rec_2['AMC'])).'</td>
                    <td style="text-align: left;">'.$rec_2['AmcChangeReasonName'].'</td>
                    <td style="text-align: right;">'.($rec_2['MOS']==0? '':number_format(($rec_2['MOS']),1)).'</td>
                </tr>';
        	$k++; 
	   }
    echo'</thead>
    </table>
    </div>
    </div>  
    </div>';	
    
    echo'</body></html>';


?>