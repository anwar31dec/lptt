<?php
require ("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
$gTEXT = $TEXT;
 
$jBaseUrl=$_GET['jBaseUrl']; 

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query('SET CHARACTER SET utf8');

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}




switch($task) {
	
	
	 
	
	
	
	
        case 'getFacilityTypeData' :
	 		  getFacilityTypeData();
	break;
	
	
	  case 'getDosesFormData' :
	 		 getDosesFormData();
	break;
	
	 
	
	 // case 'getFacilityReportingStatus':
		//  getFacilityReportingStatus();
   // break;
	
	 
		
	
	
	
	 case 'getFacilityData':
		  getFacilityData();
    break;	
	
	
	
	
	
	
	 case 'getCountryRegimen':
		  getCountryRegimen();
		 
    break;	
	 case 'getRegimenList':
		  	getRegimenList();
    break;	
		
	 case 'getCountryProduct':
		  	getCountryProduct();
    break;	
		
	
		
	 case 'getPOMasterData':
		  	getPOMasterData();
    break;		
	
	
	
	 case 'getReportStatusData':
		  	getReportStatusData();
    break;		
	
	
	
	
	 case 'getMonthData':
		  	getMonthData();
    break;		
	
	 case 'getAgencyShipment':
		  	getAgencyShipment();
    break;		
	
	
		
	
	case 'getYcProfileData':
		  	getYcProfileData();
    break;		
	
	case'getSummaryData':
		  	getSummaryData();
    break;		
	
	case 'getCountryProfileParams':
		  	getCountryProfileParams();
    break;
			
	case'getYcRegimenPatient':
		  	getYcRegimenPatient();
    break;		
	
	case'getYcFundingSource':
		  	getYcFundingSource();
    break;	
	
	case'getYcPledgedFunding':
		  	getYcPledgedFunding();
    break;
		
	 case 'getFacilityReportingStatus':
		  getFacilityReportingStatus();
    break; 
	
	case'getStockStatusAtFacility':
		  	getStockStatusAtFacility();
    break;	
		
	case'getFundingStatusData':
		  	getFundingStatusData();
    break;
	
	case'getMosType':
		  	getMosType();
    break;
		
		
}



function getFacilityTypeData()
{
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Facility Type'] )	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6',$gTEXT['Facility Type Name'] )
									
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			
   
			     $YearMonth=$_GET['YearMonth'];
				$EndYearMonth=explode(' ',$YearMonth);
				$EndYearMonth=explode('-',$EndYearMonth[0]);
				$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
	
	
	
	$sql=" SELECT  FTypeId,FTypeName	
				FROM t_facility_type order by FTypeName";
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
			$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
		
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['FTypeName'])								
								       ;  			
				
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
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}

function getDosesFormData()
{
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Dosage Form List'] )	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6',$gTEXT['Dosage Form Name'] )
									
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			
   
			     $YearMonth=$_GET['YearMonth'];
				$EndYearMonth=explode(' ',$YearMonth);
				$EndYearMonth=explode('-',$EndYearMonth[0]);
				$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
	
	
	
	$sql="SELECT  DosesFormId,DosesFormName	
				FROM t_dosesform order by DosesFormName";
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
			$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
		
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['DosesFormName'])								
								       ;  			
				
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
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}



function getFacilityData()
{
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
	 $ARegionId=$_GET['ARegionId']; 
	 $CountryId	=$_GET['CountryId']; 
     $FacilityLevel=$_GET['FacilityLevel']; 
     $FacilityType=$_GET['FacilityType'];
	 
	 $CountryName = $_GET['CountryName'];
	 $RegionName = $_GET['RegionName'];
	 $FTypeName = $_GET['FTypeName'];
	 $FLevelName = $_GET['FLevelName'];
	 
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Facility List'] )	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:H2');

	 $objPHPExcel->getActiveSheet()->SetCellValue('A3',('Country : '. $CountryName). ' , '.(' Region Name : '.$RegionName) );
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	
$objPHPExcel -> getActiveSheet() -> mergeCells('A3:H3');

	 $objPHPExcel->getActiveSheet()->SetCellValue('A4',('Facility Type : '. $FTypeName) );
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	
$objPHPExcel -> getActiveSheet() -> mergeCells('A4:H4');

	 $objPHPExcel->getActiveSheet()->SetCellValue('A5',('Facility Level : '. $FLevelName) );
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A5') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A5');
	
$objPHPExcel -> getActiveSheet() -> mergeCells('A5:H5');		
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A8', '#SL')							
									->SetCellValue('B8',$gTEXT['Facility Code'] )
									->SetCellValue('C8',$gTEXT['Facility Name'] )
									->SetCellValue('D8',$gTEXT['Facility Type'] )
									->SetCellValue('E8',$gTEXT['Region Name'] )
									->SetCellValue('F8',$gTEXT['Facility Level'])
									->SetCellValue('G8',$gTEXT['Facility Address'])
									->SetCellValue('H8',$gTEXT['Assigned Group'])
									
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A8');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B8');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C8');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D8');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E8');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F8');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G8');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H8');
	
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(20);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A9')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A8'  . ':A8') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B8'  . ':B8') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C8'  . ':C8') -> applyFromArray($styleThinBlackBorderOutline);
		  $objPHPExcel -> getActiveSheet() -> getStyle('D8'  . ':D8') -> applyFromArray($styleThinBlackBorderOutline);
		  $objPHPExcel -> getActiveSheet() -> getStyle('E8'  . ':E8') -> applyFromArray($styleThinBlackBorderOutline);
		  $objPHPExcel -> getActiveSheet() -> getStyle('F8'  . ':F8') -> applyFromArray($styleThinBlackBorderOutline);
		  $objPHPExcel -> getActiveSheet() -> getStyle('G8'  . ':G8') -> applyFromArray($styleThinBlackBorderOutline);
		  $objPHPExcel -> getActiveSheet() -> getStyle('H8'  . ':H8') -> applyFromArray($styleThinBlackBorderOutline);
			   
			   
			   
			$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);		
			$objPHPExcel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true);		
			$objPHPExcel->getActiveSheet()->getStyle('E8')->getFont()->setBold(true);		
			$objPHPExcel->getActiveSheet()->getStyle('F8')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('G8')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H8')->getFont()->setBold(true);
			
			
   
			     $YearMonth=$_GET['YearMonth'];
				$EndYearMonth=explode(' ',$YearMonth);
				$EndYearMonth=explode('-',$EndYearMonth[0]);
				$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
	 
	  if($CountryId){
		$CountryId = " AND a.CountryId = '".$CountryId."' ";
	}  
    if($ARegionId){
		$ARegionId = " AND a.RegionId = '".$ARegionId."' ";
	}    
    if($FacilityType){
		$FacilityType = " AND a.FTypeId = '".$FacilityType."' ";
	}
    if($FacilityLevel){
		$FacilityLevel = " AND a.FLevelId = '".$FacilityLevel."' ";
	}  
	  
	$sql=" SELECT SQL_CALC_FOUND_ROWS FacilityId, a.CountryId, a.RegionId, ParentFacilityId, a.FTypeId, a.FLevelId, FacilityCode, FacilityName, FacilityAddress, FacilityPhone, FacilityFax, FacilityEmail, 
             FacilityManager, Latitude, Longitude, FacilityCount, FLevelName, FTypeName, RegionName
             FROM t_facility a
             INNER JOIN t_facility_level b ON a.FLevelId = b.FLevelId
             INNER JOIN t_facility_type c ON a.FTypeId = c.FTypeId
             INNER JOIN t_region d ON a.RegionId = d.RegionId 	
             ".$CountryId." ".$ARegionId." ".$FacilityType." ".$FacilityLevel." ";
          
	$r= mysql_query($sql) ;
	$i=1; $j=9;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
		
			$sql_group = " SELECT FacilityId, GroupName
                 FROM t_facility_group_map a
                 INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                 WHERE FacilityId = ".$rec['FacilityId']." "; 
                 
        $pacrs_group = mysql_query($sql_group);
        $group_name = ""; $k = 0;
        
        if ($pacrs_group)
		
	   {
	
	    while ($row_group = mysql_fetch_object($pacrs_group)) {	  
	       
	       if ($k++) $group_name.= ", ";
	       $group_name.= $row_group -> GroupName;       
        }
		}
			 if($tempGroupId!=$rec['FLevelName']) 
		   {
		   				
	              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'DAEF62'),
				          )
		           );
		
		   	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':H'.$j);	
			
	    	$objPHPExcel->getActiveSheet()
											
									->SetCellValue('A'.$j, $rec['FLevelName'])								
									
									; 
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		   	 	
			$tempGroupId=$rec['FLevelName'];$j++;
		   }	
		
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['FacilityCode'])	
									->SetCellValue('C'.$j, $rec['FacilityName'])	
									->SetCellValue('D'.$j, $rec['FTypeName'])
									->SetCellValue('E'.$j, $rec['RegionName'])		
									->SetCellValue('F'.$j, $rec['FLevelName'])	
									->SetCellValue('G'.$j, $rec['FacilityAddress'])	
									->SetCellValue('H'.$j, $group_name)								
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
				
				
			 $i++; $j++;
				 
				
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
	
	
}


