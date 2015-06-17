<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');
//include("../function_lib.php");

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
	case 'prepareStockStatusReport' :
		prepareStockStatusReport($conn);
		break;
	case 'generateStockStatusReport' :
		generateStockStatusReport($conn);		
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
function prepareStockStatusReport($conn){
		
	$lan = $_POST['lan']; 

	if($lan == 'en-GB'){
		 $MonthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	}else{
		 $MonthList = array('1'=>'Janvier','2'=>'F�vrier','3'=>'Mars','4'=>'Avril','5'=>'Mai','6'=>'Juin','7'=>'Juillet','8'=>'Ao�t','9'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'D�cembre');
	} 
    
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
    
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->AddPage();
   
    ini_set('magic_quotes_gpc', 'off');
    $html=htmlentities($_POST['html'], ENT_QUOTES, "UTF-8");
    $html=html_entity_decode($html, ENT_QUOTES, "UTF-8");
    
    $alavel=htmlentities($_POST['alavel'], ENT_QUOTES, "UTF-8");
    $alavel=html_entity_decode($alavel, ENT_QUOTES, "UTF-8");

    $filePath = SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/stock_status.svg'; 
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
    fclose($file);
    $pdf->ImageSVG($file='pdfslice/stock_status.svg', $x=8, $y=20, $w=180, $h='', $link='', $align='left', $palign='center', $border=0, $fitonpage=false);
    
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
    $pdf->writeHTMLCell($w=150, $h=30, $x=15, $y=0, $html2, $border=0, $ln=0, $fill=false, $reseth=true, $align='C', $autopadding=true);
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/StockStatusChart.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    
	$pdf->Output('pdfslice/StockStatusChart.pdf', 'F');
}


function generateStockStatusReport($conn){
        
   	global $gTEXT;
       
  
    $MonthName = $_POST['MonthName'];
    $CountryName = $_POST['CountryName']; 
    
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    $pdf = new FPDI();
    
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    //$pdf->SetAutoPageBreak(true, 1);
    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
    
    $html_head = "<span style='text-align:center;font-size:10px;'><b>".$gTEXT['Stock Status at Different Level Report of']." ".$CountryName." on ".$MonthName.", ".$Year."</b></span>";
    $html = <<<EOF
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
    </style>
    <body>
        
    </body>
EOF;
    $pdf->writeHTMLCell(0, 0, 40, '', $html_head, '', 1, 1, false, 'L', true);
    $pdf->setSourceFile("pdfslice/StockStatusChart.pdf");
  
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 6, 20, 200,300);
    $pdf->endPage() ;	
