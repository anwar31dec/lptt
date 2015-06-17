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
	case 'generateNumberPatientReport' :
		generateNumberPatientReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}

function numberToMonth($i) {
	$i=trim($i);
	if ($i == 1)
		return "Jan ";
	else if ($i == 2)
		return "Feb";
  	else if ($i == 3)
		return "Mar ";
   	else if ($i == 4)
		return "Apr ";
	else if ($i == 5)
		return "May ";
   	else if ($i == 6)
		return "Jun ";
	else if ($i == 7)
		return "Jul ";
	else if ($i == 8)
		return "Aug ";
		else if ($i == 9)
		return "Sep ";
		else if ($i == 10)
		return "Oct ";
		else if ($i == 11)
		return "Nov ";
		else if ($i == 12)
		return "Dec ";					
}

function generateNumberPatientReport($conn){
       
    global $gTEXT;        
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);  
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    	require_once(dirname(__FILE__).'/lang/eng.php');
    	$pdf->setLanguageArray($l);
    }
    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
//=====================================================National Inventory Table=======================================================
	$monthId = $_POST['MonthId'];
	$year = $_POST['YearId'];
	$countryId = $_POST['CountryId'];
	$itemGroupId = $_POST['ItemGroupId'];

    $currentYearMonth = $_POST['YearId'] . "-" . $_POST['MonthId'] . "-" . "01";
	
	$monthList = array('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
	
	$sql =" SELECT ItemName, AMC, ClStock, FORMAT(MOS,1) MOS, Qty StockOnOrder, FORMAT(Qty/AMC,1) StockOnOrderMOS,
                     (ifnull(FORMAT(MOS,1),0)+ifnull(FORMAT(Qty/AMC,1),0)) TotalMOS,a.ItemNo,TotalPatient
                     FROM 
        				(SELECT
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
        				       AND t_cnm_masterstockstatus.MonthId =$monthId
        				       AND t_cnm_masterstockstatus.CountryId = $countryId
        				       AND t_cnm_masterstockstatus.ItemGroupId =$itemGroupId
        				       AND t_cnm_masterstockstatus.StatusId = 5)
        				GROUP BY t_cnm_masterstockstatus.CountryId, t_itemlist.ItemNo, t_itemlist.ItemName) a 
        				LEFT JOIN (SELECT
        				    CountryId
        				    , ItemNo
        				    , SUM(Qty) Qty
        				FROM
        				    t_agencyshipment
        				WHERE (ShipmentDate > CAST('$currentYearMonth' AS DATETIME)  AND ShipmentStatusId = 3)
        				GROUP BY CountryId, ItemNo) b
        				ON a.CountryId = b.CountryId AND a.ItemNo = b.ItemNo
        				LEFT JOIN (SELECT t_cnm_regimenpatient.CountryId,ItemNo,sum(TotalPatient) as TotalPatient
        				from t_cnm_regimenpatient
        				Inner Join t_regimenitems ON t_cnm_regimenpatient.RegimenId=t_regimenitems.RegimenId
        				Group By t_cnm_regimenpatient.CountryId,ItemNo) c ON a.CountryId = c.CountryId AND a.ItemNo = c.ItemNo
        				
        				HAVING MOS>0 OR StockOnOrderMOS>0 
        				;";   
                          
   	$result = mysql_query($sql,$conn);
	$total = mysql_num_rows($result);
    
    if($total>0){
        $data=array();
        $f=0; 
        $tblHTML='';
    	while ($rec = mysql_fetch_array($result)) {
    	   
            $addmonth = number_format($rec['TotalMOS']);           
            $currentYearMonth = $year . "-" . $monthId . "-" . "01";			
            $lastYearMonth = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentYearMonth)) . "$addmonth month"));
            $temp = explode('-',$lastYearMonth);
            $strMonth = numberToMonth($temp[1]);
            $lastYearMonth = $strMonth.', '.$temp[0];   
              
            $data['SL'][$f]=$f;
    		$data['ItemName'][$f] = $rec['ItemName'];
    		$data['TotalPatient'][$f]=$rec['TotalPatient']== 0? '' : number_format($rec['TotalPatient']);
    		$data['ClStock'][$f]=$rec['ClStock']== 0? '' : number_format($rec['ClStock']);
            $data['MOS'][$f]=$rec['MOS']== 0? '' : number_format($rec['MOS'],1);
            $data['StockOnOrder'][$f]=$rec['StockOnOrder']== 0? '' : number_format($rec['StockOnOrder']);
            $data['StockOnOrderMOS'][$f]=$rec['StockOnOrderMOS']== 0? '' : number_format($rec['StockOnOrderMOS'],1);
            $data['TotalMOS'][$f]=$rec['TotalMOS']== 0? '' : number_format($rec['TotalMOS'],1);
           
            $tblHTML.='<tr style="page-break-inside:avoid;">
                            <td align="center" width="30" valign="middle">'.($data['SL'][$f]+1).'</td>  
                            <td align="left" width="150" valign="middle">'.$data['ItemName'][$f].'</td>
                            <td align="right" width="60" valign="middle">'.$data['TotalPatient'][$f].'</td>
                            <td align="right" width="60" valign="middle">'.$data['ClStock'][$f].'</td>
                            <td align="right" width="85" valign="middle">'.$data['MOS'][$f].'</td>
                            <td align="right" width="60" valign="middle">'.$data['StockOnOrder'][$f].'</td>
                            <td align="right" width="80" valign="middle">'.$data['StockOnOrderMOS'][$f].'</td>
                            <td align="right" width="60" valign="middle">'.$data['TotalMOS'][$f].'</td>
                            <td align="right" width="80" valign="middle">'.$lastYearMonth.'</td>
                            
                    </tr>';
    		$f++;	    
        }
    
        $year = $_POST['YearId'];
        $MonthName = $_POST['MonthName'];
        $CountryName = $_POST['CountryName'];
        
        $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
        </style>
        <body>
            <h4 style="text-align:center;"><b>'.$gTEXT['Number of Patients by Product Report of '].'  '.$CountryName.' on '.$MonthName.','.$year.'</b></h4>
        </body>';
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 8, 10, $html, '', 0, 0, false, 'C', true);
        
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
            		<th width="30" align="center"><b>SL#</b></th>
                    <th width="150" align="left"><b>'.$gTEXT['Product Name'].'</b></th>
                    <th width="60" align="right"><b>'.$gTEXT['Total Patients'].'</b></th>
            		<th width="60" align="right"><b>'.$gTEXT['Available Stock'].'</b></th>
                    <th width="85" align="left"><b>'.$gTEXT['MOS(Available)'].'</b></th>
                    <th width="60" align="right"><b>'.$gTEXT['Stock on Order'].'</b></th>
            		<th width="80" align="right"><b>'.$gTEXT['MOS(Ordered)'].'</b></th>
                    <th width="60" align="left"><b>'.$gTEXT['Total MOS'].'</b></th>
                    <th width="80" align="right"><b>'.$gTEXT['Projected Date'].'</b></th>
                    
         	    </tr>'.$tblHTML.'</table></body>';
                  	          
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->writeHTMLCell(0, 0, 10, 40, $html, '', 1, 1, false, 'L', true);
        
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/NumberPatientReport.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/NumberPatientReport.pdf', 'F');
    
       	echo 'NumberPatientReport.pdf';	
       		
	}else{
		echo 'Processing Error';
	}
}

?>