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
$itemGroupName = $_GET['ItemGroupName'];

   $sWhere = "where t_itemgroup.ItemGroupId = ".$itemGroupId." OR ".$itemGroupId."= 0";
    
	if ($_GET['curSearch'] != "") {
		 
		$sSearch=str_replace("|","#", $_GET['curSearch']);
		$sWhere = " AND  (ParamName LIKE '%" . mysql_real_escape_string($sSearch) . "%' OR
                        ParamNameFrench LIKE '%" . mysql_real_escape_string($sSearch) . "%'
                        OR GroupName LIKE '%".mysql_real_escape_string( $sSearch)."%') ";
	}
	$sql="SELECT SQL_CALC_FOUND_ROWS ParamId, ParamName,ParamNameFrench, BShow,t_cprofileparams.ItemGroupId,GroupName
				FROM t_cprofileparams
				INNER JOIN t_itemgroup ON t_cprofileparams.ItemGroupId = t_itemgroup.ItemGroupId               
				$sWhere $sOrder $sLimit ";  
	
	mysql_query("SET character_set_results=utf8");
    $r= mysql_query($sql) ;
       
    $total = mysql_num_rows($r);
     
    if ($total>0){
         require('../lib/PHPExcel.php');	
	     $objPHPExcel = new PHPExcel();
		 $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Profile Parameter List'])	;
		 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');			
		 $objPHPExcel -> getActiveSheet() -> mergeCells('A2:D2');
		 
		 $objPHPExcel->getActiveSheet()->SetCellValue('A3',$gTEXT['Product Group'].': '.$itemGroupName);
		 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		 $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
		 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A3');			
		 $objPHPExcel -> getActiveSheet() -> mergeCells('A3:D3');	
														
	     $objPHPExcel->getActiveSheet()
									->SetCellValue('A6', 'SL#')							
									->SetCellValue('B6',$gTEXT['Product Group'])
									->SetCellValue('C6',$gTEXT['Parameter Name'])
									->SetCellValue('D6',$TEXT['Parameter Name (French)']);
									
		 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');	
		 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
		 
		 $objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 
	     $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		 $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
		 $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(45);
		 $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(45);
											
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
    	  $i=1; $j=7;	
	while($rec=mysql_fetch_array($r)){
		$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
 		$objPHPExcel->getActiveSheet()
								->SetCellValue('A'.$j, $i)							
								->SetCellValue('B'.$j, $rec['GroupName'])
								->SetCellValue('C'.$j, $rec['ParamName'])
								->SetCellValue('D'.$j, $rec['ParamNameFrench']);
								  							
		$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
		         $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 
			 $i++; $j++;
		}
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Profile_Parameter_List_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }


?>