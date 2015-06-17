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
$CountryName = $_GET['CountryName'];

$CountryId = '';
if (isset($_GET['CountryId'])) {
	$CountryId = $_GET['CountryId'];
} else if (isset($_GET['CountryId'])) {
	$CountryId = $_GET['CountryId'];
}

$sWhere = "";
	
    if($CountryId){
		$CountryId = " WHERE a.CountryId = '".$CountryId."' ";
	}
	else
		$CountryId = " WHERE 1=1 ";
    
	if ($_GET['curSearch'] != "") {
		$sWhere = " and (GroupName LIKE '%" . mysql_real_escape_string($_GET['curSearch']) . "%'
                    OR CountryName LIKE '%".mysql_real_escape_string( $_GET['curSearch'] )."%') ";
    }
	
	$sLimit = "";
	if (isset($_GET['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}
	
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_rptfrequency(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
		
	  
		$sql = "SELECT a.RepFreqId,a.CountryId,a.ItemGroupId,a.FrequencyId
		,a.StartMonthId,a.StartYearId,b.CountryName,c.GroupName,d.FrequencyName
		,case a.FrequencyId when 1 then e.MonthName
		else f.MonthName end MonthName,a.StartYearId YearName
		FROM t_reporting_frequency a
		Inner Join t_country b ON a.CountryId=b.CountryId
		Inner Join t_itemgroup c ON a.ItemGroupId=c.ItemGroupId
		Inner Join t_frequency d ON a.FrequencyId=d.FrequencyId 		
		Left Join t_month e ON a.StartMonthId=e.MonthId 
		Left Join t_quarter f ON a.StartMonthId=f.MonthId ".$CountryId."
		$sWhere $sOrder $sLimit "; 
		 mysql_query("SET character_set_results=utf8");     
		$r = mysql_query($sql);  		
        $total = mysql_num_rows($r);
        		
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
					echo ' <div class="col-md-7"> 
           			<div class="panel panel-default table-responsive" id="grid_country">
            		<div class="padding-md clearfix">
           			<div class="panel-heading">
             		<h3 style="text-align: center;">'.$gTEXT['Reporting Frequency List'].'<h3>
             		     <h4 style="text-align: center;">'.$gTEXT['Country Name'].': '.$CountryName.'<h4>
            		</div>	
    					<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    					<tr>
							<th style="text-align: center;">SL#</th>
							<th style="text-align: left;">'.$gTEXT['Country Name'].'</th>
							<th style="text-align: left;">'.$gTEXT['Product Group'].'</th>
							<th style="text-align: left;">'.$gTEXT['Frequency Name'].'</th>
							<th style="text-align: left;">'.$gTEXT['Start Year'].'</th>
							<th style="text-align: left;">'.$gTEXT['Start Month'].'</th>
		                </tr>';
		                   
		$tempGroupId='';
        $i=1;	
		while($rec = mysql_fetch_array($r)){
			//if($tempGroupId!=$rec['CountryName']){
			//echo '<tr style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#00000000>
          //   	    <td class="txtLeft"; colspan="2">'.$rec['CountryName'].'</td>
          //   	  </tr>'; 
		//	$tempGroupId=$rec['CountryName'];
			//}	 
			echo '<tr>
		 	     	<td style="text-align: center;">'.$i.'</td>
			     	<td style="text-align: left;">'.$rec['CountryName'].'</td>
					<td style="text-align: left;">'.$rec['GroupName'].'</td>
					<td style="text-align: left;">'.$rec['FrequencyName'].'</td>
					<td style="text-align: left;">'.$rec['YearName'].'</td>
					<td style="text-align: left;">'.$rec['MonthName'].'</td>
		      	  </tr>';
				 
			$i++; 
		}
		echo '</thead>
			 </table>
             </div>
			 </div>  
     		 </div>';
  		echo '</body></html>';	
		
    	} else{
			$error = "No record found for this country.";	
			echo $error;
	}
	
	
function fnColumnToField_rptfrequency($i) {
	if ($i == 2)
		return "CountryName ";
	else if ($i == 3)
		return "GroupName ";
	else if ($i == 4)
		return "FrequencyName ";
	else if ($i == 5)
		return "YearName ";
	else if ($i == 6)
		return "MonthName ";
}
	

?>