<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
include ("../universal_function_lib_ext.php");
$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$gTEXT = $TEXT; 
$jBaseUrl = $_GET['jBaseUrl'];

$countryId = $_GET['CountryId'];	
$monthId = $_GET['MonthId'];		
$year = $_GET['Year'];	
$itemGroupId = $_GET['ItemGroupId'];

$CountryName = $_GET['CountryName'];	
$GroupName = $_GET['ItemGroupName'];	
$MonthName = $_GET['MonthName'];
$reportId = $_GET['ReportId'];

$query = "SELECT CNMStockId, MonthId, Year, ItemGroupId,
				(SELECT b.name FROM  j323_users b WHERE b.id = a.CreatedBy) CreatedBy, DATE_FORMAT(CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,
				(SELECT b.name FROM  j323_users b WHERE b.id = a.LastUpdateBy)  LastUpdateBy,	
				(SELECT b.name FROM  j323_users b WHERE b.id = a.LastSubmittedBy) LastSubmittedBy ,
				c.StatusId, c.StatusName,
				DATE_FORMAT(LastSubmittedDt, '%d-%b-%Y %h:%i %p') LastSubmittedDt,	
				DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,	
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt 	
				FROM t_cnm_masterstockstatus a 
				LEFT JOIN t_status c ON a.StatusId = c.StatusId ";
	$query.= " WHERE MonthId = $monthId and Year = '$year' AND CountryId = $countryId AND ItemGroupId = $itemGroupId";  
	
		
	$r= mysql_query($query) ;
	
	$i=1;
        
//****************************************************************************************    	
    require('../lib/PHPExcel.php');	
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['National Level Patient And Stock Status']);
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A3',('CountryName : '. $CountryName). ' , '.('Product Group : '.$GroupName) );
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:F3');
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A4',('Month : '. $MonthName). ' , '.('Year : '.$year) );
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A4:F4');
	
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '14','bold' => true)), 'A5');
    
													
    $objPHPExcel->getActiveSheet()
    							->SetCellValue('A6', $gTEXT['Report Id'])							
    							->SetCellValue('B6', $gTEXT['Status'])
    							->SetCellValue('C6', $gTEXT['Created Date'])
    							->SetCellValue('D6', $gTEXT['Accepted Date'])
    							->SetCellValue('E6', $gTEXT['Submitted Date'])
    							->SetCellValue('F6', $gTEXT['Published Date']);
    							
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');	
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	
    $objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
	

    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
    
    $objPHPExcel -> getActiveSheet() -> getDefaultStyle('A7') -> getAlignment()->setWrapText(true);
    $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('E6'  . ':E6') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('F6'  . ':F6') -> applyFromArray($styleThinBlackBorderOutline);
    
    
    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
	
    $i=1; $j=7;	
	while($rec=mysql_fetch_array($r)){
	   
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        
       
       
        $objPHPExcel->getActiveSheet()
        		->SetCellValue('A'.$j, $rec['CNMStockId'])							
        		->SetCellValue('B'.$j, $rec['StatusName'])
				->SetCellValue('C'.$j, $rec['CreatedDt'])							
        		->SetCellValue('D'.$j, $rec['AcceptedDt'])
				->SetCellValue('E'.$j, $rec['LastSubmittedDt'])										
        		->SetCellValue('F'.$j, $rec['PublishedDt']);  			
        
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
        
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $i++; $j++;
	}
	 	
