<?php

include ("../define.inc");

$jBaseUrl = $_REQUEST['jBaseUrl'];

$lan = $_REQUEST['lan'];

$reportSaveName = $_REQUEST['reportSaveName'];

$reportHeaderList = json_decode($_REQUEST['reportHeaderList'], true);

if ($lan == 'en-GB')
	array_unshift($reportHeaderList, SITETITLEENG);
else
	array_unshift($reportHeaderList, SITETITLEFRN);

$tableHeaderList = json_decode($_REQUEST['tableHeaderList'], true);
$tableHeaderColSpanList = json_decode($_REQUEST['tableHeaderColSpanList'], true);
$tableHeaderRowSpanList = json_decode($_REQUEST['tableHeaderRowSpanList'], true);
$dataList = json_decode($_REQUEST['dataList'], true);

$dataColSpanList = json_decode($_REQUEST['dataColSpanList'], true);
$chart = $_REQUEST['chart'];
$dataAlignment = json_decode($_REQUEST['dataAlignment'], true);
$tableHeaderColWidthList = json_decode($_REQUEST['tableHeaderColWidthList'], true);

$cellIdentifire = array("1" => "A", "2" => "B", "3" => "C", "4" => "D", "5" => "E", "6" => "F", "7" => "G", "8" => "H"
, "9" => "I", "10" => "J", "11" => "K", "12" => "L", "13" => "M", "14" => "N", "15" => "O", "16" => "P", "17" => "Q"
, "18" => "R", "19" => "S", "20" => "T", "21" => "U", "22" => "V", "23" => "W", "24" => "X", "25" => "Y", "26" => "Z"
, "27" => "AA", "28" => "AB", "29" => "AC", "30" => "AD", "31" => "AE", "32" => "AF", "33" => "AG", "34" => "AH"
, "35" => "AI", "36" => "AJ", "37" => "AK", "38" => "AL", "39" => "AM", "40" => "AN", "41" => "AO", "42" => "AP"
, "43" => "AQ", "44" => "AR", "45" => "AS", "46" => "AT", "47" => "AU", "48" => "AV", "49" => "AW", "50" => "AX"
, "51" => "AY", "52" => "AZ", "53" => "BA", "54" => "BB", "55" => "BC", "56" => "BD", "57" => "BE", "58" => "BF"
, "59" => "BG", "60" => "BH", "61" => "BI", "62" => "BJ", "63" => "BK", "64" => "BL", "65" => "BM", "66" => "BN"
, "67" => "BO", "68" => "BP", "69" => "BQ", "70" => "BR", "71" => "BS", "72" => "BT", "73" => "BU", "74" => "BV"
, "75" => "BX", "76" => "BX", "77" => "BY", "78" => "BZ", "79" => "CA", "80" => "CB", "81" => "CC", "82" => "CD"
, "83" => "CE", "84" => "CF", "85" => "CG", "86" => "CH", "87" => "CI", "88" => "CJ", "89" => "CK", "90" => "CL");

require ('../lib/PHPExcel.php');

$objPHPExcel = new PHPExcel();

$reportHeaderListCount = count($reportHeaderList);

$tableFieldCount = array_sum($tableHeaderColSpanList[0]);
//count($tableHeaderList);

//Report Header start

for ($i = 1; $i <= $reportHeaderListCount; $i++) {

	//$objPHPExcel->getActiveSheet()->SetCellValue('A2','Health Commodity Dashboard');

	$objPHPExcel -> getActiveSheet() -> SetCellValue('A' . $i, $reportHeaderList[$i - 1]);

	$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

	//$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel -> getActiveSheet() -> getStyle('A' . $i) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	//$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);

	$objPHPExcel -> getActiveSheet() -> getStyle('A' . $i) -> getFont() -> setBold(true);

	//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '18', 'bold' => true)), 'A2');

	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => (18 - $i), 'bold' => true)), 'A' . $i);

	//$objPHPExcel -> getActiveSheet() -> mergeCells('A2:C2');

	$objPHPExcel -> getActiveSheet() -> mergeCells('A' . $i . ':' . $cellIdentifire[$tableFieldCount] . $i);
	//mergeCells('A2:C2')
}
//Report Header end
//Table Header start

/*

 for($i=1;$i<=$tableFieldCount;$i++){

 $objPHPExcel->getActiveSheet()->SetCellValue($cellIdentifire[$i].($reportHeaderListCount+2), $tableHeaderList[$i-1]);

 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i] . ($reportHeaderListCount+2));

 $objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i]) -> setWidth(18);

 $objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].($reportHeaderListCount+2)  . ':'.$cellIdentifire[$i] . ($reportHeaderListCount+2)) -> applyFromArray($styleThinBlackBorderOutline);

 $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i].($reportHeaderListCount+2))->getFont()->setBold(true);

 }*/

$headColIndex = array();
$headColStartIndexList = array();
$headRowSpanList = array();

