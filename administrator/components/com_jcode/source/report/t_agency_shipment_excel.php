<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());
mysql_query('SET CHARACTER SET utf8');

$lan=$_REQUEST['lan']; 
if($lan == 'en-GB'){
	$SITETITLE = SITETITLEENG;
}else{
   $SITETITLE = SITETITLEFRN;
}

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl'];
    
	$CountryId=$_GET['ACountryId']; 
	$AFundingSourceId=$_GET['AFundingSourceId']; 
	$ASStatusId=$_GET['ASStatusId']; 
	
	$CountryName = $_GET['CountryName'];
	$FundingSourceName = $_GET['FundingSourceName'];
	$ShipmentStatusDesc = $_GET['ShipmentStatusDesc'];
    
	$ItemGroup = $_GET['ItemGroup']; 
    $OwnerTypeId = $_GET['OwnerType']; 
    $ItemGroupName = $_GET['ItemGroupName'];
    $OwnerTypeName = $_GET['OwnerTypeName'];
    $tempGroupId ='';
	
	
    if($CountryId){
		$CountryId = " WHERE a.CountryId = '".$CountryId."' ";
	}
    if($AFundingSourceId){
		$AFundingSourceId = " AND a.FundingSourceId = '".$AFundingSourceId."' ";
	}   
    if($ASStatusId){
		$ASStatusId = " AND a.ShipmentStatusId = '".$ASStatusId."' ";
	}
    if($ItemGroup){
		$ItemGroup = " AND a.ItemGroupId = '".$ItemGroup."' ";
	} 
    if($OwnerTypeId){
		$OwnerTypeId = " AND a.OwnerTypeId = '".$OwnerTypeId."' ";
	} 
	
	
	 $sWhere = "";
	if ($_GET['sSearch'] != "") {
		
		 $sSearch=str_replace("|","+", $_GET['sSearch']);
	 
		$sWhere = " AND (a.ShipmentDate LIKE '%" . mysql_real_escape_string($sSearch) . "%'  OR " .
				    "a.Qty LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
                    "GroupName LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
                    "ItemName LIKE '%".mysql_real_escape_string($sSearch)."%' OR ".
				    "ShipmentStatusDesc LIKE '%" . mysql_real_escape_string($sSearch) . "%' OR ".
				    "g.OwnerTypeName LIKE '%" . mysql_real_escape_string($sSearch) . "%') ";
	}
	    
	    
	
    $sql ="SELECT  AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty, f.GroupName,a.ItemGroupId,a.OwnerTypeId, g.OwnerTypeName 
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo 
			INNER JOIN t_itemgroup f ON a.ItemGroupId = f.ItemGroupId 
            INNER JOIN t_owner_type g ON a.OwnerTypeId = g.OwnerTypeId
            ".$CountryId." ".$AFundingSourceId." ".$ASStatusId." " .$ItemGroup." " .$OwnerTypeId."
			$sWhere ORDER BY ShipmentStatusDesc,ItemName ";	
			//FundingSourceName asc
	mysql_query("SET character_set_results=utf8");     	  
	$r = mysql_query($sql);   
	$total = mysql_num_rows($r);
    
	if ($total>0){
	   
    require('../lib/PHPExcel.php');	
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $SITETITLE)	;
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:G2');	
 
    $objPHPExcel->getActiveSheet()->SetCellValue('A3', ($gTEXT['Shipment Entry of']).' '.($CountryName))	;
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A3');	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:G3');
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A4',(($gTEXT['Funding Source']).': '. $FundingSourceName).',  '.(($gTEXT['Product Group']).': '. $ItemGroupName));
	$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	$objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A4:G4');
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A5',(($gTEXT['Shipment Status']).': '. $ShipmentStatusDesc).',  '.(($gTEXT['Owner Type']).': '. $OwnerTypeName));
	$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	$objPHPExcel -> getActiveSheet() -> getStyle('A5') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A5');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A5:G5');
        											
    $objPHPExcel->getActiveSheet()
        				->SetCellValue('A8', 'SL#')	
						->SetCellValue('B8', $gTEXT['Product Group'])						
        				->SetCellValue('C8', $gTEXT['Item Name'])
        				->SetCellValue('D8', $gTEXT['Shipment Status'])							
        				->SetCellValue('E8', $gTEXT['Shipment Date'])
						->SetCellValue('F8', $gTEXT['Owner Type'])								
        				->SetCellValue('G8', $gTEXT['Quantity']);
        				
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A8');	
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B8');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C8');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D8');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E8');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F8');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G8');
    
    $objPHPExcel -> getActiveSheet() -> getStyle('A8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel -> getActiveSheet() -> getStyle('G8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel -> getActiveSheet() -> getStyle('D8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(40);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(15);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(15);
											
											
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
    
    $objPHPExcel->getActiveSheet()->getDefaultStyle('A9')->getAlignment()->setWrapText(true);
    $objPHPExcel -> getActiveSheet() -> getStyle('A8'  . ':A8') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('B8'  . ':B8') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('C8'  . ':C8') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('D8'  . ':D8') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('E8'  . ':E8') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('F8'  . ':F8') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('G8'  . ':G8') -> applyFromArray($styleThinBlackBorderOutline);
    
    $objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('E8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F8')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('G8')->getFont()->setBold(true);
    
    $i=1; $j=9;
        
    while($rec=mysql_fetch_array($r)){
        
     $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
     $objPHPExcel -> getActiveSheet() -> getStyle('C') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
     $objPHPExcel -> getActiveSheet() -> getStyle('E') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
     $objPHPExcel -> getActiveSheet() -> getStyle('A') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            
     if($tempGroupId!=$rec['FundingSourceName']) {
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
										            'fill' => array(
										            'type' => PHPExcel_Style_Fill::FILL_SOLID,
										            'color' => array('rgb'=>'DAEF62'),));
            
            $objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':G'.$j);	
            
            $objPHPExcel->getActiveSheet()
            			->SetCellValue('A'.$j, $rec['FundingSourceName']); 
            			
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
            
            $tempGroupId=$rec['FundingSourceName'];$j++;
        }
        $date = strtotime($rec['ShipmentDate']);
    	$newdate = date( 'd/m/Y', $date );    
	 $objPHPExcel -> getActiveSheet()->SetCellValue('A'.$j, $i)
                                     ->SetCellValue('B'.$j, $rec['GroupName'])									
                                     ->SetCellValue('C'.$j, $rec['ItemName'])								
                                     ->SetCellValue('D'.$j, $rec['ShipmentStatusDesc'])									
                                     ->SetCellValue('E'.$j, $newdate)
									 ->SetCellValue('F'.$j, $rec['OwnerTypeName'])			
                                     ->SetCellValue('G'.$j, $rec['Qty']==''? '':number_format($rec['Qty']));									
                        				
                                   
    $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);							  			
    $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		
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
	$file = 'Shipment_List'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
    } else{
        $error = "No record found";	
		echo $error;
    }

?>