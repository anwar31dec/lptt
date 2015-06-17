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
$CountryId = $_GET['CountryId'];
$CountryName = $_GET['CountryName'];

$CountryId = '';
if (isset($_GET['CountryId'])) {
	$CountryId = $_GET['CountryId'];
} else if (isset($_GET['CountryId'])) {
	$CountryId = $_GET['CountryId'];
}

$sWhere = "";
	
    if($CountryId){
		$CountryId = " WHERE a.CountryId = '".$CountryId."' ";
	}
	else
		$CountryId = " WHERE 1=1 ";
    
	if ($_GET['curSearch'] != "") {
		$sWhere = " and (GroupName LIKE '%" . mysql_real_escape_string($_GET['curSearch']) . "%'
                    OR CountryName LIKE '%".mysql_real_escape_string( $_GET['curSearch'] )."%') ";
    }
	
	$sLimit = "";
	if (isset($_GET['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}
	
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_rptfrequency(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
		
	  
		$sql = "SELECT a.RepFreqId,a.CountryId,a.ItemGroupId,a.FrequencyId
		,a.StartMonthId,a.StartYearId,b.CountryName,c.GroupName,d.FrequencyName
		,case a.FrequencyId when 1 then e.MonthName
		else f.MonthName end MonthName,a.StartYearId YearName
		FROM t_reporting_frequency a
		Inner Join t_country b ON a.CountryId=b.CountryId
		Inner Join t_itemgroup c ON a.ItemGroupId=c.ItemGroupId
		Inner Join t_frequency d ON a.FrequencyId=d.FrequencyId 		
		Left Join t_month e ON a.StartMonthId=e.MonthId 
		Left Join t_quarter f ON a.StartMonthId=f.MonthId ".$CountryId."
		$sWhere $sOrder $sLimit "; 
		 mysql_query("SET character_set_results=utf8");     
		$r = mysql_query($sql);  		
        $total = mysql_num_rows($r);
		
    if ($total>0){
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
	
        $objPHPExcel->getActiveSheet()->SetCellValue('C2',$gTEXT['Reporting Frequency List']);
        $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel -> getActiveSheet() -> getStyle('C2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'C2');	
        $objPHPExcel -> getActiveSheet() -> mergeCells('C2:F2');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('C3',$gTEXT['Country Name'].': '.$CountryName);
        $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel -> getActiveSheet() -> getStyle('C3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C3');	
        $objPHPExcel -> getActiveSheet() -> mergeCells('C3:F3');		
													
        $objPHPExcel->getActiveSheet()										
									->SetCellValue('A6', 'SL#')							
									->SetCellValue('B6', $gTEXT['Country Name'])
									->SetCellValue('C6', $gTEXT['Product Group'])
									->SetCellValue('D6', $gTEXT['Frequency Name'])
									->SetCellValue('E6', $gTEXT['Start Year'])
									->SetCellValue('F6', $gTEXT['Start Month']);

		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');

		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(25);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(25);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(25);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(25);
		
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
        
        while($rec=mysql_fetch_array($r)){
            
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
		    
           // if($tempGroupId!=$rec['CountryName']) {
            //    $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
           //     'fill' => array(
           //     'type' => PHPExcel_Style_Fill::FILL_SOLID,
          //      'color' => array('rgb'=>'DAEF62'),
          //      ));
    		
    	  // 	    $objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':B'.$j);	
    	//	    $objPHPExcel->getActiveSheet() ->SetCellValue('A'.$j, $rec['CountryName']);
    	  //      $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
          //      $j++;
          //  }
           	$tempGroupId=$rec['CountryName'];            
            
            $objPHPExcel->getActiveSheet()
				            		->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['CountryName'])
									->SetCellValue('C'.$j, $rec['GroupName'])
									->SetCellValue('D'.$j, $rec['FrequencyName'])
									->SetCellValue('E'.$j, $rec['YearName'])
									->SetCellValue('F'.$j, $rec['MonthName']);
				                      
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
			$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    			
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
            
				$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
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
	$file = 'Frequency_List_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
    }else{
   	    echo 'No record found';
    }


?>