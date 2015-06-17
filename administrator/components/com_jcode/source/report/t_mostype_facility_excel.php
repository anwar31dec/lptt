<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];

$CountryId = $_GET['CountryId'];
$FacilityLevel = $_GET['FacilityLevel'];
$CountryName = $_GET['CountryName'];
$FacilityLevelName = $_GET['FacilityLevelName'];
$MosTypeId = $_GET['MostypeFacilityId'];

$lan = $_REQUEST['lan'];
if ($lan == 'en-GB') {
    $SITETITLE = SITETITLEENG;
} else {
    $SITETITLE = SITETITLEFRN;
}

if (!$MosTypeId) {
    $MosTypeId = '"' . '"';
}

if ($FacilityLevel) {
    $FacilityLevel = " AND a.FLevelId = " . $FacilityLevel . " ";
}


$sOrder = "order by MosTypeId ";
$sql = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, a.ColorCode, IconMos, IconMos_Width, IconMos_Height,MosLabel,a.CountryId,a.FLevelId
				FROM t_mostype_facility a
				INNER JOIN t_country b ON a.CountryId = b.CountryId
				INNER JOIN t_facility_level c ON a.FLevelId = c.FLevelId
				AND (a.CountryId = " . $CountryId . " OR " . $CountryId . " = 0) " . $FacilityLevel . " $sOrder ";


$sqlMostypeDetils = "SELECT  MostypeDetailsId, MosTypeId, MosTypeName, MinMos, MaxMos,a.ColorCode, IconMos, IconMos_Width, IconMos_Height,'' MosLabel,a.CountryId,a.FLevelId
				FROM t_mostype_facility_details a
				INNER JOIN t_country b ON a.CountryId = b.CountryId
				INNER JOIN t_facility_level c ON a.FLevelId = c.FLevelId
	 			where a.CountryId = '" . $CountryId . "' AND MosTypeId = '" . $MosTypeId . "' $FacilityLevel $sOrder ";


//echo $sqlMostypeDetils;
mysql_query("SET character_set_results=utf8");

$r = mysql_query($sql);
$total = mysql_num_rows($r);

$QueryMostypeDetials = mysql_query($sqlMostypeDetils);
$totalMostypeDetails = mysql_num_rows($QueryMostypeDetials);

