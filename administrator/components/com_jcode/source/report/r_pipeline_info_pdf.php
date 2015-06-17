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
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case 'preparePipelineReport' :
		preparePipelineReport($conn);
		break;
	case 'generatePipelineReport' :
		generatePipelineReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function preparePipelineReport($conn){
		
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');

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

    $filePath = SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/pipeline_info.svg'; 
    if (file_exists($filePath)) {
    	unlink($filePath);		
    }	
    $file = fopen($filePath,"w");
    fwrite($file, $html);
    fclose($file);
    $pdf->ImageSVG($file='pdfslice/pipeline_info.svg', $x=20, $y=20, $w=180, $h=100, $link='', $align='', $palign='center', $border=0, $fitonpage=false);
    
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
    
	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/PipelineInfoChart.pdf';
	if (file_exists($filePath)) {
		unlink($filePath);		
	}	
    
	$pdf->Output('pdfslice/PipelineInfoChart.pdf', 'F');
}


function generatePipelineReport($conn){
        
   	global $gTEXT;
       
    $year = $_POST['YearId'];
    $MonthName = $_POST['MonthName'];
    $CountryName = $_POST['CountryName']; 
        
    require_once('tcpdf/tcpdf.php');
    require_once('fpdf/fpdi.php');  
    $pdf = new FPDI();
    
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);

    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
    
    $html_head = "<span style='text-align:center;'><b>".$gTEXT['National Stock Pipeline Information Report of']." ".$CountryName." On ".$MonthName.", ".$year."</b></span>";
    $html = '
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
    </style>
    <body>
        <h4 style="text-align:center;"><b>'.$gTEXT['National Stock Pipeline Information Report of'].'  '.$CountryName.' '.$gTEXT['on'].' '.$MonthName.','.$year.'</b></h4>
    </body>';
    
    $pdf->writeHTMLCell(0, 0, 20, '', $html_head, '', 1, 1, false, 'C', true, $spacing=0); 
    $pdf->setSourceFile("pdfslice/PipelineInfoChart.pdf");
  
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 3, 0, 200, 400);  
    $pdf->endPage() ; 

