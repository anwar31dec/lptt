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
    
	$sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " WHERE  (CountryCode LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                            OR " . " ISO3 LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " CenterLat LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
      	                    OR " . " CountryName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " CenterLong LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
							OR " . " ZoomLevel LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ) ";
							
	}
    $sql = "SELECT SQL_CALC_FOUND_ROWS CountryId, CountryCode, CountryName, CountryNameFrench, ISO3, NumCode, CenterLat, CenterLong, ZoomLevel, LevelType, StartMonth, StartYear
				FROM t_country
				$sWhere $sOrder $sLimit ; "; 
				 
	 mysql_query("SET character_set_results=utf8");     
  	$r = mysql_query($sql);   
    $total = mysql_num_rows($r);
     
    if ($total>0){
        
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
    		
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Country List'] )	;
        $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
    	
    	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');					
    	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:G2');	
    													
        $objPHPExcel->getActiveSheet()										
    								->SetCellValue('A6', 'SL#')							
    								->SetCellValue('B6', $gTEXT['Country Code'])
    								->SetCellValue('C6', $gTEXT['Country Name'])
									->SetCellValue('D6', $gTEXT['Country Name (French)'])								
    								->SetCellValue('E6', $gTEXT['Country Level'])						
    		  						->SetCellValue('F6', $gTEXT['Center'])
    								->SetCellValue('G6', $gTEXT['Zoom Level']);
                                    
    	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
    	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
    	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
    	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
    	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
    		
    	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    	$objPHPExcel -> getActiveSheet() -> getStyle('E6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    	$objPHPExcel -> getActiveSheet() -> getStyle('F6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('G6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    	
    	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
    	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
    	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
    	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(30);
    	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
    	$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(25);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(25);		
    										
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
     
        $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
        $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('E6'  . ':E6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('F6'  . ':F6') -> applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel -> getActiveSheet() -> getStyle('G6'  . ':G6') -> applyFromArray($styleThinBlackBorderOutline);
            	
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
    
    
        $YearMonth=$_GET['YearMonth'];
        $EndYearMonth=explode(' ',$YearMonth);
        $EndYearMonth=explode('-',$EndYearMonth[0]);
        $EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
    	
    	if($YearMonth){
    		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
    	}	
	    $i=1; $j=7;	$monthvar='';
   
	    while($rec=mysql_fetch_array($r)){
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
            $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
            $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
            $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
			$objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
            
            if($rec['StartMonth']==1) $monthvar='Jan';
            else if($rec['StartMonth']==2)$monthvar='Feb'; 
            else if($rec['StartMonth']==3)$monthvar='Mar'; 
            else if($rec['StartMonth']==4)$monthvar='April'; 
            else if($rec['StartMonth']==5)$monthvar='May'; 
            else if($rec['StartMonth']==6)$monthvar='June'; 
            else if($rec['StartMonth']==7)$monthvar='July'; 
            else if($rec['StartMonth']==8)$monthvar='August'; 
            else if($rec['StartMonth']==9)$monthvar='Sep'; 
            else if($rec['StartMonth']==10)$monthvar='October'; 
            else if($rec['StartMonth']==11)$monthvar='November'; 
            else  $monthvar='Dec';       
                                  
            if($rec['LevelType'] == 1)$LevelName = 'Facility Level'; 
        else $LevelName = 'National Level';
            
            $objPHPExcel->getActiveSheet()
                                        ->SetCellValue('A'.$j, $i)							
                                        ->SetCellValue('B'.$j, $rec['ISO3'])								
                                        ->SetCellValue('C'.$j, $rec['CountryName'])	
										 ->SetCellValue('D'.$j, $rec['CountryNameFrench'])									
                                        ->SetCellValue('E'.$j, $LevelName.' '.$rec[''])									
                                        ->SetCellValue('F'.$j, $rec['CenterLat'].' ,'.$rec['CenterLong'])											
                                        ->SetCellValue('G'.$j, $rec['ZoomLevel']);  			
            
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
            
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            
            $i++; $j++;				
		}
	 	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Country_List_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }else{
   	    echo 'No record found';
    }


?>