function getCountryRegimen()
{
	
	global $gTEXT;
	
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Country List'])	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
									//->SetCellValue('A6', $gTEXT['Country Id'])	
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Country Name'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	//$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(12);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
	//$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(16);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          //$objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			//$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			
   
			       
	
	
	$sql=" SELECT CountryId, CountryCode, CountryName 	
             FROM t_country	order by CountryName ";
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		    $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		    $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	
	 		$objPHPExcel->getActiveSheet()
			                        //->SetCellValue('A'.$j, $rec['CountryId'])	
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['CountryName'])								
									
									;  			
				
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 //$objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
			 $i++; $j++;
				 
				
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
	
}


 function getCountryProduct()
{
	global $gTEXT;
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Country Product'])	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Country Name'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(16);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
           $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			
   
			       
	 
	
	 
        $sql = "SELECT CountryId, CountryCode, CountryName 	
             FROM t_country"; 
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
	       $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
		
	
	
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['CountryName'])								
									
									;  			
				
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
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
} 


function getPOMasterData()
{
	global $gTEXT;
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Patient Overview'])	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Patient Overview'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
           $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			
   
			       
	 
	
	 
        $sql = "SELECT  POMasterId, POMasterName	
				FROM t_pomaster order by POMasterId  "; 
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
	      
	
	
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['POMasterName'])								
									
									;  			
				
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
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
}


function getReportStatusData()
{
	global $gTEXT;
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Report Status'])	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Center latitude'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
           $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			
   
			       
	 
	
	 
       $sql=" SELECT  StatusId,StatusName	
				FROM t_status order by StatusId";
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
	      
	
	
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['StatusName'])								
									
									;  			
				
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
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
} 



function getAgencyShipment()
{
	
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  
     $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Agency Shipment List'] )	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:E2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Item Name'])
									->SetCellValue('C6', $gTEXT['Shipment Status'])							
									->SetCellValue('D6', $gTEXT['Shipment Date'])						
			  						->SetCellValue('E6', $gTEXT['Quantity'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('E6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(40);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(15);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('D6'  . ':D6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('E6'  . ':E6') -> applyFromArray($styleThinBlackBorderOutline);
         
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
			
   
			     $YearMonth=$_GET['YearMonth'];
				$EndYearMonth=explode(' ',$YearMonth);
				$EndYearMonth=explode('-',$EndYearMonth[0]);
				$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
	
$CountryId=$_GET['ACountryId']; 
$AFundingSourceId=$_GET['AFundingSourceId']; 
$ASStatusId=$_GET['ASStatusId']; 
 if($CountryId){
		$CountryId = " WHERE a.CountryId = '".$CountryId."' ";
	}
    if($AFundingSourceId){
		$AFundingSourceId = " AND a.FundingSourceId = '".$AFundingSourceId."' ";
	}   
    if($ASStatusId){
		$ASStatusId = " AND a.ShipmentStatusId = '".$ASStatusId."' ";
	}
	
    $sql = "SELECT  AgencyShipmentId, a.FundingSourceId, d.FundingSourceName, a.ShipmentStatusId, c.ShipmentStatusDesc, a.CountryId, 
            b.CountryName, a.ItemNo, e.ItemName, a.ShipmentDate, a.Qty
			FROM t_agencyshipment as a
            INNER JOIN t_country b ON a.CountryId = b.CountryId
            INNER JOIN t_shipmentstatus c ON a.ShipmentStatusId = c.ShipmentStatusId
            INNER JOIN t_fundingsource d ON a.FundingSourceId= d.FundingSourceId
            INNER JOIN t_itemlist e ON a.ItemNo = e.ItemNo ".$CountryId." ".$AFundingSourceId." ".$ASStatusId."
			$sWhere $sOrder $sLimit ";  
			
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		    $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		     $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
			    if($tempGroupId!=$rec['FundingSourceName']) 
				   {
				   				
			              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
								'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb'=>'DAEF62'),
						          )
				           );
				
		   	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':E'.$j);	
			
	    	$objPHPExcel->getActiveSheet()
											
									->SetCellValue('A'.$j, $rec['FundingSourceName'])								
									
									; 
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		   	 	
			$tempGroupId=$rec['FundingSourceName'];$j++;
		   }
		   $objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['ItemName'])								
									->SetCellValue('C'.$j, $rec['ShipmentStatusDesc'])									
									->SetCellValue('D'.$j, $rec['ShipmentDate'])									
									->SetCellValue('E'.$j, $rec['Qty']);
									  			
 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $i++; $j++;
				 
				
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}



function getYcProfileData()
{
  
   global $gTEXT;
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Country Profile Values'])	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Parameter Name'])
									->SetCellValue('C6', $gTEXT['Value'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(30);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(30);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
           $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
           $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			
   
	$CountryId=$_GET['country']; 	
			$Year=$_GET['year']; 
			 if(!empty($CountryId) && !empty($Year))
	 
		 		$sql="SELECT SQL_CALC_FOUND_ROWS a.YCProfileId, a.YCValue, Year, a.CountryId, a.ParamId, ParamName
				FROM t_ycprofile a
                INNER JOIN t_country b ON a.CountryId = b.CountryId
                INNER JOIN t_cprofileparams c ON a.ParamId = c.ParamId
                WHERE a.CountryId = '".$CountryId."'
                AND a.Year = '".$Year."' AND a.ParamId NOT IN (5,7)	";
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
	$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['ParamName'])								
									->SetCellValue('C'.$j, $rec['YCValue']==''? '':number_format($rec['YCValue']))	
									;  			
				
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
			 $i++; $j++;
				 
				
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}

function getMonthData()
{
  
   global $gTEXT;
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Month'])	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:B2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Month Name'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
           $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          
        	
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			
   
			     $YearMonth=$_GET['YearMonth'];
				$EndYearMonth=explode(' ',$YearMonth);
				$EndYearMonth=explode('-',$EndYearMonth[0]);
				$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
	
	
	
	$sql="SELECT  `t_month`
                ORDER BY `t_month`.`MonthId` ASC
                LIMIT 0 , 30 ";
	
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
	$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['Month'])								
									
									;  			
				
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
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
	
}


	 
function getCountryProfileParams()
{
   global $gTEXT;
$CountryId=$_GET['CountryId']; 	 
$Year=$_GET['Year']; 
  if($_REQUEST['lan'] == 'en-GB'){
     
        $PLang = 'ParamName';   
    }else{
      
        $PLang = 'ParamNameFrench';
    } 
 if(!empty($CountryId) && !empty($Year))
    		 		$sql="SELECT  a.YCProfileId, a.YCValue, Year, a.CountryId, a.ParamId, $PLang ParamName 
    				FROM t_ycprofile a
                    
                    INNER JOIN t_cprofileparams c ON a.ParamId = c.ParamId
                    WHERE a.CountryId = " . $_REQUEST['CountryId'] . " 
                    AND a.Year = " . $_REQUEST['Year'] . " 
                    AND a.ParamId NOT IN (5,7)
                    Order By a.ParamId "; 
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	$i=1;	
    
	if($total>0){
		
    require('../lib/PHPExcel.php');	
    $objPHPExcel = new PHPExcel();
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Country Profile']. ' of '.($CountryName). ' '. ($Year));
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:C2');
    
    $objPHPExcel->getActiveSheet()->SetCellValue('A5',$gTEXT['Parameter List']);
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '14','bold' => true)), 'A5');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A5:C5');
													
    $objPHPExcel->getActiveSheet()
    							->SetCellValue('A6', 'SL')							
    							->SetCellValue('B6', $gTEXT['Parameter Name'])
    							->SetCellValue('C6', $gTEXT['Value']);
    							
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
    
    $objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(50);
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(40);

    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
    
    $objPHPExcel -> getActiveSheet() -> getDefaultStyle('A7') -> getAlignment()->setWrapText(true);
    $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
    
    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
    
    $i=1; $j=7;	
	while($rec=mysql_fetch_array($r)){
	   
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        
        if($rec['YCValue']==''){
            $rec['YCValue']=='';
        }else{
            if(is_numeric($rec['YCValue'])){
                 $rec['YCValue'] = number_format($rec['YCValue']);
            }else{
                 $rec['YCValue'] = $rec['YCValue'];
            }
        }  
       
        $objPHPExcel->getActiveSheet()
        		->SetCellValue('A'.$j, $i)							
        		->SetCellValue('B'.$j, $rec['ParamName'])								
        		->SetCellValue('C'.$j, $rec['YCValue']);  			
        
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
        
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
        $i++; $j++;
	}
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	 }
    else{
   	    echo 'No record found';
    }
	

}

Function getYcRegimenPatient()
{
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['ART Protocols with Patient Count'] )	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:C2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', 'SL')							
									->SetCellValue('B6',$gTEXT['RegimenCount'] )
									->SetCellValue('C6',$gTEXT['Patients'] )							
									
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C16') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(18);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(16);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A6'  . ':A6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B6'  . ':B6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C6'  . ':C6') -> applyFromArray($styleThinBlackBorderOutline);
         
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			
   
			     $YearMonth=$_GET['YearMonth'];
				$EndYearMonth=explode(' ',$YearMonth);
				$EndYearMonth=explode('-',$EndYearMonth[0]);
				$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
		
