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

function getNameFromNumber($num) {
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}

$ItemGroupId = $_GET['ItemGroupId'];
$CountryId = $_GET['CountryId'];
$CountryName = $_GET['CountryName'];
$Year = $_GET['Year'];
$RequirementYear = $_GET['RequirementYear'];
//if(!empty($CountryId) && !empty($Year))
$sql = "SELECT SQL_CALC_FOUND_ROWS a.YCProfileId, a.YCValue, Year, a.CountryId, a.ParamId, ParamName, ParamNameFrench
				FROM t_ycprofile a
                INNER JOIN t_country b ON a.CountryId = b.CountryId
                INNER JOIN t_cprofileparams c ON a.ParamId = c.ParamId
                WHERE a.CountryId = '" . $CountryId . "'
                AND a.Year = '" . $Year . "' AND c.ItemGroupId = '" . $ItemGroupId . "'
				order by c.ShortBy;";
//echo $sql;
mysql_query("SET character_set_results=utf8");
$r = mysql_query($sql);
$total = mysql_num_rows($r);
if ($total > 0) {

//**************************************************************Basic Information**************************    	
    require('../lib/PHPExcel.php');
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $SITETITLE);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');

    $objPHPExcel->getActiveSheet()->SetCellValue('A3', $gTEXT['Country Profile'] . ' of ' . ($CountryName) . ' ' . ($Year));
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A3');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');

    $objPHPExcel->getActiveSheet()->SetCellValue('A5', $gTEXT['Basic Information']);
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A5');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');

    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A6', 'SL#')
            ->SetCellValue('B6', $gTEXT['Parameter Name'])
            ->SetCellValue('C6', $gTEXT['Value']);

    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');

    $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6' . ':A6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B6' . ':B6')->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C6' . ':C6')->applyFromArray($styleThinBlackBorderOutline);

    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);

    $i = 1;
    $j = 7;
    while ($rec = mysql_fetch_array($r)) {

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        if ($rec['YCValue'] == '') {
            $rec['YCValue'] == '';
        } else {
            if (is_numeric($rec['YCValue'])) {
                $rec['YCValue'] = number_format($rec['YCValue']);
            } else {
                $rec['YCValue'] = $rec['YCValue'];
            }
        }

        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $i)
                ->SetCellValue('B' . $j, $rec['ParamName'])
                ->SetCellValue('C' . $j, $rec['YCValue']);

        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $i++;
        $j++;
    }

