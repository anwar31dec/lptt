<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
include_once ("../function_lib.php");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query('SET CHARACTER SET utf8');

error_reporting(0);

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 

$task = '';
if (isset($_POST['action'])) {
	$task = $_POST['action'];
} else if (isset($_GET['action'])) {
	$task = $_GET['action'];
}

switch($task) {
	case 'preparePatientTrendReport' :
		preparePatientTrendReport($conn);
		break;
	case 'generatePatientTrendReport' :
		generatePatientTrendReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function preparePatientTrendReport($conn){
	
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);  
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    	require_once(dirname(__FILE__).'/lang/eng.php');
    	$pdf->setLanguageArray($l);
    }
    
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->AddPage();
   
    ini_set('magic_quotes_gpc', 'off');
    $html=htmlentities($_POST['html'], ENT_QUOTES, "UTF-8");
    $html=html_entity_decode($html, ENT_QUOTES, "UTF-8");
    
    $alavel=htmlentities($_POST['alavel'], ENT_QUOTES, "UTF-8");
    $alavel=html_entity_decode($alavel, ENT_QUOTES, "UTF-8");

    $filePath = SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/patient_trend.svg'; 
    
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
    fclose($file);
    
    $pdf->ImageSVG($file='pdfslice/patient_trend.svg', $x=20, $y=20, $w=180, $h=100, $link='', $align='', $palign='center', $border=0, $fitonpage=false);
    
    $html2 = <<<EOF
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
    </style>
    <body>
        <div id="barchartlegend">
            $alavel
        </div>
    </body>
EOF;
    echo $html2;
    $pdf->writeHTMLCell($w=150, $h=30, $x=15, $y=130, $html2, $border=0, $ln=0, $fill=false, $reseth=true, $align='middle', $autopadding=true);
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/PatientTrendChart.pdf';
    
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    
	$pdf->Output('pdfslice/PatientTrendChart.pdf', 'F');
}

function generatePatientTrendReport($conn){
        
   	global $gTEXT;
    $CountryName = $_POST['CountryName']; 
        
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    $pdf = new FPDI();
    
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
    
    
    $StartMonthId = $_POST['StartMonthId']; 
    $StartYearId = $_POST['StartYearId']; 
    $EndMonthId = $_POST['EndMonthId']; 
    $EndYearId = $_POST['EndYearId'];
    $frequencyId = 1;
    if($_POST['MonthNumber'] != 0){
        $months = $_POST['MonthNumber'];
        $monthIndex = date("m");
        $yearIndex = date("Y");
		 settype($yearIndex, "integer");    
		if ($monthIndex == 1){
			$monthIndex = 12;				
			$yearIndex = $yearIndex - 1;				
		}else{
			$monthIndex = $monthIndex - 1;
		}
		$months = $months - 1;  
			   
		$d=cal_days_in_month(CAL_GREGORIAN,$monthIndex,$yearIndex);
		$EndYearMonth = $yearIndex."-".str_pad($monthIndex,2,"0",STR_PAD_LEFT)."-".$d; 
		$EndYearMonth = date('Y-m-d', strtotime($EndYearMonth));	
		
		$StartYearMonth = $yearIndex."-".str_pad($monthIndex,2,"0",STR_PAD_LEFT)."-"."01"; 
		$StartYearMonth = date('Y-m-d', strtotime($StartYearMonth));	
		$StartYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($StartYearMonth)) . "-".$months." month"));
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";	
		$StartYearMonth = date('Y-m-d', strtotime($startDate));	
		
		$d=cal_days_in_month(CAL_GREGORIAN,$EndMonthId,$EndYearId);
    	$endDate = $EndYearId."-".$EndMonthId."-".$d;	
		$EndYearMonth = date('Y-m-d', strtotime($endDate));	    	
    }
	
	
	
	$monthListShort = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
	$quarterList = array(3 => 'Jan-Mar', 6 => 'Apr-Jun', 9 => 'Jul-Sep', 12 => 'Oct-Dec');
	
	
	
	$output = array('aaData' => array());
	$aData = array();
	$output2 = array();
		
		if($frequencyId == 1)
			$monthQuarterList = $monthListShort;
		else 
			$monthQuarterList = $quarterList;
 	
	$month_list = array();
	$startDate = strtotime($StartYearMonth);
	$endDate   = strtotime($EndYearMonth);
	$index=0;
	// while ($endDate >= $startDate) {	
		// $month_list[$index] = date('M Y',$startDate);
		// $index++;
	    // $startDate = strtotime( date('Y/m/d',$startDate).' 1 month');
	// }
	while ($endDate >= $startDate) {
			if($frequencyId == 1){
					$monthid=date('m',$startDate);
					settype($monthid,"integer");
					$ym= $monthListShort[$monthid].' '.date('Y',$startDate);				
					$month_list[$index] = $ym;
					$output['Categories'][] = $ym;	
					$index++;
					}				
				else{
					$monthid=date('m',$startDate);
					settype($monthid,"integer");
					if($monthid==3 || $monthid==6 || $monthid==9 || $monthid==12){
						$ym=$quarterList[$monthid].' '.date('Y',$startDate);
						$month_list[$index] = $ym;
						$output['Categories'][] = $ym;	
						$index++;
						}
					}				
		
	    $startDate = strtotime( date('Y/m/d',$startDate).' 1 month');
	}
    $html = '
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
    </style>
    <body>
        <h4 style="text-align:left;"><b>'.$gTEXT['Patient Trend Time Series Report of'].'  '.$CountryName.' '.$gTEXT['from'].' '.date('M,Y', strtotime($StartYearMonth)).' '.$gTEXT['to'].' '.date('M,Y', strtotime($EndYearMonth)).'</b></h4>
    </body>';
    
    $pdf->writeHTMLCell(0, 0, 17, '', $html, '', 1, 1, false, 'L', true, $spacing=0); 
    $pdf->setSourceFile("pdfslice/PatientTrendChart.pdf");
  
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 0, 0, 200);