$CountryId=$_GET['CountryId'];
$CountryName=$_REQUEST['CountryName']; 	 	 
$Year=$_GET['Year']; 
	
	$aColumns = array('RegimenName', 'PatientCount', 'FormulationName');
	$aColumns2 = array('RegimenName', 'PatientCount', 'FormulationName');
    $sIndexColumn = "YearlyRegPatientId";
	$sTable = "t_yearly_country_regimen_patient ";
		
	$sJoin = 'INNER JOIN t_regimen ON t_yearly_country_regimen_patient.RegimenId = t_regimen.RegimenId ';
	$sJoin .= 'INNER JOIN t_formulation ON t_regimen.FormulationId = t_formulation.FormulationId ';	
	$sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}

	
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "ORDER BY  ";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY FormulationName") {
			$sOrder = "";
		}
	}
	
	$sWhere = ""; 
	 
	for ($i = 0; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
		}
	}
	$bUserFilter = true;

	if ($bUserFilter) {
		if ($sWhere == "") {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}
		$sWhere .=  "t_yearly_country_regimen_patient.CountryId = " . $_GET['CountryId'] . " AND t_yearly_country_regimen_patient.Year = " . $_GET['Year'];
	}

	$bUseSL = true;
	$serial = '';
    $sQuery = "SELECT SQL_CALC_FOUND_ROWS @rank:=@rank+1 AS SL, RegimenName, PatientCount, $FLang FormulationName
                FROM t_yearly_country_regimen_patient
                INNER JOIN t_regimen ON t_yearly_country_regimen_patient.RegimenId = t_regimen.RegimenId 
                INNER JOIN t_formulation ON t_regimen.FormulationId = t_formulation.FormulationId
                WHERE t_yearly_country_regimen_patient.CountryId = " . $_REQUEST['CountryId'] . " 
                AND t_yearly_country_regimen_patient.Year = " . $_REQUEST['Year']."
                ORDER BY t_formulation.FormulationId asc";
	mysql_query("SET character_set_results=utf8");		
	$rResult =mysql_query($sQuery);

	$K=1; $j=7;	
	 $tempGroupId='';
	 
	while ($aRow = mysql_fetch_array($rResult)) {
		
		$row = array();
		for ($i = 0; $i < count($aColumns2); $i++) {			
			if ($i == 0)
				$row[] = $serial++;
			else
				$row[] = $aRow[$aColumns2[$i]];
		}
	       
		      
		   
					 if($tempGroupId!=$aRow['FormulationName']) 
		   {
		   				
	              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'DAEF62'),
				          )
		           );
		
		   	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':C'.$j);	
			
	    	$objPHPExcel->getActiveSheet()
											
									->SetCellValue('A'.$j, $aRow['FormulationName'])								
									
									; 
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		   	 	
			$tempGroupId=$aRow['FormulationName'];$j++;
		   }
		
	             
			
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $K )							
									->SetCellValue('B'.$j, $aRow['RegimenName'])								
									->SetCellValue('C'.$j, ($aRow['PatientCount']==''? '':number_format($aRow['PatientCount'])))							
										;  			
				
			
              $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		      $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		
				
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
			 $K++; $j++;
				 
				
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
	
	
}

function getYcFundingSource()
{
	
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Funding Requirements'] )	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', 'SL')							
									->SetCellValue('B6', $gTEXT['Formulation'])
									->SetCellValue('C6', $gTEXT['2014'])							
									->SetCellValue('D6', $gTEXT['2015'])						
			  						->SetCellValue('E6', $gTEXT['2016'])
									->SetCellValue('F6', $gTEXT['Total'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('F6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('F8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C13') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D13') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E13') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('F13') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(16);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
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
	       
   
			     $YearMonth=$_GET['YearMonth'];
				$EndYearMonth=explode(' ',$YearMonth);
				$EndYearMonth=explode('-',$EndYearMonth[0]);
				$EndYearMonth=$EndYearMonth[0].'-'.$EndYearMonth[1].'-'.'31 00:00:00';
	
	if($YearMonth){
		$SupportDate=" and SupportDate >= '".$YearMonth."'  and SupportDate <= '".$EndYearMonth."' ";
	}
	
	$CountryId=$_GET['CountryId']; 	 
   $Year=$_GET['Year']; 
   
	 if($_REQUEST['lan'] == 'fr-FR'){
            $aColumns = array('SL', 'ServiceTypeNameFrench', 'FundingReqSourceNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
            $aColumns2 = array('SL', 'ServiceTypeNameFrench', 'FundingReqSourceNameFrench', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
     }else{
            $aColumns = array('SL', 'ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
            $aColumns2 = array('SL', 'ServiceTypeName', 'FundingReqSourceName', 'Y1', 'Y2', 'Y3', 'TotalRequirements');
     } 
	 
	 
	 
	 
	 
	 
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "FundingReqId";

	/* DB table to use */
	$sTable = "t_yearly_funding_requirements ";

	

// Joins
	$sJoin = 'INNER JOIN  t_fundingreqsources ON t_fundingreqsources.FundingReqSourceId = t_yearly_funding_requirements.FormulationId  ';
	$sJoin .= 'INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = t_fundingreqsources.ServiceTypeId ';
	$sJoin .= 'INNER JOIN t_itemgroup ON t_itemgroup.ItemGroupId = t_fundingreqsources.ItemGroupId ';
	////$sJoin  .= 'INNER JOIN t_country ON t_ycprofile.CountryId = t_country.CountryId ';
	
	
	
	
	
	/*
	 * Paging
	 */
	$sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}

	/*
	 * Ordering
	 */
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "ORDER BY  ";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") {
			$sOrder = "";
		}
	}
	$sOrder = "Order By t_fundingreqsources.FundingReqSourceId ";
	
	
	$sWhere = "";

	
	for ($i = 0; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " OR ";
			}
			$sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' ";
		}
	}

	/*User Data Filtering*/
	$bUserFilter = true;

	if ($bUserFilter) {
		if ($sWhere == "") {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}
		//$sWhere .= "t_ycprofile.CountryId = 2 AND ReportDate = '" . $_GET['YearId']."-".$_GET['MonthId']."-01'";
		$sWhere .= "t_yearly_funding_requirements.CountryId = " . $_GET['CountryId'] . " AND t_yearly_funding_requirements.Year = " . $_GET['Year'];
	}

	$bUseSL = true;
	$serial = '';

	if ($bUseSL) {
		mysql_query("SET @rank=0;");
		$serial = "@rank:=@rank+1 AS ";
	}

	/*
	 * SQL queries
	 * Get data to display
	 */

	$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS " . $serial . str_replace(" , ", " ", implode(", ", $aColumns)) . "
			FROM   $sTable
			$sJoin
			$sWhere
			$sOrder
			$sLimit
			";
	mysql_query("SET character_set_results=utf8");
	$rResult =mysql_query($sQuery);

	 
	$k=1; $j=7;	$tempGroupId='';
	 
	while ($aRow = mysql_fetch_array($rResult)) {
		$row = array();
		for ($i = 0; $i < count($aColumns2); $i++) {
			
			$row[] = $aRow[$aColumns2[$i]];
		}
		       

			  		 if($tempGroupId!=$aRow['ServiceTypeName']) 
		   {
		   				
	              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'DAEF62'),
				          )
		           );
		
		   	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':F'.$j);	
			
	    	$objPHPExcel->getActiveSheet()
											
									->SetCellValue('A'.$j, $aRow['ServiceTypeName'])								
									
									; 
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		   	 	
			$tempGroupId=$aRow['ServiceTypeName'];$j++;
		   }
		
			  
			  
			  
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $k)							
									->SetCellValue('B'.$j, $aRow['FundingReqSourceName'])								
									->SetCellValue('C'.$j,  ($aRow['Y1']==''? '':number_format($aRow['Y1'])))									
									->SetCellValue('D'.$j,  ($aRow['Y2']==''? '':number_format($aRow['Y2'])))									
									->SetCellValue('E'.$j, ($aRow['Y3']==''? '':number_format($aRow['Y3'])))									
									->SetCellValue('F'.$j, ($aRow['TotalRequirements']==''? '':number_format($aRow['TotalRequirements'])))											
									
									;  			
				 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	            $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
				$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
				$objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
				$objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline);
					
			 $k++; $j++;
	}
	 
 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}

