<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

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
	case 'prepareFundingStatusReport' :
		prepareFundingStatusReport($conn);
		break;
	case 'generateFundingStatusReport' :
		generateFundingStatusReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function prepareFundingStatusReport($conn){
		
    require_once('tcpdf/tcpdf.php');
    //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf = new TCPDF('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
 
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
    
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->AddPage($orientation = L, $format = 'Letter', $keepmargins = true,  $tocpage = false);
   
    ini_set('magic_quotes_gpc', 'off');
    $html=htmlentities($_POST['html'], ENT_QUOTES, "UTF-8");
    $html=html_entity_decode($html, ENT_QUOTES, "UTF-8");
    
    $alavel=htmlentities($_POST['alavel'], ENT_QUOTES, "UTF-8");
    $alavel=html_entity_decode($alavel, ENT_QUOTES, "UTF-8");

    $filePath = SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/funding_status.svg'; 
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
    fclose($file);

    $pdf->ImageSVG($file='pdfslice/funding_status.svg', $x=3, $y=20, $w=1291, $h=400, $link='', $align='', $palign='center', $border=0, $fitonpage=false);

    
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
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/FundingStatusChart.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    
	$pdf->Output('pdfslice/FundingStatusChart.pdf', 'F');
}


function generateFundingStatusReport($conn){
        
   	global $gTEXT;
    global $pdf;
	
	$ItemGroup = $_POST['ItemGroup']; 
    $lan=$_POST['lan']; 
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	} 
    
    $CountryName = $_POST['CountryName']; 
    $Year = $_POST['Year']; 
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    $pdf = new FPDI();
    
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage($orientation = L, $format = 'Letter', $keepmargins = false,  $tocpage = false);
    $pdf->SetFillColor(255,255,255);
    
    $html_head = "<span style='text-align:center;font-size:10px;'><b>".$SITETITLE."</b></span><br>
	<span style='text-align:center;font-size:10px;'><b>".$gTEXT['Funding Status Report of']." ".$CountryName." ".$gTEXT['on']." ".$Year."</b></span><br>
	<span style='text-align:center;font-size:10px;'><b>".$gTEXT['Product Group'].": ".$ItemGroup."</b></span>";
    $html = '
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
    </style>
    <body>
    </body>';
    
    $pdf->writeHTMLCell(0, 0,15, '', $html_head, '', 1, 1, false, 'C', true, $spacing=0); 
    $pdf->setSourceFile("pdfslice/FundingStatusChart.pdf");
  
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 0, 0, 500);
    }