//=====================================================Patient Trend Time Series Table=======================================================
       
    $lan = $_REQUEST['lan'];
        
	$countryId = $_POST['Country'];	
	$itemGroupId = $_POST['ItemGroupId'];
	//$frequencyId = 1;// $_POST['FrequencyId'];

	if($lan == 'en-GB'){
            $serviceTypeName = 'ServiceTypeName';
        }else{
            $serviceTypeName = 'ServiceTypeNameFrench';
        }     
	
	
	// //////////////////
 
	$sQuery = "SELECT a.ServiceTypeId, IFNULL(SUM(c.TotalPatient),0) TotalPatient
			, $serviceTypeName ServiceTypeName, a.STL_Color,c.Year,c.MonthId
                FROM t_servicetype a
                INNER JOIN t_formulation b ON a.ServiceTypeId = b.ServiceTypeId
                Inner JOIN t_cnm_patientoverview c 	
					ON (c.FormulationId = b.FormulationId 
						and STR_TO_DATE(concat(year,'/',monthid,'/02'), '%Y/%m/%d') 
						between '".$StartYearMonth."' and '".$EndYearMonth."'
                		AND (c.CountryId = ".$countryId." OR ".$countryId." = 0)
						AND (c.ItemGroupId = ".$itemGroupId." OR ".$itemGroupId." = 0))  		                       
                GROUP BY a.ServiceTypeId, $serviceTypeName, a.STL_Color
				, c.Year, c.MonthId
				HAVING TotalPatient > 0
		        ORDER BY a.ServiceTypeId asc,c.Year asc, c.MonthId asc;";
	//echo $sQuery;
	$rResult = safe_query($sQuery);
	$total = mysql_num_rows($rResult);
	$tmpServiceTypeId = -1;
	$countServiceType = 1;
	$count = 1;
	$preServiceTypeName='';
	
	if($total==0) return;
	//echo 'Rubel';
    if($total>0){
	while ($row = mysql_fetch_assoc($rResult)) {
		
		if(!is_null($row['TotalPatient']))	
			settype($row['TotalPatient'], "integer");

		if ($tmpServiceTypeId != $row['ServiceTypeId']) {
			
			if ($count > 1) {
				array_unshift($output2,$countServiceType,$preServiceTypeName);
								
				$aData[] = $output2;
				unset($output2);
				$countServiceType++;
			 }
			$count++;		
			
			$preServiceTypeName	=  $row['ServiceTypeName'];	
			$count = 0;
			while( $count < count($month_list)){					
				$output2[] = null;
				$count++;
			}

			$dataMonthYear = $monthQuarterList[$row['MonthId']].' '.$row['Year']; 
			$count = 0;
			while( $count < count($month_list)){
				if($month_list[$count] == $dataMonthYear){
					$output2[$count] = $row['TotalPatient'];
				}				
				$count++;
			}
			$tmpServiceTypeId = $row['ServiceTypeId'];
		} 
		else {
				$dataMonthYear = $monthQuarterList[$row['MonthId']].' '.$row['Year']; 
				$count = 0;
				while( $count < count($month_list)){
					if($month_list[$count] == $dataMonthYear){
						$output2[$count] = $row['TotalPatient'];
					}				
					$count++;
				}
			$tmpServiceTypeId = $row['ServiceTypeId'];
		}   
	} 
	
	array_unshift($output2,$countServiceType,$preServiceTypeName);
	$aData[] = $output2;//print_r($month_list);
    
    $col = '<tr><th width="20" align="center"><b>SL</b></th>';
    $col.= '<th width="35" align="left"><b>'.$gTEXT['Patient Type'].'</b></th>';	
    $f=0;
    for($f = 0; $f<count($month_list); $f++){       
        $col.= '<th width="30" align="right"><b>'.$month_list[$f].'</b></th>';             
    }
	$col.='</tr>';
    $p=0;
    for($p=0;$p<count($aData);$p++)
	{
			$col.='<tr>';
			for($i=0;$i<count($aData[$p]); $i++)
			{
				$col.='<td>'.$aData[$p][$i].'</td>';
			}
			$col.='</tr>';  
	}
    $i=1;
  /*  $col = '<tr><th width="38" align="center"><b>SL</b></th>';
    $col.= '<th width="38" align="left"><b>'.$gTEXT['Patient Type'].'</b></th>';	
    $f=0;
    for($f = 0; $f<count($rmonth_name); $f++){       
        $col.= '<th width="38" align="right"><b>'.$rmonth_name[$f].'</b></th>';             
    }
	$col.='</tr><tr>';
      
    $x=0;
    for($x = 0; $x<count($art); $x++){       
        $col.= '<td width="38" align="right"><b>'.$art[$x].'</b></td>';             
    }   
          
    $col.='</tr><tr>'; 
    
    $x=0;
    for($x = 0; $x<count($rtk); $x++){       
        $col.= '<td width="38" align="right"><b>'.$rtk[$x].'</b></td>';             
    }   
          
    $col.='</tr><tr>';
    
    $x=0;
    for($x = 0; $x<count($pmtct); $x++){       
        $col.= '<td width="38" align="right"><b>'.$pmtct[$x].'</b></td>';             
    }   
          
    $col.='</tr>';     */  
     	  	        
    $html_head = "<span><b>".$gTEXT['Patient Trend Time Series Data List']."</b></span>";
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->writeHTMLCell(0, 0, 17, 125, $html_head, '', 0, 0, false, 'L', true);
    
    $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
         td{
             height: 6px;
             line-height:3px;
         }
        </style>
        <body>
        <table width="550px" border="0.5" style="margin:0px auto;">'.$col.'</table></body>';
              	          
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->writeHTMLCell(0, 0, 15, 140, $html, '', 1, 1, false, 'C', true);
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/PatientTrendReport.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}
    
    $pdf->Output('pdfslice/PatientTrendReport.pdf', 'F');
    
   	echo 'PatientTrendReport.pdf';
    }else{
		echo 'Processing Error';
	}

}

?>