function getYcPledgedFunding()
{	
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  	$CountryId = $_GET['CountryId'];
	$Year = $_GET['Year'];
	$CountryName=$_GET['CountryName'];
	$ItemGroupId = 1;
	$RequirementYear = $_GET['RequirementYear'];
	 if($RequirementYear==1){					
		$colTotalFund='TotalFund';
	}else if($RequirementYear==2){					
		$colTotalFund='TotalFund1';
	}else if($RequirementYear==3){					
		$colTotalFund='TotalFund2';
	}
    $objPHPExcel = new PHPExcel();
	
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Country Profile']. ' of '.($CountryName). ' '. ($Year));
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	
									
	$objPHPExcel->getActiveSheet()->SetCellValue('A5',$gTEXT['Pledged Funding']);
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '14','bold' => true)), 'A5');
   
											
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6','SL')							
									->SetCellValue('B6', $gTEXT['Category'])
									->SetCellValue('C6', $gTEXT['Total Requirements']);
								
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
	
	
	$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	
    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
  	$objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('B6:B6') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6:C6') -> applyFromArray($styleThinBlackBorderOutline);
	
			//$sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
			//Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
			//where  CountryId='" . $CountryId . "' and Year='" . $Year . "' 
			//Order By FundingSourceName asc ";
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS t_yearly_country_fundingsource.FundingSourceId,FundingSourceName 
			FROM t_yearly_country_fundingsource
				INNER JOIN t_fundingsource ON t_yearly_country_fundingsource.FundingSourceId=t_fundingsource.FundingSourceId
				where Year ='".$Year."' AND CountryId ='".$CountryId."' AND t_fundingsource.ItemGroupId = '".$ItemGroupId."'
				Order By FundingSourceId;";
				
            mysql_query("SET character_set_results=utf8");    
			$resultPre = mysql_query($sql);
			$total = mysql_num_rows($resultPre);
			$k=0;$odd=1;$z=3;$y=6; 
			while ($row = mysql_fetch_object($resultPre)) {
				
			if($k%2==0){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $row -> FundingSourceName);
				$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),($z).$y);
	            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);					
					$odd=0;
			}else{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $row -> FundingSourceName);
				$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),($z).$y);
	            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);
				$odd=1;
			}
			$objPHPExcel -> getActiveSheet() -> getColumnDimension(getNameFromNumber($z)) -> setWidth(20);
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), getNameFromNumber($z).$y);	
			$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$k++;$z++;
			}
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),getNameFromNumber($z).$y);
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);	    
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $gTEXT['Total']);
			$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$z++;
			$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),getNameFromNumber($z).$y);
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);				
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, 'Gap/Surplus');
			$objPHPExcel -> getActiveSheet() -> getColumnDimension(getNameFromNumber($z)) -> setWidth(15);
		    $objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$totColumnVal=$z;   
	
        	
			
			
	
	$rowData = array();
	$dynamicColumns = array();
	$dynamiccolWidths = array();
	if (!empty($CountryId) && !empty($Year)) {
		$sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
		Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
		where  CountryId='" . $CountryId . "' and Year='" . $Year . "' 
		Order By FundingSourceName asc ";
        mysql_query("SET character_set_results=utf8");    
		$resultPre = mysql_query($sql);
		$total = mysql_num_rows($resultPre);

		$l = 0;
		$trecord = 0;
		if ($total > 0) {
			while($row=mysql_fetch_object($resultPre)){
				$FundingSourceId=$row->FundingSourceId;
				$col=array();				
				$col['FundingSourceId'] =  $row->FundingSourceId;
				array_push($dynamicColumns,$col);				
			}		
		}
		 if($_REQUEST['lan'] == 'en-GB'){
            $SERLang = 'ServiceTypeName'; 
            $ForLang = 'f.FormulationName'; 
        }else{
            $SERLang = 'ServiceTypeNameFrench';
            $ForLang = 'f.FormulationNameFrench';
        }
		
		$sql = "SELECT $SERLang ServiceTypeName,GroupName,f.ItemGroupId,f.FormulationId,$ForLang FormulationName FROM t_formulation f
				INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = f.ServiceTypeId
	            Inner Join t_itemgroup g on g.ItemGroupId=f.ItemGroupId
				Order By GroupName,f.FormulationId  ";
				
        mysql_query("SET character_set_results=utf8"); 
       	$result = mysql_query($sql);
		$total = mysql_num_rows($result);
		$superGrandTotalRequirements=0;$superGrandFundingTotal=array();$superGrandSubTotal=0;$superGrandGapSurplus=0;
		$groupsubtotal=0;$groupsubTmp=-1;$p=0;$q=0;$r=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;
		while ($row = mysql_fetch_object($result)) {			
			$ItemGroupId = $row -> ItemGroupId;
			$FormulationId = $row -> FormulationId;
				
			
			
			if($p!=0&&$groupsubTmp!=$row -> GroupName){
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Total';
				$cellData[$l++]=$grandTotalRequirements;				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($grandFundingTotal); $k++) 
						$subtotal+=$grandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;
					$superGrandFundingTotal[$r][$j]=$subtotal;
				}	
						
				$cellData[$l++]=$grandSubTotal;
				if ($grandGapSurplus >= 0){
					$cellData[ $l++] =number_format($grandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;
			
				$superGrandTotalRequirements+=$grandTotalRequirements;
				$superGrandSubTotal+=$grandSubTotal;
				$superGrandGapSurplus+=$grandGapSurplus;
			
				$q=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;	
				$r++;
			}
		
				$l = 0;		
				$cellData = array();
				$groupsubTmp=$row -> GroupName;
				$cellData[$l++] = $row -> GroupName;
				$cellData[$l++] = $row -> FormulationName;
			
				$sql = "Select FundingReqId, CountryId, ItemGroupId, FundingReqSourceId, FormulationId, Year, 10 Y1,20 Y2,30 Y3,40 TotalRequirements from t_yearly_funding_requirements where CountryId='" . $CountryId . "' and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "' and FormulationId='" . $FormulationId . "' ";
				$result2 = mysql_query($sql);
				$total2 = mysql_num_rows($result2);
				if ($total2 > 0) {
					$row2 = mysql_fetch_object($result2);
					if ($RequirementYear == 1) {
						$totalRequirement = $row2 -> Y1;
					} else if ($RequirementYear == 2) {
						$totalRequirement = $row2 -> Y2;
					} else if ($RequirementYear == 3) {
						$totalRequirement = $row2 -> Y3;
					}
				} else {
					$totalRequirement = 0;
				}

				
				$cellData[$l++] = $totalRequirement;
				$grandTotalRequirements+=$totalRequirement;
				$subtotal = 0;				
				for ($j = 0; $j < count($dynamicColumns); $j++) {

					$FundingSourceId = $dynamicColumns[$j]['FundingSourceId'];
					$sql = "select PledgedFundingId, CountryId, Year, ItemGroupId, FormulationId, FundingSourceId, $colTotalFund TotalFund  from t_yearly_pledged_funding where CountryId='" . $CountryId . "' and Year='" . $Year . "' and ItemGroupId='" . $ItemGroupId . "' and FormulationId='" . $FormulationId . "' and FundingSourceId='" . $FundingSourceId . "' ";
					$result3 = mysql_query($sql);
					$total3 = mysql_num_rows($result3);
					if ($total3 == 0) {
						$subtotal += 0;
						$cellData[$l++] = 0;					
					} else {
						$row3 = mysql_fetch_object($result3);
						$subtotal += $row3 -> TotalFund;
						$cellData[$l++ ] = $row3 -> TotalFund;
					}
					$grandFundingTotal[$q][$j]=$row3 -> TotalFund;

				}
				$cellData [$l++] = $subtotal;
				$grandSubTotal+=$subtotal;
				$surplus = $totalRequirement - $subtotal;
				if ($surplus >= 0){
					$cellData[ $l++] =number_format($surplus);
					$grandGapSurplus+=$surplus;
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $surplus ). ')';
					$grandGapSurplus+=$surplus;
				}
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
			
			$rowData[] = $cellData;
			
			if($p==$total-1){
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Total';
				$cellData[$l++]=$grandTotalRequirements;				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($grandFundingTotal); $k++) 
						$subtotal+=$grandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;	
					$superGrandFundingTotal[$r][$j]=$subtotal;
				}			
				$cellData[$l++]=$grandSubTotal;
				if ($grandGapSurplus >= 0){
					$cellData[ $l++] =number_format($grandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;				
				
				$superGrandTotalRequirements+=$grandTotalRequirements;
				$superGrandSubTotal+=$grandSubTotal;
				$superGrandGapSurplus+=$grandGapSurplus;
				$r++;
			
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Grand Total';
				$cellData[$l++]=$superGrandTotalRequirements;	
				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($superGrandFundingTotal); $k++) 
						$subtotal+=$superGrandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;					
				}			
				$cellData[$l++]=$superGrandSubTotal;
				if ($superGrandGapSurplus >= 0){
					$cellData[ $l++] =number_format($superGrandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $superGrandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;
			}
		
			$p++;$q++;
		
		}
		
		$rResult=array();$data=array();$k=0;
		$x=0; $f=0; $groupsubtotal=0;$groupsubTmp='-1';
		$endlimit=count($rowData);
		$groupsubTmp=-1;$p=0;
		while(count($rowData)>$x)
		{ 		  
		  // group grand  total row
		  $groupsubTmp=$rowData[$x][1];	
		  if($f) {
			//echo ','; 
			}
		  //print_r($rowData);
		  if($rowData[$x][1]=='Grand Total'){			
			$rowData[$x][1]='';
			//echo '["Grand Total"';
			$data[$k++]='Grand Total';
		  }else if($groupsubTmp=='Total')	  {
				$rowData[$x][1]='';
				//echo '["'.$rowData[$x][0].' Total"';
				$data[$k++]=$rowData[$x][0];
		  }else{			  
			$f++;
			  if($f==$endlimit) {				
				//echo '["'.$f.'"';
				$data[$k++]=$f;
			  }else  {
				//echo '["'.$f.'"';
				$data[$k++]=$f;
			  }
		  }
		  $y=0;
		  while(count($rowData[$x])>$y){		  
			if($y>1&&$y<(count($rowData[$x])-3)){
				//echo  ',"'.number_format($rowData[$x][$y]).'"'; 
				$data[$k++]=number_format($rowData[$x][$y]);
			}else{
				//echo  ',"'.$rowData[$x][$y].'"';  
				$data[$k++]=$rowData[$x][$y];
			}
			$y++; 
		  } 
		  
		  
		  //echo ']'; 
		  
		  $x++;
		  $rResult[]=$data;
		  $k=0;
		  //break;
		}
		
		//echo	$totColumnVal;	
	
	$x=0; $tempGroupId='';$y=7;$j=7;
	while(count($rResult)>$x){
		             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		            // $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
		            // $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					
					
			
			$k=0;$z=0;
		if($tempGroupId!=$rResult[$x][1]) {
			$sd=getNameFromNumber($totColumnVal); 
			$objPHPExcel -> getActiveSheet() -> mergeCells('A' . $y.':'.$sd.$y);				
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $rResult[$x][1]);
			$tempGroupId=$rResult[$x][1];
			$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'DAEF62'),
				          )
		           );
				    $sd=getNameFromNumber($totColumnVal);
				    $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
					  
					
			$y++;	
			}
			
	$f=0;			
			while(count($rResult[$x])-2>$k){				
				if($k==1){
					
					
			}else{
					$style='';
					if($rResult[$x][0]=='Grand Total')
					{
						$d=$rResult[$x][$k]; 
						 $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'50ABED'),
				          )
		           );
				  
				   
				  //$sd=getNameFromNumber($z);
				   
				   
				   $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline1);
					
					} 
					else if(is_int($rResult[$x][0])==false)
					{
					$d++;
					$f++;
						
					if($f==1) 
					{
						$d=$rResult[$x][$k].' Total';
						$styleThinBlackBorderOutline0 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'FE9929'),
				          )
		           );
				   
					}
					else 
					{
						$d=$rResult[$x][$k]; 
						$styleThinBlackBorderOutline0 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'FE9929'),
				          )
		           );
					}
					
			
	
			// $sd=getNameFromNumber($z);
				   
				  
				   $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline0);
					$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			       }
                 else
						{
							$d=$rResult[$x][$k];
							$styleThinBlackBorderOutline2 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					//'color' => array('rgb'=>'FE9929'),
				          )
		           );
				  
				   
				 // $sd=getNameFromNumber($z);
				   
				    $objPHPExcel -> getActiveSheet() -> getStyle('A'.$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				   $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $z) -> applyFromArray($styleThinBlackBorderOutline2);
							
						} 
					$objPHPExcel -> getActiveSheet() -> getStyle('B' . $y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel -> getActiveSheet() -> getStyle('A7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	                $objPHPExcel -> getActiveSheet() -> getStyle('A22') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
				    if(count($rResult[$x])==$k){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $rResult[$x][$k]);
						$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	                    $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline); 	
					}else{
						 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
                         $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline); 
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $d);
						 }	
					
				if($f==1)$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				else $objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				  
				   
				$z++;
					}
				$k++;
			}			
			
			$x++;$y++;
		}	
			
		 $k++;$j++;
			
	}
	 
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.getNameFromNumber($z-1).'2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A5:'.getNameFromNumber($z-1).'5');   
		
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'Pledged_Funding_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}
function getNameFromNumber($num) {
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}
/*
function getYcPledgedFunding()
{
	
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  	$CountryId = $_GET['CountryId'];
	$Year = $_GET['Year'];
	$CountryName=$_GET['CountryName'];
	$RequirementYear = $_GET['RequirementYear'];
	$pItemGroupId = 1;
    $objPHPExcel = new PHPExcel();
	
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Country Profile']. ' of '.($CountryName). ' '. ($Year));
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:H2');	
									
	
											
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', 'SL')							
									->SetCellValue('B6', $gTEXT['Category'])
									->SetCellValue('C6', $gTEXT['Total Requirements']);
								
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6'.$j);	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6'.$j);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6'.$j);
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
    $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);
	$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	
    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
  	$objPHPExcel -> getActiveSheet() -> getStyle('A6'.$j  . ':A6'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('B6'.$j  . ':B6'.$j) -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('C6'.$j  . ':C6'.$j) -> applyFromArray($styleThinBlackBorderOutline);
							
			$sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
					Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
					where  CountryId='" . $CountryId . "' and Year='" . $Year . "' and s.ItemGroupId=". $pItemGroupId ."
					Order By FundingSourceName asc ";
			$resultPre = mysql_query($sql);
			$total = mysql_num_rows($resultPre);
			$k=0;$odd=1;$z=3;$y=6;
			while ($row = mysql_fetch_object($resultPre)) {
				
				if($k%2==0){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $row -> FundingSourceName);
				$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),($z).$y);
	            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);					
					$odd=0;
			}else{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $row -> FundingSourceName);
				$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),($z).$y);
	            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);
				$odd=1;
			}
			$objPHPExcel -> getActiveSheet() -> getColumnDimension(getNameFromNumber($z)) -> setWidth(20);
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), getNameFromNumber($z).$y);	
			$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$k++;$z++;
			}
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),getNameFromNumber($z).$y);
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);	    
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $gTEXT['Total']);
			$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);			
			$z++;
			$objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),getNameFromNumber($z).$y);
            $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
            $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline);				
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, 'Gap/Surplus');
			$objPHPExcel -> getActiveSheet() -> getColumnDimension(getNameFromNumber($z)) -> setWidth(15);
		    $objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$totColumnVal=$z;    
	
        	
			
			
	
	     if($_GET['lan'] == 'fr-FR'){
        $aColumns = 'f.FundingReqSourceNameFrench FormulationName, ServiceTypeNameFrench GroupName';   
    }else{
        $aColumns = 'f.FundingReqSourceName FormulationName, ServiceTypeName GroupName';   
    }
    
	$rowData = array();
	$dynamicColumns = array();
	$dynamiccolWidths = array();
	if (!empty($CountryId) && !empty($Year)) {
		$sql = "select f.FundingSourceId,s.FundingSourceName from t_yearly_country_fundingsource f
		Inner Join t_fundingsource s on s.FundingSourceId=f.FundingSourceId
		where  CountryId=" . $CountryId . " and Year='" . $Year . "' and s.ItemGroupId=". $pItemGroupId ."
		Order By FundingSourceName asc ";
        
		$resultPre = mysql_query($sql);
		$total = mysql_num_rows($resultPre);

		$l = 0;
		$trecord = 0;
		if ($total > 0) {
			while($row=mysql_fetch_object($resultPre)){
				$FundingSourceId=$row->FundingSourceId;
				$col=array();				
				$col['FundingSourceId'] =  $row->FundingSourceId;
				array_push($dynamicColumns,$col);				
			}		
		}
                
        $sql = "SELECT f.ItemGroupId,f.FundingReqSourceId FormulationId, $aColumns 
                FROM t_fundingreqsources f
        		INNER JOIN t_servicetype ON t_servicetype.ServiceTypeId = f.ServiceTypeId
        		INNER JOIN t_itemgroup g on g.ItemGroupId=f.ItemGroupId
				where f.ItemGroupId = ". $pItemGroupId ."
        		Order By f.FundingReqSourceId ";
                
        mysql_query("SET character_set_results=utf8");
		$result = mysql_query($sql);
		$total = mysql_num_rows($result);
	
		$superGrandTotalRequirements=0;$superGrandFundingTotal=array();$superGrandSubTotal=0;$superGrandGapSurplus=0;
		$groupsubtotal=0;$groupsubTmp=-1;$p=0;$q=0;$r=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;
		while ($row = mysql_fetch_object($result)) {			
			$ItemGroupId = $row -> ItemGroupId;
			$FormulationId = $row -> FormulationId;
			
			if($p!=0&&$groupsubTmp!=$row -> GroupName){
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Total';
				$cellData[$l++]=$grandTotalRequirements;				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($grandFundingTotal); $k++) 
						$subtotal+=$grandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;
					$superGrandFundingTotal[$r][$j]=$subtotal;
				}	
						
				$cellData[$l++]=$grandSubTotal;
				if ($grandGapSurplus >= 0){
					$cellData[ $l++] =number_format($grandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;
			
				$superGrandTotalRequirements+=$grandTotalRequirements;
				$superGrandSubTotal+=$grandSubTotal;
				$superGrandGapSurplus+=$grandGapSurplus;
			
				$q=0;$grandTotalRequirements=0;$grandFundingTotal=array();$grandSubTotal=0;$grandGapSurplus=0;	
				$r++;
			}
		
			$l = 0;		
			$cellData = array();
			$groupsubTmp=$row -> GroupName;
			$cellData[$l++] = $row -> GroupName;
			$cellData[$l++] = $row -> FormulationName;
		
			$sql = "Select * from t_yearly_funding_requirements 
                    where CountryId='" . $CountryId . "' 
                    and Year='" . $Year . "' 
                    and ItemGroupId='" . $ItemGroupId . "' 
                    and FormulationId='" . $FormulationId . "' ";
                    
			$result2 = mysql_query($sql);
			$total2 = mysql_num_rows($result2);
			if ($total2 > 0) {
				$row2 = mysql_fetch_object($result2);
				if ($RequirementYear == 1) {
					$totalRequirement = $row2 -> Y1;
				} else if ($RequirementYear == 2) {
					$totalRequirement = $row2 -> Y2;
				} else if ($RequirementYear == 3) {
					$totalRequirement = $row2 -> Y3;
				}
			} else {
				$totalRequirement = 0;
			}

			$cellData[$l++] = $totalRequirement;
			$grandTotalRequirements+=$totalRequirement;
			$subtotal = 0;				
			for ($j = 0; $j < count($dynamicColumns); $j++) {

				$FundingSourceId = $dynamicColumns[$j]['FundingSourceId'];
				$sql = "select * from t_yearly_pledged_funding 
                where CountryId='" . $CountryId . "' 
                and Year='" . $Year . "' 
                and ItemGroupId='" . $ItemGroupId . "' 
                and FormulationId='" . $FormulationId . "' 
                and FundingSourceId='" . $FundingSourceId . "' ";
				
				$result3 = mysql_query($sql);
				$total3 = mysql_num_rows($result3);
				if ($total3 == 0) {
					$subtotal += 0;
					$cellData[$l++] = 0;					
				} else {
					$row3 = mysql_fetch_object($result3);
					$subtotal += $row3 -> TotalFund;
					$cellData[$l++ ] = $row3 -> TotalFund;
				}
				$grandFundingTotal[$q][$j]=$row3 -> TotalFund;

			}
			$cellData [$l++] = $subtotal;
			$grandSubTotal+=$subtotal;
			$surplus = $totalRequirement - $subtotal;
			if ($surplus >= 0){
				$cellData[ $l++] =number_format($surplus);
				$grandGapSurplus+=$surplus;
			}
			else{
				$cellData[ $l++] = '(' . number_format((-1) * $surplus ). ')';
				$grandGapSurplus+=$surplus;
			}
			$cellData[ $l++] = $ItemGroupId;
			$cellData[ $l++] = $FormulationId;
			
			$rowData[] = $cellData;
			
			if($p==$total-1){
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Total';
				$cellData[$l++]=$grandTotalRequirements;				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($grandFundingTotal); $k++) 
						$subtotal+=$grandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;	
					$superGrandFundingTotal[$r][$j]=$subtotal;
				}			
				$cellData[$l++]=$grandSubTotal;
				if ($grandGapSurplus >= 0){
					$cellData[ $l++] =number_format($grandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $grandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;				
				
				$superGrandTotalRequirements+=$grandTotalRequirements;
				$superGrandSubTotal+=$grandSubTotal;
				$superGrandGapSurplus+=$grandGapSurplus;
				$r++;
			
				$l = 0;		
				$cellData = array();
				$cellData[$l++]=$groupsubTmp;	
				$cellData[$l++]='Grand Total';
				$cellData[$l++]=$superGrandTotalRequirements;	
				
				for ($j = 0; $j < count($dynamicColumns); $j++) {				
					$subtotal=0;
					for ($k = 0; $k < count($superGrandFundingTotal); $k++) 
						$subtotal+=$superGrandFundingTotal[$k][$j];
					$cellData[$l++]=$subtotal;					
				}			
				$cellData[$l++]=$superGrandSubTotal;
				if ($superGrandGapSurplus >= 0){
					$cellData[ $l++] =number_format($superGrandGapSurplus);					
				}
				else{
					$cellData[ $l++] = '(' . number_format((-1) * $superGrandGapSurplus ). ')';					
				}	
				$cellData[ $l++] = $ItemGroupId;
				$cellData[ $l++] = $FormulationId;
				$rowData[] = $cellData;
			}
		
		$p++;$q++;
		
		}
		
		$rResult=array();$data=array();$k=0;
		$x=0; $f=0; $groupsubtotal=0;$groupsubTmp='-1';
		$endlimit=count($rowData);
		$groupsubTmp=-1;$p=0;
		while(count($rowData)>$x)
		{ 		  
		  $groupsubTmp=$rowData[$x][1];	
		  if($f) { 
			}
		  
		  if($rowData[$x][1]=='Grand Total'){			
			$rowData[$x][1]='';
			$data[$k++]='Grand Total';
		  }else if($groupsubTmp=='Total')	  {
				$rowData[$x][1]='';
				$data[$k++]=$rowData[$x][0];
		  }else{			  
			$f++;
			  if($f==$endlimit) {
			     
				$data[$k++]=$f;
			  }else  {
			     
				$data[$k++]=$f;
			  }
		  }
		  $y=0;
		  while(count($rowData[$x])>$y){		  
			if($y>1&&$y<(count($rowData[$x])-3)){
				//echo  ',"'.number_format($rowData[$x][$y]).'"'; 
				$data[$k++]=number_format($rowData[$x][$y]);
			}else{
				//echo  ',"'.$rowData[$x][$y].'"';  
				$data[$k++]=$rowData[$x][$y];
			}
			$y++; 
		  } 
		  
		  
		  //echo ']'; 
		  
		  $x++;
		  $rResult[]=$data;
		  $k=0;
		  //break;
		}
		
		//echo	$totColumnVal;	
	
	$x=0; $j=7;	$tempGroupId='';$y=7;
	while(count($rResult)>$x){			
		 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		            // $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
		            // $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					
					
			
			$k=0;$z=0;
		if($tempGroupId!=$rResult[$x][1]) {
			$sd=getNameFromNumber($totColumnVal); 
			$objPHPExcel -> getActiveSheet() -> mergeCells('A' . $y.':'.$sd.$y);				
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $rResult[$x][1]);
			$tempGroupId=$rResult[$x][1];
			$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'DAEF62'),
				          )
		           );
				    $sd=getNameFromNumber($totColumnVal);
				    $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
					  
					
			$y++;	
			}
			
	$f=0;			
			while(count($rResult[$x])-2>$k){				
				if($k==1){
					
					
			}else{
					$style='';
					if($rResult[$x][0]=='Grand Total')
					{
						$d=$rResult[$x][$k]; 
						 $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'50ABED'),
				          )
		           );
				  
				   
				  //$sd=getNameFromNumber($z);
				   
				   
				   $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline1);
					
					} 
					else if(is_int($rResult[$x][0])==false)
					{
					$d++;
					$f++;
						
					if($f==1) 
					{
						$d=$rResult[$x][$k].' Total';
						$styleThinBlackBorderOutline0 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'FE9929'),
				          )
		           );
				   
					}
					else 
					{
						$d=$rResult[$x][$k]; 
						$styleThinBlackBorderOutline0 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'FE9929'),
				          )
		           );
					}
					
			
	
			// $sd=getNameFromNumber($z);
				   
				  
				   $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline0);
					$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			       }
                 else
						{
							$d=$rResult[$x][$k];
							$styleThinBlackBorderOutline2 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					//'color' => array('rgb'=>'FE9929'),
				          )
		           );
				  
				   
				 // $sd=getNameFromNumber($z);
				   
				    $objPHPExcel -> getActiveSheet() -> getStyle('A'.$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				   $objPHPExcel -> getActiveSheet() -> getStyle('A' . $y . ':'. $sd. $z) -> applyFromArray($styleThinBlackBorderOutline2);
							
						} 
					$objPHPExcel -> getActiveSheet() -> getStyle('B' . $y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel -> getActiveSheet() -> getStyle('A7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	                $objPHPExcel -> getActiveSheet() -> getStyle('A22') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
				    if(count($rResult[$x])==$k){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $rResult[$x][$k]);
						$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
	                    $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline); 	
					}else{
						 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
                         $objPHPExcel->getActiveSheet()->getStyle(getNameFromNumber($z).$y.':'.getNameFromNumber($z).$y) -> applyFromArray($styleThinBlackBorderOutline); 
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $d);
						 }	
					
				if($f==1)$objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				else $objPHPExcel -> getActiveSheet() -> getStyle(getNameFromNumber($z).$y) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				  
				   
				$z++;
					}
				$k++;
			}			
			
			$x++;$y++;
		}	
			
		 $k++;$j++;
			
	}
	 
    $objPHPExcel -> getActiveSheet() -> mergeCells('A2:'.getNameFromNumber($z-1).'2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A5:'.getNameFromNumber($z-1).'5');   
		
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}

function getNameFromNumber($num) {
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}
*/

