<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
//include("../function_lib.php");

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_connection=utf8");

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
	case 'preparePatientRatioReport' :
		preparePatientRatioReport($conn);
		break;
	case 'generatePatientRatioReport' :
		generatePatientRatioReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function safe_query($query = ""){
    if (empty($query)) {
        return false;
    }   
    $result = mysql_query($query) or die("Query Fails:" . "<li> Errno = " . mysql_errno() . "<li> ErrDetails = " . mysql_error() . "<li>Query = " . $query);
    return $result;
}

function preparePatientRatioReport($conn){
		
   
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

    $filePath = SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/patient_ratio.svg'; 
    
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
    fclose($file);
    
    $pdf->ImageSVG($file='pdfslice/patient_ratio.svg', $x=20, $y=20, $w=180, $h=100, $link='', $align='', $palign='center', $border=0, $fitonpage=false);
    
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
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/patient_ratio_Chart.pdf';
    
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    
	$pdf->Output('pdfslice/patient_ratio_Chart.pdf', 'F');
    
    
}


function generatePatientRatioReport($conn){
        
   	global $gTEXT;
           
    $CountryName = $_POST['CountryName'];
    $MonthName =  $_POST['MonthName'];
    $ItemGroupName = $_POST['ItemGroupName'];
    $countryId = $_POST['Country'];	
    $year = $_POST['YearId'];
    $MonthId = $_POST['MonthId'];	
    $serviceType = $_POST['serviceType'];
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    $pdf = new FPDI();
    
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
    if($serviceType == ''){    
        
        $html_head = "<span style='text-align:center;font-size:10px;'><b>".$gTEXT['All']." ".$gTEXT['Patient Ratio Report of']." ".$CountryName." ".$gTEXT['on']." ".$MonthName.",".$year."</b></span>";
        $html_h = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
        </style>
        <body>
        <h4 style="text-align:center;"><b>'.$gTEXT['Patient Ratio Report'].' '.$gTEXT['on'].' '.$MonthName.','.$year.'</b></h4>
        <h4>'.$gTEXT['Country'].': '.$CountryName.', '.$gTEXT['Service Type'].': '.$gTEXT['All'].', '.$gTEXT['Product Group'].': '.$ItemGroupName.'</h4>
        </body>';
        
        $pdf->writeHTMLCell(0, 0,10, '', $html_h, '', 1, 1, false, 'C', true, $spacing=0); 
    }
    else{
        
        $html_head = "<span style='text-align:center;font-size:10px;'><b> ".$serviceType." ".$gTEXT['Patient Ratio Report of']." ".$CountryName." ".$gTEXT['on']." ".$MonthName.",".$year."</b></span>";
        $html_h = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
        </style>
        <body>
        <h4 style="text-align:center;"><b>'.$gTEXT['Patient Ratio Report'].' '.$gTEXT['on'].' '.$MonthName.','.$year.'</b></h4>
        <h4>'.$gTEXT['Country'].': '.$CountryName.', '.$gTEXT['Service Type'].': '.$serviceType.', '.$gTEXT['Product Group'].': '.$ItemGroupName.'</h4>
        </body>';
        
        $pdf->writeHTMLCell(0, 0,10, '', $html_h, '', 1, 1, false, 'C', true, $spacing=0);         
    }
    
    $pdf->setSourceFile("pdfslice/patient_ratio_Chart.pdf");  
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 0, 20, 200);
    

