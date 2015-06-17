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
	
	$Year=$_GET['Year'];
	$Month=$_GET['Month'];
	$ItemGroupId=$_GET['ItemGroupId'];
    $CountryId=$_GET['Country'];
	
 	$ItemGroupName = $_GET['ItemGroupName'];
	$itemNo = $_GET['ItemNo'];
	$CountryName = $_GET['CountryName'];
	$MonthName = $_GET['MonthName'];
	$Year = $_GET['Year'];
	$ItemName = $_GET['ItemName'];
	
	
	
	  $sql = "  SELECT a.ItemNo, b.ItemName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId AND a.CountryId = c.CountryId AND c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
           		WHERE a.MonthId = ".$Month." AND a.Year = ".$Year."
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 	
            	GROUP BY ItemNo, ItemName 
            	HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0";	
		
				  
	 mysql_query("SET character_set_results=utf8");		  
				 
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	$i=1;$j=7;		
	if ($r)
	
	if($total>0){
	 
     $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',($gTEXT['National Stock Pipeline Information List']) . ' on '.($MonthName) . ' '. ($Year) );
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:E2');
		
	$objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Country'].': ' .$CountryName) .' , '.($gTEXT['Product Group'].'  : '. $ItemGroupName) );
	$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	$objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '11')), 'A3');
	
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:E3');
		
													
    $objPHPExcel->getActiveSheet()				
						
									->SetCellValue('A6', 'SL#')							
									->SetCellValue('B6', $gTEXT['Products'])
									//->SetCellValue('C6', $gTEXT['Reported Consumption'])							
									->SetCellValue('C6', $gTEXT['Reported Closing Balance'])						
			  						->SetCellValue('D6', $gTEXT['Average Monthly Consumption'])
									->SetCellValue('E6', $gTEXT['MOS'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('E6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(40);
	//$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(24);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(26);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(32);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(10);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	 
	$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('E6'  . ':E6') -> applyFromArray($styleThinBlackBorderOutline);
	

	$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
	 

	
	
	
	while($rec=mysql_fetch_array($r))
	{
		       $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		       $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		       $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		       $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		      
		
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['ItemName'])								
									//->SetCellValue('C'.$j, $rec['ReportedConsumption']==''? '':number_format($rec['ReportedConsumption']))								
									->SetCellValue('C'.$j, $rec['ReportedClosingBalance']==''? '':number_format($rec['ReportedClosingBalance']))							
									->SetCellValue('D'.$j, $rec['ReportedConsumption']==''? '':number_format($rec['ReportedConsumption']))					
									->SetCellValue('E'.$j, $rec['MOS']==''? '':number_format($rec['MOS'],1))										
									;
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				// $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
			 $i++; $j++;
				 
				
		}
	 
	 	 
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Shipment_List'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
    } else{
        $error = "No record found";	
		echo $error;
    }

?>