function getFacilityReportingStatus()
{
	
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2', $gTEXT['Facility Reporting Status'] )	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:K2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Facility Code'])
									->SetCellValue('C6', $gTEXT['Facility Name'])							
									->SetCellValue('D6', $gTEXT['Entered'])						
			  						->SetCellValue('E6', $gTEXT['Entry Date'])
									->SetCellValue('F6', $gTEXT['Submitted'])
									->SetCellValue('G6', $gTEXT['Submitted Date'])
									->SetCellValue('H6', $gTEXT['Accepted'])						
			  						->SetCellValue('I6', $gTEXT['Accepted Date'])
									->SetCellValue('J6', $gTEXT['Published'])
									->SetCellValue('K6', $gTEXT['Published Date'])
									;
									
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'J6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'K6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('F6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('H6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel -> getActiveSheet() -> getStyle('J6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(28);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(22);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(22);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(22);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('J') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('K') -> setWidth(22);
	
										
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
          $objPHPExcel -> getActiveSheet() -> getStyle('J6'  . ':J6') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('K6'  . ':K6') -> applyFromArray($styleThinBlackBorderOutline);
   
        	
   
        	
			$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('J6')->getFont()->setBold(true);
	        $objPHPExcel->getActiveSheet()->getStyle('K6')->getFont()->setBold(true);
   
	$monthId=$_GET['monthId']; 
   $year=$_GET['Year']; 
   $country=$_GET['CountryId']; 
   $itemGroupId=$_GET['ItemGroupId']; 
	$sQuery = "SELECT  b.FacilityId, b.FacilityCode, b.FacilityName,
				IFNULL( a.FacilityId,0) bEntered,				
				DATE_FORMAT(a.CreatedDt, '%d-%b-%Y %h:%i %p') CreatedDt,	
				IF(c.StatusId = '2', '1', '0') bSubmitted,
				DATE_FORMAT(a.LastSubmittedDt, '%d-%b-%Y %h:%i %p')  LastSubmittedDt,
				IF(c.StatusId = '3', '1', '0') bAccepted,
				DATE_FORMAT(a.AcceptedDt, '%d-%b-%Y %h:%i %p')  AcceptedDt,
				IF(c.StatusId = '5', '1', '0') bPublished,
				DATE_FORMAT(a.PublishedDt, '%d-%b-%Y %h:%i %p')  PublishedDt
				FROM  t_cfm_masterstockstatus a RIGHT JOIN (SELECT * FROM t_facility WHERE CountryId = $country) b
				ON a.FacilityId = b.FacilityId AND  MonthId = $monthId AND Year = '$year' AND a.CountryId = $country AND a.ItemGroupId = $itemGroupId
				LEFT JOIN t_status c ON a.StatusId = c.StatusId 
				";
	$r= mysql_query($sQuery) ;
	$i=1; $j=7;	$monthvar='';
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
		       $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		       $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
			   $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	           $objPHPExcel -> getActiveSheet() -> getStyle('J' . $j . ':J' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	
		 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['FacilityCode'])								
									->SetCellValue('C'.$j, $rec['FacilityName']);
									
			  if($rec['bEntered']==0){
									
					$styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
					'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'fe402b'),
				          )
		           );
				   $objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, 'NO')	;
				   $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
				   
			} 
							
		   else {
				   $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'9AD268'),
				          )
		           );
		           $objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, 'YES');
				   $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		    }
			
	$objPHPExcel->getActiveSheet()								
									->SetCellValue('E'.$j, $rec['CreatedDt']);
														
			if($rec['bSubmitted']==0){
					
				  $styleThinBlackBorderOutline2 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
				'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'fe402b'),
				          )
				   );
				    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, 'NO')	;
				$objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline2);
									
					} 
					
			else {
				  $styleThinBlackBorderOutline2 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
				'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'9AD268'),
				      )
				 );
				   	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, 'YES');
				 $objPHPExcel -> getActiveSheet() -> getStyle('F' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline2); 
				   
				   }
				   
		   
															
	$objPHPExcel->getActiveSheet()
		                           ->SetCellValue('G'.$j, $rec['LastSubmittedDt']);
									 											
			if($rec['bAccepted']==0){
				$styleThinBlackBorderOutline3= array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
				'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'fe402b'),
				          )
				   );				
									
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, 'NO');
			    $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline3); 
				
				}
			
		    else{
		       	 $styleThinBlackBorderOutline3= array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
				'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'9AD268'),
				          )
				   );		
			      $objPHPExcel->getActiveSheet()->SetCellValue('H'.$j, 'YES');
				 $objPHPExcel -> getActiveSheet() -> getStyle('H' . $j . ':H' . $j) -> applyFromArray($styleThinBlackBorderOutline3); 
			   }
								
	$objPHPExcel->getActiveSheet()										
								->SetCellValue('I'.$j, $rec['AcceptedDt']);
														 											
				if($rec['bPublished']==0){
					$styleThinBlackBorderOutline4= array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
					'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'fe402b'),
					          )
					 );				
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, 'NO');
					$objPHPExcel -> getActiveSheet() -> getStyle('J' . $j . ':J' . $j) -> applyFromArray($styleThinBlackBorderOutline4);
				}
				
			  else{
			  	    $styleThinBlackBorderOutline4= array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
					'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'9AD268'),
					          )
					 );		
				    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$j, 'YES');
				  	$objPHPExcel -> getActiveSheet() -> getStyle('J' . $j . ':J' . $j) -> applyFromArray($styleThinBlackBorderOutline4);
		        	}
																	
	$objPHPExcel->getActiveSheet()				
								->SetCellValue('K'.$j, $rec['PublishedDt'])											
									;  			
				
							
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
                 $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
				 $objPHPExcel -> getActiveSheet() -> getStyle('G' . $j . ':G' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 
				 $objPHPExcel -> getActiveSheet() -> getStyle('I' . $j . ':I' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 
				 $objPHPExcel -> getActiveSheet() -> getStyle('K' . $j . ':K' . $j) -> applyFromArray($styleThinBlackBorderOutline);
						
			 $i++; $j++;
				 
				
		}
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}
	
