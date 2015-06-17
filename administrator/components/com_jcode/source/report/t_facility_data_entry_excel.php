<?php

//include("../define.inc");
include_once ('../database_conn.php');
include_once ("../function_lib.php");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
mysql_query('SET CHARACTER SET utf8');

$filePath = 'pdfslice/product_stock.svg';

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
$total1 = mysql_num_rows($result);

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
$total2 = mysql_num_rows($result1);

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
//$total = mysql_num_rows($result7);

$CFMStockId = '';
$CreatedBy = '';
$LastUpdateBy = '';
$LastSubmittedBy = '';
$PublishedBy = '';
$StatusName = '';
$CreatedDt = '';
$LastUpdateDt = '';
$LastSubmittedDt = '';
$PublishedDt = '';
while (@$row = mysql_fetch_array($result7)) {
	$CFMStockId = @$row['CFMStockId'];
	$CreatedBy = @$row['CreatedBy'];
	$LastUpdateBy = @$row['LastUpdateBy'];
	$LastSubmittedBy = @$row['LastSubmittedBy'];
	$PublishedBy = @$row['PublishedBy'];
	$StatusName = @$row['StatusName'];
	$CreatedDt = @$row['CreatedDt'];
	$LastUpdateDt = @$row['LastUpdateDt'];
	$LastSubmittedDt = @$row['LastSubmittedDt'];
	$PublishedDt = @$row['PublishedDt'];
}

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
$total5 = mysql_num_rows($r);
$totalr = ($total2 / 7);
$RowCount = max($total1, $totalr) + 13;
$TotalRow = max($total1, $totalr) + 14;


if ($total1 > 0 OR $total2 > 0 OR $total5 > 0) {

require('../lib/PHPExcel.php');
$objPHPExcel = new PHPExcel();

/* $objDrawing = new PHPExcel_Worksheet_Drawing();
  $objDrawing -> setPath('../images/logo.png');
  $objDrawing -> setCoordinates('B1');
  $objDrawing -> setWorksheet($objPHPExcel -> getActiveSheet()); */

$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

$objPHPExcel->getActiveSheet()->SetCellValue('C2', $SITETITLE);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'C2');
$objPHPExcel->getActiveSheet()->mergeCells('C2:O2');

$objPHPExcel->getActiveSheet()->SetCellValue('C3', $gTEXT['Facility Level Patient And Stock Status List']);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'C3');
$objPHPExcel->getActiveSheet()->mergeCells('C3:O3');

$objPHPExcel->getActiveSheet()->SetCellValue('C4', $gTEXT['Facility'].': ' . $FacilityName . ', Month:' . $MonthName . ', Year:' . $Year);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => false)), 'C4');
$objPHPExcel->getActiveSheet()->mergeCells('C4:O4');


$objPHPExcel->getActiveSheet()->SetCellValue('B5', $gTEXT['Report Id'] .' : ' . $CFMStockId);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'B5');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C4:P4');

$objPHPExcel->getActiveSheet()->SetCellValue('C5', $gTEXT['Created By'] . ' : ' . $CreatedBy);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'C5');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');

$objPHPExcel->getActiveSheet()->SetCellValue('D5', $gTEXT['Last Upadated By'] .' : ' . $LastUpdateBy);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'D5');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');

$objPHPExcel->getActiveSheet()->SetCellValue('E5', $gTEXT['Submitted By'] .' : ' . $LastSubmittedBy);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'E5');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');

$objPHPExcel->getActiveSheet()->SetCellValue('F5', $gTEXT['Published By'] .' : ' . $PublishedBy);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'F5');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');	

$objPHPExcel->getActiveSheet()->SetCellValue('B6', $StatusName);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C4:P4');

$objPHPExcel->getActiveSheet()->SetCellValue('C6', $gTEXT['Created Date'] .': ' . $CreatedDt);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'C6');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');

$objPHPExcel->getActiveSheet()->SetCellValue('D6', $gTEXT['Last Updated Date'] .' : ' . $LastUpdateDt);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'D6');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');

$objPHPExcel->getActiveSheet()->SetCellValue('E6', $gTEXT['Submitted Date']. ' : ' . $LastSubmittedDt);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'E6');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');	

$objPHPExcel->getActiveSheet()->SetCellValue('F6', $gTEXT['Published Date'] .' : ' . $PublishedDt);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '10', 'bold' => false)), 'F6');
//$objPHPExcel -> getActiveSheet() -> mergeCells('C5:P5');
//////TABLE 1		
$objPHPExcel->getActiveSheet()
		->SetCellValue('A8', 'SL')
		->SetCellValue('B8', 'Case Type')
		->SetCellValue('C8', 'Total');

$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A8');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B8');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C8');

$objPHPExcel->getActiveSheet()->SetCellValue('A7',  $gTEXT['Malaria case summary level']);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'C3');
$objPHPExcel->getActiveSheet()->mergeCells('A7:B7');


 $objPHPExcel->getActiveSheet()->SetCellValue('E7', $gTEXT['Malaria case details']);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'E7');
