<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];

$ItemGroupId = $_GET['ItemGroupId'];
$ItemGroup = $_GET['ItemGroup'];
$tempGroupId='';
if ($ItemGroupId) {
    $ItemGroupId = " AND g.ItemGroupId = '" . $ItemGroupId . "' ";
}
$Year = $_GET['Year'];
$CountryId = $_GET['Country'];
$CountryName = $_REQUEST['CountryName'];
if (isset($_GET['Country']) && !empty($_GET['Country'])) {
    $countryQuery = " and p.CountryId='" . $CountryId . "' ";
} else {
    $countryQuery = "";
}

$lan = $_GET['lan'];
if ($lan == 'fr-FR') {
    $aColumns = 'g.GroupNameFrench GroupName, f.FundingReqSourceNameFrench FundingReqSourceName';
} else {
    $aColumns = 'g.GroupName, f.FundingReqSourceName';
}

if ($lan == 'en-GB') {
    $SITETITLE = SITETITLEENG;
} else {
    $SITETITLE = SITETITLEFRN;
}

$sql = "	SELECT SQL_CALC_FOUND_ROWS $aColumns,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.Y1) Total 
			from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r 
				on r.FundingReqSourceId=p.FundingReqSourceId and r.Year=p.Year and r.CountryId=p.CountryId  and r.ItemGroupId = p.ItemGroupId
			Inner Join t_fundingreqsources f on f.FundingReqSourceId=r.FundingReqSourceId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='" . $Year . "' " . $countryQuery . " " . $ItemGroupId . "
			group by g.GroupName,p.FundingReqSourceId ";

mysql_query("SET character_set_results=utf8");
mysql_query("SET character_set_results=utf8");
$result = mysql_query($sql);
$total = mysql_num_rows($result);
$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysql_query($sQuery);
$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];
$sEcho = isset($_GET['sEcho']) ? $_GET['sEcho'] : '';
$iDisplayStart = isset($_GET['iDisplayStart']) ? $_GET['iDisplayStart'] : '';

$sOutput = '{';
$sOutput .= '"sEcho": ' . intval($sEcho) . ', ';
$sOutput .= '"iTotalRecords": ' . $iFilteredTotal . ', ';
$sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
$sOutput .= '"aaData": [ ';
$serial = $iDisplayStart + 1;

if ($total > 0) {

    require('../lib/PHPExcel.php');
    $objPHPExcel = new PHPExcel();



    $objPHPExcel->getActiveSheet()->SetCellValue('A1', $SITETITLE);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A1');
    $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');

    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Funding Status']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');

    $objPHPExcel->getActiveSheet()->SetCellValue('A3',  $CountryName . ' - ' .   $ItemGroup . ' - ' . $Year);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');

    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A6', 'SL#')
            ->SetCellValue('B6', $gTEXT['Category'])
            ->SetCellValue('C6', $gTEXT['Requirements (USD)'])
            ->SetCellValue('D6', $gTEXT['Committed (USD)'])
    ;
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');


    $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);



    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);


    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6' . ':A6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B6' . ':B6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C6' . ':C6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('D6' . ':D6')->applyFromArray($styleThinBlackBorderOutline);


    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);

    $f = 0;

    $superGrandSubTotal = 0;
    $superGrandSubTotalActual = 0;
    $groupsubTmp = -1;
    $p = 0;
    $q = 0;
    $grandSubTotal = 0;
    $grandSubTotalActual = 0;
    $grandGapSurplus = 0;
    $j = 7;
    $i = 1;
    while ($aRow = mysql_fetch_array($result)) {

        //$ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));

        if ($p != 0 && $groupsubTmp != $aRow['GroupName']) {
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'ff962b'),
                )
            );

            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $groupsubTmp . ' Total')
                    ->SetCellValue('C' . $j, number_format($grandSubTotal))
                    ->SetCellValue('D' . $j, number_format($grandSubTotalActual));
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $superGrandSubTotal+=$grandSubTotal;
            $superGrandSubTotalActual+=$grandSubTotalActual;

            $grandSubTotal = 0;
            $grandSubTotalActual = 0;
            $j++;
        }
        $groupsubTmp = $aRow['GroupName'];


        $grandSubTotal+=$aRow['Y1'];
        $grandSubTotalActual+=$aRow['Total'];

        if ($tempGroupId != $aRow['GroupName']) {
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DAEF62'),
                )
            );
            $tempGroupId = $aRow['GroupName'];

            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':D' . $j);

            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $aRow['GroupName']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline1);
            $Planned = 0;
            $j++;
        }
        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $i)
                ->SetCellValue('B' . $j, $aRow['FundingReqSourceName'])
                ->SetCellValue('C' . $j, number_format($aRow['Y1']))
                ->SetCellValue('D' . $j, number_format($aRow['Total']))
        ;
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);



        if ($p == $total - 1) {
            $j++;
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $styleThinBlackBorderOutline0 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'ff962b'),
                )
            );
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':B' . $j);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $groupsubTmp . ' Total');
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('C' . $j, number_format($grandSubTotal));
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('D' . $j, number_format($grandSubTotalActual));
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline0);

            $superGrandSubTotal+=$grandSubTotal;
            $superGrandSubTotalActual+=$grandSubTotalActual;
            $j++;

            $grandSubTotal = 0;
            $grandSubTotalActual = 0;
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '52a8ee'),
                )
            );


            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':B' . $j);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, 'Grand Total');
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('C' . $j, number_format($superGrandSubTotal));
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('D' . $j, number_format($superGrandSubTotalActual));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline1);
        }
        $j++;
        $i++;
        $p++;
        $q++;
    }

    if (function_exists('date_default_timezone_set')) {
        date_default_timezone_set('UTC');
    } else {
        putenv("TZ=UTC");
    }
    $exportTime = date("Y-m-d_His", time());
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $file = 'Funding_Status_' . $exportTime . '.xlsx';
    $objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
    header('Location:media/' . $file);
} else {
    $error = "No records found.";
    echo $error;
}
?>