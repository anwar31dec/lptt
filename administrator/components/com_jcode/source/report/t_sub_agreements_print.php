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
$itemGroupId = $_GET['ItemGroupId'];
$itemGroupName = $_GET['ItemGroupName'];

   	$sWhere = "WHERE t_itemgroup.ItemGroupId = ".$itemGroupId." OR ".$itemGroupId."= 0";
	if ($_GET['sSearch'] != "") {
		$sWhere = " AND (AgreementName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'  
                    OR FundingSourceName LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%'
                    OR GroupName LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%') ";
    }
    
	$sql = "SELECT SQL_CALC_FOUND_ROWS AgreementId, AgreementName, a.FundingSourceId, FundingSourceName
				,a.ItemGroupId,GroupName	
				FROM t_subagreements a
                INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId    
				Inner Join t_itemgroup ON a.ItemGroupId = t_itemgroup.ItemGroupId				
				$sWhere $sOrder $sLimit 
				; ";     

    mysql_query("SET character_set_results=utf8");       
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	$i=1;	
    
	if ($total>0){
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
    			 	<div class="col-md-7"> 
                   		<div class="panel panel-default table-responsive" id="grid_country">
                   		   <div class="padding-md clearfix">
                   		       <div class="panel-heading">
                                  <h3 style="text-align: center;">'.$gTEXT['Sub-agreements'].'<h3>
                                      <h3 style="text-align: center;">'.$gTEXT['Product Group'].':  '.$itemGroupName.'<h3>
            	               </div>	
    				            <table class="table table-striped display" id="gridDataCountry">
                        			<thead>
                        			<tr>
                                        <th style="text-align: center;">SL#</th>
                    					<th>'.$gTEXT['Product Group'].'</th>
                    					<th>'.$gTEXT['Funding Source'].'</th>
                    					<th>'.$gTEXT['Agreement Name'].'</th>
                    				</tr>';
									
						
	$tempGroupId='';
	while($rec=mysql_fetch_array($r)){
		if($tempGroupId!=$rec['FundingSourceName']){
				echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#00000000>
             		 <td class="txtLeft"; colspan="4">'.$rec['FundingSourceName'].'</td>
             		 </tr>'; 
				$tempGroupId=$rec['FundingSourceName'];
		} 	 
		echo '<tr>
		 	      <td style="text-align: center;">'.$i.'</td>
			      <td>'.$rec['GroupName'].'</td>
			      <td>'.$rec['FundingSourceName'].'</td>
			      <td>'.$rec['AgreementName'].'</td>
	         </tr>';
			 
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
			$error = "No records found.";	
			echo $error;
	}


?>