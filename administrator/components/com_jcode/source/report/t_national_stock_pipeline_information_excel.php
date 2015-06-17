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
	
  
	 
  $monthId=$_GET['MonthId']; 
  $year=$_GET['YearId']; 
  $countryId=$_GET['CountryId'];
  $itemGroupId=$_GET['ItemGroupId'];
  $CountryName=$_GET['CountryName'];   
  $MonthName = $_GET['MonthName'];
  $ItemGroupName = $_GET['ItemGroupName'];
  
   $sWhere = "";
	if ($_GET['sSearch'] != "") {
		 $sSearch=str_replace("|","+", $_GET['sSearch']);
		$sWhere = " WHERE (a.ItemName LIKE '%" . mysql_real_escape_string($sSearch) . "%'
        OR " . " a.AMC LIKE '%" . mysql_real_escape_string($sSearch) . "%'
        OR " . " a.ClStock LIKE '%" . mysql_real_escape_string($sSearch) . "%' 
        OR " . " a.MOS LIKE '%" . mysql_real_escape_string($sSearch) . "%'
        OR " . " b.Qty LIKE '%" . mysql_real_escape_string($sSearch) . "%' 
        )";							
	}
        
    $sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}
    $sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_Item(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
    
	$currentYearMonth = $_GET['YearId'] . "-" . $_GET['MonthId'] . "-" . "01";
	
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	
    
    
	
	$sQuery = "SELECT ItemName, IFNULL(AMC,0) AMC, IFNULL(ClStock,0) ClStock, IFNULL(MOS,0) MOS, IFNULL(Qty,0) StockOnOrder FROM 
				(SELECT
				 t_cnm_masterstockstatus.CountryId,
				  t_itemlist.ItemNo,
				  t_itemlist.ItemName,
				  SUM(t_cnm_stockstatus.AMC)    AMC,
				  SUM(t_cnm_stockstatus.ClStock)    ClStock,
				  SUM(t_cnm_stockstatus.MOS)    MOS
				FROM t_cnm_stockstatus
				  INNER JOIN t_cnm_masterstockstatus
				    ON (t_cnm_stockstatus.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
				  INNER JOIN t_itemlist
				    ON (t_cnm_stockstatus.ItemNo = t_itemlist.ItemNo)
				WHERE (t_cnm_masterstockstatus.Year = '$year'
				       AND t_cnm_masterstockstatus.MonthId = $monthId
				       AND t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId
				       AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
				LEFT JOIN (SELECT
				    CountryId
				    , ItemNo
				    , SUM(Qty) Qty
				FROM
				    t_agencyshipment
				WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  AND ShipmentStatusId = 2)
				GROUP BY CountryId, ItemNo) b
				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
                ".$sWhere."
				HAVING AMC>0 OR MOS>0 OR ClStock>0 OR StockOnOrder>0
                 order by ItemName
                $sLimit";
         // echo  $sQuery;    
     mysql_query("SET character_set_results=utf8");             
	$rResult = mysql_query($sQuery);
	$total = mysql_num_rows($rResult);
    $i=1;  $j=7; 
	if($total>0){	
     require('../lib/PHPExcel.php');	
	 $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['National Stock Pipeline Information List']);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	 $objPHPExcel -> getActiveSheet() -> mergeCells('A2:H2');
	 
    $objPHPExcel->getActiveSheet()->SetCellValue('A3',($gTEXT['Country Name'].': '. $CountryName). ' , '.($gTEXT['Product Group'].': '. $ItemGroupName));
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:H3');
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A4',($gTEXT['Month'].': '. $MonthName). ' , '.($gTEXT['Year'].': '. $year));
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A4:H4');
	 
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A6', 'SL#')		
									->SetCellValue('B6',$gTEXT['Products'])
									->SetCellValue('C6',$gTEXT['AMC'])
									->SetCellValue('D6',$gTEXT['Available Stock'])
									->SetCellValue('E6',$gTEXT['MOS(Available)'])
									->SetCellValue('F6',$gTEXT['Stock on Order'])
									->SetCellValue('G6',$gTEXT['MOS(pipeline)'])
									->SetCellValue('H6',$gTEXT['Total MOS']);
	// $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),A6);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
	
	$objPHPExcel -> getActiveSheet() -> getStyle('B6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('H6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						
	 $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> applyFromArray($styleThinBlackBorderOutline);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(5);							
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(50);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(17);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(17);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(17);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(15);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(15);
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('E6'  . ':E6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('F6'  . ':F6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('G6'  . ':G6') -> applyFromArray($styleThinBlackBorderOutline);	
		  $objPHPExcel -> getActiveSheet() -> getStyle('H6'  . ':H6') -> applyFromArray($styleThinBlackBorderOutline);	
		         
		    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
					
	
	while ($row = mysql_fetch_array($rResult)) {
		 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	   
	         $amc = ($row['AMC'] == 0? 1 : $row['AMC']);	
			 $stockOnOrderMOS =  $row['StockOnOrder'] / $amc;	
             $stockOnOrderMOS = $stockOnOrderMOS== 0? '' : number_format($stockOnOrderMOS,1);
             $totalMOS = number_format((number_format($row['MOS'],1) + $stockOnOrderMOS),1) ;
             $totalMOS = $totalMOS== 0? '' : $totalMOS;
	   
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $row['ItemName'])								
									->SetCellValue('C'.$j, $row['AMC']==''? '':number_format($row['AMC']))							
									->SetCellValue('D'.$j, $row['ClStock']==''? '':number_format($row['ClStock']))					
									->SetCellValue('E'.$j, $row['MOS']==''? '':number_format($row['MOS'],1))
									->SetCellValue('F'.$j, ($row['StockOnOrder']== 0? '' : $row['StockOnOrder']))
									->SetCellValue('G'.$j, $stockOnOrderMOS)
									->SetCellValue('H'.$j, $totalMOS)												
									;
	       
	$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
                 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline);
		 $i++;	
	     $j++;	 
		 
	}


	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'National_Stock_Pipeline_Information_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
  }	else{
			echo "No record found.";	
			
	}

?>