//===================================================== Patient Ratio Table=======================================================
    
    $countryId = $_POST['Country'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $year = $_POST['YearId'];
    $MonthId = $_POST['MonthId'];	
    $FormulationType = $_POST['serviceType'];	
    $lan = $_REQUEST['lan'];
    
    if($lan == 'en-GB'){
    		$formulationName = 'FormulationName';
	}else{
		$formulationName = 'FormulationNameFrench';
	} 
	mysql_query('SET CHARACTER SET utf8');
    
    $sq2 = "SELECT SQL_CALC_FOUND_ROWS t_regimen.FormulationId,$formulationName FormulationName,
                t_cnm_regimenpatient.RegimenId,t_regimen.RegimenName
                ,SUM(IFNULL(TotalPatient,0)) TotalPatient
                FROM t_cnm_regimenpatient
                INNER JOIN t_regimen ON t_cnm_regimenpatient.RegimenId = t_regimen.RegimenId
                INNER JOIN t_formulation ON t_regimen.FormulationId  = t_formulation.FormulationId 
                
                where (t_cnm_regimenpatient.CountryId = ".$countryId." OR ".$countryId." = 0)
                 AND (t_cnm_regimenpatient.Year = '".$year."') 
                 AND (t_cnm_regimenpatient.MonthId = ".$MonthId.") 
                 AND (t_cnm_regimenpatient.ItemGroupId = ".$ItemGroupId.")
                 AND ($formulationName = '".$FormulationType."' OR '".$FormulationType."' = '')
                 AND t_formulation.bMajore = 1	 
                GROUP BY t_regimen.FormulationId,$formulationName,
                t_cnm_regimenpatient.RegimenId,t_regimen.RegimenName
                ORDER BY t_regimen.FormulationId,t_cnm_regimenpatient.RegimenId;";
            
	 $rResult1 = safe_query($sq2);	
	 $gTotal = 0;
	 $groupTotal = 0;
	 $count = 1;

	 $series1GroupTotal = array();
	 $series1GroupName = array();

	 $preServiceTypeId = -1;
	 $preServiceTypeName = '';
	 
	 while ($row = mysql_fetch_assoc($rResult1)) {
	 	
		 if(!is_null($row['TotalPatient'])){
			 settype($row['TotalPatient'], "integer");
			 $gTotal+= $row['TotalPatient'];
		}
		
		if($count > 1){
			if($preServiceTypeId != $row['FormulationId']){
				 $series1GroupTotal[$preServiceTypeId] = $groupTotal;
				 $series1GroupName[$preServiceTypeId] = $preServiceTypeName;
				 $groupTotal = 0;
			 }
	 	}

		$preServiceTypeId = $row['FormulationId'];
		$preServiceTypeName = $row['FormulationName'];
		
		$groupTotal+= ($row['TotalPatient'] == null ? 0 : $row['TotalPatient']);
		 
		 $count++;
	 }
	 
	 $series1GroupTotal[$preServiceTypeId] = $groupTotal;
	 $series1GroupName[$preServiceTypeId] = $preServiceTypeName;
	 
	 $gTotal = ($gTotal == 0 ? 1 : $gTotal);


	$result3 = safe_query($sq2);
    $total = mysql_num_rows($result3);
    
    if($total>0){
        $tmpServiceTypeId = -1;
    	$TotalPercent = 0;    
    	$serial = 1;
            
    	if($FormulationType == ''){
    	   
            $htm='';
            
            while ($aRow = mysql_fetch_array($result3)) {
            	
                if($tmpServiceTypeId != $aRow['FormulationId']){					
                    $formulationName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['FormulationName'])));
                	$s1groupTotal = $series1GroupTotal[$aRow['FormulationId']];
                	$gPercent = number_format((($s1groupTotal*100)/($gTotal==0?1:$gTotal)),1).' %';
                	$TotalPercent+=$gPercent;
                    
                    $htm.='<tr>
                            <td style="text-align: left;">'. $serial++.'</td>
                            <td style="text-align: left;">'.$aRow['FormulationName'].'</td>
                            <td style="text-align: right;">'.number_format($s1groupTotal).'</td>
                            <td style="text-align: right;">'.$gPercent.'</td>
                	     </tr>';
                         
                }
             
                $tmpServiceTypeId = $aRow['FormulationId'];
            }
            $htm.='<tr>
	                 <td style="background-color:#ffffff;border-radius:2px;align:center;" colspan="2">Total</td>'; 
			$htm.=' <td style="background-color:#ffffff;border-radius:2px;text-align:right;" >'.number_format($gTotal).'</td>';
			$htm.=' <td style="background-color:#ffffff;border-radius:2px;text-align:right;" >'.$TotalPercent.'%'.'</td>
    	               </tr>'; 
                       
      } else {
        
            $htm = '';
            
			while ($aRow = mysql_fetch_array($result3)) {
			 
		        $regimenName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['RegimenName'])));  
				$s1groupTotal = $series1GroupTotal[$aRow['FormulationId']];
				$s1groupTotal1 = ($s1groupTotal == 0 ? 1 : $s1groupTotal);
				$totalPatient = $aRow['TotalPatient'];			 
				settype($totalPatient, "float");		
				$fPercent = number_format((($totalPatient*100)/$s1groupTotal1),1).' %';
				$TotalPercent+=$fPercent;
                
                $htm.='<tr>
                        <td style="text-align: left;">'. $serial++.'</td>
                        <td style="text-align: left;">'.$aRow['RegimenName'].'</td>
                        <td style="text-align: right;">'.number_format($aRow['TotalPatient']).'</td>
                        <td style="text-align: right;">'.$fPercent.'</td>
    			 </tr>'; 
			 }
            $htm.='<tr>
	                 <td style="background-color:#ffffff;border-radius:2px;align:center;" colspan="2">Total</td>'; 
			$htm.=' <td style="background-color:#ffffff;border-radius:2px;text-align:right;" >'.number_format($s1groupTotal).'</td>';
			$htm.=' <td style="background-color:#ffffff;border-radius:2px;text-align:right;" >'.$TotalPercent.'%'.'</td>
	               </tr>';  
                   
    }   
          
    $html_head = "<span><b>".$gTEXT['Patient Ratio Data List']."</b></span>";
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->writeHTMLCell(0, 0, 10, 150, $html_head, '', 0, 0, false, 'C', true);
    
    $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
         td{
             height: 6px;
             line-height:3px;
         }
         th{
            height: 20;
            font-size:9px;
        }
        </style> 
        <body>
        <table width="650px" border="0.5" style="margin:0 auto;">
        	  <tr style="page-break-inside:avoid;">
        		<th width="30" align="center"><b>SL#</b></th>
                <th width="200" align="left"><b>'.$gTEXT['Type'].'</b></th>
                <th width="110" align="right"><b>'.$gTEXT['Patients'].'</b></th>
        		<th width="110" align="right"><b>'.$gTEXT['Patient Percent'].'</b></th>
        	    </tr>'.$htm.'</table></body>';  //echo $htm;
    $pdf->SetFont('dejavusans', '',9);
    $pdf->writeHTMLCell(0, 0, 20, 160, $html, '', 1, 1, false, 'L', true);
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/Patient_Ratio_Report.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}
    
    $pdf->Output('pdfslice/Patient_Ratio_Report.pdf', 'F');
    
   	echo 'Patient_Ratio_Report.pdf';		
	
    
    } else{
		echo 'Processing Error';
	}
}
?>