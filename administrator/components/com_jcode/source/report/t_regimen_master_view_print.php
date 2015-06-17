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

     $ItemGroupId = $_GET['ItemGroupId'];
	 $ItemGroupName = $_GET['ItemGroupName'];
     $AGenderTypeId = $_GET['AGenderTypeId'];
	 $GenderTypeName=$_GET['GenderTypeName'];
	 
	 $sWhere = "";
	 $condition='';
	 if($AGenderTypeId)
	 {
	 	$sWhere=' WHERE ';     
		$condition.=" a.GenderTypeId = '".$AGenderTypeId."' ";
	 }
    
     
	
	if($ItemGroupId){
		
		if($sWhere=='') $sWhere=" WHERE ";
		else $condition.=" and "; 
		$condition.="  a.ItemGroupId = '".$ItemGroupId."' "; 
		 
	} 

	$sLimit = "";
	if (isset($_GET['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_getRegimenMasterData(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	
	if ($_GET['sSearch'] != "") {
			if($sWhere=='') $sWhere=" WHERE ";
		   else $condition.=" and "; 
		 
		//$condition.= "   (GroupName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') ";
		$condition.= "   (RegimenName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') ";
		//$condition.= "   (GenderType LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') "; 
		
	}
	 

	$sql = "SELECT  RegMasterId,RegimenName, a.GenderTypeId,GenderType,a.ItemGroupId,GroupName
				FROM t_regimen_master a INNER JOIN  t_itemgroup b ON a.ItemGroupId=b.ItemGroupId
				INNER JOIN t_gendertype c ON a.GenderTypeId = c.GenderTypeId
				 $sWhere  ".$condition."							
                $sOrder
                $sLimit";
	 
	 
	         
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	$i=1;	
	if ($total>0){
		echo '<!DOCTYPE html>
			<html>
			<head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
              	<h3 style="text-align:center;">'.$gTEXT['Patient Type Master List'].'<h3>
              	<h4 style="text-align:center;">'.$gTEXT['Product Group'].' : '. $ItemGroupName.',   '. $gTEXT['Gender Type'].' : '. $GenderTypeName.' <h4>
            	</div>	
    				<table class="table table-striped display" id="gridDataCountry">
    			<thead>
    			<tr>
    			    <th style="text-align: center;">SL#</th> 
    			     <th>'.$gTEXT['Group Name'].'</th>
		             <th>'.$gTEXT['Regimen Name'].'</th> 
					 <th>'.$gTEXT['Gender Type'].'</th>
				</tr>';
	while($rec=mysql_fetch_array($r)){
		echo '<tr>
				   <td style="text-align: center;">'.$i.'</td>
				   <td>'.$rec['GroupName'].'</td>
				   <td>'.$rec['RegimenName'].'</td>
				   <td>'.$rec['GenderType'].'</td>
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