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
     $CountryId = $_GET['CountryId'];
     $ARegionId = $_GET['ARegionId'];
     $CountryName = $_GET['CountryName'];
     $RegionName = $_GET['RegionName'];
    if($ARegionId){
		$ARegionId = " AND a.RegionId = '".$ARegionId."' ";
	}   
    $sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " WHERE  (DistrictName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') ";
	}

	$sql = "	SELECT  DistrictId,DistrictName, a.CountryId, a.RegionId
				FROM t_districts a
                INNER JOIN t_region b ON a.RegionId = b.RegionId
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) ".$ARegionId."
                $sWhere
                $sOrder
                $sLimit order by DistrictName";
			
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
			  <meta name="generator" content="Joomla! - Open Source Content Management" /
			  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
            <h3 style="text-align:center;">'.$gTEXT['District List'].'<h3>
            <h4 style="text-align:center;">'.$gTEXT['Country'].' : '.$CountryName.'<h4>
            <h5 style="text-align:center;">'.$gTEXT['Region'].' : '.$RegionName.'<h5>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    		<thead>
    		</thead>
    		<tbody>
    		<tr>
				<th style="text-align: center;">SL#</th>
				<th style="text-align: left;">'.$gTEXT['District Name'].'</th> 
		    </tr>';
	while($rec=mysql_fetch_array($r)){
			echo '<tr>
		 	      <td style="text-align: center;">'.$i.'</td>
			      <td style="text-align: left;">'.$rec['DistrictName'].'</td>
			      </tr>';
				 
			$i++; 
	}
	echo'</thead>
    	 </table>
         </div>
		 </div>  
         </div>';
    echo '</body></html>';	
	
    } else{
		 $error ='No record found';	
		 echo $error;
	}
	
?>