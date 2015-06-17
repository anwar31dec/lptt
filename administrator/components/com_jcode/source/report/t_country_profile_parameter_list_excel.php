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

$CountryId = isset($_GET['CountryId'])? $_GET['CountryId'] :'';
$Year = isset($_GET['Year'])? $_GET['Year'] :'';
$ItemGroupId = isset($_GET['ItemGroupId'])? $_GET['ItemGroupId'] :'';
$RequirementYear = isset($_GET['RequirementYear'])? $_GET['RequirementYear'] :'';
$CountryName = isset($_GET['CountryName'])? $_GET['CountryName'] :'';
$ItemGroupName = isset($_GET['ItemGroupName'])? $_GET['ItemGroupName'] :'';

   /* $sWhere = "";
	if ($_GET['sSearch'] != "") { 
        $sWhere = " AND (name like '%".mysql_real_escape_string($_GET['sSearch'])."%')";                                                                                         
	}*/
	
    $sql = " SELECT SQL_CALC_FOUND_ROWS @rank:=@rank+1 AS SL, ParamName, YCValue, t_cprofileparams.ParamId
			FROM   t_ycprofile 
			INNER JOIN t_cprofileparams ON t_ycprofile.ParamId = t_cprofileparams.ParamId 
			WHERE t_ycprofile.CountryId = '".$CountryId."' AND t_ycprofile.Year = '".$Year."'
			AND t_ycprofile.ItemGroupId='".$ItemGroupId."'
			Order By t_ycprofile.ParamId ";
			   

     mysql_query("SET character_set_results=utf8");                 		 
    $r = mysql_query($sql);   
    $total = mysql_num_rows($r);  
 
    if($total>0){
            
        require('../lib/PHPExcel.php');	       
        $objPHPExcel = new PHPExcel();
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('B') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('C') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		//$objPHPExcel -> getActiveSheet() -> getStyle('D') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->SetCellValue('A2',$SITETITLE)	;
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A2:C2');

        $objPHPExcel->getActiveSheet()->SetCellValue('A3','Parameter List');
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A3');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A3:C3');		

        $objPHPExcel->getActiveSheet()->SetCellValue('A4',$CountryName.' - '. $ItemGroupName.' - '. $Year);
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => false)), 'A4');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A4:C4');
/*
        $objPHPExcel->getActiveSheet()->SetCellValue('A5','Year: '.$Year);
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => false)), 'A5');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A5:C5');		
 */
        $objPHPExcel->getActiveSheet()
                       		->SetCellValue('A8', 'SL#')							
                       		->SetCellValue('B8', 'Parameter')
                       		->SetCellValue('C8', 'Value');
   								
   		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A8');	
   		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B8');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C8');
        
   		$objPHPExcel -> getActiveSheet() -> getStyle('A8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   		$objPHPExcel -> getActiveSheet() -> getStyle('B8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('C8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
   		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
   		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(40);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(20);

   	    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
    
        $objPHPExcel->getActiveSheet()->getDefaultStyle('A9')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('A8'.':A8') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B8'.':B8') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C8'.':C8') -> applyFromArray($styleThinBlackBorderOutline);
       
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);

   	
        $i=1; $j=9;	

        while($rec=mysql_fetch_array($r)){
 	
       		$objPHPExcel->getActiveSheet()
       								->SetCellValue('A'.$j, $i)							
       								->SetCellValue('B'.$j, $rec['ParamName'])
       								->SetCellValue('C'.$j, $rec['YCValue']);
       								
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);          
            $objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);          
            							  			
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
        $file = 'Parameter_List _'.$exportTime. '.xlsx';
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
