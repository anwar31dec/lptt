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
$FacilityLevel = $_GET['FacilityLevel'];
$CountryName = $_GET['CountryName'];
$FacilityLevelName = $_GET['FacilityLevelName'];
$MosTypeId = $_GET['MostypeFacilityId'];

$lan = $_REQUEST['lan'];
if ($lan == 'en-GB') {
    $SITETITLE = SITETITLEENG;
} else {
    $SITETITLE = SITETITLEFRN;
}

if (!$MosTypeId) {
   $MosTypeId='"'. '"';
}

if ($FacilityLevel) {
    $FacilityLevel = " AND a.FLevelId = " . $FacilityLevel . " ";
}


$sOrder = "order by MosTypeId ";
$sql = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, a.ColorCode, IconMos, IconMos_Width, IconMos_Height,MosLabel,a.CountryId,a.FLevelId
				FROM t_mostype_facility a
				INNER JOIN t_country b ON a.CountryId = b.CountryId
				INNER JOIN t_facility_level c ON a.FLevelId = c.FLevelId
				AND (a.CountryId = " . $CountryId . " OR " . $CountryId . " = 0) " . $FacilityLevel . " $sOrder ";


$sqlMostypeDetils = "SELECT  MostypeDetailsId, MosTypeId, MosTypeName, MinMos, MaxMos,a.ColorCode, IconMos, IconMos_Width, IconMos_Height,'' MosLabel,a.CountryId,a.FLevelId
				FROM t_mostype_facility_details a
				INNER JOIN t_country b ON a.CountryId = b.CountryId
				INNER JOIN t_facility_level c ON a.FLevelId = c.FLevelId
				where a.CountryId = '".$CountryId."' AND MosTypeId = '".$MosTypeId."' $FacilityLevel $sOrder ";

//echo $sqlMostypeDetils;
mysql_query("SET character_set_results=utf8");
$r = mysql_query($sql);
$total = mysql_num_rows($r);

$QueryMostypeDetials = mysql_query($sqlMostypeDetils);
$totalMostypeDetails = mysql_num_rows($QueryMostypeDetials);

$i = 1;
$j=1;
if ($total > 0) {
    echo '<!DOCTYPE html>
			<html>
			<head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />	
				<base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
				<meta http-equiv="content-type" content="text/html; charset=utf-8" />
				<meta name="generator" content="Joomla! - Open Source Content Management" />
				
				<link rel="stylesheet" href="'.$jBaseUrl.'templates/protostar/css/template.css" type="text/css"/> 
				<link href="'.$jBaseUrl.'administrator/components/com_jcode/source/css/bootstrap.min.css" rel="stylesheet"/>
				<link href="'.$jBaseUrl.'administrator/components/com_jcode/source/css/endless.min.css" rel="stylesheet"/>
			
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
                <h2 style="text-align:center;">'.$SITETITLE.'</h2>
              	<h3 style="text-align:center;">' . $gTEXT['MOS Type For Facility'] . '<h3>
              	<h4 style="text-align:center; font-size:15px;">' . $gTEXT['Country Name'] . ': ' . $CountryName . ',   ' . $gTEXT['Facility Level'] . ': ' . $FacilityLevelName . ' <h4>
                    <h4 style="text-align:center; font-size:15px;">' . $gTEXT['MOS Type Facility Master List'] . '<h4>
           </div>
           
            <table class="table table-striped display" id="gridDataCountry">
            <thead>
            <tr>
             <th style="text-align: center;">SL.</th> 
            <th>' . $gTEXT['MOS Type Name'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Minimum MOS'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Maximum MOS'] . '</th>
            <th style="text-align: center;">' . $gTEXT['Color Code'] . '</th> 
            <th style="text-align: left;">' . $gTEXT['Icon Mos'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Icon Mos Width'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Icon Mos Height'] . '</th>
            <th style="text-align: left;">' . $gTEXT['MosLabel'] . '</th> 
          </tr>';
    while ($rec = mysql_fetch_array($r)) {
        $bgcolor="";
        if($MosTypeId==$rec['MosTypeId']){
              $bgcolor="background-color: #9AD268;  color: #fff;";
        }
        echo '<tr style="'.$bgcolor.'">
            <td style="text-align: center;">' . $i . '</td>
            <td>' . $rec['MosTypeName'] . '</td>
            <td style="text-align: center;">' . $rec['MinMos'] . '</td>
            <td style="text-align: center;">' . $rec['MaxMos'] . '</td>
            <td style="height:5px;width:8px;background-color:' . $rec['ColorCode'] . '"></td>
            <td style="text-align: left;">' . $rec['IconMos'] . '</td>
            <td style="text-align: center;">' . $rec['IconMos_Width'] . '</td>
            <td style="text-align: center;">' . $rec['IconMos_Height'] . '</td>
            <td style="text-align: left;">' . $rec['MosLabel'] . '</td>
	</tr>';

        $i++;
    }
    echo'</thead>	
    	 </table>
         </div>
        </div>  
         </div>';

    

    // Mostype Details......
   
    echo '<div class="row"> 		 	
        <div class="panel panel-default table-responsive" id="grid_country">
       <div class="padding-md clearfix">
       <div class="panel-heading">
       <h3 style="text-align:center; font-size:15px;">' . $gTEXT['MOS Type Facility Item List'] . '<h3>
       </div>	
        <table class="table table-striped display" id="gridDataCountry">
    	<thead>
        <tr>
            <th style="text-align: center;">SL.</th> 
            <th>' . $gTEXT['MOS Type Name'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Minimum MOS'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Maximum MOS'] . '</th>
            <th style="text-align: center;">' . $gTEXT['Color Code'] . '</th> 
            <th style="text-align: left;">' . $gTEXT['Icon Mos'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Icon Mos Width'] . '</th> 
            <th style="text-align: center;">' . $gTEXT['Icon Mos Height'] . '</th>
      </tr>';
     if($totalMostypeDetails> 0 ){
    while ($recDetials = mysql_fetch_array($QueryMostypeDetials)) {
        echo '<tr>
                <td style="text-align: center;">' . $j . '</td>
                <td>' . $recDetials['MosTypeName'] . '</td>
                <td style="text-align: center;">' . $recDetials['MinMos'] . '</td>
                <td style="text-align: center;">' . $recDetials['MaxMos'] . '</td>
                <td style="height:5px;width:8px;background-color:' . $recDetials['ColorCode'] . '"></td>
                <td style="text-align: left;">' . $recDetials['IconMos'] . '</td>
                <td style="text-align: center;">' . $recDetials['IconMos_Width'] . '</td>
                <td style="text-align: center;">' . $recDetials['IconMos_Height'] . '</td>
            </tr>';
        $j++;
    }
     }else{
         $nodata="No record found";
        echo '<tr><td colspan="8" style="border:none; text-align:center;">'.$nodata.'</td></tr>';
    }
   
    echo'</thead>	
    	 </table>
         </div>
        </div>  
      </div>';
    
    echo '</body></html>';
} else {
    $error = "No record found.";
    echo $error;
   
}
?>