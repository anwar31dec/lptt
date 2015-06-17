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
	
  
	$MonthId=$_REQUEST['MonthId']; 
	$YearId=$_REQUEST['YearId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];
    $FacilityId=$_GET['FacilityId'];
    $ItemGroupId = $_GET['ItemGroupId'];
    $year = $_REQUEST['Year'];
    $CountryName = $_REQUEST['CountryName'];
    $monthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $FacilityName = $_REQUEST['FacilityName'];
	
    $regionId = $_REQUEST['RegionId'];
    $RegionName = $_REQUEST['RegionName'];
    $districtId = $_REQUEST['DistrictId'];
    $DistrictName = $_REQUEST['DistrictName'];
    $ownerTypeId = $_REQUEST['OwnerTypeId'];
    $OwnerTypeName = $_REQUEST['OwnerTypeName'];
    $lan = $_REQUEST['lan'];
	$column_name = array();
	
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        } 
		
     require('../lib/PHPExcel.php');	   
     $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Facility Inventory Control'].'  '.$CountryName.' '.$gTEXT['on'].' '.$monthName.', '.$year);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	// $objPHPExcel -> getActiveSheet() -> mergeCells('A2:I2');
	 
	    $objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Region'].':  '.$RegionName).'   ,   '.($gTEXT['District'].': '. $DistrictName));
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	    //$objPHPExcel -> getActiveSheet() -> mergeCells('A3:G3');
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A4',($gTEXT['Owner Type'].': '.$OwnerTypeName).'   ,   '.($gTEXT['Facility'].': '.$FacilityName) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	    //$objPHPExcel -> getActiveSheet() -> mergeCells('A4:G4');
	 
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A6',$gTEXT['Product Name'])
									->SetCellValue('B6',$gTEXT['Closing Balance'])
									->SetCellValue('C6',$gTEXT['AMC'])
									->SetCellValue('D6',$gTEXT['MOS']);
	
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	// $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	// $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
	// $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H6');
	// $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I6');
// 	
	$objPHPExcel -> getActiveSheet() -> getStyle('B6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						
	 $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> applyFromArray($styleThinBlackBorderOutline);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(55);							
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(10);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(10);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(10);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(25);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(12);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(15);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(15);
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
           //$objPHPExcel -> getActiveSheet() -> getStyle('E6'  . ':E6') -> applyFromArray($styleThinBlackBorderOutline);
          // $objPHPExcel -> getActiveSheet() -> getStyle('F6'  . ':F6') -> applyFromArray($styleThinBlackBorderOutline);
          // $objPHPExcel -> getActiveSheet() -> getStyle('G6'  . ':G6') -> applyFromArray($styleThinBlackBorderOutline);
		  // $objPHPExcel -> getActiveSheet() -> getStyle('H6'  . ':H6') -> applyFromArray($styleThinBlackBorderOutline);
          // $objPHPExcel -> getActiveSheet() -> getStyle('I6'  . ':I6') -> applyFromArray($styleThinBlackBorderOutline);		
// 		         
		    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);
				 						 
						$sQuery =  "SELECT
									    MosTypeId
									    , $mosTypeName MosTypeName
									    , ColorCode
										FROM
										    t_mostype_facility
										WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0)
										ORDER BY MosTypeId;";
							mysql_query("SET character_set_results=utf8");											
							$rResult = mysql_query($sQuery);
							//$totColumnVal=mysql_num_rows($rResult);
							$lett=4;
							$output = array();
						$z=4;	$y=6; $l=6;	
						            
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
								
						
 
					
		$sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
					,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
					FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c
					WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " AND a.FacilityId = " . $_REQUEST['FacilityId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . ") p
					WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
					ORDER BY ItemName";
	mysql_query("SET character_set_results=utf8");				
	$rResult = mysql_query($sQuery);
	$aData = array();
	$r= mysql_query($sQuery) ;
	$j=7; $y=7; 
	if ($r)
	 $total = mysql_num_rows($rResult);
     
    if ($total>0){	
	while ($row = mysql_fetch_array($rResult)) {
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		
	     {
	 		 $objPHPExcel->getActiveSheet() ->SetCellValue('A'.$j, $row['ItemName'])
									 		 ->SetCellValue('B'.$j, $row['ClStock'])
									 		 ->SetCellValue('C'.$j, $row['AMC']);
	 		 $temp=explode('.',$row['MOS']);
	 		 $m=count($temp)==2?$temp[0].'.'. $temp[1]:$temp[0].' ';
			 $objPHPExcel->getActiveSheet() ->SetCellValue('D'.$j, count($temp)==2? number_format($row['MOS'],1): $m.'.0');
									
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
				
				    $sd=getNameFromNumber($z+3);
					$objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'.$sd . $y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
					 
					$z++;
					 
					
			 }
			     else
					{
						
						$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					 
				          )
		           ); 	
				
				    $sd=getNameFromNumber($z+3);
					 
					$objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
						 $z++;
				
		       }
		     }
			 
			 $y++;
		}
	$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
                  $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			      $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				  $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				  $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 // $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 // $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 // $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 // $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 // $objPHPExcel -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	     $j++;	 
	}
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.getNameFromNumber($z+2).'2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:'.getNameFromNumber($z+2).'3');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A4:'.getNameFromNumber($z+2).'4');

	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Facility_Inventory_Control_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
  }else{
   	    echo 'No record found';
    }

?>