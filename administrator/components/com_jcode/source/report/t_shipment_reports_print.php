<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());


$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];

function getMonthsBtnTwoDate($firstDate, $lastDate) {
    $diff = abs(strtotime($lastDate) - strtotime($firstDate));
    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    return $months;
}

$CountryId = $_GET['ACountryId'];
$AFundingSourceId = $_GET['AFundingSourceId'];
$ASStatusId = $_GET['ASStatusId'];
$months = $_GET['MonthNumber'];
$ItemGroup = $_GET['ItemGroup'];
$OwnerTypeId = $_GET['OwnerType'];

$OwnerTypeName = $_GET['OwnerTypeName'];
$CountryName = $_GET['CountryName'];
$FundingSourceName = $_GET['FundingSourceName'];
$MonthName = isset($_POST['MonthName']) ? $_POST['MonthName'] : '';
$ItemGroupName = $_GET['ItemGroupName'];
$ASStatusName = $_GET['ASStatusName'];

$StartMonthId = $_GET['StartMonthId'];
$EndMonthId = $_GET['EndMonthId'];
$StartYearId = $_GET['StartYearId'];
$EndYearId = $_GET['EndYearId'];


if ($_GET['MonthNumber'] != 0) {
    $months = $_GET['MonthNumber'];
    $monthIndex = date("m");
    $yearIndex = date("Y");
    settype($yearIndex, "integer");

    $startDate = $yearIndex . "-" . $monthIndex . "-" . "01";
    $startDate = date('Y-m-d', strtotime($startDate));
    $months--;
    $endDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($startDate)) . "+" . $months . " month"));
} else {
    $startDate = $StartYearId . "-" . $StartMonthId . "-" . "01";
    $startDate = date('Y-m-d', strtotime($startDate));

    $d = cal_days_in_month(CAL_GREGORIAN, $EndMonthId, $EndYearId);
    $endDate = $EndYearId . "-" . $EndMonthId . "-" . $d;
    $endDate = date('Y-m-d', strtotime($endDate));
}

//////////////////

if ($AFundingSourceId) {
    $AFundingSourceId = " AND a.FundingSourceId = '" . $AFundingSourceId . "' ";
}
if ($ASStatusId) {
    $ASStatusId = " AND a.ShipmentStatusId = '" . $ASStatusId . "' ";
}
if ($ItemGroup) {
    $ItemGroup = " AND e.ItemGroupId = '" . $ItemGroup . "' ";
}
if ($OwnerTypeId) {
    $OwnerTypeId = " AND f.OwnerTypeId = '" . $OwnerTypeId . "' ";
}

$sLimit = "";
if (isset($_GET['iDisplayStart'])) {
    $sLimit = " LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
}

$sOrder = "";
if (isset($_GET['iSortCol_0'])) {
    $sOrder = " ORDER BY  ";
    for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
        $sOrder .= fnColumnToField_agencyShipment(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
    }
    $sOrder = substr_replace($sOrder, "", -2);
}

$sWhere = "";
if ($_GET['sSearch'] != "") {
    $sWhere = "  AND (a.ItemNo LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'  OR " .
            " e.ItemName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR " .
            " c.ShipmentStatusDesc LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%')  ";
}

$sql = "SELECT SQL_CALC_FOUND_ROWS AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty, a.OwnerTypeId, f.OwnerTypeName 
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo
            INNER JOIN t_owner_type f ON a.OwnerTypeId = f.OwnerTypeId 
            WHERE CAST(a.ShipmentDate AS DATETIME) BETWEEN CAST('$startDate' AS DATETIME) AND CAST('$endDate' AS DATETIME) 
            AND (a.CountryId = " . $CountryId . " OR " . $CountryId . " = 0) 
            " . $AFundingSourceId . " " . $ASStatusId . " " . $ItemGroup . " " . $OwnerTypeId . "
			$sWhere $sOrder $sLimit ";

mysql_query("SET character_set_results=utf8");
$r = mysql_query($sql);
$total = mysql_num_rows($r);
$i = 0;
$f = 0;
$GrandtotalQty = 0;
$SubtotalQty = 0;
$OldCountry = ' ';
$NewCountry = ' ';
$tbody='';
$tempGroupId='';
$sOutput='';

