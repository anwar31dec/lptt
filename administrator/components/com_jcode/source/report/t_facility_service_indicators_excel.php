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
    $Year = $_GET['Year'];   
    $Month = $_GET['Month'];
    $CountryId = $_GET['CountryId'];  
    $ServiceType = $_GET['ServiceType'];
	$CountryName=$_GET['CountryName'];   
	$MonthName = $_GET['MonthName'];
	$ServiceTypeName = $_GET['ServiceTypeName'];    
    if($CountryId){
		$CountryId = " AND a.CountryId = ".$CountryId." ";
	}
  $sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " AND (FacilityName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                         OR NewPatient LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                         OR TotalPatient LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') ";
	}
      	    
    $sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99	
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = ".$ServiceType."
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId." $sWhere  
            GROUP BY a.FacilityId, FacilityName
           	$sOrder $sLimit ";
    mysql_query("SET character_set_results=utf8");     
	$r= mysql_query($sql) ;
   $total = mysql_num_rows($r);
     $sql = "SELECT SQL_CALC_FOUND_ROWS a.FacilityId, FacilityName, IFNULL(SUM(a.NewPatient),0) NewPatient, IFNULL(SUM(a.TotalPatient),0) TotalPatient 
            FROM t_cfm_patientoverview a
            INNER JOIN t_facility b ON a.FacilityId = b.FacilityId AND b.FLevelId = 99	
            INNER JOIN t_formulation d ON a.FormulationId = d.FormulationId AND d.ServiceTypeId = ".$ServiceType."
            INNER JOIN t_cfm_masterstockstatus f ON a.CFMStockId = f.CFMStockId AND StatusId = 5
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."  
            GROUP BY a.FacilityId, FacilityName";
      mysql_query("SET character_set_results=utf8");              
    $result = mysql_query($sql);
    $totalPatient = 0;
   	while ($rec = mysql_fetch_object($result)) {
   	    $totalPatient = $totalPatient + $rec->TotalPatient;
    }
     
    if ($total>0){
        
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Facility Service indicators']);
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
		$objPHPExcel -> getActiveSheet() -> mergeCells('A2:C2');
			
		$objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Country Name'].':'.$CountryName). ' , '.($gTEXT['Service Type'].':'.$ServiceTypeName));
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:C3');
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A4',($gTEXT['Month'].':'.$MonthName). ' , '.($gTEXT['Year'].':'.$Year));
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A4:C4');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A5',($gTEXT['Total Patient'].' is '.(number_format($totalPatient))) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A5') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A5:C5');
		
	    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();										
	    $objPHPExcel->getActiveSheet()
										->SetCellValue('A6', 'SL')	
										->SetCellValue('B6',$gTEXT['Name of Facility'])						
										->SetCellValue('C6',$gTEXT['Number of Total Patients'])
										//->SetCellValue('D6',$gTEXT['Number of New Patients'])
										;
										
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
		//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(30);
		//$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(30);
	    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
	    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
        $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
		//$objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
          
	    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
	    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
		//$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
  
	    $i=1; $j=7;	
     while($rec=mysql_fetch_array($r))
        {
       	 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		// $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
 		 $objPHPExcel->getActiveSheet()
								   ->SetCellValue('A'.$j, $i)
								   ->SetCellValue('B'.$j, $rec['FacilityName'])							
								   ->SetCellValue('C'.$j, number_format($rec['TotalPatient']))
								   //->SetCellValue('D'.$j, $rec['NewPatient'])
								   ;
								     			
         $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
         $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 //$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	     $i++; $j++;
		 
				
		 }
	 
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Facility_Type_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }


?>