function getStockStatusAtFacility()
{
	
   global $gTEXT;
  
    require('../lib/PHPExcel.php');	
	
	$monthId = $_REQUEST['MonthId'];
	$year = $_REQUEST['YearId'];
	$countryId = $_REQUEST['CountryId'];
	$itemGroupId = $_REQUEST['ItemGroupId'];
	$itemNo = $_REQUEST['ItemNo'];
	$regionId = $_REQUEST['RegionId'];
	$fLevelId = $_REQUEST['FLevelId'];
	
	
    $CountryName = $_REQUEST['CountryName'];
	$MonthName = $_REQUEST['MonthName'];
	$Year = $_REQUEST['Year'];
	$ItemGroupName = $_REQUEST['ItemGroupName'];
	$ItemName = $_REQUEST['ItemName'];
	$RegionName = $_REQUEST['RegionName'];
	$FLevelName = $_REQUEST['FLevelName'];
    $objPHPExcel = new PHPExcel();
		
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2', ($gTEXT['Stock Status at Facility Level']) . ' on '. ($MonthName) . ' '. ($Year) );
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');		
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:E2');
	
//	n
	 $objPHPExcel->getActiveSheet()->SetCellValue('A3', ('Country :' .$CountryName) . (', Product Group : '. $ItemGroupName).(', Facility Level : '. $FLevelName));
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	
    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:E3');	 


	 $objPHPExcel->getActiveSheet()->SetCellValue('A4', ('Product Name : ' .$ItemName).(',Region :' .$RegionName) );
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	 
	 $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	      
     $objPHPExcel -> getActiveSheet() -> mergeCells('A4:E4');	 
//n			
													
     $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A7', '#SL')							
									->SetCellValue('B7', $gTEXT['Health Facility'])
									->SetCellValue('C7', $gTEXT['Balance'])							
									->SetCellValue('D7', $gTEXT['AMC'])						
			  						->SetCellValue('E7', $gTEXT['MOS'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A7');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B7');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C7');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D7');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E7');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle('B7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('C7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('E7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(16);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(18);
	
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
		  $objPHPExcel->getActiveSheet()->getDefaultStyle('A8')->getAlignment()->setWrapText(true);
	      $objPHPExcel -> getActiveSheet() -> getStyle('A7'  . ':A7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('B7'  . ':B7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('C7'  . ':C7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('D7'  . ':D7') -> applyFromArray($styleThinBlackBorderOutline);
          $objPHPExcel -> getActiveSheet() -> getStyle('E7'  . ':E7') -> applyFromArray($styleThinBlackBorderOutline);
        
        	 
			$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D7')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);
			
	
	$sQuery = "SELECT  " . $serial . ",
				  b.FacilityId,
				  b.FacilityName,				  
				  b.ClStock,
				  b.AMC,
				  b.MOS,
				  `Latitude`, `Longitude`
				  FROM (
				SELECT
				  t_cfm_masterstockstatus.FacilityId,
				  t_facility.FacilityName,
				  `Latitude`, `Longitude`,
				  IFNULL(t_cfm_stockstatus.ClStock,0)    ClStock,
				  IFNULL(t_cfm_stockstatus.AMC,0)       AMC,
				  IFNULL(t_cfm_stockstatus.MOS,0)       MOS
				FROM t_cfm_stockstatus
				  INNER JOIN t_cfm_masterstockstatus
				    ON (t_cfm_stockstatus.CFMStockId = t_cfm_masterstockstatus.CFMStockId)
				  INNER JOIN t_country_product
				    ON (t_country_product.CountryId = t_cfm_stockstatus.CountryId)
				      AND (t_country_product.ItemNo = t_cfm_stockstatus.ItemNo)
				  INNER JOIN t_facility
				    ON (t_facility.FacilityId = t_cfm_masterstockstatus.FacilityId)
				  INNER JOIN t_region
				    ON t_facility.RegionId = t_region.RegionId
				      AND t_region.CountryId = $countryId
				      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
				      AND (t_region.RegionId = $regionId OR $regionId=0)
				WHERE (t_cfm_masterstockstatus.StatusId = 5
				       AND t_cfm_masterstockstatus.MonthId = $monthId
				       AND t_cfm_masterstockstatus.Year = '$year'
				       AND t_cfm_masterstockstatus.CountryId = $countryId
				       AND t_country_product.ItemGroupId = $itemGroupId
				       AND t_country_product.ItemNo = $itemNo
				       AND t_cfm_stockstatus.ClStockSourceId IS NOT NULL
				       AND (t_cfm_stockstatus.ClStock <> 0
				             OR t_cfm_stockstatus.AMC <> 0))
				 UNION
				 SELECT
				  a.FacilityId, 
				  a.FacilityName,
				  a.`Latitude`, a.`Longitude`,
				  NULL ClStock,
				  NULL AMC,
				  NULL MOS
				FROM t_cfm_masterstockstatus
				  INNER JOIN t_facility
				    ON t_cfm_masterstockstatus.FacilityId = t_facility.FacilityId
				  INNER JOIN t_region
				    ON t_facility.RegionId = t_region.RegionId
				      AND t_region.CountryId = $countryId
				      AND (t_facility.FLevelId = $fLevelId OR $fLevelId=0)
				      AND (t_region.RegionId = $regionId OR $regionId=0)
				  RIGHT JOIN (SELECT
				                p.FacilityId,
				                p.FacilityCode,
				                p.FacilityName,
				                `Latitude`, `Longitude`
				              FROM t_facility p
				                INNER JOIN t_facility_group_map q
				                  ON p.FacilityId = q.FacilityId
				                INNER JOIN t_region r
				                  ON p.RegionId = r.RegionId
				              WHERE p.CountryId = $countryId
				                  AND q.ItemGroupId = $itemGroupId
				                  AND (p.FLevelId = $fLevelId OR $fLevelId=0)
				                  AND (r.RegionId = $regionId OR $regionId=0)) a
				    ON (t_cfm_masterstockstatus.FacilityId = a.FacilityId
				        AND t_cfm_masterstockstatus.MonthId = $monthId
				        AND t_cfm_masterstockstatus.Year = '$year'
				        AND t_cfm_masterstockstatus.CountryId = $countryId
				        AND t_cfm_masterstockstatus.ItemGroupId = $itemGroupId
				        AND t_cfm_masterstockstatus.StatusId = 5)
				WHERE t_cfm_masterstockstatus.FacilityId IS NULL) b
									WHERE 1=1
									$sWhere
									$sOrder
									$sLimit;";

	$r= mysql_query($sQuery) ;
	$i=1; $j=8;	$monthvar='';
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		       $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		       $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		       $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		       $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		      
		
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['FacilityName'])								
									->SetCellValue('C'.$j,$rec['ClStock']==''? '':number_format($rec['ClStock']))								
									->SetCellValue('D'.$j,$rec['AMC']==''? '':number_format($rec['AMC']))									
									->SetCellValue('E'.$j,$rec['MOS']==''? '':number_format($rec['MOS'],1))								
															
								
									;  			
				
 $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
	             $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			     $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				 $objPHPExcel -> getActiveSheet() -> getStyle('E' . $j . ':E' . $j) -> applyFromArray($styleThinBlackBorderOutline);
				
			 $i++; $j++;
				 
				
		}
	 
	 
		
	 
	
	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}	
	
function getFundingStatusData()
{
  
   global $gTEXT;
    require('../lib/PHPExcel.php');	
	
  
    $objPHPExcel = new PHPExcel();
	
	
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Funding Status'])	;
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
				
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:D2');	
													
    $objPHPExcel->getActiveSheet()
										
									->SetCellValue('A6', '#SL')							
									->SetCellValue('B6', $gTEXT['Category'])
									->SetCellValue('C6', $gTEXT['Planned'])
									->SetCellValue('D6', $gTEXT['Actual'])
									;
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
	
	
	$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	  
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(23);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(16);
	
										
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
   
   $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
   
	$Year = $_GET['Year'];    
    $CountryId = $_GET['Country']; 
	
	if(isset($_GET['Country'])&&!empty($_GET['Country'])){
		$countryQuery=" and p.CountryId='".$CountryId."' ";
	}else{
		$countryQuery="";
	}
	
	$sql="	SELECT g.GroupName,f.FormulationName,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.TotalFund) Total from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r on r.FormulationId=p.FormulationId and r.Year=p.Year and r.CountryId=p.CountryId
			Inner Join t_formulation f on f.FormulationId=r.FormulationId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery."
			group by g.GroupName,p.FormulationId ";
	 
	 
	$r= mysql_query($sql) ;
	$i=1; $j=7;	
	if ($r)
	while($rec=mysql_fetch_array($r))
	{
		
	//$objPHPExcel -> getActiveSheet() -> getStyle('D' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	        if($tempGroupId!=$rec['GroupName']) 
		   {		
	              $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'DAEF62'),
				          )
		           );
		
		   	$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':D'.$j);	
			
	    	$objPHPExcel->getActiveSheet()
											
									->SetCellValue('A'.$j, $rec['GroupName'])								
									
									; 
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':D' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
		   	 	
			$tempGroupId=$rec['GroupName'];$j++;
		   }
	
	$objPHPExcel->getActiveSheet()->getStyle('C') ->getNumberFormat() ->setFormatCode('#,##0');
	$objPHPExcel->getActiveSheet()->getStyle('D') ->getNumberFormat() ->setFormatCode('#,##0');
	
	 		$objPHPExcel->getActiveSheet()
									->SetCellValue('A'.$j, $i)							
									->SetCellValue('B'.$j, $rec['FormulationName'])								
									->SetCellValue('C'.$j, $rec['Y1'])									
									->SetCellValue('D'.$j, $rec['Total'])
									;  			
				
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
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
	
}		