$serial = 1;
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

    echo '<div class="row" style="padding: 0 30px; margin:0 auto;"> 
    			<div class="panel panel-default table-responsive" id="grid_country">
        			<div class="padding-md clearfix">
            			<div class="panel-heading">
                			<h3 style="text-align:center;">' . $gTEXT['Shipment Report Data List'] . ' of ' . $CountryName . '<h3>
                			<h4 style="text-align:center;">' . $FundingSourceName.' - '.$ASStatusName .' - '.$ItemGroupName.' - '.$OwnerTypeName. '<h4>
                			<h4 style="text-align:center;">' . 'From ' . date('M,Y', strtotime($startDate)) . ' to ' . date('M,Y', strtotime($endDate)) . '<h4>                			
                			</div>	
            			<table class="table table-striped display" id="gridDataCountry">
            				<thead>
            				</thead>
            				<tbody>
            					<tr>
            					    <th style="text-align: center;">SL.</th>
            					    <th style="text-align: left;">' . $gTEXT['Product Name'] . '</th>
            					    <th style="text-align: left;">' . $gTEXT['Funding Source'] . '</th>
            					    <th style="text-align: left;">' . $gTEXT['Shipment Status'] . '</th>
            					    <th style="text-align: right;">' . $gTEXT['Shipment Date'] . '</th>
            					    <th style="text-align: right;">' . $gTEXT['Quantity'] . '</th>
            					    </tr>';

    while ($rec = mysql_fetch_array($r)) {
        $ItemName = trim(preg_replace('/\s+/', ' ', addslashes($rec['ItemName'])));
        $date = strtotime($rec['ShipmentDate']);
        $newdate = date('d/m/Y', $date);

        /////////////////
        if ($OldCountry == ' ')
            $OldCountry = addslashes($rec['CountryName']);

        $NewCountry = addslashes($rec['CountryName']);
        if ($OldCountry != $NewCountry) {

            echo'<tr >
                   <td style="background-color:#FE9929;border-radius:2px;align:center; font-size:14px;"colspan="5">Sub Total</td>
                   <td style="background-color:#FE9929;border-radius:2px;text-align:right; font-size:14px;">' . number_format($SubtotalQty) . '</td>
				</tr>';
            echo'<tr >
                 <td style="background-color:#DAEF62;border-radius:2px;align:center; font-size:14px;"colspan="6">' . $NewCountry . '</td>
                 
                 </tr>';
            $tempGroupId = $rec['CountryName'];

            $sOutput.=',';
            $sOutput.="[";
            $sOutput.='"Sub Total",';
            $sOutput.='"",';
            $sOutput.='"",';
            $sOutput.='"",';
            $sOutput.='"",';
            $sOutput.='"' . number_format($SubtotalQty) . '",';
            $sOutput.='""';
            $sOutput.="]";

            $OldCountry = $NewCountry;
            $SubtotalQty = $rec['Qty'];
        }
        else
            $SubtotalQty+=$rec['Qty'];
        //////////////////
        if ($f++)
            $sOutput .= ',';
        $sOutput .= "[";
        $sOutput .= '"' . $serial . '",';
        $sOutput .= '"' . $ItemName . '",';
        $sOutput .= '"' . addslashes($rec['FundingSourceName']) . '",';
        $sOutput .= '"' . addslashes($rec['ShipmentStatusDesc']) . '",';
        $sOutput .= '"' . $newdate . '",';
        $sOutput .= '"' . number_format(addslashes($rec['Qty'])) . '",';
        $sOutput .= '"' . addslashes($rec['CountryName']) . '"';
        $sOutput .= "]";

        if ($tempGroupId != $rec['CountryName']) {
            echo'<tr >
								                     <td style="background-color:#DAEF62;border-radius:2px;  align:center; font-size:14px;" colspan="6">' . $rec['CountryName'] . '</td>
								                   </tr>';
            $tempGroupId = $rec['CountryName'];
        }


        echo '<tr>
                                             <td style="text-align: center;">' . $serial++ . '</td>
                                             <td style="text-align:left;">' . $ItemName . '</td>
                                	         <td style="text-align:left;">' . addslashes($rec['FundingSourceName']) . '</td>
                                             <td style="text-align:left;">' . addslashes($rec['ShipmentStatusDesc']) . '</td>
                                	         <td style="text-align:right;">' . $newdate . '</td>
                                	         <td style="text-align:right;">' . number_format(addslashes($rec['Qty'])) . '</td>
                                	     </tr>';
        $GrandtotalQty+=$rec['Qty'];
        if ($total == $i + 1) {
            echo'<tr >
			                   <td style="background-color:#FE9929;border-radius:2px;  align:center; font-size:14px;" colspan="5">Sub Total</td>
			                   <td style="background-color:#FE9929;border-radius:2px;  text-align:right; font-size:14px;" ">' . number_format($SubtotalQty) . '</td>
							</tr>';
            echo'<tr >
			                   <td style="background-color:#50ABED;color:#ffffff;border-radius:2px;  align:center; font-size:14px;" colspan="5">Grand Total</td>
			                   <td style="background-color:#50ABED;color:#ffffff;border-radius:2px;  text-align:right; font-size:14px;" ">' . number_format($GrandtotalQty) . '</td>
							</tr>';
        }
        $i++;
    }
    $sOutput.= '] }';

    echo '</tbody></table></div></div></div><br/>';

    echo'</tbody>';
    echo $tbody;
    echo'</tbody>    				
    			</table>
            </div>
		</div>  
     </div>';


    echo '</body>
      </html>';
} else {
    $error = "No record found.";
    echo $error;
}
?>