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
function crnl2br($string){	
	$patterns = array ('/\r/','/\t/','/\n/');
	$replace = array ('', ' ', ' ');
	return preg_replace($patterns, $replace, $string);
}
	
function getLatter($i){
		if($i==1) return "A";
		else if($i==2) return "B";
		else if($i==3) return "C";
		else if($i==4) return "D";
		else if($i==5) return "E";
		else if($i==6) return "F";
		else if($i==7) return "G";
		else if($i==8) return "H";
		else if($i==9) return "I";
		else if($i==10) return "J";
		else if($i==11) return "K";
		else if($i==12) return "L";
		else if($i==13) return "M";
		else if($i==14) return "N";
		else if($i==15) return "O";
		else if($i==16) return "P";
		else if($i==17) return "Q";
		else if($i==18) return "R";
		else if($i==19) return "S";
		
		
}

     
  $Month=$_GET['MonthId']; 
  $Year=$_GET['Year']; 
  $CountryId=$_GET['CountryId'];
  $ItemGroupId=$_GET['ItemGroupId'];
  $ownnerTypeId = $_GET['OwnnerTypeId'];
  $CountryName=$_GET['CountryName'];   
  $MonthName = $_GET['MonthName'];
  $ItemGroupName = $_GET['ItemGroupName'];
  $OwnnerTypeName = $_GET['OwnnerTypeName'];
  $lan = $_REQUEST['lan'];
    if($lan == 'en-GB'){ 
        $fLevelName = 'FLevelName';
    }else{
		 $fLevelName = 'FLevelNameFrench';
    } 
  
        if($CountryId){
        $CountryId = " AND a.CountryId = ".$CountryId." ";
        }
        
        $columnList = array();
        $productName = 'Product Name';
        
        //$output = array('aaData' => array());
        $aData = array();
        //$output2 = array();
        
        if($ownnerTypeId==1 || $ownnerTypeId == 2){
        $sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
        	, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            FROM t_cfm_stockstatus a 
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
            INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."
        	AND d.OwnerTypeId  = ".$ownnerTypeId."
            GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
            HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0
        	order by ItemName,f.FLevelId;";
        }
        else{
        $sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
        		, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
        		FROM t_cfm_stockstatus a 
        		INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
        		INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
        		INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
        		INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = ".$ItemGroupId."
        		INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
        		WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."
        		AND d.AgentType = ".$ownnerTypeId."
        		GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
        		HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0
        		order by ItemName,f.FLevelId;";
        }	
        //echo $sQuery;
        $rResult = mysql_query($sQuery);
        $total = mysql_num_rows($rResult);
        $tmpItemName = '';
        
        $sl = 1;
        $count = 0;
        $preItemName='';
        
        //echo 'Rubel';
        $data = array();
        $headerList = array();
        while ($row = mysql_fetch_assoc($rResult)) {
        $data[] = $row;
        }
        
        foreach($data as $row){
        ////Duplicate value not push in array
        //if (!in_array($row['FLevelName'], $headerList)) {
        //	$headerList[] = $row['FLevelName'];
        //}
        $headerList[$row['FLevelId']] = $row['FLevelName'];
        }
        //array_push($headerList,'National');
        $headerList[999] = 'National'; 
        
        foreach($headerList as $key => $value){
        $columnList[] = $value;//.' Level AMC';
        $columnList[] = $value;//.' Level SOH';
        $columnList[] = $value;//.' Level MOS';
        }
        $fetchDataList = array();
        
        foreach($data as $row){
        if ($tmpItemName != $row['ItemName']) {
        
        	if ($count > 0) {
        		$fetchDataList['999'.'2'] =  number_format($fetchDataList['999'.'2']);
        		$fetchDataList['999'.'3'] =  number_format($fetchDataList['999'.'3'],1);
        		array_unshift($fetchDataList,$sl,$preItemName);
        		$aData[] = $fetchDataList;
        		$sl++;
        	 }
        	 $count++;	
        	 
        	 $preItemName	=  $row['ItemName'];
        	 
        	 unset($fetchDataList);
        	 foreach($headerList as $key => $value){
        		 $fetchDataList[$key.'1'] = NULL; 
        		 $fetchDataList[$key.'2'] = NULL; 
        		 $fetchDataList[$key.'3'] = NULL; 
        	 }			 
        	$tmpItemName = $row['ItemName'];
        }
        
        $fLevelId = $row['FLevelId'];
        
        $fetchDataList[$fLevelId.'1'] = number_format($row['FacilityAMC']);
        $fetchDataList[$fLevelId.'2'] = number_format($row['FacilitySOH']);
        $fetchDataList[$fLevelId.'3'] = number_format($row['MOS'],1);
         
        if($fetchDataList['999'.'1'] < $row['FacilityAMC']){
        	$fetchDataList['999'.'1'] =  number_format($row['FacilityAMC']);
        }
        
        $fetchDataList['999'.'2']+=  $row['FacilitySOH'];
        $fetchDataList['999'.'3']+=  $row['MOS'];
        	
        }
        
        $fetchDataList['999'.'2'] =  number_format($fetchDataList['999'.'2']);
        $fetchDataList['999'.'3'] =  number_format($fetchDataList['999'.'3'],1);
        array_unshift($fetchDataList,$sl,$preItemName);
        $aData[] = $fetchDataList;
	
    if($total > 0){
        
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Stock Status at Different Level Data List']);
        $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	
	 
	    $objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Country Name'].' : '. $CountryName). ' , '. ($gTEXT['Product Group'].' : ' . $ItemGroupName) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	 
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A4',($gTEXT['Month'].' : '. $MonthName). ' , '. ($gTEXT['Year'].' : ' .$Year) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	
	    $objPHPExcel->getActiveSheet()->SetCellValue('A5',($gTEXT['Owner Type'].' : '. $OwnnerTypeName));
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A5') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A5');
	  
									
									
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel -> getActiveSheet() -> getStyle('A8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(52);
		 
	  
										
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
	    $objPHPExcel->getActiveSheet()->getDefaultStyle('A8')->getAlignment()->setWrapText(true);
        $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
		
      
 		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
	    
   if($Month=='1') 	        $MonthName="January";
	elseif($Month=='2') 	$MonthName="February";
	elseif($Month=='3') 	$MonthName="March";
	elseif($Month=='4') 	$MonthName="April";
	elseif($Month=='5') 	$MonthName="May";
	elseif($Month=='6') 	$MonthName="June";
	elseif($Month=='7')     $MonthName="July";
	elseif($Month=='8') 	$MonthName="August";
	elseif($Month=='9')     $MonthName="September";
	elseif($Month=='10')    $MonthName="October";
	elseif($Month=='11') 	$MonthName="November";
	elseif($Month=='12') 	$MonthName="December";   
    
		           
      
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A7', 'SL')			
									->SetCellValue('B7',$gTEXT['Product Name']); 
									
									
	$j=6;$x=3;$Header='-1';
	for($i=0;$i<count($columnList);$i++)	
	{
         
            $objPHPExcel->getActiveSheet()
            						->SetCellValue(getLatter($x).$j, $columnList[$i]); 
            $objPHPExcel->getActiveSheet()->getStyle(getLatter($x).$j)->getFont()->setBold(true);
            $objPHPExcel -> getActiveSheet() -> getColumnDimension(getLatter($x)) -> setWidth(12);
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);							
            $objPHPExcel -> getActiveSheet() -> mergeCells(getLatter($x).$j. ':'.getLatter($x+2).$j);
             
            $x++;
         
	}	
    
    $index = 0;
    $x=3;	$j=7;
	for ($i=0; $i<count($columnList); $i++) {
	   $index++;
		if($index == 1){ 
		  
            $objPHPExcel->getActiveSheet()	->SetCellValue(getLatter($x).$j, 'AMC')	;
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j) -> applyFromArray($styleThinBlackBorderOutline);
		    $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j)->getFont()->setBold(true);
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }else if($index == 2){
            
            $objPHPExcel->getActiveSheet()	->SetCellValue(getLatter($x).$j, 'SOH')	;
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j)->getFont()->setBold(true);
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             
		}else if($index == 3){
			 
            $objPHPExcel->getActiveSheet()	->SetCellValue(getLatter($x).$j, $gTEXT['MOS'])	;
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j)->getFont()->setBold(true);
            $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x). $j . ':' .getLatter($x). $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }
		if($index == 3)
			$index = 0;  
        $x++;               
    } 
 									
	  $objPHPExcel -> getActiveSheet() -> getStyle('A7:A7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	  $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	  $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
      $objPHPExcel -> getActiveSheet() -> getStyle('A7:A7')->getFont()->setBold(true);
      $objPHPExcel -> getActiveSheet() -> getStyle('B7:B7')->getFont()->setBold(true);
 	

    $j=8;
	for ($x = 0; $x < count($aData); $x++) { 

  		    $k=1;          
            for($y = 0; $y < count($aData[$x]); $y++) {
            	$l= getLatter($k);	 
				
				 
				
                 if($y == 0){
                	  $objPHPExcel->getActiveSheet()->SetCellValue($l.$j, $aData[$x][$y]);
                      $objPHPExcel -> getActiveSheet() -> getStyle($l.$j  . ':'.$l.$j) -> applyFromArray($styleThinBlackBorderOutline);
 					 
                }else{
                    $objPHPExcel->getActiveSheet()->SetCellValue($l.$j,crnl2br($aData[$x][$y]));
				    $objPHPExcel -> getActiveSheet() -> getStyle($l.$j  . ':'.$l.$j) -> applyFromArray($styleThinBlackBorderOutline);
				   } 
				
				
				 	if($l=='A')
					$objPHPExcel -> getActiveSheet() -> getStyle($l . $j . ':'.$l . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);          
					else if($l=='B')
					$objPHPExcel -> getActiveSheet() -> getStyle($l . $j . ':'.$l . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);          
					else $objPHPExcel -> getActiveSheet() -> getStyle($l . $j . ':'.$l . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);          
					
				 
				
				
				
				$k++;                                                                          
            } 
            
			 
			$j++; 	         
        }
  

 
 
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.getLatter($k-1).'2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:'.getLatter($k-1).'3');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A4:'.getLatter($k-1).'4');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A5:'.getLatter($k-1).'5');
	
 	



		


      

	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Stock_Status_at_Different_Levels_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	 }else{
        echo 'No record found .';
    }






?>