function getMosType()
{
  	
    global $gTEXT;
    require('../lib/PHPExcel.php');	
	$CountryId=$_GET['CountryId']; 
	$FacilityId=$_GET['FacilityId']; 
    $MonthId=$_GET['MonthId']; 
	$YearId=$_GET['YearId'];
    $ItemGroupId=$_GET['ItemGroupId'];
    $mosTypeId = $_REQUEST['MosTypeId'];
	
	$CountryName=$_GET['CountryName'];   
    $MonthName = $_GET['MonthName'];
    $ItemGroupName = $_GET['ItemGroupName'];
    $objPHPExcel = new PHPExcel();
	  
	 $objPHPExcel->getActiveSheet()->SetCellValue('A2',$gTEXT['Facility Inventory Control']);
	 $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	 $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	 $objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');
	 
	    $objPHPExcel->getActiveSheet()->SetCellValue('A3',('Country Name : '. $CountryName). ' , '.('Product Group : '.$ItemGroupName) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A3');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A3:F3');
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue('A4',('Month : '. $MonthName). ' , '.('Year : '.$Year) );
	    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
	    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
	    $objPHPExcel -> getActiveSheet() -> mergeCells('A4:F4');
	 
	 $objPHPExcel->getActiveSheet()
									->SetCellValue('A6',$gTEXT['Product Name'])
									->SetCellValue('B6',$gTEXT['MOS']);
	 $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)),A6);						
	 $objPHPExcel -> getActiveSheet() -> getStyle('A6:A6') -> applyFromArray($styleThinBlackBorderOutline);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(55);							
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(14);
	 $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(14);							 
						$sQuery = "SELECT MosTypeId, MosTypeName, MinMos, MaxMos, ColorCode 
										FROM
										    t_mostype
										WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0)
										ORDER BY MosTypeId;";

												
							$rResult = mysql_query($sQuery);
							$output = array();
						$z=1;	$y=6;	            
				while ($row = mysql_fetch_array($rResult)) {
					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($z, $y, $row['MosTypeName']);	
					$sd=getNameFromNumber($totColumnVal);
				    $objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
				    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $sd . $y);
					$tmpRow['sTitle'] =$row['MosTypeName'] ;
					$tmpRow['sClass'] = 'center-aln';
					$output1[] = $row;
					$z++;
				   }														
								
						
$sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , a.MOS
					,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
					FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
					WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " AND a.Year = '" . $_REQUEST['YearId'] . "' AND a.CountryId = " . $_REQUEST['CountryId'] . " AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
					WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
					ORDER BY ItemName";

	$rResult = mysql_query($sQuery);
	$aData = array();
	$r= mysql_query($sQuery) ;
	$j=7; ;$y=7;
	if ($r)	
	while ($row = mysql_fetch_array($rResult)) {
		
	     {
	 		 $objPHPExcel->getActiveSheet() ->SetCellValue('A'.$j, $row['ItemName']); 
									
			 	$z=1;	
			   foreach ($output1 as $rowMosType) {
			   if ($rowMosType['MosTypeId'] == $row['MosTypeId']) {
				//$tmpRow[] = '<i class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;"></i>';
				// '<td><span class="fa fa-check-circle fa-lg" style="color:' . $rowMosType['ColorCode'] . ';font-size:2.5em;text-align:center;"></span></td>';
				$temp=explode('#',$rowMosType['ColorCode'] );
				
				$styleThinBlackBorderOutline = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
						'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>$temp[1]),
				          )
		           ); 	
				
				    $sd=getNameFromNumber($totColumnVal);
				    $objPHPExcel -> getActiveSheet() -> getStyle($sd . $y . ':'. $sd. $y) -> applyFromArray($styleThinBlackBorderOutline);
					
					
					$z++;
					
			 }
			    else $z++;
				
		     }
		     
			 
			 $y++;
		}
	
	     $j++;	 
	}


	if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
	} else {
			putenv("TZ=UTC");
	}
	$exportTime = date("Y-m-d_His", time()); 
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
	$file = 'ExportExcel'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
	
}

?>