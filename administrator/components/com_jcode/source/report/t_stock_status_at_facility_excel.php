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
 
  
    require('../lib/PHPExcel.php');	
	
	$monthId = $_REQUEST['MonthId'];
	$year = $_REQUEST['Year'];
	$countryId = $_REQUEST['CountryId'];
	$itemGroupId = $_REQUEST['ItemGroupId'];
	$itemNo = $_REQUEST['ItemNo'];
	$regionId = $_REQUEST['RegionId'];
	$fLevelId = $_REQUEST['FLevelId'];
	
	
    $CountryName = $_REQUEST['CountryName'];
	$MonthName = $_REQUEST['MonthName'];
	$Year = $_REQUEST['Year'];
	$ItemGroupName = $_REQUEST['ItemGroupName'];
	$ItemName = $_REQUEST['ItemName'];
	$RegionName = $_REQUEST['RegionName'];
	$FLevelName = $_REQUEST['FLevelName'];
    $objPHPExcel = new PHPExcel();
		
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2', ($gTEXT['Stock Status at Facility Level']) . ' on '. ($MonthName) . ' '. ($Year) );
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');		
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:E2');
	
//	n
	 $objPHPExcel->getActiveSheet()->SetCellValue('A3', ($gTEXT['Country'].' : '.$CountryName) .' , '. ($gTEXT['Product Group'].' : '.$ItemGroupName).' , '.($gTEXT['Facility Level'].' : '.$FLevelName));
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:E3');	 


	 $objPHPExcel->getActiveSheet()->SetCellValue('A4', ($gTEXT['Product Name'].' : '.$ItemName).', '.($gTEXT['Region'].' : '.$RegionName));
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	 $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	      
     $objPHPExcel -> getActiveSheet() -> mergeCells('A4:E4');	 
//n			
													
     $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A7', 'SL#')							
									->SetCellValue('B7', $gTEXT['Health Facility'])
									->SetCellValue('C7', $gTEXT['Balance'])							
									->SetCellValue('D7', $gTEXT['AMC'])						
			  						->SetCellValue('E7', $gTEXT['MOS'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A7');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B7');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C7');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D7');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E7');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(30);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(16);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A8')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A7'  . ':A7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B7'  . ':B7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C7'  . ':C7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('D7'  . ':D7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('E7'  . ':E7') -> applyFromArray($styleThinBlackBorderOutline);
        
        	 
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);
			
		
	 $serial = "@rank:=@rank+1 AS SL";
	$sQuery = "SELECT SQL_CALC_FOUND_ROWS " . $serial . ",
				  b.FacilityId,
				  b.FacilityName,				  
				  b.ClStock,
				  b.AMC,
				  b.MOS,
				  `Latitude`, `Longitude`
				  FROM (
				SELECT
				  t_cfm_masterstockstatus.FacilityId,
				  t_facility.FacilityName,
				  `Latitude`, `Longitude`,
				  IFNULL(t_cfm_stockstatus.ClStock,0)    ClStock,
				  IFNULL(t_cfm_stockstatus.AMC,0)       AMC,
				  IFNULL(t_cfm_stockstatus.MOS,0)       MOS
				FROM t_cfm_stockstatus
				  INNER JOIN t_cfm_masterstockstatus
				    ON (t_cfm_stockstatus.CFMStockId = t_cfm_masterstockstatus.CFMStockId)
				  INNER JOIN t_country_product
				    ON (t_country_product.CountryId = t_cfm_stockstatus.CountryId)
				      AND (t_country_product.ItemNo = t_cfm_stockstatus.ItemNo)
				  INNER JOIN t_facility
				    ON (t_facility.FacilityId = t_cfm_masterstockstatus.FacilityId)
				  INNER JOIN t_region
				    ON t_facility.RegionId = t_region.RegionId
				      AND t_region.CountryId = $countryId
				      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
				      AND (t_region.RegionId = $regionId OR $regionId=0)
				WHERE (t_cfm_masterstockstatus.StatusId = 5
				       AND t_cfm_masterstockstatus.MonthId = $monthId
				       AND t_cfm_masterstockstatus.Year = '$year'
				       AND t_cfm_masterstockstatus.CountryId = $countryId
				       AND t_country_product.ItemGroupId = $itemGroupId
				       AND t_country_product.ItemNo = $itemNo
				       AND t_cfm_stockstatus.ClStockSourceId IS NOT NULL
				       AND (t_cfm_stockstatus.ClStock <> 0
				             OR t_cfm_stockstatus.AMC <> 0))
				 UNION
				 SELECT
				  a.FacilityId, 
				  a.FacilityName,
				  a.`Latitude`, a.`Longitude`,
				  NULL ClStock,
				  NULL AMC,
				  NULL MOS
				FROM t_cfm_masterstockstatus
				  INNER JOIN t_facility
				    ON t_cfm_masterstockstatus.FacilityId = t_facility.FacilityId
				  INNER JOIN t_region
				    ON t_facility.RegionId = t_region.RegionId
				      AND t_region.CountryId = $countryId
				      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
				      AND (t_region.RegionId = $regionId OR $regionId=0)
				  RIGHT JOIN (SELECT
				                p.FacilityId,
				                p.FacilityCode,
				                p.FacilityName,
				                `Latitude`, `Longitude`
				              FROM t_facility p
				                INNER JOIN t_facility_group_map q
				                  ON p.FacilityId = q.FacilityId
				                INNER JOIN t_region r
				                  ON p.RegionId = r.RegionId
				              WHERE p.CountryId = $countryId
				                  AND q.ItemGroupId = $itemGroupId
				                  AND (p.FLevelId = $fLevelId OR $fLevelId=0)
				                  AND (r.RegionId = $regionId OR $regionId=0)) a
				    ON (t_cfm_masterstockstatus.FacilityId = a.FacilityId
				        AND t_cfm_masterstockstatus.MonthId = $monthId
				        AND t_cfm_masterstockstatus.Year = '$year'
				        AND t_cfm_masterstockstatus.CountryId = $countryId
				        AND t_cfm_masterstockstatus.ItemGroupId = $itemGroupId
				        AND t_cfm_masterstockstatus.StatusId = 5)
				WHERE t_cfm_masterstockstatus.FacilityId IS NULL) b
									WHERE 1=1
									$sWhere
									$sOrder
									$sLimit;";
    mysql_query("SET character_set_results=utf8");   
	$rResult = mysql_query($sQuery);
	$i=1; $j=8;	$monthvar='';
	if ($rResult)
	while($rec=mysql_fetch_array($rResult))
	{
		       $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		       $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		       $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		       $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		      
		
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['FacilityName'])								
									->SetCellValue('C'.$j,$rec['ClStock']==''? '':number_format($rec['ClStock']))								
									->SetCellValue('D'.$j,$rec['AMC']==''? '':number_format($rec['AMC']))									
									->SetCellValue('E'.$j,$rec['MOS']==''? '':number_format($rec['MOS'],1))								
															
								
									;  			
				
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
			 $i++; $j++;
				 
				
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	

?>