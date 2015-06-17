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
 $ItemGroupName = $_GET['ItemGroupName'];
   $condition='';
	$sWhere = "";
       if($itemGroupId!=0){
    	$sWhere=' WHERE ';     
		$condition.=" a.ItemGroupId = '".$itemGroupId."' "; 
	}
	$sLimit = "";
	if (isset($_GET['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_formulation(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	
	if ($_GET['sSearch'] != "") {
		
		if($sWhere=='') $sWhere=" WHERE ";
		 else $condition.=" and "; 
		 
		$condition.= "   (FormulationName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                    OR ServiceTypeName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                    OR GroupName LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%'
                    OR ColorCode LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%') ";
                    
    }

	$sql = "SELECT SQL_CALC_FOUND_ROWS FormulationId, FormulationName,FormulationNameFrench, a.ItemGroupId, GroupName, a.ServiceTypeId, ServiceTypeName, ColorCode
				FROM t_formulation a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                INNER JOIN t_servicetype c ON a.ServiceTypeId = c.ServiceTypeId
                $sWhere $condition $sOrder $sLimit "; 
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
				echo '
	           	<div class="panel panel-default table-responsive" id="grid_country">
	           	<div class="padding-md clearfix">
	           	<div class="panel-heading">
	            <h3 style="text-align:center;">'.$gTEXT['Formulation List'].'<h3>
	            <h4 style="text-align:center;">'.$gTEXT['Product Group'].': '. $ItemGroupName.' <h4>
	            </div>	
    				<table class="table table-striped display" id="gridDataCountry">
				<thead>
				<tr>
				    <th style="text-align: center;">SL#</th>
				    <th>'.$gTEXT['Formulation Type'].'</th>
				    <th>'.$gTEXT['Formulation Type (French)'].'</th>
				    <th>'.$gTEXT['Item Group'].'</th>
				    <th>'.$gTEXT['Service Type'].'</th>
				    <th>'.$gTEXT['Color Code'].'</th>
                </tr>';
	$tempGroupId='';
	while($rec=mysql_fetch_array($r)){
		if($tempGroupId!=$rec['GroupName']) {
			echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#00000000 >
	                 <td class="txtLeft"; colspan="6">'.$rec['GroupName'].'</td>
	             </tr>'; 
		$tempGroupId=$rec['GroupName'];
   		} 
		echo '<tr>
		 	      <td style="text-align: center;">'.$i.'</td>
			      <td style="text-align: left;">'.$rec['FormulationName'].'</td>
	              <td style="text-align: left;">'.$rec['FormulationNameFrench'].'</td>
	              <td style="text-align: left;">'.$rec['GroupName'].'</td>
				  <td style="text-align: left;">'.$rec['ServiceTypeName'].'</td>
				  <td style="height:5px;width:8px;background-color:'.$rec['ColorCode'].'"></td>
			  </tr>';
				 
		$i++; 
	}
	echo'</thead>	
    	 </table>
         </div>
		
         </div>';
    echo '</body></html>';	
    }  else{
			$error = "No record found.";	
			echo $error;
	}


?>