//****************************************Patient Overview *********************************//	
    $j=$j+2; 
    
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $gTEXT['Patient Overview']);
	$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$j)->getAlignment()->setWrapText(true);
        
	$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A'.$j);					
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$j.':E'.$j);
    
    $j=$j+1;
    
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, '#SL')							
								  ->SetCellValue('B'.$j, $gTEXT['Patient type'])
								  ->SetCellValue('C'.$j, $gTEXT['Refill Patients'])
								  ->SetCellValue('D'.$j, $gTEXT['New Patients'])
								  ->SetCellValue('E'.$j, $gTEXT['Total Patients']);
							
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A'.$j);	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D'.$j);	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E'.$j);
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel -> getActiveSheet() -> getStyle('D'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j . ':A'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j . ':B'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j . ':C'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('D'.$j . ':D'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('E'.$j . ':E'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	
    
    
	$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
	
   $query_1 = "SELECT 	b.CNMPOId,
        				a.FormulationName, 
        				b.RefillPatient, 
        				b.NewPatient, 
        				b.TotalPatient
        				FROM t_formulation a INNER JOIN t_cnm_patientoverview b ON a.FormulationId = b.FormulationId ";
    
    $query_1 .= " AND MonthId = $monthId and Year = '$year' AND CountryId = $countryId AND b.ItemGroupId = $itemGroupId";
    
	$query_1 .= " ORDER BY b.CNMPOId,FormulationName";                
	$r_1= mysql_query($query_1) ;
	$K=1; $j=$j+1;	
    $tempGroupId=''; 
	while ($rec_1 = mysql_fetch_array($r_1)) {
		                       if($rec_1['RefillPatient']==0)$rec_1['RefillPatient']='';
                        		if($rec_1['NewPatient']==0)$rec_1['NewPatient']='';
                        	    if($rec_1['TotalPatient']==0)$rec_1['TotalPatient']='';
        $objPHPExcel->getActiveSheet()
        						->SetCellValue('A'.$j, $K )							
        						->SetCellValue('B'.$j, $rec_1['FormulationName'])								
        						->SetCellValue('C'.$j, ($rec_1['RefillPatient']==''? '':number_format($rec_1['RefillPatient'])))
								->SetCellValue('D'.$j, ($rec_1['NewPatient']==''? '':number_format($rec_1['NewPatient'])))								
        						->SetCellValue('E'.$j, ($rec_1['TotalPatient']==''? '':number_format($rec_1['TotalPatient'])));
		$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel -> getActiveSheet() -> getStyle('D'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	    $objPHPExcel -> getActiveSheet() -> getStyle('E'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
		
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);       
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $K++; $j++;				
    }
    
//************************************************Patient By Regimen*********************************
    
    $j = $j+2;   
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $gTEXT['Patient By Regimen'] );
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    
    $objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A'.$j);	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':E'.$j);	
		
	$j=$j+1;
													
    $objPHPExcel->getActiveSheet()										
								->SetCellValue('A'.$j, '#SL')							
								->SetCellValue('B'.$j, $gTEXT['Regimens'])
								->SetCellValue('C'.$j, $gTEXT['Refill Patients'])							
								->SetCellValue('D'.$j, $gTEXT['New Patients'])						
		  						->SetCellValue('E'.$j, $gTEXT['Total Patients']);
							
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A'.$j);	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E'.$j);
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel -> getActiveSheet() -> getStyle('D'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(50);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(40);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
	
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	 
	$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j  . ':A'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j  . ':B'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j  . ':C'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('D'.$j  . ':D'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('E'.$j  . ':E'.$j) -> applyFromArray($styleThinBlackBorderOutline);
		
	$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
	
   
    
	$query_2  = "SELECT b.CNMPatientStatusId, a.RegimenName, c.FormulationName, b.RefillPatient, b.NewPatient, b.TotalPatient 
        			FROM t_regimen a 
        			INNER JOIN "."t_cnm_regimenpatient"." b ON a.RegimenId = b.RegimenId 
        			INNER JOIN t_formulation c ON a.FormulationId = c.FormulationId ";
         
        $query_2 .= " AND MonthId = $monthId and Year = '$year' AND CountryId = $countryId AND b.ItemGroupId = $itemGroupId";
        
        $query_2 .= " ORDER BY c.FormulationName, b.CNMPatientStatusId";
	
	$r_2 = mysql_query($query_2);
	 
	$k=1;  $j=$j+1;
	$tempGroupId='';	 
	while ($rec_2 = mysql_fetch_array($r_2)) {
		
		
			  		 if($tempGroupId!=$rec_2['FormulationName']) 
		   {
		   				
	              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'DAEF62'),
				          )
		           );
		
		   	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':E'.$j);	
			
	    	$objPHPExcel->getActiveSheet()
											
									->SetCellValue('A'.$j, $rec_2['FormulationName'])								
									
									; 
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		   	 	
			$tempGroupId=$rec_2['FormulationName'];$j++;
		   }
			    if($rec_2['RefillPatient']==0)$rec_2['RefillPatient']='';
				if($rec_2['NewPatient']==0)$rec_2['NewPatient']='';
        	    if($rec_2['TotalPatient']==0)$rec_2['TotalPatient']='';
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $k)							
									->SetCellValue('B'.$j, $rec_2['RegimenName'])								
									->SetCellValue('C'.$j, ($rec_2['RefillPatient']==''? '':number_format($rec_2['RefillPatient'])))								
									->SetCellValue('D'.$j, ($rec_2['NewPatient']==''? '':number_format($rec_2['NewPatient'])))									
									->SetCellValue('E'.$j, ($rec_2['TotalPatient']==''? '':number_format($rec_2['TotalPatient'])));
				  			
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
		 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		
		 $k++; $j++;
	}
		
