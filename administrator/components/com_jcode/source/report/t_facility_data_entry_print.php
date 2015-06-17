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

	$CFMStockId = $_GET['CFMStockId'];
	$MonthId = $_GET['MonthId'];
	$Year = $_GET['YearId'];
	$CountryId = $_GET['CountryId'];
	$RegionId = $_GET['RegionId'];
	$DistrictId = $_GET['DistrictId'];
	$OwnerTypeId = $_GET['OwnerTypeId'];
	$FacilityId = $_GET['FacilityId'];
	
	$CountryName = $_GET['CountryName'];	
	$FacilityName = $_GET['FacilityName'];
	$GroupName = $_GET['ItemGroupName'];	
	$MonthName = $_GET['MonthName'];	
	
	$jBaseUrl = $_GET['jBaseUrl'];


	function checkNullable($value) {
		$retVal = '';
		if ($value == 0)
			$retVal = '';
		else
			$retVal = number_format($value);
		return $retVal;
	}

	function checkNull($value) {
		$retVal = '';
		if ($value == 0)
			$retVal = '';
		else
			$retVal = $value;
		return $retVal;
	}

	$sqlf = " SELECT FacilityId, FacilityName FROM t_facility WHERE FacilityId=$FacilityId";

	$resultf = mysql_query($sqlf);
	$FacilityName = '';
	while ($row = mysql_fetch_array($resultf)) {
		$FacilityName = $row['FacilityName'];
	}

	 $sql1 = " SELECT
		  b.CFMPOId,
		  a.FormulationName    PatientTypeName,
		  b.RefillPatient,
		  b.NewPatient,
		  b.TotalPatient
		FROM t_formulation a
		  INNER JOIN t_cfm_patientoverview b
			ON a.FormulationId = b.FormulationId
		WHERE b.CFMStockId = $CFMStockId
			AND FacilityId = $FacilityId
			AND MonthId = $MonthId
			AND YEAR = '".$Year."'
			AND CountryId = $CountryId
		ORDER BY b.CFMPOId ASC";	

	$result = mysql_query($sql1);
	$total = mysql_num_rows($result);

	$sql7 = "SELECT CFMStockId, FacilityId, MonthId, Year, 
		(SELECT b.name FROM  ykx9st_users b WHERE b.username = a.CreatedBy) CreatedBy, DATE_FORMAT(CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,
		(SELECT b.name FROM  ykx9st_users b WHERE b.username = a.LastUpdateBy)  LastUpdateBy,	
		(SELECT b.name FROM ykx9st_users b WHERE b.username = a.LastSubmittedBy) LastSubmittedBy ,
		c.StatusId, c.StatusName,
		DATE_FORMAT(LastUpdateDt, '%d-%b-%Y %h:%i %p') LastUpdateDt,	
		DATE_FORMAT(LastSubmittedDt, '%d-%b-%Y %h:%i %p') LastSubmittedDt,	
		DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,	
		(SELECT b.name FROM ykx9st_users b WHERE b.username = a.PublishedBy) PublishedBy ,
		DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt 	
		FROM t_cfm_masterstockstatus a LEFT JOIN t_status c ON a.StatusId = c.StatusId ";
		$sql7 .= " WHERE FacilityId = " . $FacilityId . " and MonthId = " . $MonthId . " and Year = '" . $Year . "' AND CountryId = $CountryId ";
		
		
	$result7 = mysql_query($sql7);
	$total = mysql_num_rows($result7);

	$sql = "SELECT a.CFMStockStatusId, a.FacilityId, a.MonthId, a.Year, a.ItemGroupId, b.ItemSL, a.ItemNo, b.ItemName, OpStock OpStock_A, a.OpStock_C, a.ReceiveQty, a.DispenseQty, a.AdjustQty, a.AdjustId AdjustReason";
		$sql .= ",a.StockoutDays, a.StockOutReasonId, a.ClStock ClStock_A, a.ClStock_C, a.ClStockSourceId, a.MOS, a.AMC, a.AMC_C, a.AmcChangeReasonId, a.MaxQty, a.OrderQty, a.ActualQty, a.OUReasonId, 
		 a.UserId, a.LastEditTime, c.ProductSubGroupName FormulationName FROM t_cfm_stockstatus a, t_itemlist b, t_product_subgroup c ";
		$sql .= " WHERE a.CFMStockId = $CFMStockId
					AND `YEAR` = '$Year'
					AND MonthId = $MonthId
					AND CountryId = 1
					AND a.FacilityId =  $FacilityId
					AND a.ItemNo = b.ItemNo
					AND b.ProductSubGroupId = c.ProductSubGroupId ";
		$sql .= " ORDER BY b.ItemSL asc";

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
				  <link href="' . $jBaseUrl . 'templates/hoxa/js/mainmenu/bootstrap.min.css" rel="stylesheet">
				  <link href="' . $jBaseUrl . 'templates/hoxa/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
				.logow {
					width: 114px;
					height:auto;
					float: left;
					margin: 5px 20px 0 10%;
					}
				.logow img {
					width: 114px;
					height: auto;
					}
				.w1 {
					width: 100%;
					}
					.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
						border: 1px solid #ddd !important;
						padding: 4px !important;
					}
					
				.mb0 {
					margin-bottom: 0;
					}
				.paddT0 {
					padding: 10px 0 !important;
					}
				.padd10 {
					padding: 10px !important;
					}
				.panel-heading {
					border-bottom: none !important;
				}

	.cl{clear:both;}			
	.printw {
		width: 1000px;
		height: auto;
		margin: 0 auto;
		margin-bottom:50px;
		overflow: hidden;
		}
	.print_head {
		width: 1000px;
		height: auto;
		margin: 0 auto;
		overflow: hidden;
		margin-bottom:20px;
		}
	.printw .col4 {
		width: 228px;
		height: auto;
		margin: 0 4px 4px 4px;
		float: left;
		padding: 3px;
		overflow: hidden;

		}
		.col4 table {
			width: 220px;
			}
		.col4 table {
			border-collapse: collapse;
		}
		
		.col4 table, th, td {
			border: 1px solid #ddd;
			padding: 2px;
			font-size:10px;
			word-break:break-all;
		}	

	/*----------*/
	.printw .col8 {
		width: 710px;
		height: auto;
		margin: 0 4px 4px 4px;
		float: left;
		padding: 3px;
		overflow: hidden;

		}
		.col8 table {
			width: 710px;
			}
		.col8 table {
			border-collapse: collapse;
		}
		
		.col8 table, th, td {
			border: 1px solid #ddd;
			padding: 2px;
			font-size:10px;
			word-break:break-all;
		}	
	/*----------*/	

		
		.selfClear:after {
		content: ".";
		display: block;
		height: 0px;
		clear: both;
		visibility: hidden;
		}	
		.panel-heading {
			margin: 0 auto;
			width:100%;
			text-align:center;
			padding: 0 15px;
		}	
			
				</style>
				</head>
				<body>';

		echo '</br></br><div class="row"> 							
					<div class="panel panel-default table-responsive" id="grid_country">
					<div class="padding-md clearfix">
					<div class="panel-heading">
						<span class="w1" style="text-align:center; font-size: 22px; font-weight:bold;"> 
							' . $SITETITLE . '</span></br>
							
						<span class="w1" style="text-align: center; font-size: 15px; font-weight:bold;">
						' . $gTEXT['Facility Level Patient And Stock Status List'] . '</span></br>
						
						<span class="w1" style="text-align: center; font-size: 12px; font-weight:bold;">' . $gTEXT['Facility'] . ': ' . $FacilityName . '</span></br>
						
						<span class="w1" style="text-align: center; font-size: 12px; font-weight:bold;">
							' . $gTEXT['Month'] . ': ' . $MonthName . ', ' . $gTEXT['Year'] . ': ' . $Year . '
						</span></br></br></br>
						</div>';
		$headertable = '';
		while (@$row = mysql_fetch_array($result7)) { 
			$headertable = '<tr>
							<td style="word-break:break-all; width: 120px;">
							' . $gTEXT['Report Id'] . ' : ' . @$row['CFMStockId'] . '</td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Created By'] . ' : ' . @$row['CreatedBy'] . '</td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Last Upadated By'] . ' : ' . @$row['LastUpdateBy'] . '</td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Submitted By'] . ' : ' . @$row['LastSubmittedBy'] . '</td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Published By'] . ' : ' . @$row['PublishedBy'] . '</td>
						</tr>
						<tr> 
							<td style="word-break:break-all; width: 120px;">
							<b style="font-size:18px;">' . @$row['StatusName'] . '</b></td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Created Date'] . ' : ' . @$row['CreatedDt'] . '</td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Last Updated Date'] . ' : ' . @$row['LastUpdateDt'] . '</td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Submitted Date'] . '  : ' . @$row['LastSubmittedDt'] . '</td>
							<td style="word-break:break-all; width: 200px;">
							' . $gTEXT['Published Date'] . ' : ' . @$row['PublishedDt'] . '</td> 
						</tr>';
		}
		echo '<div class="table table-striped display print_head selfClear" style="padding:0 7px;"> 
						<table>						
							' . $headertable . '						
						</table>					
					</div>';

		echo '<div class="printw selfClear">
		<div class="col-xs-3" style="font-weight:bold; font-size:14px; padding-left:6px;">' . $gTEXT['Malaria case summary level'] . '</div>
		<div class="col-xs-9" style="font-weight:bold; font-size:14px; padding-left:0px;">' . $gTEXT['Malaria case details'] . '</div>';

		echo '<div class="col4" style="width: 210px;">
	<table  style="width: 200px;">
				<thead>
				</thead>
				<tbody> 
				<tr>
					<th style="text-align: left;">' . $gTEXT['SL'] . '</th> 
					<th style="text-align: left;">' . $gTEXT['Case Type'] . '</th> 
					<th style="text-align: left; width:40px;">' . $gTEXT['Total'] . '</th>
				</tr>';

		$getpatienttype = '';
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			echo '<tr>
					  <td style="text-align: left; width:30px;">' . ++$i . '</td>
					  <td style="text-align: left; word-wrap: break-word; width:130px;">' . $row['PatientTypeName'] . '</td>
					  <td style="text-align: right; width:40px;">' . checkNullable($row['TotalPatient']) . '</td>
				 </tr>';
		}

		echo '</thead></table></div>';


		echo '<div class="col8">
		<table style="width: 100%;">
			<thead>
			</thead>
				<tbody>
				<tr >
					<th style="border-radius:2px; text-align:center;"></th> 
					<th style="border-radius:2px; text-align:center;"></th>
					<th style="border-radius:2px; text-align:center;" colspan="2">' . $gTEXT['0-4 Years'] . '</th>
					<th style="border-radius:2px; text-align:center;" colspan="2">' . $gTEXT['5-14 Years'] . '</th>
					<th style="border-radius:2px; text-align:center;" colspan="2">' . $gTEXT['15+ Years'] . '</th>
					<th style="border-radius:2px; text-align:center;" ></th> 
				</tr>			
				<tr>
					<th style="text-align: center; width:30px;">' . $gTEXT['SL'] . '</th> 
					<th style="text-align: left; word-wrap: break-word; width:195px;">' . $gTEXT['Case Type'] . '</th> 
					<th style="text-align: right; width:50px;">' . $gTEXT['M'] . '</th>
					<th style="text-align: right; width:50px;">' . $gTEXT['F'] . '</th>
					<th style="text-align: right; width:50px;">' . $gTEXT['M'] . '</th>
					<th style="text-align: right; width:50px;">' . $gTEXT['F'] . '</th>
					<th style="text-align: right; width:50px;">' . $gTEXT['M'] . '</th>
					<th style="text-align: right; width:50px;">' . $gTEXT['F'] . '</th>
					<th style="text-align: right; width:90px;">' . $gTEXT['Pregnant Women'] . '</th>
				</tr>';

		$sql1 = " SELECT
				  b.CFMPatientStatusId,
				  c.FormulationId,
				  c.FormulationName,
				  b.RegimenId,
				  d.RegimenName RegimenMasterName,
				  a.GenderTypeId,
				  b.RefillPatient,
				  b.NewPatient,
				  b.TotalPatient
				FROM t_regimen a
				  INNER JOIN t_cfm_regimenpatient b
					ON a.RegimenId = b.RegimenId
				  INNER JOIN t_formulation c
					ON a.FormulationId = c.FormulationId
				INNER JOIN t_regimen_master d
					ON a.RegMasterId = d.RegMasterId
				WHERE b.CFMStockId = $CFMStockId
					AND FacilityId = $FacilityId
					AND MonthId = $MonthId
					AND `Year` = '$Year'
					AND CountryId = $CountryId
				ORDER BY c.FormulationName, b.RegimenId, a.GenderTypeId  desc";
			//echo $sql1;	
		$result1 = mysql_query($sql1);
		$total = mysql_num_rows($result1);

		$tmpFormulationId = -1;
		$i = 0;
		while ($row = mysql_fetch_array($result1)) {

			if ($tmpFormulationId != $row['FormulationId']) {
				if ($i > 0)
					echo '<tr>';
				echo '<tr>
						<td style="text-align: center; width:30px;">' . ++$i . '</td> 
						<td style="text-align: left; word-wrap: break-word; width:200px;">' . $row['FormulationName'] . '</td>';
				if (!$row['TotalPatient'] == 0 || !$row['TotalPatient'] == '') {
					echo '<td style="text-align: right; width:50px;">' . number_format($row['TotalPatient']) . '</td>';
				} else {
					echo '<td></td>';
				}
				$tmpFormulationId = $row['FormulationId'];
			} else {
				if (!$row['TotalPatient'] == 0 || !$row['TotalPatient'] == '') {
					echo '<td style="text-align: right; width:50px;">' . number_format($row['TotalPatient']) . '</td>';
				} else {
					echo '<td></td>';
				}
				$tmpFormulationId = $row['FormulationId'];
			}
		}
		if ($i > 0)
			echo '</tr>';
		echo '</tbody>
			</thead>
		</table>
	</div>';


		echo '<div class="printw selfClear" style="padding-left:7px;">';

		echo '<div class="col-xs-12" style="font-weight:bold; font-size:14px; padding-left:6px;">' . $gTEXT['Stock Information'] . '</div>';

		echo '<table class="table table-striped display" id="gridDataCountry" style="width: 93%;"> 
				<thead>
				</thead>
				<tbody>
				<tr>
					<th style="text-align: center;  width: 50px;">' . $gTEXT['SL'] . '#</th>
					<th style="text-align: left; word-break:break-all; width: 210px;">' . $gTEXT['Item'] . '</th>   
					<th style="text-align: left; word-break:break-all; width: 70px;">' . $gTEXT['OBL (A)'] . '</th>
					<th style="text-align: center; word-break:break-all; width: 70px;">' .$gTEXT['Received (B)'] . '</th>
					<th style="text-align: left; word-break:break-all; width: 70px;">' . $gTEXT['Dispensed (C)'] . '</th> 
					<th style="text-align: left; word-break:break-all; width: 70px;">' . $gTEXT['Adjusted (+-D)'] . '</th> 
					<th style="text-align: center; word-break:break-all; width: 70px;">' .$gTEXT['Adjust Reason'] . '</th> 
					<th style="text-align: left; word-break:break-all; width: 70px;">' . $gTEXT['Stock Out Days'] . '</th>  
					<th style="text-align: left; word-break:break-all; width: 70px;">' . $gTEXT['Stock Out Reason'] . '</th> 
					<th style="text-align: center; word-break:break-all; width: 70px;">' . $gTEXT['Closing Balance (E)'] . '</th>
					<th style="text-align: left; word-break:break-all; width: 50px;">' . $gTEXT['AMC (F)'] . '</th> 
					<th style="text-align: left; word-break:break-all; width: 50px;">' . $gTEXT['MOS (G)'] . '</th> 
					<th style="text-align: center; word-break:break-all; width: 50px;">' . $gTEXT['Max Qty (H)'] . '</th>  
					<th style="text-align: left; word-break:break-all; width: 70px;">' . $gTEXT['Order Qty (I)'] . '</th> 
					<th style="text-align: left; word-break:break-all; width: 70px;">' . $gTEXT['Actual Order Qty (J)'] . '</th>
					<th style="text-align: left; text-align: left; word-break:break-all; width: 90px;">' . $gTEXT['Order Qty Update Reason'] . '</th> 
				</tr>';
		$tempGroupId = '';
		$i = 1;
		while ($rec = mysql_fetch_array($r)) {

			echo '<tr>
						  <td style="text-align: center;  width: 50px;">' . $i . '</td>
						  <td style="text-align: left; text-align: left; word-break:break-all; width: 210px;">
						  ' . $rec['ItemName'] . '</td>
						  <td style="text-align: right;">' . checkNullable($rec['OpStock_A']) . '</td>
						  <td style="text-align: right;">' . checkNullable($rec['ReceiveQty']) . '</td>
						  <td style="text-align: right;">' . checkNullable($rec['DispenseQty']) . '</td>
						  <td style="text-align: right;">' . checkNullable($rec['AdjustQty']) . '</td>
						  <td style="text-align: left;">' . $rec['AdjustReason'] . '</td>
						  <td style="text-align: right; word-break:break-all; width: 70px;">' . $rec['StockoutDays'] . '</td>
						  <td style="text-align: left; word-break:break-all; width: 70px;">' . $rec['StockOutReasonName'] . '</td>
						  <td style="text-align: right;">' . checkNullable($rec['ClStock_A']) . '</td>
						  <td style="text-align: right;">' . checkNullable($rec['AMC']) . '</td>
						  <td style="text-align: right;">' . checkNull(number_format($rec['MOS'], 1)) . '</td>
						  <td style="text-align: right;">' . checkNullable($rec['MaxQty']) . '</td>
						  <td style="text-align: right;">' . checkNullable(($rec['OrderQty'] < 0?  0: $rec['OrderQty'])) . '</td>
						  <td style="text-align: right;">' . checkNullable(($rec['ActualQty'] < 0?  0: $rec['ActualQty'])) . '</td>
						  <td style="text-align: left; text-align: left; word-break:break-all; width: 90px;">' . $rec['OUReason'] . '</td>
					 </tr>';

			$i++;
		}
		echo'</thead>
			 </table>
			 </div>
			 </div>  
			 </div></div>';
		echo '</body></html>';
	} else {
		$error = "No record found";
		echo $error;
	}
	?>
