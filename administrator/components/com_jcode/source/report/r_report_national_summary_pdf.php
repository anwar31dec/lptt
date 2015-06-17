<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

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
	case 'prepareNationalSummaryReport' :
		prepareNationalSummaryReport($conn);
		break;
	case 'generateNationalSummaryReport' :
		generateNationalSummaryReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function prepareNationalSummaryReport($conn){
		
	$MonthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');

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

    $filePath = SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/national_summary.svg'; 
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
    fclose($file);
    $pdf->ImageSVG($file='pdfslice/national_summary.svg', $x=20, $y=20, $w=180, $h=100, $link='', $align='', $palign='center', $border=0, $fitonpage=false);
    
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
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/NationalSummaryChart.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    
	$pdf->Output('pdfslice/NationalSummaryChart.pdf', 'F');
}


function generateNationalSummaryReport($conn){
        
   	global $gTEXT;
       
    $Year = $_POST['Year'];
    $MonthName = $_POST['MonthName'];
    $CountryName = $_POST['CountryName']; 
        
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    $pdf = new FPDI();
    
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
    
    $html_head = "<span style='text-align:center;'><b>".$gTEXT['National Stock Summary Report of']." ".$CountryName." On ".$MonthName.", ".$Year."</b></span>";
    $html = '
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
    </style>
    <body>
        <h4 style="text-align:center;"><b>'.$gTEXT['National Stock Summary Report of'].'  '.$CountryName.' On '.$MonthName.','.$Year.'</b></h4>
    </body>';
    
    $pdf->writeHTMLCell(0, 0, 30, '', $html_head, '', 1, 1, false, 'C', true, $spacing=0); 
    $pdf->setSourceFile("pdfslice/NationalSummaryChart.pdf");
  
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 6, 0, 200);

//=====================================================Summary List Table=======================================================
    
    if($Month=='1') 	    $MonthName="January";
	elseif($Month=='2') 	$MonthName="February";
	elseif($Month=='3') 	$MonthName="March";
	elseif($Month=='4') 	$MonthName="April";
	elseif($Month=='5') 	$MonthName="May";
	elseif($Month=='6') 	$MonthName="June";
	elseif($Month=='7')     $MonthName="July";
	elseif($Month=='8') 	$MonthName="August";
	elseif($Month=='9')     $MonthName="September";
	elseif($Month=='10')    $MonthName="October";
	elseif($Month=='11') 	$MonthName="November";
	elseif($Month=='12') 	$MonthName="December";   
      
    $Year = $_POST['Year'];
    $ItemGroupId = $_POST['ItemGroupId'];
    $Month = $_POST['Month'];
    $MonthName = $_POST['MonthName'];
    $CountryId = $_POST['Country'];
    $CountryName = $_POST['CountryName'];
       
	$sql = "  SELECT a.ItemNo, b.ItemName, SUM(DispenseQty) ReportedConsumption, SUM(ClStock) ReportedClosingBalance, SUM(AMC) AMC, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            	FROM t_cnm_stockstatus a 
                INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            	INNER JOIN t_cnm_masterstockstatus c ON a.CNMStockId = c.CNMStockId AND a.CountryId = c.CountryId AND c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
           		WHERE a.MonthId = ".$Month." AND a.Year = ".$Year."
                AND (a.CountryId = ".$CountryId." OR ".$CountryId." = 0) 	
            	GROUP BY ItemNo, ItemName 
            	HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0";	
                    
	$result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);
    
    if($total>0){
        $data=array();
        $f=0; 
        $tblHTML='';
    	while ($rec = mysql_fetch_array($result)) {
            $data['SL'][$f]=$f;
    		$data['ItemName'][$f] = $rec['ItemName'];
    		//$data['ReportedConsumption'][$f]=number_format($rec['ReportedConsumption']);
    		$data['ReportedClosingBalance'][$f]=number_format($rec['ReportedClosingBalance']);
    		$data['AMC'][$f]=number_format($rec['AMC']);
    		$data['MOS'][$f] = number_format(($rec['MOS']),1);
            
            $tblHTML.='<tr style="page-break-inside:avoid;">
                            <td align="center" width="30" valign="middle">'.($data['SL'][$f]+1).'</td>  
                            <td align="left" width="200" valign="middle">'.$data['ItemName'][$f].'</td>
                            <td align="right" width="90" valign="middle">'.$data['ReportedClosingBalance'][$f].'</td>
                            <td align="right" width="120" valign="middle">'.$data['AMC'][$f].'</td>
                            <td align="right" width="60" valign="middle">'.$data['MOS'][$f].'</td> 
                    </tr>';
    		$f++;	
            //<td align="right" width="90" valign="middle">'.$data['ReportedConsumption'][$f].'</td>  		
    	}
        
        $html_head = "<span><b>".$gTEXT['National Stock Summary List']."</b></span>";
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 10, 160, $html_head, '', 0, 0, false, 'C', true);
        
        $html = '
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
            </style>
            <body>
            <table width="450px" border="0.5" style="margin:0 auto;">
            	  <tr>
            		<th width="30" align="center"><b>SL</b></th>
                    <th width="200" align="left"><b>'.$gTEXT['Products'].'</b></th>
            		<th width="90" align="right"><b>'.$gTEXT['Reported Closing Balance'].'</b></th>
            		<th width="120" align="right"><b>'.$gTEXT['Average Monthly Consumption'].'</b></th>
            		<th width="60"  align="right"><b>'.$gTEXT['MOS'].'</b></th>
            	  </tr>'.$tblHTML.'</table></body>';
                 //<th width="90" align="right"><b>'.$gTEXT['Reported Consumption'].'</b></th> 	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, '', 170, $html, '', 1, 1, false, 'L', true);
        
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/NationalSummaryPage.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/NationalSummaryPage.pdf', 'F');
        
       	echo 'NationalSummaryPage.pdf';	
       		
	}else{
		echo 'Processing Error';
	}
}

?>