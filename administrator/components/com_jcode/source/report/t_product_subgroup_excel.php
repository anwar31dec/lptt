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
	if ($_GET['curSearch'] != "") {
		$sWhere = " WHERE (ProductSubGroupName LIKE '%" . mysql_real_escape_string($_GET['curSearch']) . "%'
                    OR GroupName LIKE '%".mysql_real_escape_string( $_GET['curSearch'] )."%') ";
    }

    $sql="SELECT  ProductSubGroupId, ProductSubGroupName, a.ItemGroupId, GroupName 
				FROM t_product_subgroup a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId ".$sWhere."order by GroupName  ";
	mysql_query("SET character_set_results=utf8");      
  	$r= mysql_query($sql);   
    $total = mysql_num_rows($r);
     
    if ($total>0){
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Product Subgroup List'])	;
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
					
		$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
	    $objPHPExcel->getActiveSheet()
									->SetCellValue('A6', 'SL#')							
									->SetCellValue('B6', $gTEXT['Product Subgroup Name']);
									
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(30);
		
										
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
	    $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
        $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
	   
    	$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
		
		$i=1; $j=7;	
	  while($rec=mysql_fetch_array($r)){
     
	   $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      

	  if($tempGroupId!=$rec['GroupName']){		
              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
					'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'DAEF62'),
			          )
	           );
		//$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':B'.$j);	
		$objPHPExcel->getActiveSheet()
								->SetCellValue('A'.$j, $rec['GroupName']); 
		//$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
	  	$tempGroupId=$rec['GroupName'];$j++;
	    }
		  
		  
		  
    	$objPHPExcel->getActiveSheet()
								->SetCellValue('A'.$j, $i)							
								->SetCellValue('B'.$j, $rec['ProductSubGroupName']);  			
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
	$file = 'Product_Subgroup_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }


?>