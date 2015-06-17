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
	
    if ($lan == 'en-GB') {
        $FormulationName = 'FormulationName';
    } else {
        $FormulationName = 'FormulationNameFrench';
    }

    $columnsName = "";
    $sql = "SELECT RegMasterId,RegimenName FROM `t_regimen_master`
					where ItemGroupId=" . $ItemGroupId . " Order By RegMasterId ASC;";


    $result = mysql_query($sql, $conn);
    $total = mysql_num_rows($result);
    if ($total > 0) {
        while ($row = mysql_fetch_object($result)) {
            $columnsName.=',{ "sTitle": "' . $row->RegimenName . '","sWidth":"12%"}';
        }
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS YearlyRegPatientId, t_regimen_master.RegimenName, PatientCount,
					t_yearly_country_regimen_patient.FormulationId,$FormulationName	FormulationName
					FROM t_yearly_country_regimen_patient 
					INNER JOIN t_regimen_master ON t_yearly_country_regimen_patient.RegMasterId = t_regimen_master.RegMasterId 
					INNER JOIN t_formulation ON t_yearly_country_regimen_patient.FormulationId = t_formulation.FormulationId 
					WHERE t_yearly_country_regimen_patient.CountryId = '" . $CountryId . "' AND t_yearly_country_regimen_patient.Year = '" . $Year . "'	 
					AND t_regimen_master.ItemGroupId = " . $ItemGroupId . "	 				
					Order BY t_formulation.FormulationId ASC, t_yearly_country_regimen_patient.RegMasterId ASC;";
    //	echo $sql;		
    $result = mysql_query($sql);
    $total = mysql_num_rows($result);
    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query($sQuery);
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];
			   

     mysql_query("SET character_set_results=utf8");                 		 
   /* $r = mysql_query($sql);   
    $total = mysql_num_rows($result); */ 
 
    if($total>0){
            
        require('../lib/PHPExcel.php');	       
        $objPHPExcel = new PHPExcel();
		
		$objPHPExcel -> getActiveSheet() -> getStyle('A') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('B') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('C') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('D') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('E') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel -> getActiveSheet() -> getStyle('F') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->SetCellValue('A2',$SITETITLE)	;
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');

        $objPHPExcel->getActiveSheet()->SetCellValue('A3','Malaria Cases ');
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A3');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A3:F3');		

        $objPHPExcel->getActiveSheet()->SetCellValue('A4','Country: '.$CountryName.', '. 'Product Group: '. $ItemGroupName);
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => false)), 'A4');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A4:F4');

        $objPHPExcel->getActiveSheet()->SetCellValue('A5','Year: '.$Year);
        $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->duplicateStyleArray(array('font' => array('size' => '12', 'bold' => false)), 'A5');						
        $objPHPExcel -> getActiveSheet() -> mergeCells('A5:F5');		
 
        $objPHPExcel->getActiveSheet()
                       		->SetCellValue('A8', 'SL#')							
							->SetCellValue('B8', $gTEXT['Formulation'])
							->SetCellValue('C8', '(0-4 Years)')
							->SetCellValue('D8', '(5-14 Years)')
							->SetCellValue('E8', '(15+ Years)')
							->SetCellValue('F8', 'Pregnant women');
   								
   		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A8');	
   		$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B8');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C8');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'D8');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'E8');
        $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'F8');
        
   		$objPHPExcel -> getActiveSheet() -> getStyle('A8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   		$objPHPExcel -> getActiveSheet() -> getStyle('B8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel -> getActiveSheet() -> getStyle('C8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel -> getActiveSheet() -> getStyle('D8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel -> getActiveSheet() -> getStyle('E8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel -> getActiveSheet() -> getStyle('F8') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
   		$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(10);
   		$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(30);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(15);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setWidth(15);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setWidth(15);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setWidth(15);

   	    $styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
    
        $objPHPExcel->getActiveSheet()->getDefaultStyle('A9')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('A8'.':A8') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('B8'.':B8') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('C8'.':C8') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('D8'.':D8') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('E8'.':E8') -> applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('F8'.':F8') -> applyFromArray($styleThinBlackBorderOutline);
       
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('F8')->getFont()->setBold(true);
		
		$cellIdentifire = array("1" => "A", "2" => "B", "3" => "C", "4" => "D", "5" => "E", "6" => "F", "7" => "G", "8" => "H", "9" => "I", "10" => "J", "11" => "K", "12" => "L", "13" => "M", "14" => "N", "15" => "O", "16" => "P", "17" => "Q", "18" => "R", "19" => "S", "20" => "T", "21" => "U", "22" => "V", "23" => "W", "24" => "X", "25" => "Y", "26" => "Z");

        //$i=1; 
		$j=8;	
		$tmpFormulationId = -1;
		$serial = 0;
		$K = 1;
		$tempGroupId = '';
		while ($aRow = mysql_fetch_array($result)) {
			if ($tmpFormulationId != $aRow['FormulationId']) {
				$i = 3;
				$j++;
				$objPHPExcel->getActiveSheet()
						->SetCellValue('A' . $j, ++$serial)
						->SetCellValue('B' . $j, $aRow['FormulationName'])
						->SetCellValue($cellIdentifire[$i] . $j, number_format($aRow['PatientCount']));

				$tmpFormulationId = $aRow['FormulationId'];
			} else {
				$i++;
				$objPHPExcel->getActiveSheet()
						->SetCellValue($cellIdentifire[$i] . $j, number_format($aRow['PatientCount']));
				$tmpFormulationId = $aRow['FormulationId'];
			}
       								
            $objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);          
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
           // $i++; $j++;
        }

		
        if (function_exists('date_default_timezone_set')) {
        		date_default_timezone_set('UTC');
        } else {
        		putenv("TZ=UTC");
        }
        $exportTime = date("Y-m-d_His", time()); 
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $file = 'Malaria_Cases_'.$exportTime. '.xlsx';
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
