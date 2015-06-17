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
		$sWhere = " WHERE  (CountryCode LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                            OR " . " ISO3 LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " CenterLat LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
      	                    OR " . " CountryName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " CenterLong LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " ZoomLevel LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ) ";
							
	}
    $sql = "SELECT SQL_CALC_FOUND_ROWS CountryId, CountryCode, CountryName, CountryNameFrench, ISO3, NumCode, CenterLat, CenterLong, ZoomLevel, LevelType, StartMonth, StartYear
				FROM t_country
				$sWhere $sOrder $sLimit ; "; 
				 
	 mysql_query("SET character_set_results=utf8");     
	$r = mysql_query($sql) ;
    $total = mysql_num_rows($r);
	$i = 1;	
    
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
               <h3>'.$gTEXT['Country List'].'<h3>
                </div>	
                    <table class="table table-striped display" id="gridDataCountry">
				<thead>
                    <tr>
                    		
                        <th style="text-align: center;">SL #</th>
                        <th>'.$gTEXT['Country Code'].'</th>
                        <th>'.$gTEXT['Country Name'].'</th>
                        <th>'.$gTEXT['Country Name (French)'].'</th>
                        <th style="text-align:left;">'.$gTEXT['Country Level'].'</th>
                        <th style="text-align: center;">'.$gTEXT['Center'].'</th> 
                        <th style="text-align: center;">'.$gTEXT['Zoom Level'].'</th> 
				   </tr>
                </thead>
				<tbody>';
                
    while($rec=mysql_fetch_array($r)){
        if($rec['StartMonth']==1) $monthvar='Jan';
		else if($rec['StartMonth']==2)$monthvar='Feb'; 
		else if($rec['StartMonth']==3)$monthvar='Mar'; 
		else if($rec['StartMonth']==4)$monthvar='April'; 
        else if($rec['StartMonth']==5)$monthvar='May'; 
        else if($rec['StartMonth']==6)$monthvar='June'; 
        else if($rec['StartMonth']==7)$monthvar='July'; 
        else if($rec['StartMonth']==8)$monthvar='August'; 
        else if($rec['StartMonth']==9)$monthvar='Sep'; 
        else if($rec['StartMonth']==10)$monthvar='October'; 
        else if($rec['StartMonth']==11)$monthvar='November'; 
        else  $monthvar='Dec';           

	    if($rec['LevelType'] == 1)$LevelName = 'Facility Level'; 
        else $LevelName = 'National Level';
		echo '<tr>
                <td style="text-align: center;">'.$i.'</td>
                <td>'.$rec['ISO3'].'</td>
                <td>'.$rec['CountryName'].'</td>
                 <td>'.$rec['CountryNameFrench'].'</td>
                <td style="text-align:left;" >'.$LevelName.' '.$rec[''].'</td>	
                <td style="text-align: center;" >'. $rec['CenterLat'].' ,'.$rec['CenterLong'].'</td>
                <td style="text-align: center;" >'.$rec['ZoomLevel'].'</td>			
             </tr>';
				 
       $i++; 
	}		
    echo'</tbody>
         </table>
         </div>
         </div>         
         </div>';   
    echo '</body></html>';	
    
 } else{
    		$error = "No record found";	
    		echo $error;
	}



?>