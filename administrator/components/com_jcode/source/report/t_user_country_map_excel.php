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
//$username = $_GET['userName'];
//$name = $_GET['Name'];

$username = isset($_GET['userName'])? $_GET['userName'] :'';
$name = isset($_GET['Name'])? $_GET['Name'] :'';

    $sWhere = "";
	if ($_GET['sSearch'] != "") { 
        $sWhere = " AND (name like '%".mysql_real_escape_string($_GET['sSearch'])."%')";                                                                                         
	}
    $sql = " SELECT SQL_CALC_FOUND_ROWS a.id, name, username, GROUP_CONCAT(title SEPARATOR ', ') title
             FROM ykx9st_users a
             INNER JOIN ykx9st_user_usergroup_map b ON a.id = b.user_id 
             INNER JOIN ykx9st_usergroups c ON b.group_id = c.id           
             WHERE b.group_id IN(3, 10, 11, 12, 13, 14, 15)  ".$sWhere." GROUP BY a.id, name, username ORDER BY name ASC";
			   
    /*$sql = " SELECT SQL_CALC_FOUND_ROWS a.id, name, username, title
               FROM j323_users a
               INNER JOIN j323_user_usergroup_map b ON a.id = b.user_id 
               INNER JOIN j323_usergroups c ON b.group_id = c.id           
               WHERE b.group_id IN(3, 10, 11, 12, 13, 14, 15) 	
               ".$sWhere." order by title, name ";*/
     mysql_query("SET character_set_results=utf8");                 		 
    $r = mysql_query($sql);   
    $total = mysql_num_rows($r);  
 
    if($total>0){
            
        require('../lib/PHPExcel.php');	       
        $objPHPExcel = new PHPExcel();
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('B') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('C') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('D') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('E') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('F') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('G') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		
        $objPHPExcel->getActiveSheet()->SetCellValue('A2',$SITETITLE)	;
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A2:G2');

        $objPHPExcel->getActiveSheet()->SetCellValue('A3',$gTEXT['Country User Map List'])	;
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A3');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A3:G3');		
        										
        $objPHPExcel->getActiveSheet()
                       		->SetCellValue('A6', 'SL#')							
                       		->SetCellValue('B6', $gTEXT['User Name'])
                       		->SetCellValue('C6', $gTEXT['User Group'])
                            ->SetCellValue('D6', $gTEXT['Country Name'])
							->SetCellValue('E6', $gTEXT['Product Group'])
							->SetCellValue('F6', $gTEXT['Owner Type'])
							->SetCellValue('G6', $gTEXT['Region List']);
   								
   		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A6');	
   		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B6');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C6');
		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D6');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E6');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F6');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'G6');
        
   		$objPHPExcel -> getActiveSheet() -> getStyle('A6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   		$objPHPExcel -> getActiveSheet() -> getStyle('B6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('C6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel -> getActiveSheet() -> getStyle('D6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('E6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('F6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('G6') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
   		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
   		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(25);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(30);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(25);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(30);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(30);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(30);

		
   	    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
    
        $objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('A6'.':A6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B6'.':B6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C6'.':C6') -> applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle('D6'.':D6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('E6'.':E6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('F6'.':F6') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('G6'.':G6') -> applyFromArray($styleThinBlackBorderOutline);
        
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
   	
        $i=1; $j=7;	
        $tempGroupId='';
        
        while($rec=mysql_fetch_array($r)){
               
            $data = '';
			$data1 = '';
			$data2 = '';
			$data5 = '';
			
            /*if($tempGroupId!=$rec['title']){
            
                $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
                                                       'fill' => array(
                                                       'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                                       'color' => array('rgb'=>'DAEF62'),
                                                       ));
            
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$j.':F'.$j);	
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $rec['title']);
                				
                $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':F' . $j) -> applyFromArray($styleThinBlackBorderOutline1);	   	 	
                $tempGroupId=$rec['title'];$j++;
            }*/
            $sql_2 = "  SELECT CountryName
                        FROM t_user_country_map a 
                        INNER JOIN t_country b ON (a.CountryId = b.CountryId AND a.UserId = '".$rec['username']."')
                        ORDER BY CountryName ";
            $r_2 = mysql_query($sql_2) ;
            
            $h = 0;
            while($rec_2 = mysql_fetch_array($r_2)){
               if($h++) $data.= ", ";
               $data.= $rec_2['CountryName']; 
                                                        
            } 
             
			 $sql_3 = " SELECT  a.ItemGroupMapId, a.UserId, b.ItemGroupId, GroupName
			            FROM t_user_itemgroup_map a 
			            INNER JOIN t_itemgroup b ON (a.ItemGroupId = b.ItemGroupId AND a.UserId ='".$rec['username']."')
			            ORDER BY GroupName ";
            $r_3 = mysql_query($sql_3) ;
            
            $h = 0;
            while($rec_3 = mysql_fetch_array($r_3)){
               if($h++) $data1.= ", ";
               $data1.= $rec_3['GroupName']; 
                                                        
            } 
             $sql_4 = " SELECT  a.OwnerTypeMapId, a.UserId, b.OwnerTypeId, OwnerTypeName
						 FROM t_user_owner_type_map a 
						 INNER JOIN t_owner_type b ON (a.OwnerTypeId = b.OwnerTypeId AND a.UserId = '".$rec['username']."')
						 ORDER BY OwnerTypeName";
            $r_4 = mysql_query($sql_4) ;
            
            $h = 0;
            while($rec_4 = mysql_fetch_array($r_4)){
               if($h++) $data2.= ", ";
               $data2.= $rec_4['OwnerTypeName']; 
                                                        
            } 
		$sql_5 = " SELECT SQL_CALC_FOUND_ROWS a.RegionMapId, a.UserId, b.RegionId, IF(a.RegionMapId IS NULL,'false','true') chkValue, RegionName
					FROM t_user_region_map a 
					INNER JOIN t_region b ON (a.RegionId = b.RegionId AND a.UserId = '".$rec['username']."')
					ORDER BY RegionName;";
            $r_5 = mysql_query($sql_5) ;
            
            $h = 0;
            while($rec_5 = mysql_fetch_array($r_5)){
               if($h++) $data5.= ", ";
               $data5.= $rec_5['RegionName']; 
                                                        
            }	
       		$objPHPExcel->getActiveSheet()
       								->SetCellValue('A'.$j, $i)							
       								->SetCellValue('B'.$j, $rec['name'])
       								->SetCellValue('C'.$j, $rec['title'])
                                    ->SetCellValue('D'.$j, $data)
									->SetCellValue('E'.$j, $data1)
									->SetCellValue('F'.$j, $data2)
									->SetCellValue('G'.$j, $data5);
       								
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);          
            							  			
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
        $file = 'user_country_map_'.$exportTime. '.xlsx';
        $objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
        header('Location:media/' . $file);

    } else{
   	    echo 'No record found';
    }


    function getcheckBox($v){ 
        if ($v == "true") {
            $x="<input type='checkbox' checked class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
        } else {
            $x="<input type='checkbox' class='datacell' value='".$v."' /><span class='custom-checkbox'></span>";
        } 
        return $x;
    }
?>