//=====================================================Pipeline Info List Table=======================================================
    $monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];
    
    $currentYearMonth = $_POST['YearId'] . "-" . $_POST['MonthId'] . "-" . "01";
	
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	       
	$sWhere = "";
	if ($_POST['sSearch'] != "") {
		$sWhere = " WHERE (a.ItemName LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
        OR " . " a.AMC LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
        OR " . " a.ClStock LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
        OR " . " a.MOS LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%'
        OR " . " b.Qty LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' 
        )";							
	}
        
    $sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}
    $sOrder = "";
	if (isset($_POST['iSortCol_0'])) { $sOrder = " ORDER BY  ";
		for ($i = 0; $i < mysql_real_escape_string($_POST['iSortingCols']); $i++) {
			$sOrder .= fnColumnToField_Item(mysql_real_escape_string($_POST['iSortCol_' . $i])) . "
								" . mysql_real_escape_string($_POST['sSortDir_' . $i]) . ", ";
		}
		$sOrder = substr_replace($sOrder, "", -2);
	}
    
    $sql = "  SELECT ItemName, IFNULL(AMC,0) AMC, IFNULL(ClStock,0) ClStock, IFNULL(MOS,0) MOS, IFNULL(Qty,0) StockOnOrder 
            FROM (SELECT
                    t_cnm_masterstockstatus.CountryId,
                    t_itemlist.ItemNo,
                    t_itemlist.ItemName,
                    SUM(t_cnm_stockstatus.AMC)    AMC,
                    SUM(t_cnm_stockstatus.ClStock)    ClStock,
                    SUM(t_cnm_stockstatus.MOS)    MOS
                    FROM t_cnm_stockstatus
                    INNER JOIN t_cnm_masterstockstatus
                    ON (t_cnm_stockstatus.CNMStockId = t_cnm_masterstockstatus.CNMStockId)
                    INNER JOIN t_itemlist
                    ON (t_cnm_stockstatus.ItemNo = t_itemlist.ItemNo)
                    WHERE (t_cnm_masterstockstatus.Year = '$year'
                    AND t_cnm_masterstockstatus.MonthId = $monthId
                    AND t_cnm_masterstockstatus.CountryId = $countryId
                    AND t_cnm_masterstockstatus.ItemGroupId = $itemGroupId
                    AND t_cnm_masterstockstatus.StatusId = 5)
                    GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
            LEFT JOIN (SELECT
                        CountryId, ItemNo, SUM(Qty) Qty
                        FROM t_agencyshipment
                        WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  AND ShipmentStatusId = 2)
                        GROUP BY CountryId, ItemNo) b
            ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
            ".$sWhere."
            HAVING AMC>0 OR MOS>0 OR ClStock>0 OR StockOnOrder>0
            ORDER BY ItemName
            $sLimit";//	
                    
	$result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);
    
    if($total>0){
        $data=array();
        $f=0; 
        $tblHTML='';
    	while ($rec = mysql_fetch_array($result)) {
            $data['SL'][$f]=$f;
    		$data['ItemName'][$f] = $rec['ItemName'];
    		$data['AMC'][$f]=number_format($rec['AMC']);
    		$data['ClStock'][$f]=number_format($rec['ClStock']);
    		$data['MOS'][$f]=number_format($rec['MOS'],1);
    		$data['StockOnOrder'][$f] = $rec['StockOnOrder']== 0? '' : $rec['StockOnOrder'];
            
            $amc = ($rec['AMC'] == 0? 1 : $rec['AMC']);		
            $stockOnOrderMOS =  $rec['StockOnOrder'] / $amc;	
            	
            $stockOnOrderMOS = $stockOnOrderMOS== 0? '' : number_format($stockOnOrderMOS,1);
            
            $totalMOS = number_format((number_format($rec['MOS'],1) + $stockOnOrderMOS),1) ;
            
            $totalMOS = $totalMOS== 0? '' : $totalMOS;
            
            //$data['StockOnOrderMOS'][$f] = $rec['StockOnOrderMOS']== 0? '' : $rec['StockOnOrderMOS'];
            //$data['TotalMOS'][$f] = $rec['TotalMOS']== 0? '' : $rec['TotalMOS'];
            
            $tblHTML.='<tr style="page-break-inside:avoid;">
                            <td align="center" width="20" valign="middle">'.($data['SL'][$f]+1).'</td>  
                            <td align="left" width="150" valign="middle">'.$data['ItemName'][$f].'</td>
                            <td align="right" width="50" valign="middle">'.$data['AMC'][$f].'</td>
                            <td align="right" width="70" valign="middle">'.$data['ClStock'][$f].'</td>
                            <td align="right" width="74" valign="middle">'.$data['MOS'][$f].'</td>
                            <td align="right" width="50" valign="middle">'.$data['StockOnOrder'][$f].'</td>
                            <td align="right" width="70" valign="middle">'.$stockOnOrderMOS.'</td>
                            <td align="right" width="50" valign="middle">'.$totalMOS.'</td> 
                    </tr>';
    		$f++;	  		
    	}
        
        $pdf->startPage(); 
        $html_head = "<span><b>".$gTEXT['National Stock Pipeline Information List']."</b></span>";
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 10, 15, $html_head, '', 0, 0, false, 'C', true);
        
        $html = '
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
             td{
                 height: 6px;
                 line-height:3px;
             }
            </style>
            <body>
            <table width="600px" border="0.5" style="margin:0 auto;">
            	  <tr>
            		<th width="20" align="center"><b>SL</b></th>
                    <th width="150" align="left"><b>'.$gTEXT['Products'].'</b></th>
                    <th width="50" align="right"><b>'.$gTEXT['AMC'].'</b></th>
            		<th width="70" align="right"><b>'.$gTEXT['Available Stock'].'</b></th>
            		<th width="74" align="right"><b>'.$gTEXT['MOS(Available)'].'</b></th>
            		<th width="50"  align="right"><b>'.$gTEXT['Stock on Order'].'</b></th>
                    <th width="70"  align="right"><b>'.$gTEXT['MOS(pipeline)'].'</b></th>
                    <th width="50"  align="right"><b>'.$gTEXT['Total MOS'].'</b></th>
            	  </tr>'.$tblHTML.'</table></body>';
                  	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, '', 25, $html, '', 1, 1, false, 'L', true);
        $pdf->endPage() ;  
        
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/PipelineInfoReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/PipelineInfoReport.pdf', 'F');
        
       	echo 'PipelineInfoReport.pdf';	
       		
	}else{
		echo 'Processing Error';
	}
}

function fnColumnToField_Item($i) {
	if ($i == 1)
		return "ItemName";
    if ($i == 2)
		return "AMC";
    if ($i == 3)
		return "ClStock";
    if ($i == 4)
		return "MOS";
    if ($i == 5)
		return "StockOnOrder";
    
}

?>