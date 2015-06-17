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
	 
   $monthId=$_GET['MonthId']; 
  $year=$_GET['Year']; 
  $countryId=$_GET['CountryId'];
  $itemGroupId=$_GET['ItemGroupId'];
  $CountryName=$_GET['CountryName'];   
  $MonthName = $_GET['MonthName'];
  $ItemGroupName = $_GET['ItemGroupName'];
   function numberToMonth($i) {
	$i=trim($i);
	if ($i == 1)
		return "Jan ";
	else if ($i == 2)
		return "Feb";
  	else if ($i == 3)
		return "Mar ";
   	else if ($i == 4)
		return "Apr ";
	else if ($i == 5)
		return "May ";
   	else if ($i == 6)
		return "Jun ";
	else if ($i == 7)
		return "Jul ";
	else if ($i == 8)
		return "Aug ";
		else if ($i == 9)
		return "Sep ";
		else if ($i == 10)
		return "Oct ";
		else if ($i == 11)
		return "Nov ";
		else if ($i == 12)
		return "Dec ";
			
		
}
   $sWhere = "";
	if ($_GET['sSearch'] != "") {
		$sWhere = " WHERE (a.ItemName LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
        OR " . " a.AMC LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
        OR " . " a.ClStock LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
        OR " . " a.MOS LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'
        OR " . " b.Qty LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' 
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
    
	$currentYearMonth = $_GET['Year'] . "-" . $_GET['MonthId'] . "-" . "01";
	
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	
    
  
	 $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Number of Patients by Product']);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	 $objPHPExcel -> getActiveSheet() -> mergeCells('A2:I2');
	 
	    $objPHPExcel->getActiveSheet()->SetCellValue('A3',('Country Name : '. $CountryName). ' , '.('Product Group : '. $ItemGroupName) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:I3');
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A4',('Month : '. $MonthName). ' , '.('Year : '. $year) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A4:I4');
	 
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A6', '#SL')		
									->SetCellValue('B6',$gTEXT['Products'])
									->SetCellValue('C6',$gTEXT['Total Patients'])
									->SetCellValue('D6',$gTEXT['Available Stock'])
									->SetCellValue('E6',$gTEXT['MOS(Available)'])
									->SetCellValue('F6',$gTEXT['Stock on Order'])
									->SetCellValue('G6',$gTEXT['MOS(Ordered)'])
									->SetCellValue('H6',$gTEXT['Total MOS'])
									->SetCellValue('I6',$gTEXT['Projected Date']);
	// $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),A6);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I6');
	
	$objPHPExcel -> getActiveSheet() -> getStyle('B6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('H6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('I6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						
	 $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> applyFromArray($styleThinBlackBorderOutline);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(5);							
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(50);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(17);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(17);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(17);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(15);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(15);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(30);
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
		  $objPHPExcel -> getActiveSheet() -> getStyle('I6'  . ':I6') -> applyFromArray($styleThinBlackBorderOutline);	
		         
		    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);
					
$sQuery =" SELECT ItemName, AMC, ClStock, FORMAT(MOS,1) MOS, Qty StockOnOrder, FORMAT(Qty/AMC,1) StockOnOrderMOS, (ifnull(FORMAT(MOS,1),0)+ifnull(FORMAT(Qty/AMC,1),0)) TotalMOS
				,a.ItemNo,TotalPatient
				 FROM 
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
				       AND t_cnm_masterstockstatus.MonthId =$monthId
				       AND t_cnm_masterstockstatus.CountryId = $countryId
				       AND t_cnm_masterstockstatus.ItemGroupId =$itemGroupId
				       AND t_cnm_masterstockstatus.StatusId = 5)
				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
				LEFT JOIN (SELECT
				    CountryId
				    , ItemNo
				    , SUM(Qty) Qty
				FROM
				    t_agencyshipment
				WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  AND ShipmentStatusId = 3)
				GROUP BY CountryId, ItemNo) b
				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
				LEFT JOIN (SELECT t_cnm_regimenpatient.CountryId,ItemNo,sum(TotalPatient) as TotalPatient
				from t_cnm_regimenpatient
				Inner Join t_regimenitems ON t_cnm_regimenpatient.RegimenId=t_regimenitems.RegimenId
				Group By t_cnm_regimenpatient.CountryId,ItemNo) c ON a.CountryId = c.CountryId AND a.ItemNo = c.ItemNo
				 ".$sWhere."
				HAVING MOS>0 OR StockOnOrderMOS>0 
				 $sOrder
                $sLimit order by ItemNo,ItemName"; 
	
                
                 
	$rResult = mysql_query($sQuery);
	 $i=1;  $j=7; 
	if ($rResult)	
	while ($row = mysql_fetch_array($rResult)) {
		                 $addmonth=number_format($row['TotalMOS']);  							  
					     $currentYearMonth = $year . "-" . $monthId . "-" . "01";			
						 $lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "$addmonth month"));
						 
						 $temp=explode('-',$lastYearMonth);
						 $strMonth=numberToMonth($temp[1]);
						 $lastYearMonth=$strMonth.'  , '.$temp[0];
	 		    	  		                       
			   
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $row['ItemName'])								
									->SetCellValue('C'.$j, $row['TotalPatient']==''? '':number_format($row['TotalPatient']))							
									->SetCellValue('D'.$j, $row['ClStock']==''? '':number_format($row['ClStock']))					
									->SetCellValue('E'.$j, $row['MOS']==''? '':number_format($row['MOS'],1))
									->SetCellValue('F'.$j, $row['StockOnOrder']==''? '0':number_format($row['StockOnOrder']))
									->SetCellValue('G'.$j, $row['StockOnOrderMOS']==''? '0.0':number_format($row['StockOnOrderMOS'],1))
									->SetCellValue('H'.$j, $row['TotalMOS']==''? '':number_format($row['TotalMOS'],1))
									->SetCellValue('I'.$j, $lastYearMonth)													
									;
	     $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		 $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	     $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		 $objPHPExcel -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	   
	$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
                 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> applyFromArray($styleThinBlackBorderOutline);
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
	$file = 'Number_of_Patients_by_Product_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	


?>