//=====================================================Stock List Table=======================================================
    
    $Year = $_POST['Year'];
    $ItemGroupId = $_POST['ItemGroup'];
    $Month = $_POST['Month'];
    $CountryId = $_POST['Country'];
	$ownnerTypeId = $_POST['OwnnerTypeId'];
    $lan = $_REQUEST['lan'];
    if($lan == 'en-GB'){ 
        $fLevelName = 'FLevelName';
    }else{
		 $fLevelName = 'FLevelNameFrench';
    } 
    
    if($CountryId){
		$CountryId = " AND a.CountryId = ".$CountryId." ";
	}
	
	$columnList = array();
	$productName = 'Product Name';
 
	 
	$aData = array();
	 
	
	if($ownnerTypeId==1 || $ownnerTypeId == 2){
	$sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
			, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
            FROM t_cfm_stockstatus a 
            INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
            INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = ".$ItemGroupId."
            INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
            WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."
			AND d.OwnerTypeId  = ".$ownnerTypeId."
            GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
            HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0
			order by ItemName,f.FLevelId;";
	}
	else{
	$sQuery = "SELECT f.FLevelId, $fLevelName FLevelName, a.ItemNo, b.ItemName, f.ColorCode, IFNULL(SUM(ClStock),0) FacilitySOH, IFNULL(SUM(AMC),0) FacilityAMC
				, IFNULL(((SUM(ClStock))/(SUM(AMC))),0) MOS                 
				FROM t_cfm_stockstatus a 
				INNER JOIN t_itemlist b ON a.ItemNo = b.ItemNo AND b.bKeyItem = 1 AND b.ItemGroupId = ".$ItemGroupId."
				INNER JOIN t_cfm_masterstockstatus c ON a.CFMStockId = c.CFMStockId and c.StatusId = 5 AND c.ItemGroupId = ".$ItemGroupId."
				INNER JOIN t_facility d ON a.FacilityId = d.FacilityId
				INNER JOIN t_facility_group_map e ON d.FacilityId = e.FacilityId AND e.ItemGroupId = ".$ItemGroupId."
				INNER JOIN t_facility_level f ON d.FLevelId = f.FLevelId
				WHERE a.MonthId = ".$Month." AND a.Year = '".$Year."' ".$CountryId."
				AND d.AgentType = ".$ownnerTypeId."
				GROUP BY f.FLevelId, $fLevelName, ItemNo, ItemName, f.ColorCode
				HAVING IFNULL(((SUM(ClStock))/(SUM(AMC))),0)>0
				order by ItemName,f.FLevelId;";
	}	
	//echo $sQuery;
	$rResult = safe_query($sQuery);
	$total = mysql_num_rows($rResult);
	$tmpItemName = '';
	
	$sl = 1;
	$count = 0;
	$preItemName='';
	
	//echo 'Rubel';
    if($total>0){
	$data = array();
	$headerList = array();
	while ($row = mysql_fetch_assoc($rResult)) {
		$data[] = $row;
	}
	
	foreach($data as $row){
		////Duplicate value not push in array
		//if (!in_array($row['FLevelName'], $headerList)) {
		//	$headerList[] = $row['FLevelName'];
		//}
		$headerList[$row['FLevelId']] = $row['FLevelName'];
	}
	//array_push($headerList,'National');
	$headerList[999] = 'National'; 
	
	foreach($headerList as $key => $value){
		$columnList[] = $value;//.' Level AMC';
		$columnList[] = $value;//.' Level SOH';
		$columnList[] = $value;//.' Level MOS';
	}
	$fetchDataList = array();
	
	foreach($data as $row){
		if ($tmpItemName != $row['ItemName']) {
		
			if ($count > 0) {
				$fetchDataList['999'.'2'] =  number_format($fetchDataList['999'.'2']);
				$fetchDataList['999'.'3'] =  number_format($fetchDataList['999'.'3'],1);
				array_unshift($fetchDataList,$sl,$preItemName);
				$aData[] = $fetchDataList;
				$sl++;
			 }
			 $count++;	
			 
			 $preItemName	=  $row['ItemName'];
			 
			 unset($fetchDataList);
			 foreach($headerList as $key => $value){
				 $fetchDataList[$key.'1'] = NULL; 
				 $fetchDataList[$key.'2'] = NULL; 
				 $fetchDataList[$key.'3'] = NULL; 
			 }			 
			$tmpItemName = $row['ItemName'];
		}
		
		$fLevelId = $row['FLevelId'];
		
		$fetchDataList[$fLevelId.'1'] = number_format($row['FacilityAMC']);
		$fetchDataList[$fLevelId.'2'] = number_format($row['FacilitySOH']);
		$fetchDataList[$fLevelId.'3'] = number_format($row['MOS'],1);
		 
		if($fetchDataList['999'.'1'] < $row['FacilityAMC']){
			$fetchDataList['999'.'1'] =  number_format($row['FacilityAMC']);
		}
		
		$fetchDataList['999'.'2']+=  $row['FacilitySOH'];
		$fetchDataList['999'.'3']+=  $row['MOS'];
			
	}
	
	$fetchDataList['999'.'2'] =  number_format($fetchDataList['999'.'2']);
	$fetchDataList['999'.'3'] =  number_format($fetchDataList['999'.'3'],1);
	array_unshift($fetchDataList,$sl,$preItemName);
	$aData[] = $fetchDataList;
    
    $col='';
	$col.=' <tr><th rowspan="2" style="text-align:center; width:5%;"><b>SL</b></th>
		  <th rowspan="2" style="text-align:center; width:10%;"><b>'.$gTEXT['Product Name'].'</b></th>';
 
    $Header = '-1';
	for($i=0;$i<count($columnList);$i++)	
	{
		  if($Header != $columnList[$i]){
    		  $col.='<th colspan="3" style="text-align:center;width:90px;"><b>'.$columnList[$i].'</b></th>';                     
    		  $Header = $columnList[$i];
	       }   
                   	
    }  
    $index = 0;
	$col.= '</tr><tr>';
	for ($i=0; $i<count($columnList); $i++) {
	   $index++;
		if($index == 1)
			$col.= '<th  style="text-align:left; ">AMC</th>';
		else if($index == 2)
			$col.= '<th  style="text-align:left; ">SOH</th>';
		else if($index == 3)
			$col.= '<th  style="text-align:left; ">'.$gTEXT['MOS'].'</th>';
		
		if($index == 3)
			$index = 0;                 
    }  
        
	$col.='</tr>';



    $data = '';
    for($p=0;$p<count($aData);$p++)
    {
    		$data.='<tr>';
    		for($i=0;$i<count($aData[$p]); $i++)
    		{
    			$data.='<td>'.$aData[$p][$i].'</td>';
    		}
    		$data.='</tr>';  
    }
    
    $pdf->startPage();    
    $html_head = "<span><b>".$gTEXT['Stock Status at Different Level Data List']."</b></span>";
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->writeHTMLCell(0, 0, 3, 10, $html_head, '', 0, 0, false, 'C', true);
   
    $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
         td{
             height: 6px;
             line-height:3px;
         }
         th{
            height:20;
            font-size:6px;
        }
        </style>
        <body>
        <table width="510px" border="0.5" style="margin:0 auto;">
        '.$col.''.$data.'</table>
        </body>';
            	          
    $pdf->SetFont('dejavusans', '', 6);
    $pdf->writeHTMLCell(0, 0, '', 20, $html, '', 1, 1, false, 'L', true);
    $pdf->endPage() ;    
        
    $filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/StockStatusatDifferentLevelReport.pdf';
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }
    
    $pdf->Output('pdfslice/StockStatusatDifferentLevelReport.pdf', 'F');
    
    echo 'StockStatusatDifferentLevelReport.pdf';	
    	
    }else{
        echo 'Processing Error';
    }
}

?>