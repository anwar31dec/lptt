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
 	 $ItemGroupId = $_GET['ItemGroupId'];
	 $ItemGroupName = $_GET['ItemGroupName'];
     $AGenderTypeId = $_GET['AGenderTypeId'];
	 $GenderTypeName=$_GET['GenderTypeName'];
	 
	 $sWhere = "";
	 $condition='';
	 if($AGenderTypeId)
	 {
	 	$sWhere=' WHERE ';     
		$condition.=" a.GenderTypeId = '".$AGenderTypeId."' ";
	 }
    
     
	
	if($ItemGroupId){
		
		if($sWhere=='') $sWhere=" WHERE ";
		else $condition.=" and "; 
		$condition.="  a.ItemGroupId = '".$ItemGroupId."' "; 
		 
	} 

	$sLimit = "";
	if (isset($_GET['iDisplayStart'])) { $sLimit = " LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}

	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_getRegimenMasterData(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}

	
	if ($_GET['sSearch'] != "") {
			if($sWhere=='') $sWhere=" WHERE ";
		   else $condition.=" and "; 
		 
		//$condition.= "   (GroupName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') ";
		$condition.= "   (RegimenName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') ";
		//$condition.= "   (GenderType LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%') "; 
		
	}
	 

	$sql = "SELECT  RegMasterId,RegimenName, a.GenderTypeId,GenderType,a.ItemGroupId,GroupName
				FROM t_regimen_master a INNER JOIN  t_itemgroup b ON a.ItemGroupId=b.ItemGroupId
				INNER JOIN t_gendertype c ON a.GenderTypeId = c.GenderTypeId
				 $sWhere  ".$condition."							
                $sOrder
                $sLimit";
	 
	 
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);        
	 if ($total>0){
        require('../lib/PHPExcel.php');	
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Patient Type Master List'] )	;
		$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
		$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A3',('Product Group : '. $ItemGroupName). ' , '.('Gender Type : '. $GenderTypeName) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:D3');
	    	
					
		$objPHPExcel -> getActiveSheet() -> mergeCells('A2:D2');	
														
	    $objPHPExcel->getActiveSheet()
								->SetCellValue('A6', 'SL#')							
								->SetCellValue('B6',$gTEXT['Group Name'])
								->SetCellValue('C6',$gTEXT['Regimen Name'])
								->SetCellValue('D6',$gTEXT['Gender Type'])
								;
								
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');	
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
		
	    $objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(15);
		
									
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
       while($rec=mysql_fetch_array($r))
	      {
	      	 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	         $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		     $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['GroupName'])
									->SetCellValue('C'.$j, $rec['RegimenName'])		
									->SetCellValue('D'.$j, $rec['GenderType'])										
									; 
			
  $objPHPExcel -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
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
	$file = 'Regimen_Master_List_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);

    }
    else{
   	    echo 'No record found';
    }


?>