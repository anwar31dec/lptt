<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());





   $sql = "SELECT SQL_CALC_FOUND_ROWS MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode, MosLabel,1 ItemGroupId 	
				FROM t_mostype; ";
				
	 $r= mysql_query($sql);   
	 $total = mysql_num_rows($r);
	 
	 if ($total>0){
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->SetCellValue('A2','MOS Type List' )	;
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');		
		$objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A3','Product Group : '.$ItemGroupName.'');
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A3');						
		$objPHPExcel -> getActiveSheet() -> mergeCells('A3:F3');	
														
	    $objPHPExcel->getActiveSheet()
								->SetCellValue('A6', 'SL#')							
								->SetCellValue('B6','MOS Type Name')
								->SetCellValue('C6','Maximum MOS')							
								->SetCellValue('D6','Minimum MOS')						
		  						->SetCellValue('E6','Color Code')
								->SetCellValue('F6','MosLabel');
								
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
		
		
	    $objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel -> getActiveSheet() -> getStyle('F6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(18);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(15);
									
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
	    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
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
       while($rec=mysql_fetch_array($r))
	      {
		// print_r($rec);
	      	$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		    $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	        $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	        
			
			$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['MosTypeName'])
									->SetCellValue('C'.$j, number_format($rec['MinMos'],1))									
									->SetCellValue('D'.$j, number_format($rec['MaxMos'],1)); 
									  			
		    $c=explode('#', $rec['ColorCode']);
		    $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>$c[1]),
				          )
		           );
			$objPHPExcel->getActiveSheet()
										->SetCellValue('F'.$j, $rec['MosLabel']);
											
			 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
			 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			 $i++; $j++;
		    }
	 
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'MOS_Type_List_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }


?>