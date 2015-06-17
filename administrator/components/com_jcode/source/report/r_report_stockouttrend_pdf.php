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
	case 'prepareStockoutTrendReport' :
		prepareStockoutTrendReport($conn);
		break;
	case 'generateStockoutTrendReport' :
		generateStockoutTrendReport($conn);		
		break;
   	default :
		echo "{failure:true}";
		break;
}

function getMonthsBtnTwoDate($firstDate, $lastDate) {
	$diff = abs(strtotime($lastDate) - strtotime($firstDate));
	$years = floor($diff / (365 * 60 * 60 * 24));
	$months = $years * 12 + floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	return $months;
}

function prepareStockoutTrendReport($conn){
	
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

    $filePath = SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/stockout_trend.svg'; 
    
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
    fclose($file);
    
    $pdf->ImageSVG($file='pdfslice/stockout_trend.svg', $x=20, $y=20, $w=180, $h=100, $link='', $align='', $palign='center', $border=0, $fitonpage=false);
    
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
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/StockoutTrendChart.pdf';
    
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    
	$pdf->Output('pdfslice/StockoutTrendChart.pdf', 'F');
}

function generateStockoutTrendReport($conn){
        
   	global $gTEXT;
    $CountryId = $_POST['Country']; 
    $months = $_POST['MonthNumber'];
    $StartMonthId = $_POST['StartMonthId'];
    $EndMonthId = $_POST['EndMonthId'];
    $StartYearId= $_POST['StartYearId'];
    $EndYearId= $_POST['EndYearId'];
    $CountryName=$_POST['CountryName']; 
	$MonthName=$_GET['MonthName'];
    
    if($_POST['MonthNumber'] != 0){
        $months = $_POST['MonthNumber'];
        $monthIndex = date("m");
        $yearIndex = date("Y");
            if ($monthIndex == 1){
            $monthIndex = 12;                
            $yearIndex = $yearIndex - 1;                
            }else{
            $monthIndex = $monthIndex - 1;
            
            $endDate = $yearIndex."-".$monthIndex."-"."01";    
            $startDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($endDate)) . "+".-($months-1)." month"));      
    }
    }else{
        $startDate = $StartYearId."-".$StartMonthId."-"."01";    
        $endDate = $EndYearId."-".$EndMonthId."-"."01";    
        $months = getMonthsBtnTwoDate($startDate, $endDate)+1;          
        $monthIndex = $EndMonthId;
        $yearIndex = $EndYearId;   
    }   
    settype($yearIndex, "integer");    
    $month_name = array();
    $Tdetails = array();  
    $sumRiskCount = array();  
    $sumTR = 0;
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    $pdf = new FPDI();
    
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
    
    $html_head = '
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
    </style>
    <body>
        <h4 style="text-align:left;"><b>'.$gTEXT['Stockout trend Report of '].'  '.$CountryName.' '.$gTEXT['from'].' '.date('M,Y', strtotime($startDate)).' '.$gTEXT['to'].' '.date('M,Y', strtotime($endDate)).'</b></h4>
    </body>';
    
    $pdf->writeHTMLCell(0, 0, 12, '', $html_head, '', 1, 1, false, 'L', true, $spacing=0); 
    $pdf->setSourceFile("pdfslice/StockoutTrendChart.pdf");
    print_r();
  
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, -5, 0, 200);

