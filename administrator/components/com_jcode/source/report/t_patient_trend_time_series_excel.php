<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];

 $StartMonthId = $_GET['StartMonthId']; 
    $StartYearId = $_GET['StartYearId']; 
    $EndMonthId = $_GET['EndMonthId']; 
    $EndYearId = $_GET['EndYearId'];    
	$countryId = $_GET['CountryId'];
	$itemGroupId = $_GET['ItemGroupId'];
    $months = $_GET['MonthNumber'];
	$CountryName=$_GET['CountryName']; 
	 $ItemGroupName=$_GET['ItemGroupName'];

  $frequencyId = 1;
	if($_GET['MonthNumber'] != 0){
        $months = $_GET['MonthNumber'];
        $monthIndex = date("m");
        $yearIndex = date("Y");
		 settype($yearIndex, "integer");    
		if ($monthIndex == 1){
			$monthIndex = 12;				
			$yearIndex = $yearIndex - 1;				
		}else{
			$monthIndex = $monthIndex - 1;
		}
		$months = $months - 1;  
			   
		$d=cal_days_in_month(CAL_GREGORIAN,$monthIndex,$yearIndex);
		$EndYearMonth = $yearIndex."-".str_pad($monthIndex,2,"0",STR_PAD_LEFT)."-".$d; 
		$EndYearMonth = date('Y-m-d', strtotime($EndYearMonth));	
		
		$StartYearMonth = $yearIndex."-".str_pad($monthIndex,2,"0",STR_PAD_LEFT)."-"."01"; 
		$StartYearMonth = date('Y-m-d', strtotime($StartYearMonth));	
		$StartYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartYearMonth)) . "-".$months." month"));
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";	
		$StartYearMonth = date('Y-m-d', strtotime($startDate));	
		
		$d=cal_days_in_month(CAL_GREGORIAN,$EndMonthId,$EndYearId);
    	$endDate = $EndYearId."-".$EndMonthId."-".$d;	
		$EndYearMonth = date('Y-m-d', strtotime($endDate));	    	
    }
	
	$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
	$quarterList = array(3 => 'Jan-Mar', 6 => 'Apr-Jun', 9 => 'Jul-Sep', 12 => 'Oct-Dec');
	$output = array('aaData' => array());
	$aData = array();
	$output2 = array();
		
		if($frequencyId == 1)
			$monthQuarterList = $monthListShort;
		else 
			$monthQuarterList = $quarterList;
 	
	$month_list = array();
	$startDate = strtotime($StartYearMonth);
	$endDate   = strtotime($EndYearMonth);
	$index=0;
	
	while ($endDate >= $startDate) {
			if($frequencyId == 1){
					$monthid=date('m',$startDate);
					settype($monthid,"integer");
					$ym= $monthListShort[$monthid].' '.date('Y',$startDate);				
					$month_list[$index] = $ym;
					$output['Categories'][] = $ym;	
					$index++;
					}				
				else{
					$monthid=date('m',$startDate);
					settype($monthid,"integer");
					if($monthid==3 || $monthid==6 || $monthid==9 || $monthid==12){
						$ym=$quarterList[$monthid].' '.date('Y',$startDate);
						$month_list[$index] = $ym;
						$output['Categories'][] = $ym;	
						$index++;
						}
					}				
		
	    $startDate = strtotime( date('Y/m/d',$startDate).' 1 month');
	}
	// //////////////////
 
	$lan = $_REQUEST['lan'];
	if($lan == 'en-GB'){
            $serviceTypeName = 'ServiceTypeName';
        }else{
            $serviceTypeName = 'ServiceTypeNameFrench';
        }     
	 
	$sQuery = "SELECT a.ServiceTypeId, IFNULL(SUM(c.TotalPatient),0) TotalPatient
			, $serviceTypeName ServiceTypeName, a.STL_Color,c.Year,c.MonthId
                FROM t_servicetype a
                INNER JOIN t_formulation b ON a.ServiceTypeId = b.ServiceTypeId
                Inner JOIN t_cnm_patientoverview c 	
					ON (c.FormulationId = b.FormulationId 
						and STR_TO_DATE(concat(year,'/',monthid,'/02'), '%Y/%m/%d') 
						between '".$StartYearMonth."' and '".$EndYearMonth."'
                		AND (c.CountryId = ".$countryId." OR ".$countryId." = 0)
						AND (c.ItemGroupId = ".$itemGroupId." OR ".$itemGroupId." = 0))  		                       
                GROUP BY a.ServiceTypeId, $serviceTypeName, a.STL_Color
				, c.Year, c.MonthId
				HAVING TotalPatient > 0
		        ORDER BY a.ServiceTypeId asc,c.Year asc, c.MonthId asc;";
				
    mysql_query("SET character_set_results=utf8");
	$rResult = mysql_query($sQuery);
	$total = mysql_num_rows($rResult);
	$tmpServiceTypeId = -1;
	$countServiceType = 1;
	$count = 1;
	$preServiceTypeName='';
	
	//if($total==0) return;
	
	while ($row = mysql_fetch_assoc($rResult)) {
		
		if(!is_null($row['TotalPatient']))	
			settype($row['TotalPatient'], "integer");

		if ($tmpServiceTypeId != $row['ServiceTypeId']) {
			
			if ($count > 1) {
				array_unshift($output2,$countServiceType,$preServiceTypeName);
								
				$aData[] = $output2;
				unset($output2);
				$countServiceType++;
			 }
			$count++;		
			
			$preServiceTypeName	=  $row['ServiceTypeName'];	
			$count = 0;
			while( $count < count($month_list)){					
				$output2[] = null;
				$count++;
			}

			$dataMonthYear = $monthQuarterList[$row['MonthId']].' '.$row['Year']; 
			$count = 0;
			while( $count < count($month_list)){
				if($month_list[$count] == $dataMonthYear){
					$output2[$count] = $row['TotalPatient'];
				}				
				$count++;
			}
			$tmpServiceTypeId = $row['ServiceTypeId'];
		} 
		else {
				$dataMonthYear = $monthQuarterList[$row['MonthId']].' '.$row['Year']; 
				$count = 0;
				while( $count < count($month_list)){
					if($month_list[$count] == $dataMonthYear){
						$output2[$count] = $row['TotalPatient'];
					}				
					$count++;
				}
			$tmpServiceTypeId = $row['ServiceTypeId'];
		}   
	}
	
	array_unshift($output2,$countServiceType,$preServiceTypeName);
	$aData[] = $output2;
	
	 
	 if($lan=='en-GB'){
        $TypeLang='Patient Type';
    }
    else{
        $TypeLang='Type de Patient';
    }
	
	 $str = ',"COLUMNS":[{"sTitle": "SL", "sWidth":"5%"}, {"sTitle": "'.$TypeLang.'", "sClass" : "' . 'PatientType' . '"}, ';	
    $f=0;
	
	
	

	$i=1;	
   
	if($total>0){
	    require('../lib/PHPExcel.php');	
	    $objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Patient Trend Time Series']);
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
		 
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Country Name'].' : '. $CountryName). ' , '. ($gTEXT['Month'].' : '.  ' From '. date('M,Y',strtotime($StartYearMonth)).' to '.date('M,Y',strtotime($EndYearMonth))));
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	   
	    
	   									
	   
									
									
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('J') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('K') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('L') -> setWidth(12);
	  
										
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
			else if($i==15) return "P";
		}
      	$i=1;$j=6;$x=2;							
		 
	
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A6', 'SL')							
									->SetCellValue('B6', $TypeLang);
	
									
	  $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	  $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	  $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
 	
	  
    foreach($month_list as $mon){
        if($f++) $str.=', ';
        $str.= '{"sTitle": "'.$mon.'", "sClass" : "MonthName"}';   
		$td.='<th>'.$mon.'</th>';    
		$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $mon); 
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),getLatter($x) . $j,$mon); 							
		$objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);							 
		$x++;                     
    }
	
    $j++;
    for($p=0;$p<count($aData);$p++)
	{
		
			$x=0; 
			for($i=0;$i<count($aData[$p]); $i++)
			{
				 
				
				$objPHPExcel->getActiveSheet()
									->SetCellValue(getLatter($x).$j, $aData[$p][$i]);
   $objPHPExcel -> getActiveSheet() -> getStyle(getLatter($x).$j. ':'.getLatter($x).$j) -> applyFromArray($styleThinBlackBorderOutline);
   $objPHPExcel -> getActiveSheet() -> getStyle('A'.$j.':A'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	          $x++;
			 }
			$j++;    
	}		 		
	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.getLatter($x-1).'2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:'.getLatter($x-1).'3');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A4:'.getLatter($x-1).'4');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A5:'.getLatter($x-1).'5');
	
      
		 
	 
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file ='Patient_Trend_Time_Series_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }

?>