$objPHPExcel->getActiveSheet()->mergeCells('E7:H7');


$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);

$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getDefaultStyle('A9')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A8' . ':A8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('B8' . ':B8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('C8' . ':C8')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);

$i = 1;
$j = 9;
while ($rec = mysql_fetch_array($result)) {
	$objPHPExcel->getActiveSheet()
			->SetCellValue('A' . $j, $i)
			->SetCellValue('B' . $j, $rec['PatientTypeName'])
			->SetCellValue('C' . $j, checkNullable($rec['TotalPatient']));

	$objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


	$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
	$objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);

	$i++;
	$j++;
}

/////////TABLE 2

$objPHPExcel->getActiveSheet()->SetCellValue('E8', '');
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('E8' . ':E8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('E8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'E8');
$objPHPExcel->getActiveSheet()->mergeCells('E8:E8');

$objPHPExcel->getActiveSheet()->SetCellValue('F8', '');
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('F8' . ':F8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('F8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'F8');
$objPHPExcel->getActiveSheet()->mergeCells('F8:F8');

$objPHPExcel->getActiveSheet()->SetCellValue('G8', $gTEXT['0-4 Years']);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('G8' . ':H8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'G8');
$objPHPExcel->getActiveSheet()->mergeCells('G8:H8');

$objPHPExcel->getActiveSheet()->SetCellValue('I8', $gTEXT['5-14 Years']);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('I8' . ':J8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'I8');
$objPHPExcel->getActiveSheet()->mergeCells('I8:J8');

$objPHPExcel->getActiveSheet()->SetCellValue('K8', $gTEXT['15+ Years']);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('K8' . ':L8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('K8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('K8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'K8');
$objPHPExcel->getActiveSheet()->mergeCells('K8:L8');

$objPHPExcel->getActiveSheet()->SetCellValue('M8', '');
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('M8' . ':M8')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('M8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('M8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'M8');
$objPHPExcel->getActiveSheet()->mergeCells('M8:M8');

$cellIdentifire = array("1" => "A", "2" => "B", "3" => "C", "4" => "D", "5" => "E", "6" => "F", "7" => "G", "8" => "H", "9" => "I", "10" => "J", "11" => "K", "12" => "L", "13" => "M", "14" => "N", "15" => "O", "16" => "P", "17" => "Q", "18" => "R", "19" => "S", "20" => "T", "21" => "U", "22" => "V", "23" => "W", "24" => "X", "25" => "Y", "26" => "Z");

$objPHPExcel->getActiveSheet()
		->SetCellValue('E9', $gTEXT['SL'])
		->SetCellValue('F9', $gTEXT['Case Type'])
		->SetCellValue('G9', $gTEXT['M'])
		->SetCellValue('H9', $gTEXT['F'])
		->SetCellValue('I9', $gTEXT['M'])
		->SetCellValue('J9', $gTEXT['F'])
		->SetCellValue('K9', $gTEXT['M'])
		->SetCellValue('L9', $gTEXT['F'])
		->SetCellValue('M9', $gTEXT['Pregnant Women']);

$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'J9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'K9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'L9');
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'M9');


$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('F9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('J9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('K9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('L9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('M9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);

$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getDefaultStyle('E10')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('E9' . ':E9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('F9' . ':F9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('G9' . ':G9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('H9' . ':H9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('I9' . ':I9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('J9' . ':J9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('K9' . ':K9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('L9' . ':L9')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('M9' . ':M9')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M9')->getFont()->setBold(true);

$tmpFormulationId = -1;
$serial = 0;
$i = 1;
$j = 9;
$stock_inforamtion=$total1+12;
//echo $stock_inforamtion;
while ($rec = mysql_fetch_array($result1)) {
	if ($tmpFormulationId != $rec['FormulationId']) {
		$i = 7;
		$j++;
		$objPHPExcel->getActiveSheet()
				->SetCellValue('E' . $j, ++$serial)
				->SetCellValue('F' . $j, $rec['FormulationName'])
				->SetCellValue($cellIdentifire[$i] . $j, checkNullable($rec['TotalPatient']));
		$tmpFormulationId = $rec['FormulationId'];
	} else {
		$i++;
		$objPHPExcel->getActiveSheet()
				->SetCellValue($cellIdentifire[$i] . $j, checkNullable($rec['TotalPatient']));
		$tmpFormulationId = $rec['FormulationId'];
	}

	$objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
	$objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->applyFromArray($styleThinBlackBorderOutline);
}

///////TABLE 3

$objPHPExcel->getActiveSheet()
		->SetCellValue('A' . $RowCount, $gTEXT['SL'])
		->SetCellValue('B' . $RowCount, $gTEXT['Item'])
		->SetCellValue('C' . $RowCount, $gTEXT['OBL (A)'])
		->SetCellValue('D' . $RowCount, $gTEXT['Received (B)'])
		->SetCellValue('E' . $RowCount, $gTEXT['Dispensed (C)'])
		->SetCellValue('F' . $RowCount, $gTEXT['Adjusted (+-D)'])
		->SetCellValue('G' . $RowCount, $gTEXT['Adjust Reason'])
		->SetCellValue('H' . $RowCount, $gTEXT['Stock Out Days'])
		->SetCellValue('I' . $RowCount, $gTEXT['Stock Out Reason'])
		->SetCellValue('J' . $RowCount, $gTEXT['Closing Balance (E)'])
		->SetCellValue('K' . $RowCount, $gTEXT['AMC (F)'])
		->SetCellValue('L' . $RowCount, $gTEXT['MOS (G)'])
		->SetCellValue('M' . $RowCount, $gTEXT['Max Qty (H)'])
		->SetCellValue('N' . $RowCount, $gTEXT['Order Qty (I)'])
		->SetCellValue('O' . $RowCount, $gTEXT['Actual Order Qty (J)'] )
		->SetCellValue('P' . $RowCount, $gTEXT['Order Qty Update Reason']);

$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'J' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'K' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'L' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'M' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'N' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'O' . $RowCount);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'P' . $RowCount);

$objPHPExcel->getActiveSheet()->getStyle('A' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('B' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('E' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->getStyle('F' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('H' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('J' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('K' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('L' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('M' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('N' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('O' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('P' . $RowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getDefaultStyle('A' . $TotalRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $RowCount . ':A' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('B' . $RowCount . ':B' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('C' . $RowCount . ':C' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('D' . $RowCount . ':D' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('E' . $RowCount . ':E' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('F' . $RowCount . ':F' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('G' . $RowCount . ':G' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('H' . $RowCount . ':H' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('I' . $RowCount . ':I' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('J' . $RowCount . ':J' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('K' . $RowCount . ':K' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('L' . $RowCount . ':L' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('M' . $RowCount . ':M' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('N' . $RowCount . ':N' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('O' . $RowCount . ':O' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('P' . $RowCount . ':P' . $RowCount)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A' . $RowCount)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('P' . $RowCount)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->SetCellValue('A'. $stock_inforamtion,  $gTEXT['Stock Information']);
$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
$objPHPExcel->getActiveSheet()->getStyle('A'. $stock_inforamtion)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getStyle('A'. $stock_inforamtion)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '13', 'bold' => true)), 'C3');
$objPHPExcel->getActiveSheet()->mergeCells('A'. $stock_inforamtion.':B'. $stock_inforamtion);

$i = 1;
$j = $TotalRow;
$tempGroupId = '';
while (@$rec = mysql_fetch_array($r)) {

	$objPHPExcel->getActiveSheet()
			->SetCellValue('A' . $j, $i)
			->SetCellValue('B' . $j, $rec['ItemName'])
			->SetCellValue('C' . $j, checkNullable($rec['OpStock_A']))
			->SetCellValue('D' . $j, checkNullable($rec['ReceiveQty']))
			->SetCellValue('E' . $j, checkNullable($rec['DispenseQty']))
			->SetCellValue('F' . $j, checkNullable($rec['AdjustQty']))
			->SetCellValue('G' . $j, $rec['AdjustReason'])
			->SetCellValue('H' . $j, $rec['StockoutDays'])
			->SetCellValue('I' . $j, $rec['StockOutReasonName'])
			->SetCellValue('J' . $j, checkNullable($rec['ClStock_A']))
			->SetCellValue('K' . $j, checkNullable($rec['AMC']))
			->SetCellValue('L' . $j, checkNull(number_format($rec['MOS'], 1)))
			->SetCellValue('M' . $j, checkNullable($rec['MaxQty']))
			->SetCellValue('N' . $j, checkNullable(($rec['OrderQty'] < 0? 0 : $rec['OrderQty'])))
			->SetCellValue('O' . $j, checkNullable(($rec['ActualQty'] < 0?  0: $rec['ActualQty'])))
			->SetCellValue('P' . $j, $rec['OUReason']);

	$objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G' . $j . ':G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H' . $j . ':H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('I' . $j . ':I' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('J' . $j . ':J' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('K' . $j . ':K' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('L' . $j . ':L' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('M' . $j . ':M' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('N' . $j . ':N' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('O' . $j . ':O' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('P' . $j . ':P' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
	$objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('G' . $j . ':G' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('H' . $j . ':H' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('I' . $j . ':I' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('J' . $j . ':J' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('K' . $j . ':K' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('L' . $j . ':L' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('M' . $j . ':M' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('N' . $j . ':N' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('O' . $j . ':O' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('P' . $j . ':P' . $j)->applyFromArray($styleThinBlackBorderOutline);
	$i++;
	$j++;
}



if (function_exists('date_default_timezone_set')) {
	date_default_timezone_set('UTC');
} else {
	putenv("TZ=UTC");
}

$exportTime = date("Y-m-d_His", time());
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$file = 'Facility_Level_Patient_And_Stock_Status_' . $exportTime . '.xlsx';
$objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
header('Location:media/' . $file);
} else {
	echo 'No record found';
}
?>