for ($th = 0; $th < count($tableHeaderList[$th]); $th++) {
	$preIndex = 1;
	for ($i = 0; $i < count($tableHeaderList[$th]); $i++) {
		$ColSpan = $tableHeaderColSpanList[$th][$i];
		$headColIndex[$th][$i] = $preIndex;
		$headColStartIndexList[$th][$i] = $ColSpan;
		$preIndex = $preIndex + $ColSpan;
	}
}

//for($i=1;$i<=$tableFieldCount;$i++)
//	$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i]) -> setWidth(10);

for ($th = 0; $th < 1; $th++) {

	for ($i = 0; $i < count($headColIndex[$th]); $i++) {
		$rowNo = $reportHeaderListCount + 2 + $th;
		$cellColIndex = $headColIndex[$th][$i];

		$objPHPExcel -> getActiveSheet() -> SetCellValue($cellIdentifire[$cellColIndex] . $rowNo, $tableHeaderList[$th][$i]);

		//$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$cellColIndex].$rowNo)->getFont()->setBold(true);

		$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$cellColIndex] . $rowNo);

		$colSpan = $headColStartIndexList[$th][$i];
		$rowSpan = $tableHeaderRowSpanList[$th][$i];

		if ($colSpan > 1) {
			//mergeColumn
			$objPHPExcel -> getActiveSheet() -> mergeCells($cellIdentifire[$cellColIndex] . $rowNo . ':' . $cellIdentifire[$cellColIndex + $colSpan - 1] . $rowNo);
			//mergeCells('A2:C2')

			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo . ':' . $cellIdentifire[$cellColIndex + $colSpan - 1] . $rowNo) -> applyFromArray($styleThinBlackBorderOutline);

			//when colSpan > 1 then align always center
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		} else if ($rowSpan > 1) {
			//mergeRow
			$objPHPExcel -> getActiveSheet() -> mergeCells($cellIdentifire[$cellColIndex] . $rowNo . ':' . $cellIdentifire[$cellColIndex] . ($rowNo + $rowSpan - 1));
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo . ':' . $cellIdentifire[$cellColIndex] . ($rowNo + $rowSpan - 1)) -> applyFromArray($styleThinBlackBorderOutline);
		} else {
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo . ':' . $cellIdentifire[$cellColIndex] . ($rowNo + $rowSpan - 1)) -> applyFromArray($styleThinBlackBorderOutline);

			//set table header align start
			if ($dataAlignment[$cellColIndex - 1] == 'left')
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			else if ($dataAlignment[$cellColIndex - 1] == 'right')
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						
			else if ($dataAlignment[$cellColIndex - 1] == 'center')
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
			else
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//set table header align end
		}

		if ($colSpan > 1)
			setCelvalues($th, $cellColIndex, $rowNo, $colSpan, $objPHPExcel, $cellIdentifire, $tableHeaderList, $headColStartIndexList, $styleThinBlackBorderOutline, $dataAlignment);
	}

}

//Recursive function for multiple table merge header

function setCelvalues($ths, $cellColumnIndex, $rowsNo, $columnSpan, $objPHPExcel, $cellIdentifire, $tableHeaderList, $headColStartIndexList, $styleThinBlackBorderOutline, $dataAlignment) {
	$ths++;
	$rowsNo++;
	$p = 0;

	while ($p < $columnSpan) {
		$objPHPExcel -> getActiveSheet() -> SetCellValue($cellIdentifire[$cellColumnIndex] . $rowsNo, $tableHeaderList[$ths][0]);

		//$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$cellColumnIndex].$rowsNo)->getFont()->setBold(true);

		$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$cellColumnIndex] . $rowsNo);

		$CurrColSpan = $headColStartIndexList[$ths][0];
		$CurrRowSpan = $tableHeaderRowSpanList[$ths][0];

		if ($CurrColSpan > 1) {
			//mergeColumn
			$objPHPExcel -> getActiveSheet() -> mergeCells($cellIdentifire[$cellColumnIndex] . $rowsNo . ':' . $cellIdentifire[$cellColumnIndex + $CurrColSpan - 1] . $rowsNo);
			//mergeCells('A2:C2')

			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo . ':' . $cellIdentifire[$cellColumnIndex + $CurrColSpan - 1] . $rowsNo) -> applyFromArray($styleThinBlackBorderOutline);

			//when colSpan > 1 then align always center
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColIndex] . $rowNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		} else if ($CurrRowSpan > 1) {
			//mergeRow
			$objPHPExcel -> getActiveSheet() -> mergeCells($cellIdentifire[$cellColumnIndex] . $rowsNo . ':' . $cellIdentifire[$cellColumnIndex] . ($rowsNo + $CurrRowSpan - 1));
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo . ':' . $cellIdentifire[$cellColumnIndex] . ($rowsNo + $CurrRowSpan - 1)) -> applyFromArray($styleThinBlackBorderOutline);
		} else {
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo . ':' . $cellIdentifire[$cellColumnIndex] . $rowsNo) -> applyFromArray($styleThinBlackBorderOutline);

			//set table header align start
			if ($dataAlignment[$cellColumnIndex - 1] == 'left')
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			else if ($dataAlignment[$cellColumnIndex - 1] == 'right')
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						
			else if ($dataAlignment[$cellColumnIndex - 1] == 'center')
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
			else
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$cellColumnIndex] . $rowsNo) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//set table header align end
		}

		if ($CurrColSpan > 1)
			setCelvalues($ths, $cellColumnIndex, $rowsNo, $headColStartIndexList[$ths][0], $objPHPExcel, $cellIdentifire, $tableHeaderList, $headColStartIndexList, $styleThinBlackBorderOutline, $dataAlignment);

		$cellColumnIndex = $cellColumnIndex + $CurrColSpan;

		$p = $p + $CurrColSpan;

		if ($tableHeaderList[$ths])

			array_shift($tableHeaderList[$ths]);

		if ($headColStartIndexList[$ths])

			array_shift($headColStartIndexList[$ths]);

		if ($tableHeaderRowSpanList[$ths])

			array_shift($tableHeaderRowSpanList[$ths]);

	}

	/**/

}

