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

    $ARegionId=$_GET['ARegionId']; 
    $CountryId	=$_GET['CountryId']; 
    $FacilityLevel=$_GET['FacilityLevel']; 
    $FacilityType=$_GET['FacilityType'];
    $CountryName = $_GET['CountryName'];
    $RegionName = $_GET['RegionName'];
    $FTypeName = $_GET['FTypeName'];
    $FLevelName = $_GET['FLevelName'];
	 $OwnerTypeId = $_GET['OwnerType']; 
    $DistrictId = $_GET['District-list'];
    $ServiceAreaId = $_GET['ServiceAreaId'];
	$OwnerTypeName = $_GET['OwnerTypeName'];
    $DistrictName = $_GET['DistrictName'];
    $ServiceArealName = $_GET['ServiceAreaName'];
	
    if($ARegionId){
		$ARegionId = " AND a.RegionId = '".$ARegionId."' ";
	}    
    if($FacilityType){
		$FacilityType = " AND a.FTypeId = '".$FacilityType."' ";
	}
    if($FacilityLevel){
		$FacilityLevel = " AND a.FLevelId = '".$FacilityLevel."' ";
	}  
    
    if($OwnerTypeId){
		$OwnerTypeId = " AND a.OwnerTypeId = '".$OwnerTypeId."' ";
	} 
    
    if($DistrictId){
		$DistrictId = " AND a.DistrictId = '".$DistrictId."' ";
	}
    
    if($ServiceAreaId){
		$ServiceAreaId = " AND a.ServiceAreaId = '".$ServiceAreaId."' ";
	}
    
    $sWhere = "";
	if ($_GET['sSearch'] != "") { 
        $sWhere = " AND (FacilityCode like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FTypeName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR RegionName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FLevelName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityAddress like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityPhone like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityFax like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityEmail like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR FacilityManager like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR DistrictName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR OwnerTypeName like '%".mysql_real_escape_string($_GET['sSearch'])."%'
                    OR ServiceAreaName like '%".mysql_real_escape_string($_GET['sSearch'])."%' ) ";                                                                                         
	}
    
    $sLimit = "";
	if (isset($_GET['iDisplayStart'])) { 
	   $sLimit = "limit " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
	}
    
    $sOrder = "";
	if (isset($_GET['iSortCol_0'])) { $_GET = " ORDER BY FLevelName, ";
		for ($i = 0; $i < mysql_real_escape_string($_GET['iSortingCols']); $i++) {
			$sOrder .= fnColumnToGetFacility(mysql_real_escape_string($_GET['iSortCol_' . $i])) . "" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
 
	$sql = " SELECT SQL_CALC_FOUND_ROWS FacilityId, a.CountryId, a.RegionId, ParentFacilityId, 
             a.FTypeId, a.FLevelId, FacilityCode, FacilityName, FacilityAddress, FacilityPhone, FacilityFax, FacilityEmail, 
             FacilityManager, Latitude, Longitude, FacilityCount, FLevelName, FTypeName, RegionName,
             a.DistrictId, a.OwnerTypeId, a.ServiceAreaId, e.DistrictName, f.OwnerTypeName, g.ServiceAreaName, a.AgentType
             FROM t_facility a
             INNER JOIN t_facility_level b ON a.FLevelId = b.FLevelId
             INNER JOIN t_facility_type c ON a.FTypeId = c.FTypeId
             INNER JOIN t_region d ON a.RegionId = d.RegionId
             INNER JOIN t_districts e ON a.DistrictId = e.DistrictId
             INNER JOIN t_owner_type f ON a.OwnerTypeId = f.OwnerTypeId
             INNER JOIN t_service_area g ON a.ServiceAreaId = g.ServiceAreaId 	
             AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 
             ".$ARegionId." ".$DistrictId." ".$OwnerTypeId." ".$ServiceAreaId." ".$FacilityType." ".$FacilityLevel." 
             ".$sWhere." ".$sOrder." ".$sLimit." ORDER BY FLevelName,FacilityCode "; 
     mysql_query("SET character_set_results=utf8");          
	$r= mysql_query($sql) ;
	$total = mysql_num_rows($r);
	
	if ($total>0){
		
    require('../lib/PHPExcel.php'); 
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $SITETITLE )	;
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');	
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:K2');

    $objPHPExcel->getActiveSheet()->SetCellValue('A3',$gTEXT['Facility List'] )	;
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A3');	
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:K3');

    $objPHPExcel->getActiveSheet()->SetCellValue('A4',($CountryName.' - '.$RegionName.' - '.$DistrictName.' - '.$FTypeName));
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A4') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A4');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A4:K4');

    $objPHPExcel->getActiveSheet()->SetCellValue('A5',($FLevelName.' - '.$ServiceArealName.' - '.$OwnerTypeName));
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A5') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A5');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A5:K5');
/*
    $objPHPExcel->getActiveSheet()->SetCellValue('A6',($gTEXT['Facility Level'].' : '. $FLevelName).' ,   '.($gTEXT['District Name'].' : '. $DistrictName) );
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A6');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A6:K6');
	
	$objPHPExcel->getActiveSheet()->SetCellValue('A7',($gTEXT['Region Name'].' : '.$RegionName).' ,   '.($gTEXT['Service Area Name'].' : '. $ServiceArealName) );
    $styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel -> getActiveSheet() -> getStyle('A7') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A7')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12')), 'A7');
    $objPHPExcel -> getActiveSheet() -> mergeCells('A7:K7');		
													*/
    $objPHPExcel->getActiveSheet()		
                                    ->SetCellValue('A10', 'SL#')							
                                    ->SetCellValue('B10',$gTEXT['Facility Code'] )
                                    ->SetCellValue('C10',$gTEXT['Facility Name'] )
                                    ->SetCellValue('D10',$gTEXT['Facility Type'] )
                                    ->SetCellValue('E10',$gTEXT['Region Name'] )
									->SetCellValue('F10',$gTEXT['District'] )
                                    ->SetCellValue('G10',$gTEXT['Owner Type'] )
									->SetCellValue('H10','PPM')
                                    ->SetCellValue('I10',$gTEXT['Service Area'])
                                    ->SetCellValue('J10',$gTEXT['Facility Address'])
                                    ->SetCellValue('K10',$gTEXT['Assigned Group']);
									
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A10');	
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'H10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'I10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'J10');
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'K10');

	$objPHPExcel -> getActiveSheet() -> getStyle('A10') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(22);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('I') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('J') -> setWidth(20);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension('K') -> setWidth(20);
										
    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
 
    $objPHPExcel -> getActiveSheet() -> getDefaultStyle('A11') -> getAlignment()-> setWrapText(true);
    $objPHPExcel -> getActiveSheet() -> getStyle('A10'  . ':A10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('B10'  . ':B10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('C10'  . ':C10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('D10'  . ':D10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('E10'  . ':E10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('F10'  . ':F10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('G10'  . ':G10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('H10'  . ':H10') -> applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel -> getActiveSheet() -> getStyle('I10'  . ':I10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('J10'  . ':J10') -> applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel -> getActiveSheet() -> getStyle('K10'  . ':K10') -> applyFromArray($styleThinBlackBorderOutline);
       
    $objPHPExcel->getActiveSheet()->getStyle('A10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('C10')->getFont()->setBold(true);		
    $objPHPExcel->getActiveSheet()->getStyle('D10')->getFont()->setBold(true);		
    $objPHPExcel->getActiveSheet()->getStyle('E10')->getFont()->setBold(true);		
    $objPHPExcel->getActiveSheet()->getStyle('F10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('G10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('H10')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('J10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('K10')->getFont()->setBold(true);
   
   
    $i=1; $j=11;
    $tempGroupId='';
	
	while($rec=mysql_fetch_array($r)){
	   	$agentType = $rec['AgentType'];
	   
	    if($rec['ParentFacilityId'] == NULL) $rec['ParentFacilityId']=0;
        
//        $sql_parent = " SELECT FacilityName PFacilityName
//                        FROM t_facility
//                        WHERE FacilityId = ".$rec['ParentFacilityId']." "; 
//        $pacrs_parent = mysql_query($sql_parent);
//        $row = mysql_fetch_object($pacrs_parent);
//        $PFacilityName = $row -> PFacilityName; 
//        
//        if($PFacilityName == "")$PFacilityName='None';
                        
        $sql_group = "  SELECT FacilityId, GroupName
                        FROM t_facility_group_map a
                        INNER JOIN t_itemgroup b ON a.ItemGroupId = b.ItemGroupId
                        WHERE FacilityId = ".$rec['FacilityId']." "; 
        $pacrs_group = mysql_query($sql_group);
        $group_name = "";
        
        $x = 0;
        while ($row_group = @mysql_fetch_object($pacrs_group)) {	  
            if ($x++) $group_name.= ", ";
                $group_name.= $row_group -> GroupName;           
        }	
        
        
        if($tempGroupId!=$rec['FLevelName']){
        
            $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
                                                'fill' => array(
                                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('rgb'=>'DAEF62'),));
            $objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':K'.$j);	
            $objPHPExcel->getActiveSheet()			
            								->SetCellValue('A'.$j, $rec['FLevelName']); 
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':K' . $j) -> applyFromArray($styleThinBlackBorderOutline1);
            $tempGroupId=$rec['FLevelName'];$j++;
        }	
        $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        if ($rec['AgentType'] == 0)
            $objDrawing->setPath('image/unchecked.png');
        else
            $objDrawing->setPath('image/checked.png');

        $objDrawing->setCoordinates('H' . $j);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		
		
        $objPHPExcel->getActiveSheet()
        						->SetCellValue('A'.$j, $i)							
        						->SetCellValue('B'.$j, $rec['FacilityCode'])	
        						->SetCellValue('C'.$j, $rec['FacilityName'])	
        						->SetCellValue('D'.$j, $rec['FTypeName'])
        						->SetCellValue('E'.$j, $rec['RegionName'])
								->SetCellValue('F'.$j, $rec['DistrictName'])	
        						->SetCellValue('G'.$j, $rec['OwnerTypeName'])

        						->SetCellValue('I'.$j, $rec['ServiceAreaName'])				
        						->SetCellValue('J'.$j, $rec['FacilityAddress'])	
        						->SetCellValue('K'.$j, $group_name);  			
        			
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
        $objPHPExcel -> getActiveSheet() -> getStyle('J' . $j . ':J' . $j) -> applyFromArray($styleThinBlackBorderOutline);
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
	$file = 'Health_Facility_'.$exportTime. '.xlsx';
	$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
	header('Location:media/' . $file);
    }
    else{
        $error = "No record found";	
        echo $error;
    }


?>