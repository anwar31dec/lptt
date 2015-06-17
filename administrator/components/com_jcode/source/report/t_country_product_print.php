<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');


$lan = $_REQUEST['lan'];
if ($lan == 'en-GB') {
    $SITETITLE = SITETITLEENG;
} else {
    $SITETITLE = SITETITLEFRN;
}

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];


$CountryId = $_GET['SelCountryId'];
$CountryName = $_GET['SelCountryName'];
$ShowSelected = $_GET['ShowSelected'];

//echo $ShowSelected;

$sWhere = "";
$sSearch="";
if ($_GET['sSearch'] != "")
    $sSearch = str_replace("|", "+", $_GET['sSearch']); {
    $sWhere = " WHERE (ItemName like '%" . mysql_real_escape_string($sSearch) . "%' 
                         OR " . " ItemCode like '%" . mysql_real_escape_string($sSearch) . "%' )";
}

if ($ShowSelected == 'false') {
    $sql = " SELECT a.CountryProductId, a.CountryId, b.ItemNo, IF(a.CountryProductId is Null,'false','true') chkValue, ItemCode, b.ItemGroupId, 
                 ItemName, GroupName  	 	
                 FROM  t_country_product a 
                 RIGHT JOIN t_itemlist b ON (a.ItemNo = b.ItemNo AND a.CountryId = '" . $CountryId . "')
                 INNER JOIN t_itemgroup c ON b.ItemGroupId = c.ItemGroupId
                 " . $sWhere . " ORDER BY GroupName, ItemName, ItemCode";
} else {
    $sql = " SELECT a.CountryProductId, a.CountryId, b.ItemNo, IF(a.CountryProductId is Null,'false','true') chkValue, ItemCode, b.ItemGroupId, 
                 ItemName, GroupName  	 	
                 FROM  t_country_product a 
                 INNER JOIN t_itemlist b ON (a.ItemNo = b.ItemNo AND a.CountryId = '" . $CountryId . "')
                 INNER JOIN t_itemgroup c ON b.ItemGroupId = c.ItemGroupId
                 " . $sWhere . "  ORDER BY GroupName, ItemName, ItemCode";
}

mysql_query("SET character_set_results=utf8");
$r = mysql_query($sql);
$total = mysql_num_rows($r);
$i = 1;
if ($total > 0) {
    echo '<!DOCTYPE html>
			<html>
			<head>
			 <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
			 <base href="http://localhost/warp/index.php/fr/adminfr/country-fr" />
			 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
			 <meta name="generator" content="Joomla! - Open Source Content Management" />
			 <link rel="stylesheet" href="' . $jBaseUrl . 'templates/protostar/css/template.css" type="text/css" /> 
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/bootstrap/css/bootstrap.min.css" rel="stylesheet">
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/font-awesome.min.css" rel="stylesheet">
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/pace.css" rel="stylesheet">	
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/colorbox/colorbox.css" rel="stylesheet">
			 <link href="' . $jBaseUrl . 'templates/protostar/endless/css/morris.css" rel="stylesheet"/> 	
             <link href="' . $jBaseUrl . 'templates/protostar/endless/css/endless.min.css" rel="stylesheet"> 
	        <link href="' . $jBaseUrl . 'templates/protostar/endless/css/endless-skin.css" rel="stylesheet">
			<link href="' . $jBaseUrl . 'administrator/components/com_jcode/source/css/custom.css" rel="stylesheet"/>
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
           	  <div class="panel-heading" style="text-align:center;">
			  <h2>' . $SITETITLE . '</h2>
              <h3 style="text-align:center;">' . $gTEXT['Product List of'] . '  ' . $CountryName . '</h3>
            </div>	
    			<table class="table table-striped display" id="gridDataCountry">
    				<thead>
    				</thead>
    				<tbody>
    					<tr>
                            <th style="text-align: center;">SL#</th>
						    <th>' . $gTEXT['Product Code'] . '</th>
						    <th >' . $gTEXT['Product Name'] . '</th>
		                </tr>';

    $tempGroupId = '';
    while ($rec = mysql_fetch_array($r)) {
        if ($tempGroupId != $rec['GroupName']) {
            echo'<tr >
                     <td style=background-color:#DAEF62;border-radius:2px; font-size:9px;align: center;color:#00000000; class="txtLeft"; colspan="3">' . $rec['GroupName'] . '</td>
                   </tr>';
            $tempGroupId = $rec['GroupName'];
        }
        echo '<tr>
                    <td style="text-align: center;">' . $i . '</td>
                    <td width="10%">' . $rec['ItemCode'] . '</td>
                    <td>' . $rec['ItemName'] . '</td>
			     </tr>';

        $i++;
    }
    echo'</thead>
    				
    			</table>
            </div>
		</div>  
     </div>';

    echo '</body>
      </html>';
} else {
    $error = "No records found.";
    echo $error;
}

function getcheckBox($v) {
    if ($v == "true") {
        $x = "<input type='checkbox' checked class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    } else {
        $x = "<input type='checkbox' class='datacell' value='" . $v . "' /><span class='custom-checkbox'></span>";
    }
    return $x;
}

?>