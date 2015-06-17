<?php

include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];
$lan = $_GET['lan'];
if ($lan == 'en-GB') {
    $SITETITLE = SITETITLEENG;
} else {
    $SITETITLE = SITETITLEFRN;
}
$monthId = $_GET['MonthId'];
$year = $_GET['Year'];
$country = $_GET['CountryId'];
//$itemGroupId = $_GET['ItemGroupId'];
$CountryName = $_GET['CountryName'];
$MonthName = $_GET['MonthName'];
//$ItemGroupName = $_GET['ItemGroupName'];
$regionId = $_GET['RegionId'];
$RegionName = $_GET['RegionName'];
$districtId = $_GET['DistrictId'];
$DistrictName = $_GET['DistrictName'];
$ownerTypeId = $_GET['OwnerTypeId'];
$OwnerTypeName = $_GET['OwnerTypeName'];
$sWhere = "";
$condition = "";
 if ($regionId) {
        //$sWhere=" WHERE "; 
        $condition.= " and  (x.RegionId = $regionId OR $regionId =0 ) ";
    }

    if ($districtId) {
        $condition.= " and (x.DistrictId = $districtId OR $districtId = 0) ";
    }
    if ($ownerTypeId) {
        $condition.= " and  (x.OwnerTypeId = $ownerTypeId OR $ownerTypeId = 0) ";
    }

    /* Array of database columns which should be read and sent back to DataTables. Use a space where
     * you want to insert a non-database field (for example a counter or static image)
     */
    $aColumns = array('SL', 'FacilityId', 'FacilityCode', 'FacilityName', 'bEntered', 'CreatedDt', 'bSubmitted',
        'LastSubmittedDt', 'bPublished', 'PublishedDt','FLevelName', 'FLevelId');

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "FacilityId";

    /* DB table to use */
    $sTable = "t_cfm_masterstockstatus";
    /*
     * Paging
     */
    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
    }

   
    /* Individual column filtering */
    for ($i = 0; $i < count($aColumns); $i++) {

        if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {

            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " OR ";
            }
            $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
        }
    }

    /*
     * SQL queries
     * Get data to display
     */


    mysql_query("SET @rank=0;");

    $serial = "@rank:=@rank+1 AS SL";

  
    $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . $serial . ", b.FacilityId, b.FacilityCode, b.FacilityName,
				IFNULL( a.FacilityId,0) bEntered,				
				DATE_FORMAT(a.CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,	
				IF(c.StatusId = '2', '1', '0') bSubmitted,
				DATE_FORMAT(a.LastSubmittedDt, '%d-%b-%Y %h:%i %p')  LastSubmittedDt,
				IF(c.StatusId = '5', '1', '0') bPublished,
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt,FLevelName,b.FLevelId
				FROM  t_cfm_masterstockstatus a 
				RIGHT JOIN (SELECT x.FacilityId, x.FacilityCode, x.FacilityName ,FLevelName,t_facility_level.FLevelId
				FROM t_facility x 
				INNER JOIN t_facility_level ON x.FLevelId = t_facility_level.FLevelId
				WHERE x.CountryId = $country  $condition) b
				ON a.FacilityId = b.FacilityId AND  MonthId = $monthId 
				AND Year = '$year' AND a.CountryId = $country 
				LEFT JOIN t_status c ON a.StatusId = c.StatusId 
				$sWhere
				order by b.FLevelId;";
				
mysql_query("SET character_set_results=utf8");
$r = mysql_query($sQuery);
$total = mysql_num_rows($r);

if ($total > 0) {
    require('../lib/PHPExcel.php');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', $SITETITLE);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A1');
    $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');

    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Facility Reporting Status']);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
    $objPHPExcel->getActiveSheet()->SetCellValue('A3', $CountryName . ' - ' .$RegionName.' - '.$DistrictName.' - '.$OwnerTypeName.' - '.$MonthName.', '.$year);
    $styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'))));
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');

    $objPHPExcel->getActiveSheet()
            ->SetCellValue('A6', 'SL')
            ->SetCellValue('B6', $gTEXT['Facility Code'])
            ->SetCellValue('C6', $gTEXT['Facility Name'])
            ->SetCellValue('D6', $gTEXT['Entered'])
            ->SetCellValue('E6', $gTEXT['Entry Date'])
            ->SetCellValue('F6', $gTEXT['Submitted'])
            ->SetCellValue('G6', $gTEXT['Submitted Date'])
            ->SetCellValue('H6', $gTEXT['Published'])
            ->SetCellValue('I6', $gTEXT['Published Date'])
    ;

    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H6');
    $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I6');
    //$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'J6');
    //$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'K6');


    $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('J6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
   // $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    //$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


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
    //$objPHPExcel->getActiveSheet()->getStyle('J6' . ':J6')->applyFromArray($styleThinBlackBorderOutline);
    //$objPHPExcel->getActiveSheet()->getStyle('K6' . ':K6')->applyFromArray($styleThinBlackBorderOutline);




    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);
    //$objPHPExcel->getActiveSheet()->getStyle('J6')->getFont()->setBold(true);
    //$objPHPExcel->getActiveSheet()->getStyle('K6')->getFont()->setBold(true);
	$styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
		'fill' => array(
		)
	);

    $h = 1;
    $nm = 7;
    $monthvar = '';
	$tmpLevelId='';
    $j = '';
    if ($r)
        while ($rec = mysql_fetch_array($r)) {
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j . ':A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j . ':D' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j . ':F' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j . ':H' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('J' . $j . ':J' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			if($rec[11] != $tmpLevelId){
				 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $nm, $rec[10]);
				 $objPHPExcel -> getActiveSheet() -> mergeCells('A'. $nm.':I'. $nm);
				 $objPHPExcel->getActiveSheet()->getStyle('A' . $nm . ':A' . $nm)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				 $objPHPExcel->getActiveSheet()->getStyle('A' . $nm . ':' . 'I' . $nm)->applyFromArray($styleThinBlackBorderOutline1);				 
				 $tmpLevelId = $rec[11];
				 $nm++;
			}
			
			
			
            $narr = array();
            for ($i = 0; $i < count($aColumns)-2; $i++) {

                if ($aColumns[$i] == "bEntered") {

                    $narr[] = ($rec[$aColumns[$i]] == "0") ? 'No' : 'Yes';
                } else if ($aColumns[$i] == "bSubmitted") {

                    $narr[] = ($rec[$aColumns[$i]] == "0") ? 'No' : 'Yes'
                    ;
                } else if ($aColumns[$i] == "bAccepted") {


                    $narr[] = ($rec[$aColumns[$i]] == "0") ? 'No' : 'Yes'
                    ;
                    if ($rec[$aColumns[$i]] == "1") {
                        $narr[6] = 'Yes';
                    }
                } else if ($aColumns[$i] == "bPublished") {

                    $narr[] = ($rec[$aColumns[$i]] == "0") ? 'No' : 'Yes'
                    ;
                    if ($rec[$aColumns[$i]] == "1") {
                        $narr[6] = 'Yes';
                        $narr[8] = 'Yes';
                    }
                } else if ($aColumns[$i] != ' ') {
                    $narr[] = $rec[$aColumns[$i]];
                }
            }


            $j = 1;
            for ($k = 0; $k < count($narr) - 1; $k++) {

                if ($k == 0) {
                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $nm, $h);                   
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nm . ':' . 'A' . $nm)->applyFromArray($styleThinBlackBorderOutline1);
                } else {

                    $clmn = getCellLatter($j);

                    $objPHPExcel->getActiveSheet()->SetCellValue($clmn . $nm, $narr[$j]);
                    if ($narr[$j] == 'Yes') {
                        $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '99d268'),
                            )
                        );
                    } else if ($narr[$j] == 'No') {
                        $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'fe402b'),
                            )
                        );
                    } else {
                        $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'),)),
                            'fill' => array(
                            )
                        );
                    }


                    $objPHPExcel->getActiveSheet()->getStyle($clmn . $nm . ':' . $clmn . $nm)->applyFromArray($styleThinBlackBorderOutline1);
                }

                $j++;
            }

            $h++;
            $nm++;
        }


    if (function_exists('date_default_timezone_set')) {
        date_default_timezone_set('UTC');
    } else {
        putenv("TZ=UTC");
    }
    $exportTime = date("Y-m-d_His", time());
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $file = 'facility_reporting_status_' . $exportTime . '.xlsx';
    $objWriter->save(str_replace('.php', '.xlsx', 'media/' . $file));
    header('Location:media/' . $file);
} else {
    echo 'No record found';
}

function getCellLatter($j) {
    if ($j == 1)
        return 'A';
    else if ($j == 2)
        return 'B';
    else if ($j == 3)
        return 'C';
    else if ($j == 4)
        return 'D';
    else if ($j == 5)
        return 'E';
    else if ($j == 6)
        return 'F';
    else if ($j == 7)
        return 'G';
    else if ($j == 8)
        return 'H';
    else if ($j == 9)
        return 'I';
    else if ($j == 10)
        return 'J';
    else if ($j == 11)
        return 'K';
    else if ($j == 12)
        return 'L';
}

?>