//****************************************Funding Source*********************************//	
    $j = $j + 2;

    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $gTEXT['Funding Source']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getDefaultStyle('A' . $j)->getAlignment()->setWrapText(true);

    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A' . $j);
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':C' . $j);

    $j = $j + 1;

    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, 'SL')
            ->SetCellValue('B' . $j, $gTEXT['Funding Source Name'])
            ->SetCellValue('C' . $j, '');

    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A' . $j);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B' . $j);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C' . $j);

    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);

    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getFont()->setBold(true);

    $sql = "SELECT t_fundingsource.ItemGroupId, t_fundingsource.FundingSourceName
		,t_yearly_country_fundingsource.YearlyFundingSrcId,t_fundingsource.FundingSourceId
		,IF(t_yearly_country_fundingsource.YearlyFundingSrcId is Null,'false','true') chkValue
		FROM t_fundingsource
		LEFT JOIN t_yearly_country_fundingsource ON (t_yearly_country_fundingsource.FundingSourceId = t_fundingsource.FundingSourceId
				AND t_yearly_country_fundingsource.CountryId = $CountryId
				AND t_yearly_country_fundingsource.Year = $Year
				AND t_fundingsource.ItemGroupId = $ItemGroupId)
		WHERE t_fundingsource.ItemGroupId = $ItemGroupId;";
    $pacrs = mysql_query($sql, $conn);

    $K = 1;
    $j = $j + 1;
    //$tempGroupId = '';
    while ($aRow = mysql_fetch_array($pacrs)) {
        //$FundingSourceId = $aRow->FundingSourceId;
        //$FundingSourceName = $aRow->FundingSourceName;
        //$chkValue = $aRow->chkValue;
        //$YearlyFundingSrcId = $aRow->YearlyFundingSrcId;
        
 


        $objDrawing = new PHPExcel_Worksheet_Drawing();
        if ($aRow['chkValue'] == 'false') {
            $objDrawing->setPath('image/unchecked.png');
        } else {
            $objDrawing->setPath('image/checked.png');
        }
        $objDrawing->setCoordinates('C' . $j);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $K)
                ->SetCellValue('B' . $j, $aRow['FundingSourceName']);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $K++;
        $j++;
    }

    //****************************************Cases*********************************//	

    $j = $j + 2;
    $ItemGroupId = $_GET['ItemGroupId'];
    if ($ItemGroupId == 1) {
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $gTEXT['Cases']);
        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getDefaultStyle('A' . $j)->getAlignment()->setWrapText(true);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A' . $j);
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':C' . $j);

        $j = $j + 1;

        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, 'SL')
                ->SetCellValue('B' . $j, $gTEXT['Formulation'])
                ->SetCellValue('C' . $j, '(0-4 Years)')
                ->SetCellValue('D' . $j, '(5-14 Years)')
                ->SetCellValue('E' . $j, '(15+ Years)')
                ->SetCellValue('F' . $j, 'Pregnant women');

        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A' . $j);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B' . $j);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C' . $j);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D' . $j);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E' . $j);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F' . $j);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);

        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getFont()->setBold(true);
    }
    $cellIdentifire = array("1" => "A", "2" => "B", "3" => "C", "4" => "D", "5" => "E", "6" => "F", "7" => "G", "8" => "H", "9" => "I", "10" => "J", "11" => "K", "12" => "L", "13" => "M", "14" => "N", "15" => "O", "16" => "P", "17" => "Q", "18" => "R", "19" => "S", "20" => "T", "21" => "U", "22" => "V", "23" => "W", "24" => "X", "25" => "Y", "26" => "Z");

    if ($lan == 'en-GB') {
        $FormulationName = 'FormulationName';
    } else {
        $FormulationName = 'FormulationNameFrench';
    }

    $columnsName = "";
    $sql = "SELECT RegMasterId,RegimenName FROM `t_regimen_master`
					where ItemGroupId=" . $ItemGroupId . " Order By RegMasterId ASC;";


    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    if ($total > 0) {
        while ($row = mysql_fetch_object($result)) {
            $columnsName.=',{ "sTitle": "' . $row->RegimenName . '","sWidth":"12%"}';
        }
    }


    $sql = "SELECT SQL_CALC_FOUND_ROWS YearlyRegPatientId, t_regimen_master.RegimenName, PatientCount,
					t_yearly_country_regimen_patient.FormulationId,$FormulationName	FormulationName
					FROM t_yearly_country_regimen_patient 
					INNER JOIN t_regimen_master ON t_yearly_country_regimen_patient.RegMasterId = t_regimen_master.RegMasterId 
					INNER JOIN t_formulation ON t_yearly_country_regimen_patient.FormulationId = t_formulation.FormulationId 
					WHERE t_yearly_country_regimen_patient.CountryId = '" . $CountryId . "' AND t_yearly_country_regimen_patient.Year = '" . $Year . "'	 
					AND t_regimen_master.ItemGroupId = " . $ItemGroupId . "	 				
					Order BY t_formulation.FormulationId ASC, t_yearly_country_regimen_patient.RegMasterId ASC;";
    //	echo $sql;		
    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    //$f = 0;
    $tmpFormulationId = -1;
    $serial = 0;

    $K = 1;
    $tempGroupId = '';
    while ($aRow = mysql_fetch_array($result)) {
        if ($tmpFormulationId != $aRow['FormulationId']) {
            $i = 3;
            $j++;
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, ++$serial)
                    ->SetCellValue('B' . $j, $aRow['FormulationName'])
                    ->SetCellValue($cellIdentifire[$i] . $j, number_format($aRow['PatientCount']));

            $tmpFormulationId = $aRow['FormulationId'];
        } else {
            $i++;
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue($cellIdentifire[$i] . $j, number_format($aRow['PatientCount']));
            $tmpFormulationId = $aRow['FormulationId'];
        }
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);
    }

