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
$tempGroupId = '';
$sOutput='';


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

if ($total > 0) {
    require('../lib/PHPExcel.php');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Shipment Report Data List'] . ' on ' . ($CountryName));
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');


    $objPHPExcel->getActiveSheet()->SetCellValue('A3',  $FundingSourceName . ' - ' . $ASStatusName . ' - ' . $ItemGroupName . ' - ' . $OwnerTypeName);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');


    $objPHPExcel->getActiveSheet()->SetCellValue('A4', (' From ' . date('M,Y', strtotime($startDate)) . ' to ' . date('M,Y', strtotime($endDate))));
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
    $objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A6', 'SL#')
            ->SetCellValue('B6', $gTEXT['Product Name'])
            ->SetCellValue('C6', $gTEXT['Funding Source'])
            ->SetCellValue('D6', $gTEXT['Shipment Status'])
            ->SetCellValue('E6', $gTEXT['Shipment Date'])
            ->SetCellValue('F6', $gTEXT['Quantity']);


    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');


    $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('E6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(55);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);


    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6' . ':A6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B6' . ':B6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C6' . ':C6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('D6' . ':D6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('E6' . ':E6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('F6' . ':F6')->applyFromArray($styleThinBlackBorderOutline);


    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);


    $serial = 1;
    $j = 7;
    while ($rec = mysql_fetch_array($r)) {
        $ItemName = trim(preg_replace('/\s+/', ' ', addslashes($rec['ItemName'])));
        $date = strtotime($rec['ShipmentDate']);
        $newdate = date('d/m/Y', $date);

        /////////////////
        if ($OldCountry == ' ')
            $OldCountry = addslashes($rec['CountryName']);

        $NewCountry = addslashes($rec['CountryName']);
        if ($OldCountry != $NewCountry) {
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':E' . $j);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, 'Sub Total');
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FE9929'),
                )
            );
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline1);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('F' . $j, number_format($SubtotalQty));
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $j++;


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
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DAEF62'),));
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':F' . $j);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $rec['CountryName']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline1);
            $tempGroupId = $rec['CountryName'];
            $j++;
        }


        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $serial++)
                ->SetCellValue('B' . $j, $ItemName)
                ->SetCellValue('C' . $j, addslashes($rec['FundingSourceName']))
                ->SetCellValue('D' . $j, addslashes($rec['ShipmentStatusDesc']))
                ->SetCellValue('E' . $j, $newdate)
                ->SetCellValue('F' . $j, number_format(addslashes($rec['Qty'])));
        $GrandtotalQty+=$rec['Qty'];


        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);

        if ($total == $i + 1) {
            $j++;

            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':E' . $j);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, 'Sub Total');
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FE9929'),
                )
            );
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline1);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('F' . $j, number_format($SubtotalQty))
            ;
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $j++;
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':E' . $j);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, 'Grand Total');
            $styleThinBlackBorderOutline2 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '50ABED'),
                )
            );
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline2);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('F' . $j, number_format($GrandtotalQty))
            ;
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }



        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));



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
    $file = 'Shipment_Report_Data_List_' . $exportTime . '.xlsx';
    $objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
    header('Location:media/' . $file);
} else {
    echo 'No record found';
}
?>