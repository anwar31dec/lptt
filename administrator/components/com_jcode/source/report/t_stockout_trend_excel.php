<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];

function getMonthsBtnTwoDate($firstDate, $lastDate) {
	$diff = abs(strtotime($lastDate) - strtotime($firstDate));
	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	return $months;
}
   
	 $CountryId = $_GET['CountryId'];
     $months = $_GET['MonthNumber'];
	 $StartMonthId = $_GET['StartMonthId'];
     $EndMonthId = $_GET['EndMonthId'];
     $StartYearId= $_GET['StartYearId'];
     $EndYearId= $_GET['EndYearId'];
	 $CountryName=$_GET['CountryName']; 
	 $MonthName=$_GET['MonthName'];
	  // $ownerTypeId = $_REQUEST['OwnerTypeId'];
    // $OwnerTypeName = $_REQUEST['OwnerTypeName']; 
	  $lan = $_GET['lan'];
	 if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
			$lblMOSTypeName='MOS Type Name';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
			$lblMOSTypeName='Type MSD Nom';
        } 
		
		
	if($_GET['MonthNumber'] != 0){
        $months = $_GET['MonthNumber'];
        $monthIndex = date("n");
        $yearIndex = date("Y");
            if ($monthIndex == 1){
            $monthIndex = 12;                
            $yearIndex = $yearIndex - 1;                
            }else{
            $monthIndex = $monthIndex - 1;
            
            $endDate = $yearIndex."-".$monthIndex."-"."01";    
            $startDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($endDate)) . "+".-($months-1)." month"));      
    }
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";    
        $endDate = $EndYearId."-".$EndMonthId."-"."01";    
        $months = getMonthsBtnTwoDate($startDate, $endDate)+1;          
        $monthIndex = $EndMonthId;
        $yearIndex = $EndYearId;   
    }   
    settype($yearIndex, "integer");    
    $month_name = array();
    $Tdetails = array();  
    $sumRiskCount = array();  
    $sumTR = 0;
        
   	for ($i = 1; $i <= $months; $i++){
	$sql = " SELECT v.MosTypeId, $mosTypeName MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, a.MOS,(SELECT MosTypeId FROM t_mostype x WHERE  a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
				     WHERE a.MOS IS NOT NULL AND a.MonthId = ".$monthIndex. " AND Year = ".$yearIndex." AND (CountryId = ".$CountryId." OR ".$CountryId." = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId"; 
        mysql_query("SET character_set_results=utf8");                  
        $result = mysql_query($sql);
        $total = mysql_num_rows($result); 
        $Pdetails = array();  
        
        if($total>0){      
    		while ($aRow = mysql_fetch_array($result)) {
                $Pdetails['MosTypeId'] = $aRow['MosTypeId'];
                $Pdetails['MonthIndex'] = $monthIndex;
                $Pdetails['MosTypeName'] = $aRow['MosTypeName'];
                $Pdetails['RiskCount'] = $aRow['RiskCount'];
                array_push($Tdetails, $Pdetails);  
       	    }
            $mn = date("M", mktime(0,0,0,$monthIndex,1,0));
            $mn = $mn." ".$yearIndex;
            array_push($month_name, $mn);  
        }                          
   	    $monthIndex--;
		if ($monthIndex == 0){
			$monthIndex = 12;   				
			$yearIndex = $yearIndex - 1;			
		}
    }
    $veryHighRisk = array();
    $highRisk = array();
    $mediumRisk = array();
    $lowRisk = array();
    $noRisk = array();
    $areaName = array();
    
    $rmonth_name = array_reverse($month_name);
    $RTdetails = array_reverse($Tdetails);
    
    foreach($RTdetails as $key => $value){
         $MosTypeId = $value['MosTypeId'];
         $MonthIndex = $value['MonthIndex'];
         $MosTypeName = $value['MosTypeName'];
         $RiskCount = $value['RiskCount'];  
         
         if($MosTypeId == 1){
            array_push($veryHighRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);  
         }else if($MosTypeId == 2){
            array_push($highRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);
         }else if($MosTypeId == 3){
            array_push($mediumRisk, $RiskCount);
            array_push($areaName, $MosTypeName); 
         }else if($MosTypeId == 4){
            array_push($lowRisk, $RiskCount);
            array_push($areaName, $MosTypeName); 
         }else if($MosTypeId == 5){
            array_push($noRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);
         }                               		            
    }      
    
    $vhr = array();
    $hr = array();
    $mr = array();
    $lr = array();
    $nr = array();
    
    for($i = 0; $i<count($veryHighRisk); $i++){                                     
        $sumOfRiskCount = $veryHighRisk[$i] + $highRisk[$i] + $mediumRisk[$i] + $lowRisk[$i] + $noRisk[$i];   
        if($sumOfRiskCount==0)$sumOfRiskCount = 1;   
        $newPercentVHR = number_format($veryHighRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentHR = number_format($highRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentMR = number_format($mediumRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentLR = number_format($lowRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentNR = number_format($noRisk[$i]*100/$sumOfRiskCount, 1);
        
        array_push($vhr, $newPercentVHR."%");
        array_push($hr, $newPercentHR."%");
        array_push($mr, $newPercentMR."%");
        array_push($lr, $newPercentLR."%");
        array_push($nr, $newPercentNR."%");
    }
    $unique = array_reverse(array_unique($areaName));     
    array_unshift($vhr, "1", $unique[0]);
    array_unshift($hr, "2", $unique[1]);
    array_unshift($mr, "3", $unique[2]);
    array_unshift($lr, "4", $unique[3]);
    array_unshift($nr, "5", $unique[4]);
   
  // $str = ',"COLUMNS":[{"sTitle": "SL", "sWidth":"5%"}, {"sTitle": "MOS Type Name", "sClass" : "PatientType"}, ';	
   $f=0;
	
	                      
	
	$i=1;	
   
	if($total>0){
	    require('../lib/PHPExcel.php');	
	    $objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Stockout Trend']);
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
		 
			
		$objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Country Name'].' : '. $CountryName). ' , '. ($gTEXT['Month'].' : '.  ' From '. date('M,Y',strtotime($startDate)).' to '.date('M,Y',strtotime($endDate))));
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	   
	    
	   									
	   
									
									
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('J') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('K') -> setWidth(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('L') -> setWidth(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('M') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('N') -> setWidth(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('O') -> setWidth(12);
	  
										
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
	    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
        $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
		
      
 		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
		
	    
		function getLatter($i)
		{
			if($i==0) return "A";
			else if($i==1) return "B";
			else if($i==2) return "C";
			else if($i==3) return "D";
			else if($i==4) return "E";
			else if($i==5) return "F";
			else if($i==6) return "G";
			else if($i==7) return "H";
			else if($i==8) return "I";
			else if($i==9) return "J";
			else if($i==10) return "K";
			else if($i==11) return "L";
			else if($i==12) return "M";
			else if($i==13) return "N";
			else if($i==14) return "O";
		}
      	$i=1;$j=6;$x=2;							
		 
	
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A6', 'SL')							
									->SetCellValue('B6', $gTEXT['MOS Type Name']);
	 $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	
		
		 		
	 
    foreach($rmonth_name as $mon){
        if($f++) $str.=', '; 
		$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $mon); 
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),getLatter($x) . $j); 
		
		
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								 							
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);
		 
		$x++;                    
    }
    
			
	
	 $j++;$x=0;
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	for($i=0;$i<count($vhr); $i++)
	{
		 
		$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $vhr[$i])							
									;
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j.':'.getLatter($x).$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);
	$x++;  
	}
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j.':B'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
    $j++;$x=0;
	 for($i=0;$i<count($hr); $i++)
	{
		 
		$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $hr[$i]); 
	    $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j.':'.getLatter($x).$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);
										 
	$x++;
	}
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j.':B'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$j++;$x=0;
	
	for($i=0;$i<count($mr); $i++)
	{
		 
		$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $mr[$i]);
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j.':'.getLatter($x).$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);						  
	$x++;
	}
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j.':B'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 

    $j++;$x=0;
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	for($i=0;$i<count($lr); $i++)
	{
		 
		$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $lr[$i]);
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j.':'.getLatter($x).$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);						   
    
	$x++;
	}
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j.':B'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
	$j++;$x=0;
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	for($i=0;$i<count($nr); $i++)
	{
		 
		
		$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $nr[$i]); 
	$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j.':'.getLatter($x).$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
    $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);						   
    								 
	$x++;
	}
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j.':B'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.getLatter($x-1).'2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:'.getLatter($x-1).'3');
	
      
		 
	 
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file ='Stockout_Trend_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }


?>