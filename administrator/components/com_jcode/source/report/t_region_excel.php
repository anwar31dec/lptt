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
$CountryId=$_GET['CountryId'];
$CountryName=$_GET['CountryName'];

    if($CountryId){
             $CountryId = " WHERE a.CountryId = '".$CountryId."' ";}

    if ($_GET['curSearch'] != "") {
		$sWhere = " and (RegionName LIKE '%" . mysql_real_escape_string($_GET['curSearch']) . "%'
                    OR CountryName LIKE '%".mysql_real_escape_string( $_GET['curSearch'] )."%') ";
     }
	  
	$sql = "SELECT RegionId, RegionName, a.CountryId, CountryName
				FROM t_region a
                INNER JOIN t_country b ON a.CountryId = b.CountryId ".$CountryId."".$sWhere." "; 
	mysql_query("SET character_set_results=utf8");	 
	$r = mysql_query($sql) ;
    $total = mysql_num_rows($r);
    if ($total>0){
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
	
        $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Region List'])	;
        $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
        $objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A3',$gTEXT['Country Name'].': '.$CountryName)	;
        $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A3');	
        $objPHPExcel -> getActiveSheet() -> mergeCells('A3:B3');
													
        $objPHPExcel->getActiveSheet()										
									->SetCellValue('A6', 'SL#')							
									->SetCellValue('B6', $gTEXT['Region Name']);
                                    
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');

		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
		
		$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
		 
		$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
		$objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
		 
		$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
				
  	    $i=1; $j=7;	
        
        while($rec=mysql_fetch_array($r)){
            
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
		    
            if($tempGroupId!=$rec['CountryName']) {
                $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
                'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb'=>'DAEF62'),
                ));
    		
    	   	    $objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':B'.$j);	
    		    $objPHPExcel->getActiveSheet() ->SetCellValue('A'.$j, $rec['CountryName']);
    	        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
                $j++;
            }
           	$tempGroupId=$rec['CountryName'];            
            
            $objPHPExcel->getActiveSheet()
				            		->SetCellValue('A'.$j, $i)							
				            		->SetCellValue('B'.$j, $rec['RegionName']);
				                      
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
                    			
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
            
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
            $i++; $j++;
      }
      
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Region_List_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
    }else{
   	    echo 'No record found';
    }


?>