//===================================================== Funding Status List Table=======================================================
   $ItemGroupId = $_POST['ItemGroupId']; 
    if($ItemGroupId){
		$ItemGroupId = " AND g.ItemGroupId = '".$ItemGroupId."' ";
	}
    $Year = $_POST['Year'];    
    $CountryId = $_POST['Country'];  
      
  	if(isset($_POST['Country'])&&!empty($_POST['Country'])){
		$countryQuery=" and p.CountryId='".$CountryId."' ";
	}else{
		$countryQuery="";
	}
    mysql_query('SET CHARACTER SET utf8');
    
	
    if($lan == 'fr-FR'){
		$aColumns = 'g.GroupNameFrench GroupName, f.FundingReqSourceNameFrench FundingReqSourceName';   
		
    }else{
        $aColumns = 'g.GroupName, f.FundingReqSourceName';   
    }
	 
	
	
	/*
	$sql="	SELECT g.GroupName,f.FormulationName,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.TotalFund) Total from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r on r.FormulationId=p.FormulationId and r.Year=p.Year and r.CountryId=p.CountryId
			Inner Join t_formulation f on f.FormulationId=r.FormulationId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery."
			group by g.GroupName,p.FormulationId ";
			*/
	$sql="	SELECT SQL_CALC_FOUND_ROWS $aColumns,r.FundingReqId,r.ItemGroupId,r.Y1,r.Year,sum(p.Y1) Total 
			from t_yearly_pledged_funding p
			Inner Join t_yearly_funding_requirements r 
				on r.FundingReqSourceId=p.FundingReqSourceId and r.Year=p.Year and r.CountryId=p.CountryId  and r.ItemGroupId = p.ItemGroupId
			Inner Join t_fundingreqsources f on f.FundingReqSourceId=r.FundingReqSourceId
			Inner Join t_itemgroup g on g.ItemGroupId =f.ItemGroupId 
			where p.Year='".$Year."' ".$countryQuery." ".$ItemGroupId."
			group by g.GroupName,p.FundingReqSourceId "; 
     mysql_query("SET character_set_results=utf8"); 
	$result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);
    //$tempGroupId='';$countrec=0;
    if($total>0){
        $data=array();
        $f=0; 
       // $tblHTML=''; $Planned=0;$Actual=0;
        $superGrandSubTotal=0;$superGrandSubTotalActual=0;
    	$groupsubTmp=-1;$p=0;$q=0;$grandSubTotal=0;$grandSubTotalActual=0;$grandGapSurplus=0;
    	$htm='';
    	while ($aRow = mysql_fetch_array($result)) {
    		 
            $ItemName = trim(preg_replace('/\s+/', ' ', addslashes($aRow['ItemName'])));
			
		  if($p!=0&&$groupsubTmp!=$aRow['GroupName']){ 
		    
			$htm.='<tr>
	                  <td style="background-color:#ff962b;border-radius:2px;align:center;" colspan="2"> '.$groupsubTmp.' Total</td>
	               '; 
					   
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotal).'</td>
	                    ';
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotalActual).'</td>
	                   </tr>';   
			
			$superGrandSubTotal+=$grandSubTotal;
			$superGrandSubTotalActual+=$grandSubTotalActual;
			
			$grandSubTotal=0;
			$grandSubTotalActual=0;			
		}
		$groupsubTmp=$aRow['GroupName'];
		
		if ($f++)
			$sOutput .= ',';
		$sOutput .= "[";
		$sOutput .= '"' . $serial++ . '",';
		$sOutput .= '"' . $aRow['GroupName'] . '",';
        $sOutput .= '"' . $aRow['FundingReqSourceName'] . '",';
		$sOutput .= '"' . number_format($aRow['Y1']) . '",';
 	    $sOutput .= '"' . number_format($aRow['Total']) . '"';        
		$sOutput .= "]";
		$grandSubTotal+=$aRow['Y1'];
		$grandSubTotalActual+=$aRow['Total'];
		
		if($tempGroupId!=$aRow['GroupName']) 
		   {
			     	   
				   
		   	 $htm.='<tr>
                     <td style="background-color:#DAEF62;border-radius:2px;align:center;" colspan="4">'.$aRow['GroupName'].'</td>
                   </tr>';  
			   
			    $tempGroupId=$aRow['GroupName'];
				$Planned= 0;
		   }	
		$htm.='<tr>
                    <td style="text-align: left;">'. $serial.'</td>
                    <td style="text-align: left;">'.$aRow['FundingReqSourceName'].'</td>
                    <td style="text-align: right;">'.number_format($aRow['Y1']).'</td>
                    <td style="text-align: right;">'.number_format($aRow['Total']).'</td>
			 </tr>';
		
		if($p==$total-1){
			$htm.='<tr>
	                 <td style="background-color:#ff962b;border-radius:2px;align:center;" colspan="2"> '.$groupsubTmp.' Total</td>'; 
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotal).'</td>';
			$htm.=' <td style="background-color:#ff962b;border-radius:2px;text-align:right;" >'.number_format($grandSubTotalActual).'</td>
	               </tr>'; 
			
			$superGrandSubTotal+=$grandSubTotal;
			$superGrandSubTotalActual+=$grandSubTotalActual;
			
			$grandSubTotal=0;
			$grandSubTotalActual=0; 
			 	
			 $htm.='<tr>
	                  <td style="background-color:#52a8ee; color:#ffffff;border-radius:2px;align:center;" colspan="2">Grand Total</td>'; 
			 $htm.='<td style="background-color:#52a8ee; color:#ffffff;border-radius:2px;text-align:right;" >'.number_format($superGrandSubTotal).'</td>';
			 $htm.='<td style="background-color:#52a8ee; color:#ffffff;border-radius:2px;text-align:right;" >'.number_format($superGrandSubTotalActual).'</td>
	                 </tr>'; 		

		}
		$p++;$q++;	
            							
    			  		
    	}
        
        $html_head = "<span><b>".$gTEXT['Funding Status Data']."</b></span>";
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 10, 130, $html_head, '', 0, 0, false, 'C', true);
        
        $html = '
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
             th{
                height: 20;
            }
            </style> 
            <body>
            <table width="650px" border="0.5" style="margin:0 auto;">
            	  <tr style="page-break-inside:avoid;">
            		<th width="50" align="center"><b>SL#</b></th>
                    <th width="200" align="left"><b>'.$gTEXT['Category'].'</b></th>
                    <th width="180" align="right"><b>'.$gTEXT['Planned'].'</b></th>
            		<th width="180" align="right"><b>'.$gTEXT['Actual'].'</b></th>
            	    </tr>'.$htm.'</table></body>';     
        $pdf->SetFont('dejavusans', '',9);
        $pdf->writeHTMLCell(0, 0, 30, 140, $html, '', 1, 1, false, 'L', true);
        
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/FundingStatusReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/FundingStatusReport.pdf', 'F');
        
       	echo 'FundingStatusReport.pdf';	
       		
	}else{
		echo 'Processing Error';
	}

?>