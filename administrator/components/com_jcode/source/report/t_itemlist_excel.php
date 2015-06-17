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
 	
	$itemGroupId = $_GET['ItemGroupId'];
    $ItemGroupName = $_GET['ItemGroupName']; 
    
    if($itemGroupId){
		$itemGroupId = " WHERE a.ItemGroupId = '".$itemGroupId."' ";
	}
	
	$sWhere = "";
	if ($_GET['sSearch'] != "") {
		 $sSearch=str_replace("|","+", $_GET['sSearch']);
		$sWhere = " AND (a.ItemCode LIKE '%" . mysql_real_escape_string($sSearch) . "%'  OR " .
				" a.ItemName LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
				" a.ATCcode LIKE '%" . mysql_real_escape_string($sSearch) . "%' OR ".
				" d.ProductSubGroupName LIKE '%".mysql_real_escape_string($sSearch)."%') ";
	}
	
	$sql="SELECT  a.ItemNo, a.ItemCode, a.ItemName, a.bKeyItem, a.ItemGroupId, b.GroupName, a.ProductSubGroupId, d.ProductSubGroupName
				FROM t_itemlist as a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                INNER JOIN t_unitofmeas c ON a.UnitId = c.UnitId
                INNER JOIN t_product_subgroup d ON a.ProductSubGroupId = d.ProductSubGroupId
                ".$itemGroupId." ".$sWhere." ORDER BY ItemCode,GroupName, ItemName";
	mysql_query("SET character_set_results=utf8");
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	if ($total>0){
	require('../lib/PHPExcel.php');		
    $objPHPExcel = new PHPExcel();
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT[ 'Product List']);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:E2');
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A3', ($gTEXT['Product Group'].' : ' .$ItemGroupName));
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:E3');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', 'SL#')							
									->SetCellValue('B6', $gTEXT['Product Code'])
									->SetCellValue('C6', $gTEXT['Product Name'])							
									->SetCellValue('D6', $gTEXT[ 'Key Product'])
									->SetCellValue('E6', $gTEXT[ 'Product Subgroup']);
									
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(40);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(25);
	
										
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
   
	
	
	$i=1; $j=7;
	$tempGroupId='';
	if ($r)	
	while($rec=mysql_fetch_array($r)){
			 
		
		 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
		
		 if($tempGroupId!=$rec['GroupName']){
		   				
	              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
														'fill' => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'color' => array('rgb'=>'DAEF62'),
				          								)
		           );
		
		   	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':E'.$j);	
			
	    	$objPHPExcel->getActiveSheet()
										->SetCellValue('A'.$j, $rec['GroupName']);
										 
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
			$tempGroupId=$rec['GroupName'];
			$j++;
		    }
		
	 	    $objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['ItemCode'])								
									->SetCellValue('C'.$j, $rec['ItemName']);
 
 
 		   $objDrawing = new PHPExcel_Worksheet_Drawing();
		   if($rec['bKeyItem']==0)
			     
				 $objDrawing -> setPath('image/unchecked.png');
		   else 
				 $objDrawing -> setPath('image/checked.png');
				 
				$objDrawing -> setCoordinates('D' . $j);
				$objDrawing -> setWorksheet($objPHPExcel -> getActiveSheet()); 
				
				$objPHPExcel->getActiveSheet()						
									->SetCellValue('E'.$j, $rec['ProductSubGroupName'])	;
			    $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j ) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j ) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
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
	$file = 'Product_List'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
 }else{
   	    echo 'No record found';
    }
?>