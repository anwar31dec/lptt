<?php
include("../define.inc");
include('../language/lang_en.php');
include('../language/lang_fr.php');
include('../language/lang_switcher_report.php');

$conn = mysql_connect(HOSTNAME, DBUSER, DBPWD) or die('Could not connect: ' . mysql_error());
mysql_select_db(DBNAME, $conn) or die('Could not connect: ' . mysql_error());

//mysql_query('SET CHARACTER SET utf8');

error_reporting(0);

$gTEXT = $TEXT;
$jBaseUrl = $_GET['jBaseUrl']; 

    

$task = '';
if (isset($_REQUEST['operation'])) {
	$task = $_REQUEST['operation'];
}

switch($task) {
	case 'generateFacilityInventoryReport' :
		generateFacilityInventoryReport($conn);		
		break;	
   	default :
		echo "{failure:true}";
		break;
}


function generateFacilityInventoryReport($conn){       

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
	$lan = $_REQUEST['lan'];
	if($lan == 'en-GB'){
		$SITETITLE = SITETITLEENG;
	}else{
	   $SITETITLE = SITETITLEFRN;
	} 
	$mosTypeId = $_REQUEST['MosTypeId'];
	$countryId = $_REQUEST['CountryId'];
    $CountryName = $_REQUEST['CountryName'];
    $monthName = $_REQUEST['MonthName'];
    $ItemGroupName = $_REQUEST['ItemGroupName'];
    $OwnerTypeName = $_REQUEST['OwnerTypeName'];
    $year = $_REQUEST['Year'];
	$OwnerTypeId = $_REQUEST['OwnerTypeId'];
	$OwnerType = $_REQUEST['OwnerType'];
	$ItemGroupId = $_REQUEST['ItemGroupId'];
	$column_name = array();
  
	if($lan == 'en-GB'){
            $mosTypeName = 'MosTypeName';
        }else{
            $mosTypeName = 'MosTypeNameFrench';
        }   

	$sQuery1 = "SELECT MosTypeId, $mosTypeName MosTypeName, ColorCode
                FROM t_mostype
                WHERE (MosTypeId = ".$mosTypeId." OR ".$mosTypeId." = 0)
                ORDER BY MosTypeId;";
                
    mysql_query("SET character_set_results=utf8");
	$rResult1 = mysql_query($sQuery1);
	$output1 = array();
    $col = '';
	while ($row1 = mysql_fetch_array($rResult1)) {
		$output1[] = $row1;
        array_push($column_name, $row1['MosTypeName']);
	}  
    
    $col.= '<tr><th width="180" align="left"><b>'.$gTEXT['Product Name'].'</b></th>';
    $col.= '<th width="50" align="left"><b>'.$gTEXT['MOS'].'</b></th>';	
    $f=0;
    for($f = 0; $f<count($output1); $f++){       
        $col.= '<th width="90" align="right"><b>'.$column_name[$f].'</b></th>';             
    }
    $col.='</tr>';  
    /*
	$sQuery = "SELECT p.MosTypeId, ItemName, MOS 
                FROM 
                (SELECT a.ItemNo, b.ItemName, 
                a.MOS,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
                FROM 
                t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
                WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL 
                AND a.MonthId = " . $_REQUEST['MonthId'] . " 
                AND a.Year = '" . $_REQUEST['Year'] . "' 
                AND a.CountryId = " . $_REQUEST['CountryId'] . " 
                AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " 
                AND a.CNMStockId = c.CNMStockId AND c.StatusId = 5 ) p
                WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
                ORDER BY ItemName";
    */
if($ItemGroupId > 0){
	$sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , a.MOS
				,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
				WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL AND a.MonthId = " . $_REQUEST['MonthId'] . " 
				AND a.Year = '" . $_REQUEST['YearId'] . "' 
				AND a.CountryId = " . $_REQUEST['CountryId'] . " 
				AND a.ItemGroupId = " . $_REQUEST['ItemGroupId'] . " 
				AND c.OwnerTypeId = " . $OwnerTypeId . " 
				AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				ORDER BY ItemName";
   }else{
	$sQuery = "SELECT p.MosTypeId, ItemName, MOS FROM (SELECT
				    a.ItemNo
				    , b.ItemName
				    , a.MOS
				,(SELECT MosTypeId FROM t_mostype x WHERE (MosTypeId = $mosTypeId OR $mosTypeId = 0) AND a.MOS >= x.MinMos AND a.MOS < x.MaxMos) MosTypeId
				 FROM t_cnm_stockstatus a, t_itemlist b,  t_cnm_masterstockstatus c
				WHERE a.itemno = b.itemno AND a.MOS IS NOT NULL 
				AND a.MonthId = " . $_REQUEST['MonthId'] . " 
				AND a.Year = '" . $_REQUEST['YearId'] . "' 
				AND a.CountryId = " . $_REQUEST['CountryId'] . " 
				AND c.OwnerTypeId = " . $OwnerTypeId . " 
				AND b.bCommonBasket = 1 
				AND a.CNMStockId = c.CNMStockId" . " AND c.StatusId = 5 " . ") p
				WHERE (p.MosTypeId = $mosTypeId OR $mosTypeId = 0) 
				GROUP by p.MosTypeId, ItemName
				ORDER BY ItemName";
	
  }
  
    mysql_query("SET character_set_results=utf8");

	$rResult = mysql_query($sQuery);
	$aData = array();
    $total = mysql_num_rows($rResult);
    
    if($total>0){
	while ($row = mysql_fetch_array($rResult)) {
		$tmpRow = array();
        $col.= '<tr style="page-break-inside:avoid;">
                    <td>'.$row['ItemName'].'</td>
                    <td> '.number_format($row['MOS'],1).'</td>  ';
        
        foreach ($output1 as $rowMosType) {
        if ($rowMosType['MosTypeId'] == $row['MosTypeId']) {
                $tmpRow[] = $rowMosType['ColorCode'];
                $col.= '<td style="background-color:'.$rowMosType['ColorCode'].'"></td>';
        } else
                $col.= '<td> </td>';
        }
        
        array_unshift($tmpRow, $row['ItemName'], number_format($row['MOS'], 1));
        $aData[] = $tmpRow;
        $col.= ' </tr>';
	}

    $html_head = '<style>
    </style>
    <head></head>
    <body>
		<h3 style="text-align:center;"><b>'.$SITETITLE.'</b></h3>
        <h4 style="text-align:center;"><b>'.$gTEXT['National Inventory Control Report of '].'  '.$CountryName.' '.$gTEXT['on'].' '.$monthName.','.$year.'</b></h4>
        <h4>'.$gTEXT['Product Group'].': '. $ItemGroupName.', '.$gTEXT['Report By'].': '.$OwnerType.'</h4>
    </body>';
    
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->writeHTMLCell(0, 0, 6, 10, $html_head, '', 0, 0, false, 'C', true);
    
    $html = '<!-- EXAMPLE OF CSS STYLE -->
    <style>
    td{
    height: 6px;
    line-height:3px;
    }
    th{
        height:20;
        font-size:10px;
    }
    </style>
    <body>
    <table width="600px" border="0.5" style="margin:0 auto;">'.$col.'</table></body>'; 
    
    $pdf->SetFont('dejavusans', '', 7);
    $pdf->writeHTMLCell(0, 0, 5, 45, $html, '', 1, 1, false, 'L', true);
    
		if (function_exists('date_default_timezone_set')) {
			date_default_timezone_set('UTC');
		} else {
				putenv("TZ=UTC");
		}
		$exportName ='NationalInventoryReport_'.date("Y-m-d_His", time()); 
		//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		//$file = $reportSaveName.'_'.$exportTime. '.xlsx';
		//$objWriter -> save(str_replace('.php', '.xlsx', 'media/' . $file)); 
		//header('Location:media/' . $file);
		
    $filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/'.$exportName.'.pdf';
    if (file_exists($filePath)) {
    unlink($filePath);		
    }
    
    $pdf->Output('pdfslice/'.$exportName.'.pdf', 'F');
    
    echo $exportName.'.pdf';	
       		
	}else{
		echo 'Processing Error';
	}
}

?>