//********************************************Stock Status*****************************************************  
    $j = $j+2; 
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $gTEXT['Stock Status'])	;
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  
    $objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A'.$j);	   		
    $objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':M'.$j);	
    							   
    $j = $j+1; 										
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, 'SL')							
            					  ->SetCellValue('B'.$j, $gTEXT['Item Name'])
            					  ->SetCellValue('C'.$j, $gTEXT['OBL'])
                                  ->SetCellValue('D'.$j, $gTEXT['Received'])
            					  ->SetCellValue('E'.$j, $gTEXT['Dispensed']) 
            					  ->SetCellValue('F'.$j, $gTEXT['Adjusted'])
            					  ->SetCellValue('G'.$j, $gTEXT['Adjust Reason'])
                                  ->SetCellValue('H'.$j, $gTEXT['Stock Out Days'])
            					  ->SetCellValue('I'.$j, $gTEXT['Closing Balance']) 
            					  ->SetCellValue('J'.$j, $gTEXT['CL Stock Source'])
            					  ->SetCellValue('K'.$j, $gTEXT['AMC'])
								  ->SetCellValue('L'.$j, $gTEXT['AMC Change Reason'])
            					  ->SetCellValue('M'.$j, $gTEXT['MOS']);
						
   							
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A'.$j);	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F'.$j);	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'J'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'K'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'L'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'M'.$j);
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel -> getActiveSheet() -> getStyle('D'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
	$objPHPExcel -> getActiveSheet() -> getStyle('J'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('F'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel -> getActiveSheet() -> getStyle('G'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('H'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
	$objPHPExcel -> getActiveSheet() -> getStyle('I'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('K'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel -> getActiveSheet() -> getStyle('L'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('M'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
	
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('J') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('K') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('L') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('M') -> setWidth(20);
	
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
    
  	$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j  . ':A'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j  . ':B'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j  . ':C'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('D'.$j  . ':D'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('E'.$j  . ':E'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('F'.$j  . ':F'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('G'.$j  . ':G'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('H'.$j  . ':H'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('I'.$j  . ':I'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('J'.$j  . ':J'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('K'.$j  . ':K'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('L'.$j  . ':L'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('M'.$j  . ':M'.$j) -> applyFromArray($styleThinBlackBorderOutline);
			
		
	$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('M'.$j)->getFont()->setBold(true);
    								
  
   $query_3 = " SELECT a.CNMStockStatusId, a.MonthId, a.Year, a.ItemNo, b.ItemName, a.OpStock OpStock_A, 0 OpStock_C, a.ReceiveQty, a.DispenseQty, 
                    a.AdjustQty, a.AdjustId AdjustReason, a.StockoutDays, a.ClStock ClStock_A, 0 ClStock_C, a.ClStockSourceId, a.MOS, a.AMC, a.AmcChangeReasonId, 
                    a.MaxQty, a.OrderQty, a.ActualQty, a.UserId, a.LastEditTime, c.ProductSubGroupName FormulationName, SourceName
                    FROM t_cnm_stockstatus a 
                    INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo 
                    INNER JOIN t_product_subgroup c ON b.ProductSubGroupId = c.ProductSubGroupId 
                    LEFT JOIN t_clstock_source d ON a.ClStockSourceId = d.ClStockSourceId
                    WHERE a.CNMStockId = $reportId 
                    AND MonthId = $monthId 
                    AND Year = '$year' 
                    AND a.ItemGroupId = $itemGroupId 
                    AND CountryId = $countryId
                    ORDER BY c.ProductSubGroupName, b.ItemName ASC ";
		
	$r_3 = mysql_query($query_3);
	$k=1;  $j=$j+1;
	while ($rec_3 = mysql_fetch_array($r_3)) {
	
	            if($rec_3['OpStock_A']==0)$rec_3['OpStock_A']='';
				if($rec_3['ReceiveQty']==0)$rec_3['ReceiveQty']='';
        	    if($rec_3['DispenseQty']==0)$rec_3['DispenseQty']='';
        	    if($rec_3['AdjustQty']==0)$rec_3['AdjustQty']='';
				if($rec_3['AdjustId AdjustReason']==0)$rec_3['AdjustId AdjustReason']='';
        	    if($rec_3['StockoutDays']==0)$rec_3['StockoutDays']='';
        	    if($rec_3['ClStock_A']==0)$rec_3['ClStock_A']='';
        	    if($rec_3['AMC']==0)$rec_3['AMC']='';
        	    if($rec_3['AmcChangeReasonId']==0)$rec_3['AmcChangeReasonId']='';
        	    if($rec_3['MOS']==0)$rec_3['MOS']='';
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $k)							
									->SetCellValue('B'.$j, $rec_3['ItemName'])								
									->SetCellValue('C'.$j, ($rec_3['OpStock_A']==''? '':number_format($rec_3['OpStock_A'])))								
									->SetCellValue('D'.$j, ($rec_3['ReceiveQty']==''? '':number_format($rec_3['ReceiveQty'])))									
									->SetCellValue('E'.$j, ($rec_3['DispenseQty']==''? '':number_format($rec_3['DispenseQty'])))
									->SetCellValue('F'.$j, $rec_3['AdjustQty'])								
									->SetCellValue('G'.$j, $rec_3['AdjustId AdjustReason'])									
									->SetCellValue('H'.$j, $rec_3['StockoutDays'])									
									->SetCellValue('I'.$j, ($rec_3['ClStock_A']==''? '':number_format($rec_3['ClStock_A'])))
									->SetCellValue('J'.$j, $rec_3['SourceName'])								
									->SetCellValue('K'.$j, ($rec_3['AMC']==''? '':number_format($rec_3['AMC'])))									
									->SetCellValue('L'.$j, $rec_3['AmcChangeReasonId'])									
									->SetCellValue('M'.$j, ($rec_3['MOS']==0? '':number_format(($rec_3['MOS']),1))); 
				  
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
        $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
		$objPHPExcel -> getActiveSheet() -> getStyle('J' . $j . ':J' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		$objPHPExcel -> getActiveSheet() -> getStyle('K' . $j . ':K' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel -> getActiveSheet() -> getStyle('L' . $j . ':L' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel -> getActiveSheet() -> getStyle('M' . $j . ':M' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
		 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('J' . $j . ':J' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('K' . $j . ':K' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('L' . $j . ':L' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('M' . $j . ':M' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		
		 $k++; $j++;
	}
	 	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'National_Level_Patient_And_Stock_Status_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

  

?>