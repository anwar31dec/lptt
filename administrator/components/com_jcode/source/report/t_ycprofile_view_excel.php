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

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	
	case'getYcPledgedFunding':
		  	getYcPledgedFunding();
    break;	
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


function getYcPledgedFunding()
{

   global $gTEXT;
    require('../lib/PHPExcel.php');	

	$lan=$_REQUEST['lan']; 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	}	
		
	$lan = $_GET['lan'];
	 if($lan == 'en-GB'){
            $ServiceTypeName = 'ServiceTypeName';
            $FundingReqSourceName = 'FundingReqSourceName';
        }else{
            $ServiceTypeName = 'ServiceTypeNameFrench';
            $FundingReqSourceName = 'FundingReqSourceNameFrench';
		  }
	
	$CountryId = $_GET['CountryId'];
	$CountryName=$_GET['CountryName'];
    $Year = $_GET['Year'];
	//echo $Year. ' nazim masija ';
	$RequirementYear = $_GET['RequirementYear'];
	$ItemGroupId = $_GET['ItemGroupId'];
	
    $objPHPExcel = new PHPExcel();	

	$objPHPExcel->getActiveSheet()->SetCellValue('A2',$SITETITLE);
	$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	$objPHPExcel -> getActiveSheet() -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '16', 'bold' => true)), 'A2');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A2:F2');	
	
	$objPHPExcel->getActiveSheet()->SetCellValue('A3',$gTEXT['Country Profile']. ' of '.($CountryName). ' '. ($Year));
	$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
	$objPHPExcel -> getActiveSheet() -> getStyle('A3') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '14', 'bold' => true)), 'A3');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A3:F3');	
						
	$objPHPExcel->getActiveSheet()->SetCellValue('A5',$gTEXT['Pledged Funding']);
	$styleThinBlackBorderOutline = array('borders' => array('outline'   => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'  => array('argb' => 'FF000000') )));
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont();
    $objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '13','bold' => true)), 'A5');
	$objPHPExcel -> getActiveSheet() -> mergeCells('A5:F5');

	$cellIdentifire = array("1"=>"A","2"=>"B","3"=>"C","4"=>"D","5"=>"E","6"=>"F","7"=>"G","8"=>"H","9"=>"I","10"=>"J","11"=>"K","12"=>"L","13"=>"M","14"=>"N","15"=>"O","16"=>"P","17"=>"Q","18"=>"R","19"=>"S","20"=>"T","21"=>"U","22"=>"V","23"=>"W","24"=>"X","25"=>"Y","26"=>"Z");			
	$j=6;
	$columnsName="";		
		$sql = "SELECT SQL_CALC_FOUND_ROWS t_yearly_country_fundingsource.FundingSourceId,FundingSourceName FROM t_yearly_country_fundingsource
		INNER JOIN t_fundingsource ON t_yearly_country_fundingsource.FundingSourceId=t_fundingsource.FundingSourceId
				where Year ='".$Year."' AND CountryId ='".$CountryId."' AND t_fundingsource.ItemGroupId = '".$ItemGroupId."'
				Order By FundingSourceId;";
				
		$result=mysql_query($sql);
		$total=mysql_num_rows($result);
		$subTotal = array();
		$grandTotal = array();
		if($total>0){
			if($lan == 'en-GB'){
			
			$objPHPExcel->getActiveSheet()
				->SetCellValue('A'.$j, 'SL')
				->SetCellValue('B'.$j, 'Category')
				->SetCellValue('C'.$j, 'Total Requirements');
				
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A'.$j);	
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B'.$j);
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C'.$j);	

			$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(50);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(40); 
			
			$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
			 
			$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);
			
			$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j  . ':A'.$j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j  . ':B'.$j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j  . ':C'.$j) -> applyFromArray($styleThinBlackBorderOutline);	

			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);
			
			}
       else{
			$objPHPExcel->getActiveSheet()
				->SetCellValue('A'.$j, 'SL')
				->SetCellValue('B'.$j, 'catégorie')
				->SetCellValue('C'.$j, 'total des besoins');
				
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'A'.$j);	
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'B'.$j);
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), 'C'.$j);	

			$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setWidth(15);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(50);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setWidth(40); 

			$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FF000000') )));
			 
			$objPHPExcel->getActiveSheet()->getDefaultStyle('A7')->getAlignment()->setWrapText(true);			
			
			$objPHPExcel -> getActiveSheet() -> getStyle('A'.$j  . ':A'.$j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('B'.$j  . ':B'.$j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('C'.$j  . ':C'.$j) -> applyFromArray($styleThinBlackBorderOutline);	

			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->getFont()->setBold(true);

          }	
			$i= 4;
            while($row=mysql_fetch_object($result)){
			$objPHPExcel->getActiveSheet()
				->SetCellValue($cellIdentifire[$i].$j, $row->FundingSourceName);
				
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i].$j);	
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i]) -> setWidth(15);
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j  . ':'.$cellIdentifire[$i].$j) -> applyFromArray($styleThinBlackBorderOutline);	
			$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i].$j)->getFont()->setBold(true);

			$i++;
			
			}
			
		if($lan == 'en-GB'){		
			$objPHPExcel->getActiveSheet()
				->SetCellValue($cellIdentifire[$i].$j, 'Total')
				->SetCellValue($cellIdentifire[$i+1].$j, 'Gap/Surplus');
				
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i].$j);	
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i+1].$j);
			
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i+1].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i]) -> setWidth(15);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i+1]) -> setWidth(15);
			
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j  . ':'.$cellIdentifire[$i].$j) -> applyFromArray($styleThinBlackBorderOutline);	
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i+1].$j  . ':'.$cellIdentifire[$i+1].$j) -> applyFromArray($styleThinBlackBorderOutline);	
			
			$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i].$j)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i+1].$j)->getFont()->setBold(true);

			}
        else{
			$objPHPExcel->getActiveSheet()
				->SetCellValue($cellIdentifire[$i].$j, 'Total')
				->SetCellValue($cellIdentifire[$i+1].$j, 'Gap/Surplus');
				
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i].$j);	
			$objPHPExcel -> getActiveSheet() -> duplicateStyleArray(array('font' => array('size' => '12', 'bold' => true)), $cellIdentifire[$i+1].$j);
			
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i+1].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i]) -> setWidth(15);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellIdentifire[$i+1]) -> setWidth(15);
			
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j  . ':'.$cellIdentifire[$i].$j) -> applyFromArray($styleThinBlackBorderOutline);	
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i+1].$j  . ':'.$cellIdentifire[$i+1].$j) -> applyFromArray($styleThinBlackBorderOutline);	
			
			$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i].$j)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($cellIdentifire[$i+1].$j)->getFont()->setBold(true);
		
          }							
		}				  
       	
		$YValue = 'a.Y'.$RequirementYear;
			
		$sql="SELECT d.ServiceTypeId, d.$ServiceTypeName ServiceTypeName, d.ServiceTypeNameFrench,
			b.FundingReqSourceId, b.$FundingReqSourceName FundingReqSourceName, b.FundingReqSourceNameFrench, IFNULL($YValue,0) YReq
			FROM t_yearly_funding_requirements a
			INNER JOIN t_fundingreqsources b ON a.FundingReqSourceId = b.FundingReqSourceId
			INNER JOIN t_servicetype d ON b.ServiceTypeId = d.ServiceTypeId						
			WHERE a.CountryId = ".$CountryId."
			AND  a.ItemGroupId = ".$ItemGroupId."
			AND a.Year = '".$Year."'
			ORDER BY b.ServiceTypeId, b.FundingReqSourceId;";		
		//echo $sql;				
		$result = mysql_query($sql);
		$tmpServiceTypeId = -1;
		$tmpServiceTypeName = ' ';
		//$sOutput = '"aaData": [ ';		
		$f = 0;		
		$sl = 0;
		$j=$j+1;
		$tempGroupId='';
		while ($aRow = mysql_fetch_array($result)) {		
			$Total = 0;
			$YReq = $aRow['YReq'];
			
			$FundingReqSourceId = $aRow['FundingReqSourceId'];
			
			if($tmpServiceTypeId != $aRow['ServiceTypeId']){
				if($sl > 0){
					$count = count($subTotal);
					$j++;
					for($i=0; $i<$count; $i++){	
						if($i > 1){
						$subTotals = number_format($subTotal[$i]);
						}else{		
						$subTotals = $subTotal[$i];				
						}
					$objPHPExcel->getActiveSheet() ->SetCellValue($cellIdentifire[$i+1].$j, $subTotals); 					
				}
					
					unset($subTotal);
				}
				$subTotal[0] = $aRow['ServiceTypeName'].' Total';
				$subTotal[1] = '';//$aRow['ServiceTypeName'];
				//$subTotal[2] = '';
				
				$grandTotal[0] = 'Grand Total';
				$grandTotal[1] = '';
				//$grandTotal[2] = '';				
				}
				
			@$subTotal[2] = @$subTotal[2] + $YReq;
			@$grandTotal[2] = @$grandTotal[2] + $YReq;
				
			if($sl==0)
				$tmpServiceTypeId = $aRow['ServiceTypeId'];
				
			//$tmpServiceTypeName
			//$j=$j+1;
			if($tempGroupId!=$aRow['ServiceTypeName']) 
			   {		   				
					  $styleThinBlackBorderOutline1 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
							'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb'=>'DAEF62'),
							  )
					   );
				$objPHPExcel -> getActiveSheet() -> mergeCells('A'.$j.':'.$cellIdentifire[$i+1].$j);	
				
				$objPHPExcel->getActiveSheet()											
										->SetCellValue('A'.$j, $aRow['ServiceTypeName']); 
				$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':' . $cellIdentifire[$i+1].$j) -> applyFromArray($styleThinBlackBorderOutline1);		   	 	
				$tempGroupId=$aRow['ServiceTypeName'];
				//$j++;
			   }			
			
			$j=$j+1;
			//if ($f++)
			$objPHPExcel->getActiveSheet()
						->SetCellValue('A'.$j, ++$sl)
						->SetCellValue('B'.$j, $aRow['FundingReqSourceName'])
						->SetCellValue('C'.$j, number_format($YReq));
						
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
			$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':C' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
			$objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':D' . $j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	

			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':A' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('B' . $j . ':B' . $j) -> applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':C' . $j) -> applyFromArray($styleThinBlackBorderOutline);	
			
				$sql1="SELECT a.PledgedFundingId, b.FundingSourceId, b.FundingSourceName, IFNULL($YValue,0) YCurr	
					FROM t_yearly_pledged_funding a
					INNER JOIN t_fundingsource b ON a.FundingSourceId = b.FundingSourceId								
					WHERE a.CountryId = ".$CountryId."
					AND  a.ItemGroupId = ".$ItemGroupId."
					AND a.Year = '".$Year."'
					AND a.FundingReqSourceId = ".$aRow['FundingReqSourceId']."
					ORDER BY b.FundingSourceId;";		
				
					$sResult = mysql_query($sql1);
					$index = 2;
					$i= 4;
					while ($r = mysql_fetch_array($sResult)){					
						$objPHPExcel->getActiveSheet()
									->SetCellValue($cellIdentifire[$i].$j, number_format($r['YCurr']));
						$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j . ':' .$cellIdentifire[$i].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);									
						$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j . ':' . $cellIdentifire[$i].$j) -> applyFromArray($styleThinBlackBorderOutline);			
						$i++;	
					
						$Total+= $r['YCurr'];
						
						$index++;
						@$subTotal[$index] = @$subTotal[$index] + $r['YCurr'];
						@$grandTotal[$index] = @$grandTotal[$index] + $r['YCurr'];
						
					}
			
			$objPHPExcel->getActiveSheet()		
						->SetCellValue($cellIdentifire[$i].$j, number_format($Total));			
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j . ':' . $cellIdentifire[$i].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i].$j . ':' . $cellIdentifire[$i].$j) -> applyFromArray($styleThinBlackBorderOutline);
			$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
			@$subTotal[$index+1]+= $Total;
			@$grandTotal[$index+1]+= $Total;
			
			$objPHPExcel->getActiveSheet()		
						->SetCellValue($cellIdentifire[$i+1].$j, number_format($YReq-$Total));			
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i+1].$j . ':' . $cellIdentifire[$i+1].$j) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i+1].$j . ':' . $cellIdentifire[$i+1].$j) -> applyFromArray($styleThinBlackBorderOutline);
			$styleThinBlackBorderOutline = array('borders' => array('outline'=> array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color'=> array('argb' => 'FF000000') )));
			
			//$sOutput .=	'"' . number_format(($YReq-$Total)) .'"';//'"'.getTextBox1(number_format(($YReq-$Total),1),'ycgaporsurplus_'.$FundingReqSourceId,$aRow['PledgedFundingId']). '"';
			@$subTotal[$index+2] = $subTotal[$index+2] + ($YReq-$Total);
			@$grandTotal[$index+2] = $grandTotal[$index+2] + ($YReq-$Total);
			
			//$subTotal[$index+3] = '';
			//$grandTotal[$index+3] = '';
		}
		
		$count = count($subTotal);
		$j++;

		for($i=0; $i<$count; $i++){	

			if($i > 1){
			$subTotals = number_format($subTotal[$i]);
			$gTotal = number_format($grandTotal[$i]);
		    }else{		
			$subTotals = $subTotal[$i];		
			$gTotal = $grandTotal[$i];		
			}
			$styleThinBlackBorderOutline3 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
				'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb'=>'fe9929'),
				  )
			);		
			$objPHPExcel->getActiveSheet() ->SetCellValue($cellIdentifire[$i+1].$j, $subTotals);
			$objPHPExcel -> getActiveSheet() -> getStyle('A' . $j . ':' . $cellIdentifire[$count].$j) -> applyFromArray($styleThinBlackBorderOutline3);					
			$objPHPExcel -> getActiveSheet() -> getStyle('C' . $j . ':' . $cellIdentifire[$count].($j)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
		  $styleThinBlackBorderOutline2 = array('borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FF000000'),)),
				'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb'=>'50abed'),
				  )
		   );		   			
			$objPHPExcel->getActiveSheet() ->SetCellValue($cellIdentifire[$i+1].($j+1), $gTotal); 			
			$objPHPExcel -> getActiveSheet() -> getStyle($cellIdentifire[$i+1].($j+1). ':' . $cellIdentifire[$count].($j+1)) -> applyFromArray($styleThinBlackBorderOutline2);
			$objPHPExcel -> getActiveSheet() -> getStyle('C'.($j+1) . ':' . $cellIdentifire[$count].($j+1)) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		}

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
?>