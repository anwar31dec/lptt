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

	$sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " WHERE  (MosTypeName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
							OR " . " MinMos LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " MaxMos LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " ColorCode LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ) ";
							
	}

   $sql = "	SELECT SQL_CALC_FOUND_ROWS MosTypeId, MosTypeName, MosTypeNameFrench, MinMos, MaxMos, MosLabel, ColorCode
				FROM t_mostype
				$sWhere $sOrder $sLimit ; ";
	       
				
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
           		<div class="panel-heading">
              	<h3>'.$gTEXT['MOS Type List'].'<h3>
            	</div>	
    				<table class="table table-striped display" id="gridDataCountry">
    			<thead>
    			<tr>
    			    <th style="text-align: center;">SL#</th> 
		            <th>'.$gTEXT['MOS Type Name'].'</th> 
		            <th>'.$gTEXT['MOS Type Name (French)'].'</th> 
					<th style="text-align: center;">'.$gTEXT['Minimum MOS'].'</th> 
					<th style="text-align: center;">'.$gTEXT['Maximum MOS'].'</th>
					<th style="text-align: center;">'.$gTEXT['Color Code'].'</th> 
					<th style="text-align: left;">'.$gTEXT['MosLabel'].'</th> 
				</tr>';
	while($rec=mysql_fetch_array($r)){
		echo '<tr>
				  <td style="text-align: center;">'.$i.'</td>
				   <td>'.$rec['MosTypeName'].'</td>
				   <td>'.$rec['MosTypeNameFrench'].'</td>
				   <td style="text-align: center;">'.$rec['MinMos'].'</td>
				   <td style="text-align: center;">'.$rec['MaxMos'].'</td>
				   <td style="height:5px;width:8px;background-color:'.$rec['ColorCode'].'"></td>
				   <td style="text-align: left;">'.$rec['MosLabel'].'</td>
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
			$error = "No record found.";	
			echo $error;
   }


?>