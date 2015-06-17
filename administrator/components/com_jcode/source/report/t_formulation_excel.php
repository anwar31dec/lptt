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
 $ItemGroupName = $_GET['ItemGroupName'];
   $condition='';
	$sWhere = "";
       if($itemGroupId!=0){
    	$sWhere=' WHERE ';     
		$condition.=" a.ItemGroupId = '".$itemGroupId."' "; 
	}
	$sLimit = "";
	if (isset($_GET['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_formulation(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	
	if ($_GET['sSearch'] != "") {
		
		if($sWhere=='') $sWhere=" WHERE ";
		 else $condition.=" and "; 
		 
		$condition.= "   (FormulationName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                    OR ServiceTypeName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
                    OR GroupName LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%'
                    OR ColorCode LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%') ";
                    
    }

	$sql = "SELECT SQL_CALC_FOUND_ROWS FormulationId, FormulationName,FormulationNameFrench, a.ItemGroupId, GroupName, a.ServiceTypeId, ServiceTypeName, ColorCode
				FROM t_formulation a
                INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                INNER JOIN t_servicetype c ON a.ServiceTypeId = c.ServiceTypeId
                $sWhere $condition $sOrder $sLimit "; 
     mysql_query("SET character_set_results=utf8");     
	$r= mysql_query($sql);   
	$total = mysql_num_rows($r);
	 
	if ($total>0){
		        
			require('../lib/PHPExcel.php');	
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Formulation List'] )	;
			$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
			$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
			$objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Product Group'].' :  '. $ItemGroupName));
			$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
			$objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A3');	
			$objPHPExcel -> getActiveSheet() -> mergeCells('A3:F3');	
				
			$objPHPExcel->getActiveSheet()
										->SetCellValue('A6', 'SL#')							
							            ->SetCellValue('B6',$gTEXT['Formulation Type'])
							            ->SetCellValue('C6',$gTEXT['Formulation Type (French)'])
										->SetCellValue('D6',$gTEXT['Item Group'])
										->SetCellValue('E6',$gTEXT['Service Type'])
										->SetCellValue('F6',$gTEXT['Color Code']);
							
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
            $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');	
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
            
			$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(35);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(50);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(10);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(15);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(15);

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
	 	 if($tempGroupId!=$rec['GroupName']) 
	     {
	     $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
					'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'DAEF62'),
			          )
	           );
		
	   	 $objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':F'.$j);	
		 $objPHPExcel->getActiveSheet()
								->SetCellValue('A'.$j, $rec['GroupName']); 
								 
		 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
	   	 $tempGroupId=$rec['GroupName'];$j++;
	     }
	     $objPHPExcel->getActiveSheet()
								->SetCellValue('A'.$j, $i)							
								->SetCellValue('B'.$j, $rec['FormulationName'])	
								->SetCellValue('C'.$j, $rec['FormulationNameFrench'])
								->SetCellValue('D'.$j, $rec['GroupName'])
								->SetCellValue('E'.$j, $rec['ServiceTypeName']);
		 $c=explode('#', $rec['ColorCode']);
		 $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>$c[1]),
				          )
		           );  			
		 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
         $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
		 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
	     $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		 $i++; $j++;
		 
				
		}
	 
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Formulation_Type_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }


?>