//Table Header end

//cell width start

$totalCell = 0;
for ($r = 0; $r < count($tableHeaderColSpanList); $r++) {
	for ($c = 0; $c < count($tableHeaderColSpanList[$r]); $c++) {
		if ($tableHeaderColSpanList[$r][$c] == 1)
			$totalCell++;
	}
}

for ($c = 1; $c <= $totalCell; $c++) {
	$width = 15;
	if ($tableHeaderColWidthList[$c - 1] == '')
		$width = end($tableHeaderColWidthList);
	else
		$width = $tableHeaderColWidthList[$c - 1];
	$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$c]) -> setWidth($width);

	//$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$c]) -> setWidth(100);
}
//cell width end

$sl = 1;

$cell = ($reportHeaderListCount + count($tableHeaderList) + 2);
//Start table body

//$objPHPExcel -> getActiveSheet() -> getStyle('A' . $cell . ':A' . $cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//$styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),

for ($row = 1; $row <= count($dataList); $row++) {

	//dataColSpanList

	for ($i = 1; $i <= count($dataList[$row - 1]); $i++) {

		$objPHPExcel -> getActiveSheet() -> SetCellValue($cellIdentifire[$i] . $cell, $dataList[$row - 1][$i - 1]);

		//$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$i.':'.$cellIdentifire[$tableFieldCount].$i);//mergeCells('A2:C2')

		$endMergeCell = intval($i + $dataColSpanList[$row - 1][$i - 1]) - 1;

		$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell . ':' . $cellIdentifire[$endMergeCell] . $cell) -> applyFromArray($styleThinBlackBorderOutline);
		//Table body Line color     $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);

		$objPHPExcel -> getActiveSheet() -> mergeCells($cellIdentifire[$i] . $cell . ':' . $cellIdentifire[$endMergeCell] . $cell);
		//mergeCells('A2:C2')

		//If $endMergeCell==$tableFieldCount then this row is group. so this need to left align

		//Check group(Here: group left and others center align)

		if ($i == $endMergeCell) {

			if ($dataAlignment[$i - 1] == 'left')
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			else if ($dataAlignment[$i - 1] == 'right')
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						
			else if ($dataAlignment[$i - 1] == 'center')
							$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
			else
				$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		} else
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i] . $cell) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	}
	$cell++;
}
// echo 'Hello world';
// exit;
//$reportSaveNameUTF8 = iconv("UTF-8", "ISO-8859-9//TRANSLIT", $reportSaveName); // For french

//$exportTime = date("Y-m-d_His", time());

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

// $file = $reportSaveNameUTF8.'.xlsx';

$file = $reportSaveName . '.xlsx';

$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file));

//header('Location:media/' . $file);

//echo 'mr';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// $objDrawing = new PHPExcel_Worksheet_Drawing();

// $objDrawing -> setPath('../images/logo.png');

//  $objDrawing -> setCoordinates('A1');

//  $objDrawing -> setWorksheet($objPHPExcel -> getActiveSheet());

/*

 $objDrawing = new PHPExcel_Worksheet_Drawing();

 $objDrawing->setName('PHPExcel logo');

 $objDrawing->setDescription('PHPExcel logo');

 //$objDrawing->setPath('../images/logo.png'); //svg file //National.svg

 $objDrawing->setPath('../images/National.svg'); //svg file //

 //$objDrawing->setHeight(136);

 //$objDrawing->setWidth(136);

 $objDrawing->setCoordinates('D14');

 //$objDrawing->setOffsetX(10);

 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

 */
?>