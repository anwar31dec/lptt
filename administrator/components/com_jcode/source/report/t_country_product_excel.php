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
$username = isset($_GET['userName'])? $_GET['userName'] : '';

$CountryId = $_GET['SelCountryId'];
$CountryName = $_GET['SelCountryName'];
$ShowSelected = $_GET['ShowSelected'];


$sWhere = "";
$sSearch = "";
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
                 " . $sWhere . " ORDER BY GroupName, ItemName, ItemCode ";
} else {
    $sql = " SELECT a.CountryProductId, a.CountryId, b.ItemNo, IF(a.CountryProductId is Null,'false','true') chkValue, ItemCode, b.ItemGroupId, 
                 ItemName, GroupName  	 	
                 FROM  t_country_product a 
                 INNER JOIN t_itemlist b ON (a.ItemNo = b.ItemNo AND a.CountryId = '" . $CountryId . "')
                 INNER JOIN t_itemgroup c ON b.ItemGroupId = c.ItemGroupId
                 " . $sWhere . " ORDER BY GroupName, ItemName, ItemCode";
}
mysql_query("SET character_set_results=utf8");
$r = mysql_query($sql);
$total = mysql_num_rows($r);
if ($total > 0) {
    require('../lib/PHPExcel.php');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $SITETITLE);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');

    $objPHPExcel->getActiveSheet()->SetCellValue('A3', ($gTEXT['Product List of']) . '  ' . ($CountryName));
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A3');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');


    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A6', 'SL#')
            ->SetCellValue('B6', $gTEXT['Product Code'])
            ->SetCellValue('C6', $gTEXT['Product Name']);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');

    $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);

    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('C7')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6' . ':A6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B6' . ':B6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C6' . ':C6')->applyFromArray($styleThinBlackBorderOutline);

    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
    $i = 1;
    $j = 7;

    $tempGroupId = '';
    while ($sql = mysql_fetch_array($r)) {
        if ($tempGroupId != $sql['GroupName']) {
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DAEF62'),));
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':C' . $j);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $sql['GroupName']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline1);
            $tempGroupId = $sql['GroupName'];
            $j++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));


        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $i)
                ->SetCellValue('B' . $j, $sql['ItemCode'])
                ->SetCellValue('C' . $j, $sql['ItemName']);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);

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
    $file = 'country_product_' . $exportTime . '.xlsx';
    $objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
    header('Location:media/' . $file);
} else {
    echo 'No record found';
}
?>