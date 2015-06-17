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
    
    if($itemGroupId){
		$itemGroupId = " WHERE a.ItemGroupId = '".$itemGroupId."' ";
	}

	$sWhere = "";
	if ($_GET['sSearch'] != "") {
		 $sSearch=str_replace("|","+", $_GET['sSearch']);
		$sWhere = " AND (a.ItemCode LIKE '%" . mysql_real_escape_string($sSearch) . "%'  OR " .
				" a.ItemName LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
				" a.ATCcode LIKE '%" . mysql_real_escape_string($sSearch) . "%' OR ".
				" d.ProductSubGroupName LIKE '%".mysql_real_escape_string($sSearch)."%') ";
	}
	
	$sql=" SELECT a.ItemNo, a.ItemCode, a.ItemName, a.bKeyItem, a.ItemGroupId, b.GroupName, a.ProductSubGroupId, d.ProductSubGroupName
				FROM t_itemlist as a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                INNER JOIN t_unitofmeas c ON a.UnitId = c.UnitId
                INNER JOIN t_product_subgroup d ON a.ProductSubGroupId = d.ProductSubGroupId
                ".$itemGroupId." ".$sWhere." order by ItemCode,GroupName, ItemName ";
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
              	<h3 style="text-align:center;">'.$gTEXT['Product List'].'<h3>
				 <p style="text-align:center; font-size:14px;">'.$gTEXT['Product Group'].' : '.$ItemGroupName.'</p>
            	</div>	
    				<table class="table table-striped display" id="gridDataCountry">
    			<thead>
    			<tr>
		              <th style="text-align: center;">SL#</th>
		              <th>'.$gTEXT['Product Code'].'</th>
		              <th>'.$gTEXT['Product Name'].'</th>
		              <th style="text-align: center;">'.$gTEXT['Key Product'].'</th>
		              <th style="text-align: left;">'.$gTEXT['Product Subgroup'].'</th>
	            </tr>';
		    
	      
                  
	 $tempGroupId='';
	while($rec=mysql_fetch_array($r)){
    	if($tempGroupId!=$rec['GroupName']) {
        	echo'<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#000000>
        	     <td class="txtLeft"; colspan="5">'.$rec['GroupName'].'</td>
        	    </tr>'; 
        	$tempGroupId=$rec['GroupName'];
    	} 	
    	echo '<tr>
    	 	      <td style="text-align: center;">'.$i.'</td>
    		      <td>'.$rec['ItemCode'].'</td>
                  <td>'.$rec['ItemName'].'</td>';
    		echo '<td style="text-align: center;" >';
            
			if($rec['bKeyItem']==0)
			     echo '<img src="'.$jBaseUrl.'/administrator/components/com_jcode/source/report/image/unchecked.png" />';
			else 
			    echo '<img src="'.$jBaseUrl.'/administrator/components/com_jcode/source/report/image/checked.png" />';
                        
    		echo   '</td>';
    		echo   '<td>'.$rec['ProductSubGroupName'].'</td>'; 
    	    echo '</tr>';    				 
    	$i++; 
	}
		
	echo'</thead>
    	</table>
        </div>
		</div>  
        </div>
        </div>';
    echo '</body></html>';	
   }else{
   	    echo 'No record found';
    }


?>