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
    
    $Year=$_GET['Year'];
	$Month=$_GET['Month'];
	$ItemGroupId=$_GET['ItemGroupId'];
    $CountryId=$_GET['Country'];
	
   $CountryName = $_GET['CountryName'];
	$MonthName = $_GET['MonthName'];
	$Year = $_GET['Year'];
	$ItemGroupName = $_GET['ItemGroupName'];	
	
	
       $sql = "  SELECT a.ItemNo, b.ItemName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId AND a.CountryId = c.CountryId AND c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
           		WHERE a.MonthId = ".$Month." AND a.Year = ".$Year."
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 	
            	GROUP BY ItemNo, ItemName 
            	HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0";	 
				  
	 mysql_query("SET character_set_results=utf8");		  
				 
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	$i=1;	
	if ($r)
	
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
			  <h3 style="text-align:center;">'.$gTEXT['National Stock Summary List'].' on '.$MonthName.' '.$Year.'<h3>
			  <h4 style="text-align:center;">'.$gTEXT['Country'].': ' .$CountryName .' , '.$gTEXT['Product Group'].': '. $ItemGroupName.'<h4>		  
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
    					    <th>SL#</th>
						    <th>'.$gTEXT['Products'].'</th>
						      <th style="text-align: right;">'.$gTEXT['Reported Closing Balance'].'</th>
						      <th style="text-align: right;">'.$gTEXT['Average Monthly Consumption'].'</th>
						      <th style="text-align: right;">'.$gTEXT['MOS'].'</th>
		                </tr>';
						
						
		while($rec=mysql_fetch_array($r))
		{
				 
			echo '<tr>
			       <td style="text-align: center;">
			     '.$i.'
			     </td>
			       <td>
			     '.$rec['ItemName'].'
			     </td>
		         <td style="text-align: right;">
			     '.($rec['ReportedClosingBalance']==''? '':number_format($rec['ReportedClosingBalance'])).'
			     </td>
			       <td style="text-align: right;">
			     '.($rec['ReportedConsumption']==''? '':number_format($rec['ReportedConsumption'])).' 
			     </td>
	
			     <td style="text-align: right;">
			     '.($rec['MOS']==''? '':number_format($rec['MOS'],1)).'
			     </td>
	
	
			     </tr>
			     ';
				 
				 $i++; 
		}
		
		
		
			echo'</thead>
    				
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