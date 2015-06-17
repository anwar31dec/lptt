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

	require('../lib/PHPExcel.php');	
	$objPHPExcel = new PHPExcel();   
	$ItemGroupId = $_GET['ItemGroupId']; 
	$ItemGroupName = $_GET['ItemGroupName'];  
	$CountryId = $_GET['CountryId'];
	$CountryName = $_GET['CountryName'];  
	$RegimenId = $_GET['RegimenId'];  

	if($RegimenId != ""){
		$RegimenId = "WHERE a.RegimenId = ".$RegimenId." ";
	}

    $query = "SELECT b.FormulationName, RegimenName, ItemName, PatientPercentage, OptionId
			FROM t_regimen a
            INNER JOIN t_formulation b ON a.FormulationId = b.FormulationId AND b.ItemGroupId = '".$ItemGroupId."'
            INNER JOIN t_regimenitems c ON a.RegimenId = c.RegimenId AND c.CountryId = '".$CountryId."'
            INNER JOIN t_itemlist e ON c.ItemNo = e.ItemNo 
            INNER JOIN t_country f ON c.CountryId = f.CountryId 
            ".$RegimenId. " order by b.FormulationName, RegimenName ";
			 
	$query2 = "SELECT b.FormulationName, RegimenName, ItemName, PatientPercentage, OptionId
			FROM t_regimen a
            INNER JOIN t_formulation b ON a.FormulationId = b.FormulationId AND b.ItemGroupId = '".$ItemGroupId."'
            INNER JOIN t_regimenitems c ON a.RegimenId = c.RegimenId AND c.CountryId = '".$CountryId."'
            INNER JOIN t_itemlist e ON c.ItemNo = e.ItemNo 
            INNER JOIN t_country f ON c.CountryId = f.CountryId 
            ".$RegimenId. " order by b.FormulationName, RegimenName ";
               
	$query3 = "SELECT b.FormulationName, RegimenName, ItemName, PatientPercentage, OptionId
			FROM t_regimen a
            INNER JOIN t_formulation b ON a.FormulationId = b.FormulationId AND b.ItemGroupId = '".$ItemGroupId."'
            INNER JOIN t_regimenitems c ON a.RegimenId = c.RegimenId AND c.CountryId = '".$CountryId."'
            INNER JOIN t_itemlist e ON c.ItemNo = e.ItemNo 
            INNER JOIN t_country f ON c.CountryId = f.CountryId 
            ".$RegimenId. " order by b.FormulationName, RegimenName "; 
            
    mysql_query("SET character_set_results=utf8");          
    $result = mysql_query($query);
    $nbrows = mysql_num_rows($result);  
    
    $result2 = mysql_query($query2);
    $nbrows2 = mysql_num_rows($result2);  
    
    $result3 = mysql_query($query3);
    $nbrows3 = mysql_num_rows($result3);   
    
    if($nbrows2>0){
        
         	while ($rec = mysql_fetch_object($result)) {
        
       	        $rec2 = mysql_fetch_object($result2);
                $rec3 = mysql_fetch_object($result3);
                $rec->BudgetAmount = $rec->BudgetAmount/100000; 
                $rec->service = $rec2->BudgetAmount/100000;
                $rec->work = $rec3->BudgetAmount/100000;

                //$rec->BudgetAmount=$rec->BudgetAmount==''?'-':$rec->BudgetAmount; 
                //$rec->service=$rec->service==''?'-':$rec->service;  
               // $rec->work=$rec->work==''?'-':$rec->work;   
                
                $arr[] = $rec;   
           }	
            $dataLength = count($arr);
            $data = $arr;
            $temp = "";  
            $kh = 7;
            $start = $rowcount = $k = $kh+1; 
    
   
    $objPHPExcel->getProperties()
				->setCreator("http://scmpbd.org")
				->setLastModifiedBy("http://scmpbd.org")
				->setTitle("Budget Table");
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
   // $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    
    $objPHPExcel -> getActiveSheet() -> SetCellValue('A'.$kh, 'Regimen Name') 
                                     -> SetCellValue('B'.$kh,'Combination') 
                                     -> SetCellValue('C'.$kh, 'Regimen Item Name')
                                     -> SetCellValue('D'.$kh, 'Percentage');
                                    // -> SetCellValue('E'.$kh, 'Works (in lakh) ');
    
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$kh) -> getFont() -> setBold(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$kh) -> getFont() -> setBold(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$kh) -> getFont() -> setBold(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('D'.$kh) -> getFont() -> setBold(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('E'.$kh) -> getFont() -> setBold(true);

	$styleColourandBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'), )), 'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array()), );
	$objPHPExcel -> getActiveSheet() -> getStyle('A'.$kh) -> applyFromArray($styleColourandBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('B'.$kh) -> applyFromArray($styleColourandBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C'.$kh) -> applyFromArray($styleColourandBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('D'.$kh) -> applyFromArray($styleColourandBorderOutline);
    //$objPHPExcel -> getActiveSheet() -> getStyle('E'.$kh) -> applyFromArray($styleColourandBorderOutline);
    
     $tempGroupId='';     
	for ($i = 0; $i < $dataLength; $i++) {
		
	$objPHPExcel -> getActiveSheet() -> getStyle('D7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		 if($tempGroupId!=$data[$i] ->FormulationName) 
		{
		 $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
		'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID,
		'color' => array('rgb'=>'DAEF62'),
				          )
		   );
	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$k.':D'.$k);	
	$objPHPExcel->getActiveSheet()
			                     	->SetCellValue('A'.$k, $data[$i] ->FormulationName); 
					
	$objPHPExcel -> getActiveSheet() -> getStyle('A' . $k . ':D' . $k) -> applyFromArray($styleThinBlackBorderOutline1);
	$tempGroupId=$data[$i] ->FormulationName;
	$k++;
	$rowcount++;
	$start++;
		}
		
      	 
	$objPHPExcel -> getActiveSheet() //-> SetCellValue('B' . $k, $data[$i] -> Combination.'OptionId') 
                                 -> SetCellValue('C' . $k, $data[$i] ->ItemName) 
                                 -> SetCellValue('D' . $k, $data[$i] -> PatientPercentage.'%') ;
                                 //-> SetCellValue('E' . $k, number_format($data[$i] -> work)); 
                                 
	$rowcount++;
        
	$objPHPExcel -> getActiveSheet() -> getStyle('B' . $k) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('C' . $k) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('D' . $k) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	//$objPHPExcel -> getActiveSheet() -> getStyle('E' . $k) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('C' . $k) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D' . $k) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	//$objPHPExcel -> getActiveSheet() -> getStyle('E' . $k) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '9', 'bold' => false)), 'A' . $k);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '9', 'bold' => false)), 'B' . $k);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '9', 'bold' => false)), 'C' . $k);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '9', 'bold' => false)), 'D' . $k);
	///$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '9', 'bold' => false)), 'E' . $k);
	
	$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'), )));
	
	//$objPHPExcel -> getActiveSheet() -> getStyle('B' . $k . ':B' . $k) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C' . $k . ':C' . $k) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('D' . $k . ':D' . $k) -> applyFromArray($styleThinBlackBorderOutline);
	//$objPHPExcel -> getActiveSheet() -> getStyle('E' . $k . ':E' . $k) -> applyFromArray($styleThinBlackBorderOutline);
	
if ($data[$i] -> RegimenName != $data[$i+1] -> RegimenName) {

	$styleColourandBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'), )), 'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array()), );
	$objPHPExcel -> getActiveSheet() -> getStyle('A' . $start . ':A' . ($rowcount-1)) -> applyFromArray($styleColourandBorderOutline);
	$objPHPExcel -> getActiveSheet() -> mergeCells('A' . $start . ':A' . ($rowcount-1));
	$objPHPExcel -> getActiveSheet() -> SetCellValue('A' . $start , $data[$i] -> RegimenName);
    $objPHPExcel -> getActiveSheet() -> getStyle('A' . $start) -> getFont() -> setBold(true);
    $objPHPExcel -> getActiveSheet() -> getStyle('A' . $start) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel -> getActiveSheet() -> getStyle('A' . $start) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$styleColourandBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000'), )), 'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array()), );
	$objPHPExcel -> getActiveSheet() -> getStyle('B' . $start . ':B' . ($rowcount-1)) -> applyFromArray($styleColourandBorderOutline);
	$objPHPExcel -> getActiveSheet() -> mergeCells('B' . $start . ':B' . ($rowcount-1));
	$objPHPExcel -> getActiveSheet() -> SetCellValue('B' . $start , 'Combination-'.$data[$i] -> OptionId);
    $objPHPExcel -> getActiveSheet() -> getStyle('B' . $start) -> getFont() -> setBold(true);
    $objPHPExcel -> getActiveSheet() -> getStyle('B' . $start) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel -> getActiveSheet() -> getStyle('B' . $start) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $start = $rowcount;
}        
$k++;     
    }

	$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getFont() -> setBold(true);
	$objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getFont() -> setBold(true);

	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:D2');
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	//$objPHPExcel -> getActiveSheet() -> getHeaderFooter() -> setOddHeader('&RPrinted on &D');
	//$objPHPExcel -> getActiveSheet() -> getHeaderFooter() -> setOddFooter('&L&B' . $objPHPExcel -> getProperties() -> getCreator() . '&RPage &P of &N');

	$objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Regimen Item List']. ' of '.($CountryName));

	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '15', 'bold' => true)), 'A2');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12.5', 'bold' => true)), 'A3');
    
   if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Regimen_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
    }else{
   	    echo 'No record found';
    }
 
    

?>