//************************************************Funding Requirements*********************************

    $j = $j + 2;
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $gTEXT['Funding Requirements']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A' . $j);
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':F' . $j);

    $j = $j + 1;

    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A' . $j, 'SL')
            ->SetCellValue('B' . $j, $gTEXT['Formulation'])
            ->SetCellValue('C' . $j, $Year)
            ->SetCellValue('D' . $j, $Year+1)
            ->SetCellValue('E' . $j, $Year+2)
            ->SetCellValue('F' . $j, $gTEXT['Total']);

    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A' . $j);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B' . $j);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C' . $j);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D' . $j);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E' . $j);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F' . $j);


    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);


    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);


    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getFont()->setBold(true);

    if ($lan == 'en-GB') {
        $ServiceTypeName = 'ServiceTypeName';
        $FundingReqSourceName = 'FundingReqSourceName';
    } else {
        $ServiceTypeName = 'ServiceTypeNameFrench';
        $FundingReqSourceName = 'FundingReqSourceNameFrench';
    }
    $sql = "SELECT SQL_CALC_FOUND_ROWS a.FundingReqId, a.TotalRequirements, a.Year, Y1, Y2, Y3,
			d.$ServiceTypeName ServiceTypeName, a.CountryId, a.FormulationId, c.$FundingReqSourceName FundingReqSourceName
			FROM t_yearly_funding_requirements a
			INNER JOIN  t_fundingreqsources c ON c.FundingReqSourceId = a.FundingReqSourceId 
			INNER JOIN t_servicetype d ON d.ServiceTypeId = c.ServiceTypeId
			INNER JOIN t_itemgroup b ON c.ItemGroupId = b.ItemGroupId                
			WHERE a.CountryId = '" . $CountryId . "'
			AND a.Year = '" . $Year . "' AND a.ItemGroupId = '" . $ItemGroupId . "'
			ORDER BY a.FundingReqSourceId ASC;";

    $rResult = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);

    $serial = 0;
    $f = 0;

    $k = 1;
    $j = $j + 1;
    $tempGroupId = '';
    while ($aRow = mysql_fetch_array($rResult)) {


        if ($tempGroupId != $aRow['ServiceTypeName']) {

            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'DAEF62'),
                )
            );

            $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':F' . $j);

            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $aRow['ServiceTypeName'])

            ;
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline1);

            $tempGroupId = $aRow['ServiceTypeName'];
            $j++;
        }

        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $k)
                ->SetCellValue('B' . $j, $aRow['FundingReqSourceName'])
                ->SetCellValue('C' . $j, ($aRow['Y1'] == '' ? '' : number_format($aRow['Y1'])))
                ->SetCellValue('D' . $j, ($aRow['Y2'] == '' ? '' : number_format($aRow['Y2'])))
                ->SetCellValue('E' . $j, ($aRow['Y3'] == '' ? '' : number_format($aRow['Y3'])))
                ->SetCellValue('F' . $j, ($aRow['TotalRequirements'] == '' ? '' : number_format($aRow['TotalRequirements'])));

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j . ':E' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->applyFromArray($styleThinBlackBorderOutline);

        $k++;
        $j++;
    }

