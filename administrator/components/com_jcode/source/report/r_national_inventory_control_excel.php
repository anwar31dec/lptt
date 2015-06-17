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
	
   require('../lib/PHPExcel.php');	
	$CountryId=$_GET['CountryId']; 
	$FacilityId=$_GET['FacilityId']; 
    $MonthId=$_GET['MonthId']; 
	$YearId=$_GET['YearId'];
    $ItemGroupId=$_GET['ItemGroupId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
	
	$CountryName=$_GET['CountryName'];   
    $MonthName = $_GET['MonthName'];
    $ItemGroupName = $_GET['ItemGroupName'];
	// $ownerTypeId = $_REQUEST['OwnerTypeId'];
    // $OwnerTypeName = $_REQUEST['OwnerTypeName'];  
	
	
     $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['National Inventory Control']);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	// $objPHPExcel -> getActiveSheet() -> mergeCells('A2:G2');
	 
	    $objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Country Name'].':'.$CountryName). ' , '.($gTEXT['Product Group'].':'.$ItemGroupName) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	    //$objPHPExcel -> getActiveSheet() -> mergeCells('A3:G3');
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A4',($gTEXT['Month'].':'.$MonthName). ' , '.($gTEXT['Year'].':'.$YearId) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	   // $objPHPExcel -> getActiveSheet() -> mergeCells('A4:G4');
	 
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A6',$gTEXT['Product Name'])
									->SetCellValue('B6',$gTEXT['MOS']);
	// $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),A6);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('B6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
	 $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> applyFromArray($styleThinBlackBorderOutline);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(55);							
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(10);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(15);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(10);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(15);
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          
		    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
				 					
						$lan = $_REQUEST['lan'];
					       if($lan == 'en-GB'){
					   	
					            $mosTypeName = 'MosTypeName';
					        }else{
					            $mosTypeName = 'MosTypeNameFrench';
					        }   				 
						$sQuery = "SELECT
									    MosTypeId
									    , $mosTypeName MosTypeName
									    , ColorCode
									    FROM
									    t_mostype
									    WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0)
									    ORDER BY MosTypeId;";
					mysql_query("SET character_set_results=utf8");													
							$rResult = mysql_query($sQuery);
								$lett=2;    
							$output = array();
						$z=2;	$y=6; $l=6;        
				while ($row = mysql_fetch_array($rResult)) {
					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $l, $row['MosTypeName']);	
					$sd=getNameFromNumber($lett);
				   $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
				    $objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
				   	$objPHPExcel -> getActiveSheet() -> getColumnDimension($sd) -> setWidth(20);
					$tmpRow['sTitle'] =$row['MosTypeName'] ;
					$tmpRow['sClass'] = 'center-aln';
					$output1[] = $row;
					$z++;
					$lett++;
				   }														
								
						
 
					
		 $sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , a.MOS
					,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
					FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
					WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
					WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
					ORDER BY ItemName";
 mysql_query("SET character_set_results=utf8");
	$r = mysql_query($sQuery);
	$aData = array();
	$r= mysql_query($sQuery) ;
	$j=7; ;$y=7; 
	if ($r)	
	 $total = mysql_num_rows($r);
     
    if ($total>0){	
	while ($row = mysql_fetch_array($r)) {
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		
	     {
	 		 $objPHPExcel->getActiveSheet() ->SetCellValue('A'.$j, $row['ItemName']);
	 		 
			 
			 $objPHPExcel->getActiveSheet() ->SetCellValue('B'.$j, $row['MOS']==''? '':number_format($row['MOS'],1)); 
									
			 	$z=$totColumnVal+1;	
			   foreach ($output1 as $rowMosType) {
			   if ($rowMosType['MosTypeId'] == $row['MosTypeId']) {
				$temp=explode('#',$rowMosType['ColorCode'] );
				
				$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>$temp[1]),
				          )
		           ); 	
				
				    $sd=getNameFromNumber($z+1);
					 
					$objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
					 
					$z++;
					 
					
			 }
			    else{
						
						$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					 
				          )
		           ); 	
				
				    $sd=getNameFromNumber($z+1);
					 
					$objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
						 $z++;
					}
				
		     }
		     
			 
			 $y++;
		}
	$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
                 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
	     $j++;	 
	}
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.getNameFromNumber($z).'2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:'.getNameFromNumber($z).'3');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A4:'.getNameFromNumber($z).'4');

	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'National_Inventory_Control_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
}else{
   	    echo 'No record found';
    }

?>