//=====================================================Stockout Trend Table=======================================================
   
        
   	for ($i = 1; $i <= $months; $i++){
   	    
        $sql = " SELECT v.MosTypeId, MosTypeName, ColorCode, IFNULL(RiskCount,0) RiskCount FROM
        		    (SELECT p.MosTypeId, COUNT(*) RiskCount FROM (
                     SELECT a.ItemNo, a.MOS,(SELECT MosTypeId FROM t_mostype x WHERE  a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				     FROM t_cnm_stockstatus a
				     WHERE a.MOS IS NOT NULL AND a.MonthId = ".$monthIndex. " AND Year = ".$yearIndex." AND (CountryId = ".$CountryId." OR ".$CountryId." = 0)) p 
				     GROUP BY p.MosTypeId) u
				     RIGHT JOIN t_mostype v ON u.MosTypeId = v.MosTypeId
				     GROUP BY v.MosTypeId"; 
          mysql_query("SET character_set_results=utf8"); 
        $result = mysql_query($sql);
        $total = mysql_num_rows($result); 
        $Pdetails = array();  
        
        if($total>0){      
    		while ($aRow = mysql_fetch_array($result)) {
                $Pdetails['MosTypeId'] = $aRow['MosTypeId'];
                $Pdetails['MonthIndex'] = $monthIndex;
                $Pdetails['MosTypeName'] = $aRow['MosTypeName'];
                $Pdetails['RiskCount'] = $aRow['RiskCount'];
                array_push($Tdetails, $Pdetails);  
       	    }
            $mn = date("M", mktime(0,0,0,$monthIndex,1,0));
            $mn = $mn." ".$yearIndex;
            array_push($month_name, $mn);  
        }                          
   	    $monthIndex--;
		if ($monthIndex == 0){
			$monthIndex = 12;   				
			$yearIndex = $yearIndex - 1;			
		}
    }
    $veryHighRisk = array();
    $highRisk = array();
    $mediumRisk = array();
    $lowRisk = array();
    $noRisk = array();
    $areaName = array();
    
    $rmonth_name = array_reverse($month_name);
    $RTdetails = array_reverse($Tdetails);
    
    foreach($RTdetails as $key => $value){
         $MosTypeId = $value['MosTypeId'];
         $MonthIndex = $value['MonthIndex'];
         $MosTypeName = $value['MosTypeName'];
         $RiskCount = $value['RiskCount'];  
         
         if($MosTypeId == 1){
            array_push($veryHighRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);  
         }else if($MosTypeId == 2){
            array_push($highRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);
         }else if($MosTypeId == 3){
            array_push($mediumRisk, $RiskCount);
            array_push($areaName, $MosTypeName); 
         }else if($MosTypeId == 4){
            array_push($lowRisk, $RiskCount);
            array_push($areaName, $MosTypeName); 
         }else if($MosTypeId == 5){
            array_push($noRisk, $RiskCount); 
            array_push($areaName, $MosTypeName);
         }                               		            
    }      
    
    $vhr = array();
    $hr = array();
    $mr = array();
    $lr = array();
    $nr = array();
    
    for($i = 0; $i<count($veryHighRisk); $i++){                                     
        $sumOfRiskCount = $veryHighRisk[$i] + $highRisk[$i] + $mediumRisk[$i] + $lowRisk[$i] + $noRisk[$i];   
        if($sumOfRiskCount==0)$sumOfRiskCount = 1;   
        $newPercentVHR = number_format($veryHighRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentHR = number_format($highRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentMR = number_format($mediumRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentLR = number_format($lowRisk[$i]*100/$sumOfRiskCount, 1);
        $newPercentNR = number_format($noRisk[$i]*100/$sumOfRiskCount, 1);
        
        array_push($vhr, $newPercentVHR."%");
        array_push($hr, $newPercentHR."%");
        array_push($mr, $newPercentMR."%");
        array_push($lr, $newPercentLR."%");
        array_push($nr, $newPercentNR."%");
    }
    $unique = array_reverse(array_unique($areaName));     
    array_unshift($vhr, "1", $unique[0]);
    array_unshift($hr, "2", $unique[1]);
    array_unshift($mr, "3", $unique[2]);
    array_unshift($lr, "4", $unique[3]);
    array_unshift($nr, "5", $unique[4]);
    $col = '';
    $col = '<tr><th width="38" align="left"><b>SL</b></th>';
    $col.= '<th width="38" align="left"><b>'.$gTEXT['MOS Type Name'].'</b></th>';	
    $f=0;
    for($f = 0; $f<count($rmonth_name); $f++){       
        $col.= '<th width="38" align="right"><b>'.$rmonth_name[$f].'</b></th>';             
    }
	$col.='</tr><tr>';
      
    $x=0;
    for($x = 0; $x<count($vhr); $x++){       
        $col.= '<td width="38" align="left"><b>'.$vhr[$x].'</b></td>';             
    }   
          
    $col.='</tr><tr>'; 
    
    $x=0;
    for($x = 0; $x<count($hr); $x++){       
        $col.= '<td width="38" align="left"><b>'.$hr[$x].'</b></td>';             
    }   
          
    $col.='</tr><tr>';
    
    $x=0;
    for($x = 0; $x<count($mr); $x++){       
        $col.= '<td width="38" align="left"><b>'.$mr[$x].'</b></td>';             
    }   
          
    $col.='</tr><tr>'; 
    
    $x=0;
    for($x = 0; $x<count($lr); $x++){       
        $col.= '<td width="38" align="left"><b>'.$lr[$x].'</b></td>';             
    }   
          
    $col.='</tr><tr>'; 
     
    $x=0;
    for($x = 0; $x<count($nr); $x++){       
        $col.= '<td width="38" align="left"><b>'.$nr[$x].'</b></td>';             
    }   
          
    $col.='</tr>';        
     	  	        
    $html_head = "<span><b>".$gTEXT['Stockout Trend Data List']."</b></span>";
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->writeHTMLCell(0, 0, 12, 110, $html_head, '', 0, 0, false, 'L', true);
    
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
    $pdf->writeHTMLCell(0, 0, 10, 120, $html, '', 1, 1, false, 'C', true);
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/StockoutTrendReport.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}
    
    $pdf->Output('pdfslice/StockoutTrendReport.pdf', 'F');
    
   	echo 'StockoutTrendReport.pdf';


}

?>