//********************************************Pledged Funding*****************************************************  
    $j = $j + 2;
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $j, $gTEXT['Pledged Funding']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A' . $j);
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $j . ':G' . $j);

    $j = $j + 1;


    /* ->SetCellValue('A'.$j, $gTEXT['Service Type'])							
      ->SetCellValue('B'.$j, $gTEXT['Category'])
      ->SetCellValue('C'.$j, 'Total Requirements')
      ->SetCellValue('D'.$j, 'Government')
      ->SetCellValue('E'.$j, 'GFATM')
      ->SetCellValue('F'.$j, $gTEXT['Total'])
      ->SetCellValue('G'.$j, $gTEXT['Gap/Surplus']); */

    if ($lan == 'en-GB') {
        $ServiceTypeName = 'ServiceTypeName';
        $FundingReqSourceName = 'FundingReqSourceName';
    } else {
        $ServiceTypeName = 'ServiceTypeNameFrench';
        $FundingReqSourceName = 'FundingReqSourceNameFrench';
    }

    $columnsName = "";

    $sql = "SELECT SQL_CALC_FOUND_ROWS t_yearly_country_fundingsource.FundingSourceId,FundingSourceName FROM t_yearly_country_fundingsource
		INNER JOIN t_fundingsource ON t_yearly_country_fundingsource.FundingSourceId=t_fundingsource.FundingSourceId
				where Year ='" . $Year . "' AND CountryId ='" . $CountryId . "' AND t_fundingsource.ItemGroupId = '" . $ItemGroupId . "'
				Order By FundingSourceId;";
    //echo $sql;	

    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);

    /* $objPHPExcel->getActiveSheet()										
      ->SetCellValue('A'.$j, $gTEXT['Service Type'])
      ->SetCellValue('B'.$j, $gTEXT['Category'])
      ->SetCellValue('C'.$j, 'Total Requirements')
      ->SetCellValue('D'.$j, 'Government')
      ->SetCellValue('E'.$j, 'GFATM')
      ->SetCellValue('F'.$j, $gTEXT['Total'])
      ->SetCellValue('G'.$j, $gTEXT['Gap/Surplus']); */
    if ($total > 0) {
        if ($lan == 'en-GB') {
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $gTEXT['Service Type'])
                    ->SetCellValue('B' . $j, $gTEXT['Category'])
                    ->SetCellValue('C' . $j, $gTEXT['Total Requirements']);

            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A' . $j);
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B' . $j);
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C' . $j);

            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getFont()->setBold(true);
        } else {
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue('A' . $j, $gTEXT['Type de service'])
                    ->SetCellValue('B' . $j, $gTEXT['catégorie'])
                    ->SetCellValue('C' . $j, $gTEXT['total des besoins']);

            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A' . $j);
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B' . $j);
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C' . $j);

            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getFont()->setBold(true);
        }
        $i = 4;
        while ($row = mysql_fetch_object($result)) {
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue($cellIdentifire[$i] . $j, $row->FundingSourceName);

            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i] . $j);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellIdentifire[$i])->setWidth(40);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j)->getFont()->setBold(true);
            $i++;
        }

        if ($lan == 'en-GB') {
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue($cellIdentifire[$i] . $j, 'Total')
                    ->SetCellValue($cellIdentifire[$i + 1] . $j, 'Gap/Surplus');

            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i] . $j);
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i + 1] . $j);

            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $objPHPExcel->getActiveSheet()->getColumnDimension($cellIdentifire[$i])->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellIdentifire[$i + 1])->setWidth(20);

            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j . ':' . $cellIdentifire[$i + 1] . $j)->applyFromArray($styleThinBlackBorderOutline);

            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j)->getFont()->setBold(true);
        } else {

            $objPHPExcel->getActiveSheet()
                    ->SetCellValue($cellIdentifire[$i] . $j, 'Total')
                    ->SetCellValue($cellIdentifire[$i + 1] . $j, 'Gap/Surplus');

            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i] . $j);
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i + 1] . $j);

            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $objPHPExcel->getActiveSheet()->getColumnDimension($cellIdentifire[$i])->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellIdentifire[$i + 1])->setWidth(20);

            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j . ':' . $cellIdentifire[$i + 1] . $j)->applyFromArray($styleThinBlackBorderOutline);

            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j)->getFont()->setBold(true);
        }
    }

    $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));

    $objPHPExcel->getActiveSheet()->getDefaultStyle('A' . $j)->getAlignment()->setWrapText(true);


    $sql = "SELECT FundingReqSourceId FROM t_fundingreqsources  WHERE ItemGroupId = '" . $ItemGroupId . "';";
