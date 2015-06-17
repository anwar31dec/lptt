	<?php

	//include_once ('../database_conn.php');
	include("../universal_function_lib.php");
	include('../language/lang_en.php');
	include('../language/lang_fr.php');
	include('../language/lang_switcher_report.php');
	mysql_query('SET CHARACTER SET utf8');


	$TEXT = $TEXT_EN;

	$lan = $_REQUEST['lan'];
	if ($lan == 'en-GB') {
		$SITETITLE = SITETITLEENG;
	} else {
		$SITETITLE = SITETITLEFRN;
	}




	$gTEXT = $TEXT;
	$task = '';
	if (isset($_POST['action'])) {
		$task = $_POST['action'];
	} else if (isset($_GET['action'])) {
		$task = $_GET['action'];
	}

 switch ($task) {
    case 'FacilityDataEntryReportPDF' :
        FacilityDataEntryReportPDF($conn);
        break;
    default :
        echo "{failure:true}";
        break;
 }

 function FacilityDataEntryReportPDF($conn) {
    global $gTEXT;
    global $SITETITLE;
	
    $Year = isset($_REQUEST['Year']) ? $_REQUEST['Year'] : '';
    $Month = isset($_REQUEST['Month']) ? $_REQUEST['Month'] : '';
    $RegionId = isset($_REQUEST['RegionId']) ? $_REQUEST['RegionId'] : '';
    $RegionName = isset($_REQUEST['RegionName']) ? $_REQUEST['RegionName'] : '';
    $MonthName = isset($_REQUEST['MonthName']) ? $_REQUEST['MonthName'] : '';
    $ItemGroupName = isset($_REQUEST['ItemGroupName']) ? $_REQUEST['ItemGroupName'] : '';
    $lastyear = getYearForLastMonth($Year, $Month);
    $lastmonth = getLastMonth($Year, $Month);

    $CFMStockId = isset($_REQUEST['CFMStockId']) ? $_REQUEST['CFMStockId'] : '';
    $CountryId = isset($_REQUEST['CountryId']) ? $_REQUEST['CountryId'] : '';
    //$DistrictId = isset($_REQUEST['DistrictId']) ? $_REQUEST['DistrictId'] : '';
    //$OwnerTypeId = isset($_REQUEST['OwnerTypeId']) ? $_REQUEST['OwnerTypeId'] : '';
    $Year = isset($_REQUEST['YearId']) ? $_REQUEST['YearId'] : '';
    $MonthId = isset($_REQUEST['MonthId']) ? $_REQUEST['MonthId'] : '';
	
    $MonthName = isset($_REQUEST['MonthName']) ? $_REQUEST['MonthName'] : '';
	
    $FacilityId = isset($_REQUEST['FacilityId']) ? $_REQUEST['FacilityId'] : '';
    

	
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
    $pdf->SetFont('times', 'B', 20);
    $pdf->AddPage('L', 'A4');

    $sqlf = " SELECT FacilityId, FacilityName FROM t_facility WHERE FacilityId=$FacilityId";

    $resultf = mysql_query($sqlf);
    $FacilityName = '';
    while ($row = mysql_fetch_array($resultf)) {
        $FacilityName = $row['FacilityName'];
    }

    $html3 = '<div class="padding-md clearfix" style="text-align:center;">
					<h2 style="text-align:center;">' . $SITETITLE . '</h2>
					<h4 style="text-align:center;">' . $gTEXT['Facility Level Patient And Stock Status List'] . '</h4>
					<h5 style="text-align:center;">' . $gTEXT['Facility'] . ': ' . $FacilityName . ',
					' . $gTEXT['Month'] . ': ' . $MonthName . ', 
					' . $gTEXT['Year'] . ': ' . $Year . '<h5>
			  </div>';

    $pdf->SetFont('dejavusans', '', 9);
    $pdf->writeHTMLCell(140, 20, 75, 10, $html3, '', 0, 0, false, 'C', true);

//===============================facility data entry Table======================================
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

	$sql7 = " SELECT CFMStockId, FacilityId, MonthId, Year, 
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

    if ($total > 0) {
        $data = array();

        $i = 0;
        $getRegimen = '';
        $htmlPrint1 = '';
        while ($row = mysql_fetch_array($result)) {

            $htmlPrint1.= '<tr nobr="true">
				  <td style="text-align: left; word-wrap: break-word; width:30px;">' . ++$i . '</td>
				  <td style="text-align: left; word-wrap: break-word; width:100px;">' . $row['PatientTypeName'] . '</td>
				  <td style="text-align: right; width:40px;">' . checkNullable($row['TotalPatient']) . '</td>
			 </tr>';
        }

        $htmlC1 = '
	<style>
	.cl{clear:both;}
	.col4 {
		width: 260px;
		height: auto;
		float: left;
		overflow: hidden;
		padding: 20px;
		background: #00CCCC;
		font-size:10px;
		clear: left;
		}
	.col4 table, th, td {
		border: 1px solid black;
		padding: 2px;
	}
        
        .printw {
    height: auto;
    margin: 0 auto 50px;
    overflow: hidden;
    width: 1000px;
	}
	</style>       
	<div class="col4">
		<table  style="width: 200px;">
			<tr>
			 <th style="text-align: left; word-wrap: break-word; width:170px;" colspan="3">
                         ' . $gTEXT['Malaria case summary level'] . '</th> 
			</tr>
			<tr>
				<th style="text-align: left; width:30px;">' . $gTEXT['SL'] . '</th> 
				<th style="text-align: left; width:100px;">' . $gTEXT['Case Type'] . '</th> 
				<th style="text-align: left; width:40px;">' . $gTEXT['Total'] . '</th>
			</tr>
			' . $htmlPrint1 . '
		</table>
	</div>';
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
        $result1 = mysql_query($sql1);
        $total = mysql_num_rows($result1);

        $tmpFormulationId = -1;
        $i = 0;
        $htmlPrint2 = '';
        while (@$row = mysql_fetch_array($result1)) {

            if ($tmpFormulationId != @$row['FormulationId']) {
                if ($i > 0)
                    $htmlPrint2.= '</tr>';

                $htmlPrint2.= '<tr nobr="true">
				  <td style="text-align: left; word-wrap: break-word; width:30px;">' . ++$i . '</td>
				  <td style="text-align: left; word-wrap: break-word; width:250px;">' . @$row['FormulationName'] . '</td>';
                $htmlPrint2.='<td style="text-align: right; width:70px;">' . checkNullable(@$row['TotalPatient']) . '</td>';
                $tmpFormulationId = @$row['FormulationId'];
            }else {
                $htmlPrint2.= '<td style="text-align: right; width:70px;">' . checkNullable(@$row['TotalPatient']) . '</td>';

                $tmpFormulationId = @$row['FormulationId'];
            }
        }
        if ($i > 0)
            $htmlPrint2.= '</tr>';

        $htmlC2 = '
	<style>
	.cl{clear:both;}
	.col4 {
		width: 300px;
		height: auto;
		float: left;
		overflow: hidden;
		padding: 20px;
		background: #00CCCC;
		font-size:10px;
		clear: left;
		}
	.col4 table, th, td {
		border: 1px solid black;
		padding: 2px;
	}
	</style>
	<div class="col4">
		<table  style="width: 800px;">
                        <tr>
			 <th style="text-align: left; word-wrap: break-word; width:770px;" colspan="9">
                         ' . $gTEXT['Malaria case details'] . '</th> 
			</tr>

			<tr nobr="true">
				<th style="text-align: left; word-wrap: break-word; width:30px;"></th> 
				<th style="text-align: left; word-wrap: break-word; width:250px;"></th> 
				<th style="text-align: center; width:140px;"  colspan="2">' . $gTEXT['0-4 Years'] . '</th>
				<th style="text-align: center; width:140px;"  colspan="2">' . $gTEXT['5-14 Years'] . '</th>
				<th style="text-align: center; width:140px;"  colspan="2">' . $gTEXT['15+ Years'] . '</th>
				<th style="text-align: left; width:70px;"></th>
			</tr>
			<tr nobr="true">
				<th style="text-align: left; word-wrap: break-word; width:30px;">' . $gTEXT['SL'] . '</th> 
				<th style="text-align: left; word-wrap: break-word; width:250px;">' . $gTEXT['Case Type'] . '</th> 
				<th style="text-align: right; width:70px;">' . $gTEXT['M'] . '</th>
				<th style="text-align: right; width:70px;">' . $gTEXT['F'] . '</th>
				<th style="text-align: right; width:70px;">' . $gTEXT['M'] . '</th>
				<th style="text-align: right; width:70px;">' . $gTEXT['F'] . '</th>
				<th style="text-align: right; width:70px;">' . $gTEXT['M'] . '</th>
				<th style="text-align: right; width:70px;">' . $gTEXT['F'] . '</th>
				<th style="text-align: right; width:70px;">' . $gTEXT['Pregnant Women'] . '</th>
			</tr>
			' . $htmlPrint2 . '
		</table>
	</div>';


        $f = 0;
        $tblHTML = '';
        $tempGroupId = '';
        while (@$rec = mysql_fetch_array($r)) {

            $data['SL'][$f] = $f;
            $data['ItemName'][$f] = @$rec['ItemName'];
            $data['OpStock_A'][$f] = checkNullable(@$rec['OpStock_A']);
            $data['ReceiveQty'][$f] = checkNullable(@$rec['ReceiveQty']);
            $data['ActualQty'][$f] = checkNullable(@$rec['ActualQty']);
            $data['DispenseQty'][$f] = checkNullable(@$rec['DispenseQty']);
            $data['AdjustQty'][$f] = checkNullable(@$rec['AdjustQty']);
            $data['AdjustReason'][$f] = @$rec['AdjustReason'];
            $data['StockoutDays'][$f] = @$rec['StockoutDays'];
            $data['StockOutReasonName'][$f] = @$rec['StockOutReasonName'];

            $data['ClStock_A'][$f] = checkNullable(@$rec['ClStock_A']);
            $data['AMC'][$f] = checkNullable(@$rec['AMC']);
            $data['MOS'][$f] = checkNull(number_format(@$rec['MOS'], 1));
            $data['MaxQty'][$f] = checkNullable(@$rec['MaxQty']);
            $data['OrderQty'][$f] = checkNullable((@$rec['OrderQty'] < 0?  0: @$rec['OrderQty']));
            $data['ActualQty'][$f] = checkNullable((@$rec['ActualQty'] < 0?  0: @$rec['ActualQty']));
            $data['OUReason'][$f] = @$rec['OUReason'];

            $tblHTML.='<tr style="page-break-inside:avoid;">
            				<td align="center" width="30" valign="middle">' . ($data['SL'][$f] + 1) . '</td>  
                            <td align="left" width="157" valign="middle">' . $data['ItemName'][$f] . '</td>
                            <td align="right" width="50" valign="middle">' . $data['OpStock_A'][$f] . '</td>
                            <td align="right" width="50" valign="middle">' . $data['ReceiveQty'][$f] . '</td> 
                            <td align="right" width="50" valign="middle">' . $data['DispenseQty'][$f] . '</td>
                            <td align="right" width="50" valign="middle">' . $data['AdjustQty'][$f] . '</td>
                            <td align="left" width="55" valign="middle">' . $data['AdjustReason'][$f] . '</td> 
							<td align="right" width="50" valign="middle">' . $data['StockoutDays'][$f] . '</td> 
							<td align="left" width="55" valign="middle">' . $data['StockOutReasonName'][$f] . '</td> 
                            <td align="right" width="50" valign="middle">' . $data['ClStock_A'][$f] . '</td> 
                            <td align="right" width="50" valign="middle">' . $data['AMC'][$f] . '</td>
                            <td align="right" width="50" valign="middle">' . $data['MOS'][$f] . '</td>
                            <td align="right" width="50" valign="middle">' . $data['MaxQty'][$f] . '</td> 
							<td align="right" width="50" valign="middle">' . $data['OrderQty'][$f] . '</td> 
							<td align="right" width="60" valign="middle">' . $data['ActualQty'][$f] . '</td>
							<td align="left" width="100" valign="middle">' . $data['OUReason'][$f] . '</td>  
                    </tr>';
            $f++;
        }
        $headertable = '';
        while (@$row = mysql_fetch_array($result7)) {
            $headertable = '<tr>
						<td align="left" width="80" valign="middle">
						' . $gTEXT['Report Id'] . ' : ' . @$row['CFMStockId'] . '</td>
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Created By'] . ' : ' . @$row['CreatedBy'] . '</td> 
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Last Upadated By'] . ' : ' . @$row['LastUpdateBy'] . '</td>
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Submitted By'] . ' : ' . @$row['LastSubmittedBy'] . '</td>
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Published By'] . ' : ' . @$row['PublishedBy'] . '</td>
				  </tr>
				  <tr>
						<td align="left" width="80" valign="middle">
						<b style="font-size:11px;">' . @$row['StatusName'] . '</b></td>
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Created Date'] . ' : ' . @$row['CreatedDt'] . '</td> 
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Last Updated Date'] . ' : ' . @$row['LastUpdateDt'] . '</td>
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Submitted Date'] . ' : ' . @$row['LastSubmittedDt'] . '</td>
						<td align="left" width="220" valign="middle">
						' . $gTEXT['Published Date'] . ' : ' . @$row['PublishedDt'] . '</td>
				  </tr>';
        }
        $htmlHead = '
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
            th{
                height: 20;
                font-size:9px;
            }
		
            </style>
			<div style="margin:0 0 10px 0;">
			<table width="100%" border="0.5" style="margin:0 auto;" class="clearfix">
            	  ' . $headertable . '
			</table>
			</div>
			<div class="clearfix"></div>';


        $htmlC5 = '
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
            th{
                height: 20;
                font-size:9px;
            }
		
            </style>
			<div class="clearfix"></div>
                          <div class="col-xs-12" style="font-weight:bold; font-size:14px; padding-left:6px; text-align:left;">' . $gTEXT['Stock Information'] . '</div>
            <table width="100%" border="0.5" style="margin:0 auto;">
                       
                                        <tr>
						<th align="center" width="30" valign="middle">' . $gTEXT['SL'] . '#</th>
						<th align="left" width="157" valign="middle">' . $gTEXT['Item'] . '</th> 
						<th align="right" width="50" valign="middle">' . $gTEXT['OBL (A)'] . '</th>
						<th align="right" width="50" valign="middle">' . $gTEXT['Received (B)'] . '</th>
						<th align="right" width="50" valign="middle">' . $gTEXT['Dispensed (C)'] . '</th> 
						<th align="right" width="50" valign="middle">' . $gTEXT['Adjusted (+-D)'] . '</th> 
						<th align="left" width="55" valign="middle">' . $gTEXT['Adjust Reason'] . '</th>
						<th align="right" width="50" valign="middle">' . $gTEXT['Stock Out Days'] . '</th> 
						<th align="left" width="55" valign="middle">' . $gTEXT['Stock Out Reason'] . '</th> 
						<th align="right" width="50" valign="middle">' . $gTEXT['Closing Balance (E)'] . '</th>
						<th align="right" width="50" valign="middle">' . $gTEXT['AMC (F)'] . '</th> 
						<th align="right" width="50" valign="middle">' . $gTEXT['MOS (G)'] . '</th> 
						<th align="right" width="50" valign="middle">' . $gTEXT['Max Qty (H)'] . '</th>
						<th align="right" width="50" valign="middle">' . $gTEXT['Order Qty (I)'] . '</th> 
						<th align="right" width="60" valign="middle">' . $gTEXT['Actual Order Qty (J)'] . '</th>
						<th align="left" width="100" valign="middle">' . $gTEXT['Order Qty Update Reason'] . '</th> 
            	  </tr>' . $tblHTML . '</table>';


        //echo $htmlC2;
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 50, 10, 40, $htmlHead, '', 0, 0, false, 'C', true);
        $pdf->writeHTMLCell(0, 50, 10, 63, $htmlC1, '', 0, 0, false, 'C', true);
        $pdf->writeHTMLCell(0, 50, 63, 63, $htmlC2, '', 0, 0, false, 'C', true);
        //$pdf->writeHTMLCell(0, 50, 132, 60, $htmlC3, '', 0, 0, false, 'C', true);
        //$pdf->writeHTMLCell(0, 50, 208, 60, $htmlC4, '', 0, 0, false, 'C', true);
        $pdf->writeHTMLCell(0, 50, 10, 140, $htmlC5, '', 0, 0, false, 'C', true);
        //$pdf->writeHTMLCell(0, 50, 10, 290, $htmlC5, '', 0, 0, false, 'C', true); 
        //$pdf->Cell(0, 0, $htmlC5, 1, 1, 'C');		
        $pdf->endPage();
        $filename = 'Facility_Level_Patient_And_Stock_Status_' . $ItemGroupName . '_' . $MonthName . '_' . $Year . '.pdf';
        $filePath = SITEDOCUMENT . 'administrator/components/com_jcode/source/report/pdfslice/' . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $pdf->Output('pdfslice/' . $filename, 'F');

        echo $filename;
    } else {
        echo 'Processing Error';
    }
	}

	?>