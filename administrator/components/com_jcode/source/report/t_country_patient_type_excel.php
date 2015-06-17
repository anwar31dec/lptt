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

$CountryName = $_GET['SelCountryName'];
$CountryId = $_GET['SelCountryId'];
$ShowSelected = $_GET['ShowSelected'];
$ItemGroupId = $_GET['ItemGroupId'];
$tempGroupId='';

$sWhere = "";
if ($_GET['sSearch'] != "") {

    $sSearch = str_replace("|", "+", $_GET['sSearch']);

    $sWhere = "WHERE (RegimenName LIKE '%" . mysql_real_escape_string($sSearch) . "%') ";
}

if ($ShowSelected == 'false') {
    $sql = " SELECT  a.CountryRegimenId, a.CountryId, b.RegimenId, IF(a.CountryRegimenId is Null,'false','true') chkValue, RegimenName, FormulationName  	 	
                 FROM  t_country_regimen a 
                 RIGHT JOIN t_regimen b ON (a.RegimenId = b.RegimenId AND a.CountryId = '" . $CountryId . "')
                 INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId AND c.ItemGroupId = '" . $ItemGroupId . "' 
                 " . $sWhere . " ORDER BY FormulationName, RegimenName";
} else {
    $sql = " SELECT  a.CountryRegimenId, a.CountryId, b.RegimenId, IF(a.CountryRegimenId is Null,'false','true') chkValue, RegimenName, FormulationName
                 FROM  t_country_regimen a 
                 INNER JOIN t_regimen b ON (a.RegimenId = b.RegimenId AND a.CountryId = '" . $CountryId . "')
                 INNER JOIN t_formulation c ON b.FormulationId = c.FormulationId AND c.ItemGroupId = '" . $ItemGroupId . "' 
                 " . $sWhere . " ORDER BY FormulationName, RegimenName ";
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
    $objPHPExcel->getActiveSheet()->mergeCells('A2:B2');

    $objPHPExcel->getActiveSheet()->SetCellValue('A3', $gTEXT['Country Patient of'] . ' ' . $CountryName);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A3');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:B3');

    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->SetCellValue('A6', 'SL#');
    $objPHPExcel->getActiveSheet()->SetCellValue('B6', $gTEXT['Regimen Name']);

    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');

    $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);


    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6' . ':A6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B6' . ':B6')->applyFromArray($styleThinBlackBorderOutline);

    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);

    $i = 1;
    $j = 7;

    while ($rec = mysql_fetch_array($r)) {

        if ($tempGroupId != $rec['FormulationName']) {
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DAEF62'),
                )
            );

            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':B' . $j);
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $rec['FormulationName']);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline1);

            $tempGroupId = $rec['FormulationName'];
            $j++;
        }
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $i);
        /* $objDrawing = new PHPExcel_Worksheet_Drawing();
          if($rec['chkValue']== 'false')
          $objDrawing -> setPath('image/unchecked.png');
          else
          $objDrawing -> setPath('image/checked.png');

          $objDrawing -> setCoordinates('B' . $j);
          $objDrawing -> setWorksheet($objPHPExcel -> getActiveSheet()); */

        $objPHPExcel->getActiveSheet()
                ->SetCellValue('B' . $j, $rec['RegimenName']);

        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);

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
    $file = 'Country_Regimen_' . $exportTime . '.xlsx';
    $objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
    header('Location:media/' . $file);
} else {
    echo 'No record found';
}

/* function getcheckBox($v){ 
  if ($v == "true") {
  $x="<input type='checkbox' checked class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
  } else {
  $x="<input type='checkbox' class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
  }
  return $x;
  } */
?>