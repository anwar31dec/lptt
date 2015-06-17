<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

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
	$MonthId=$_REQUEST['MonthId']; 
	$YearId=$_REQUEST['YearId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
	$fLevelId = $_REQUEST['FLevelId'];
    $FacilityId=$_REQUEST['FacilityId'];
    $ItemGroupId = $_REQUEST['ItemGroupId'];
	
	$regionId = $_REQUEST['RegionId'];
    $districtId = $_REQUEST['DistrictId'];
    $ownerTypeId = $_REQUEST['OwnerTypeId'];
    $region = $_REQUEST['Region'];
    $district = $_REQUEST['District'];
    $ownerType = $_REQUEST['OwnerType'];
	
	$year = $_REQUEST['Year'];
    $CountryName = $_REQUEST['CountryName'];
    $monthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $FacilityName = $_REQUEST['FacilityName'];
	
	$lan=$_REQUEST['lan']; 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	} 
	
    $objPHPExcel = new PHPExcel();
	  
	 $objPHPExcel->getActiveSheet()->SetCellValue('A1',$SITETITLE);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A1') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A1');	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Facility Inventory Control'].' '.$gTEXT['on'].' '.$monthName.', '.$YearId);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	 //$objPHPExcel -> getActiveSheet() -> mergeCells('A2:G2');

    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A3', ($gTEXT['Country Name'].' : '.$CountryName).' , ' . ($gTEXT['Region'].' : '. $region).' , ' . ($gTEXT['District'].' : '. $district));
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	    //$objPHPExcel -> getActiveSheet() -> mergeCells('A3:G3');
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A4', ($gTEXT['Product Group'] .' : ' .$ItemGroupName).' , '.($gTEXT['Report By'] .' : ' .$ownerType).($gTEXT['Facility'].' : '.$FacilityName));
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
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I6');
	
	$objPHPExcel -> getActiveSheet() -> getStyle('B6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						
	 $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> applyFromArray($styleThinBlackBorderOutline);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(55);							
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(12);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(15);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(15);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(15);
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
			$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
			$objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);

		         
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
			    , MosTypeName
			    , ColorCode
		         FROM
			    t_mostype_facility
			    WHERE CountryId = $countryId AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0)
			    ORDER BY MosTypeId;";
	mysql_query("SET character_set_results=utf8");											
	$rResult = mysql_query($sQuery);
	$output = array();
							
    $total = mysql_num_rows($rResult);
     
    if ($total>0){
				$z=4;	$y=6; $l=6;$totColumnVal=4;  	            
				while ($row = mysql_fetch_array($rResult)) {

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $l, $row['MosTypeName']);	
					 
					$sd=getNameFromNumber($totColumnVal);
					 
					$objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
				   	$tmpRow['sTitle'] =$row['MosTypeName'] ;
					$tmpRow['sClass'] = 'center-aln';
					$output1[] = $row;
					$z++;
					$totColumnVal++;
				   }														
								
						

if($ownerTypeId == 1 || $ownerTypeId == 2){
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno AND a.MonthId = " . $MonthId . " 
                AND a.Year = '" . $YearId . "' AND a.CountryId = " . $countryId . " 
                AND a.FacilityId = " . $FacilityId . " AND a.ItemGroupId = " . $ItemGroupId . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId 
                AND g.OwnerTypeId = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0)
                 ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
     }else{
        $sQuery = "SELECT p.MosTypeId, ItemName, MOS ,ClStock,AMC FROM (SELECT
				    a.ItemNo, b.ItemName, a.MOS ,a.ClStock,a.AMC
				,(SELECT MosTypeId FROM t_mostype_facility x WHERE CountryId = $countryId 
                AND FLevelId = $fLevelId  AND (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND IFNULL(a.MOS,0) >= x.MinMos AND IFNULL(a.MOS,0) < x.MaxMos ) MosTypeId
				FROM t_cfm_stockstatus a, t_itemlist b,  t_cfm_masterstockstatus c, t_facility g
				WHERE a.itemno = b.itemno AND a.MonthId = " . $MonthId . " 
                AND a.Year = '" . $YearId . "' AND a.CountryId = " . $countryId . " 
                AND a.FacilityId = " . $FacilityId . " AND a.ItemGroupId = " . $ItemGroupId . "
                AND a.CFMStockId = c.CFMStockId" . " AND c.StatusId = 5 " . "
                AND a.FacilityId=g.FacilityId
                AND g.AgentType = $ownerTypeId 
                AND  (g.RegionId = $regionId OR $regionId = 0)
                AND (g.DistrictId = $districtId OR $districtId = 0) ) p
                
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
     }	   
    mysql_query("SET character_set_results=utf8");
	$rResult = mysql_query($sQuery);
	$aData = array();
	$r= mysql_query($sQuery) ;
	$j=7;$y=7;  $totColumnVal=2;
	if ($r)	
	while ($row = mysql_fetch_array($rResult)) {
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		
	     {
	 		 $objPHPExcel->getActiveSheet() ->SetCellValue('A'.$j, $row['ItemName']);
			 $objPHPExcel->getActiveSheet() ->SetCellValue('B'.$j, number_format($row['ClStock']));
			 $objPHPExcel->getActiveSheet() ->SetCellValue('C'.$j, number_format($row['AMC']));
	 		 $temp=explode('.',$row['MOS']);
	 		 $m=count($temp)==2?$temp[0].'.'. $temp[1]:$temp[0].' ';
			 
			 $objPHPExcel->getActiveSheet() ->SetCellValue('D'.$j, count($temp)==2? number_format($row['MOS'],1): $m.'.0');
			
		   $objPHPExcel -> getActiveSheet() -> getStyle('A'.$j  . ':A'.$j ) -> applyFromArray($styleThinBlackBorderOutline);
		   $objPHPExcel -> getActiveSheet() -> getStyle('B'.$j   . ':B'.$j ) -> applyFromArray($styleThinBlackBorderOutline);
		   $objPHPExcel -> getActiveSheet() -> getStyle('C'.$j   . ':C'.$j ) -> applyFromArray($styleThinBlackBorderOutline);
		   $objPHPExcel -> getActiveSheet() -> getStyle('D'.$j   . ':D'.$j ) -> applyFromArray($styleThinBlackBorderOutline);
									
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
			    else
					{
						
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
			
    
	     $j++;	 
	}
	$objPHPExcel -> getActiveSheet() -> mergeCells('A1:'.getNameFromNumber($z).'1');
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
	$file = 'Facility_Inventory_Control_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
 }else{
   	    echo 'No record found';
    }


?>