//echo $sql;

    $result = mysql_query($sql, $conn);
    while ($row = mysql_fetch_object($result)) {
        $FundingReqSourceId = $row->FundingReqSourceId;


        $sqldel = "SELECT PledgedFundingId FROM t_yearly_pledged_funding
			 WHERE YEAR = '" . $Year . "' AND CountryId = " . $CountryId . " AND ItemGroupId = '" . $ItemGroupId . "'
			 AND FundingReqSourceId = " . $FundingReqSourceId . "			 
			 AND FundingSourceId NOT IN (			 
				SELECT FundingSourceId
				FROM t_yearly_country_fundingsource a
				WHERE a.Year = '" . $Year . "' AND a.CountryId = " . $CountryId . " AND a.ItemGroupId = '" . $ItemGroupId . "');";
        //echo $sqldel;
        $delResult = mysql_query($sqldel, $conn);
        while ($r = mysql_fetch_object($delResult)) {
            $sqldel1 = "DELETE FROM t_yearly_pledged_funding WHERE PledgedFundingId = " . $r->PledgedFundingId . ";";
            mysql_query($sqldel1, $conn);
        }

        $sql = "INSERT INTO t_yearly_pledged_funding 
						(`PledgedFundingId` ,`CountryId` ,`Year` ,`ItemGroupId` ,`FundingReqSourceId` ,`FundingSourceId` ,`TotalFund`)
						 SELECT NULL, a.CountryId, a.Year, a.ItemGroupId, '" . $FundingReqSourceId . "' ,a.FundingSourceId,0
							FROM t_yearly_country_fundingsource a
							WHERE a.Year = '" . $Year . "' AND a.CountryId = " . $CountryId . " AND a.ItemGroupId = '" . $ItemGroupId . "'
							AND FundingSourceId NOT IN (
							SELECT DISTINCT FundingSourceId FROM t_yearly_pledged_funding
							WHERE YEAR = '" . $Year . "' AND CountryId = " . $CountryId . " 
							AND ItemGroupId = '" . $ItemGroupId . "' AND FundingReqSourceId = " . $FundingReqSourceId . ");";
        mysql_query($sql, $conn);
    }

    $YValue = 'a.Y' . $RequirementYear;

    $sql = "SELECT d.ServiceTypeId, d.$ServiceTypeName ServiceTypeName, d.ServiceTypeNameFrench,
			b.FundingReqSourceId, b.$FundingReqSourceName FundingReqSourceName, b.FundingReqSourceNameFrench, IFNULL($YValue,0) YReq
			FROM t_yearly_funding_requirements a
			INNER JOIN t_fundingreqsources b ON a.FundingReqSourceId = b.FundingReqSourceId
			INNER JOIN t_servicetype d ON b.ServiceTypeId = d.ServiceTypeId
						
			WHERE a.CountryId = " . $CountryId . "
			AND  a.ItemGroupId = " . $ItemGroupId . "
			AND a.Year = '" . $Year . "'
			ORDER BY b.ServiceTypeId, b.FundingReqSourceId;";
    //echo $sql;

    $result = mysql_query($sql, $conn);
    $f = 0;

    $ColumnClass = 0;
    $tmpServiceTypeId = -1;
    $tmpFundingReqSourceId = -1;
    $sl = 0;

    $k = 1;
    $j = $j + 1;
    while ($aRow = mysql_fetch_array($result)) {

        $Total = 0;
        $YReq = $aRow['YReq'];
        $FundingReqSourceId = $aRow['FundingReqSourceId'];

        $objPHPExcel->getActiveSheet()
                ->SetCellValue('A' . $j, $aRow['ServiceTypeName'])
                ->SetCellValue('B' . $j, $aRow['FundingReqSourceName'])
                ->SetCellValue('C' . $j, $YReq);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j . ':B' . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j . ':C' . $j)->applyFromArray($styleThinBlackBorderOutline);

        $sql1 = "SELECT a.PledgedFundingId, b.FundingSourceId, b.FundingSourceName, IFNULL($YValue,0) YCurr	
					FROM t_yearly_pledged_funding a
					INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId								
					WHERE a.CountryId = " . $CountryId . "
					AND  a.ItemGroupId = " . $ItemGroupId . "
					AND a.Year = '" . $Year . "'
					AND a.FundingReqSourceId = " . $aRow['FundingReqSourceId'] . "
					ORDER BY b.FundingSourceId;";

        $sResult = mysql_query($sql1, $conn);
        $i = 4;
        while ($r = mysql_fetch_array($sResult)) {
            $objPHPExcel->getActiveSheet()
                    ->SetCellValue($cellIdentifire[$i] . $j, number_format($r['YCurr']));
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->applyFromArray($styleThinBlackBorderOutline);
            $i++;
            $Total+= $r['YCurr'];
        }
        $objPHPExcel->getActiveSheet()
                ->SetCellValue($cellIdentifire[$i] . $j, number_format($Total, 1))
                ->SetCellValue($cellIdentifire[$i + 1] . $j, number_format(($YReq - $Total), 1));

        $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j . ':' . $cellIdentifire[$i + 1] . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i] . $j . ':' . $cellIdentifire[$i] . $j)->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i + 1] . $j . ':' . $cellIdentifire[$i + 1] . $j)->applyFromArray($styleThinBlackBorderOutline);


        //$objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
        //$objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	

        $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));


        /* $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
         */
        $k++;
        $j++;
    }

    if (function_exists('date_default_timezone_set')) {
        date_default_timezone_set('UTC');
    } else {
        putenv("TZ=UTC");
    }
    $exportTime = date("Y-m-d_His", time());
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $file = 'Country_Profile_' . $exportTime . '.xlsx';
    $objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
    header('Location:media/' . $file);
} else {
    echo 'No record found';
}
?>