if ($total > 0) {
    require('../lib/PHPExcel.php');
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getActiveSheet()->SetCellValue('A1', $SITETITLE);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A1');


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['MOS Type Facility List']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');

    $objPHPExcel->getActiveSheet()->SetCellValue('A3', ('Country Name : ' . $CountryName) . ' , ' . ('Facility Level : ' . $FacilityLevelName));
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');

    $objPHPExcel->getActiveSheet()->SetCellValue('A4', $gTEXT['MOS Type Facility Master List']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
    $objPHPExcel->getActiveSheet()->mergeCells('A4:I4');


    $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');

    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A6', 'SL.')
            ->SetCellValue('B6', $gTEXT['MOS Type Name'])
            ->SetCellValue('C6', $gTEXT['Maximum MOS'])
            ->SetCellValue('D6', $gTEXT['Minimum MOS'])
            ->SetCellValue('E6', $gTEXT['Color Code'])
            ->SetCellValue('F6', $gTEXT['Icon Mos'])
            ->SetCellValue('G6', $gTEXT['Icon Mos Width'])
            ->SetCellValue('H6', $gTEXT['Icon Mos Height'])
            ->SetCellValue('I6', $gTEXT['MosLabel']);

    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I6');
    $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);



    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6' . ':A6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B6' . ':B6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C6' . ':C6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('D6' . ':D6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('E6' . ':E6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('F6' . ':F6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('G6' . ':G6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('H6' . ':H6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('I6' . ':I6')->applyFromArray($styleThinBlackBorderOutline);


    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);

    $i = 1;
    $j = 7;
    while ($rec = mysql_fetch_array($r)) {
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j . ':H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j . ':G' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $i)
                ->SetCellValue('B' . $j, $rec['MosTypeName'])
                ->SetCellValue('C' . $j, $rec['MinMos'])
                ->SetCellValue('D' . $j, $rec['MaxMos']);

        $c = explode('#', $rec['ColorCode']);
        //$bgcolor="";
        if ($MosTypeId == $rec['MosTypeId']) {
            $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DAEF62')));
        } else {
            $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)));
        }


        $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $c[1]),
            )
        );
        $objPHPExcel->getActiveSheet()
                ->SetCellValue('F' . $j, $rec['IconMos'])
                ->SetCellValue('G' . $j, $rec['IconMos_Width'])
                ->SetCellValue('H' . $j, $rec['IconMos_Height'])
                ->SetCellValue('I' . $j, $rec['MosLabel']);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j . ':I' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        //$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline1);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j . ':G' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j . ':H' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j . ':I' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $i++;
        $j++;
    }

    /// from mostype details.....

    $objPHPExcel->getActiveSheet()->SetCellValue('A13', $gTEXT['MOS Type Facility Item List']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A13')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A13');
    $objPHPExcel->getActiveSheet()->mergeCells('A13:I13');



    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A15', 'SL.')
            ->SetCellValue('B15', $gTEXT['MOS Type Name'])
            ->SetCellValue('C15', $gTEXT['Maximum MOS'])
            ->SetCellValue('D15', $gTEXT['Minimum MOS'])
            ->SetCellValue('E15', $gTEXT['Color Code'])
            ->SetCellValue('F15', $gTEXT['Icon Mos'])
            ->SetCellValue('G15', $gTEXT['Icon Mos Width'])
            ->SetCellValue('H15', $gTEXT['Icon Mos Height']);


    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A15');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B15');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C15');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D15');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E15');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F15');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G15');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H15');
    $objPHPExcel->getActiveSheet()->getStyle('A15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A16')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A15' . ':A15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B15' . ':B15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C15' . ':C15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('D15' . ':D15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('E15' . ':E15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('F15' . ':F15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('G15' . ':G15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('H15' . ':H15')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('A15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('F15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('G15')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('H15')->getFont()->setBold(true);

    if ($totalMostypeDetails > 0) {
        $k = 1;
        $m = $total + 11;
        while ($recDetails = mysql_fetch_array($QueryMostypeDetials)) {
            $objPHPExcel->getActiveSheet()->getStyle('A' . $m . ':A' . $m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $m . ':D' . $m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $m . ':C' . $m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $m . ':E' . $m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $m . ':H' . $m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $m . ':G' . $m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $m, $k)
                    ->SetCellValue('B' . $m, $recDetails['MosTypeName'])
                    ->SetCellValue('C' . $m, $recDetails['MinMos'])
                    ->SetCellValue('D' . $m, $recDetails['MaxMos']);

            $c = explode('#', $recDetails['ColorCode']);
            $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)));
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => $c[1]),
                )
            );
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('F' . $m, $recDetails['IconMos'])
                    ->SetCellValue('G' . $m, $recDetails['IconMos_Width'])
                    ->SetCellValue('H' . $m, $recDetails['IconMos_Height']);
            $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
            $objPHPExcel->getActiveSheet()->getStyle('A' . $m . ':A' . $m)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $m . ':B' . $m)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $m . ':C' . $m)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $m . ':D' . $m)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $m . ':E' . $m)->applyFromArray($styleThinBlackBorderOutline1);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $m . ':F' . $m)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $m . ':G' . $m)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $m . ':H' . $m)->applyFromArray($styleThinBlackBorderOutline);
            $k++;
            $m++;
        }
    } else {
        //echo 'No record found';
        $m = $total + 11;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $m, 'No record found');
        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
        $objPHPExcel->getActiveSheet()->getStyle('A' . $m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $m)->getFont();
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A' . $m);
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $m . ':H' . $m);
    }
    if (function_exists('date_default_timezone_set')) {
        date_default_timezone_set('UTC');
    } else {
        putenv("TZ=UTC");
    }
    $exportTime = date("Y-m-d_His", time());
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $file = 'MOS_Type_For_Facility_' . $exportTime . '.xlsx';
    $objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
    header('Location:media/' . $file);
} else {
    echo 'No record found';
}
?>