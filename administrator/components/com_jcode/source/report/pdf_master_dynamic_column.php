<?php
include("../define.inc");

error_reporting(0);

$gTEXT = $TEXT;

/* Receive all parameter*/			
$jBaseUrl = $_REQUEST['jBaseUrl'];
$lan = $_REQUEST['lan'];
$reportSaveName = $_REQUEST['reportSaveName'];
$reportHeaderList = json_decode($_REQUEST['reportHeaderList'], true );
if($lan == 'en-GB')
	array_unshift($reportHeaderList, SITETITLEENG);
else
	array_unshift($reportHeaderList,SITETITLEFRN);
$htmlTable = $_REQUEST['htmlTable'];
$chart = $_REQUEST['chart'];

$reportHeaderListCount = count($reportHeaderList);
      
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
	
	$pdf->setFontSubsetting(false);
    $pdf->AddPage();	
	$pdf->SetFillColor(255, 255, 255);
	 $html = '
        <!-- EXAMPLE OF CSS STYLE -->
        <style>
        </style>
        <body>';
		
			for($i=0;$i<$reportHeaderListCount;$i++){
				if($i==0)
					$html.= '<h2>'.$reportHeaderList[$i].'<h2>';
				else if($i==1)
					$html.= '<h3>'.$reportHeaderList[$i].'<h3>';
				else
					$html.= '<h4>'.$reportHeaderList[$i].'<h4>';
			}
        $html.= '</body>';
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTMLCell(0, 0, 10, 10, $html, '', 0, 0, false, 'C', true);
        $tblHTML='';
		
        $tblHTML.='
            <!-- EXAMPLE OF CSS STYLE -->
            <style>
         
			.SL {
				text-align: center !important;
			}
			.amc, .soh, .mos {
				text-align: right !important;
			}
			
			.right-aln {
				text-align: right !important;
			}
			.left-aln {
				text-align: left !important;
			}
			.center-aln {
				text-align: center !important;
			}			
			tr  th {
			vertical-align: middle !important;
			}			
			
            </style> ';
			
            if($chart==1){
				$svgfilePath='pdfslice/'.$reportSaveName.'.svg';
				$tblHTML.='<img src="'.$svgfilePath.'" width=auto height=auto />';
			}
			
			$tblHTML.='<body> ';
			/*
			$postdata = http_build_query(
							$parameterList,'','&'
						);

						$opts = array('http' =>
							array(
								'method'  => 'POST',
								'header'  => 'Content-type: application/x-www-form-urlencoded',
								'content' => $postdata
							)
						);

			$context  = stream_context_create($opts);
			//////$tblHTML.= file_get_contents($jBaseUrl."administrator/components/com_jcode/source/report/print_pdf_excel_dynamic_column_server.php", false, $context);
			*/
			
			//$myfile = fopen("D:/xampp/htdocs/ospsante/administrator/components/com_jcode/source/report/pdfslice/htmlTable.txt", "r") or die("Unable to open file!");
			//$tblHTML.= fread($myfile,filesize("D:/xampp/htdocs/ospsante/administrator/components/com_jcode/source/report/pdfslice/htmlTable.txt"));
			//fclose($myfile);
			
			
			$tblHTML.=  $htmlTable;

//try			
// set auto page breaks, it also specifies margin-bottom. This scales the footer somehow...
$PDF_MARGIN_BOTTOM = 20;
$pdf->SetAutoPageBreak(true, $PDF_MARGIN_BOTTOM);
//try

		$pdf->SetFont('dejavusans', '', 8);
		$pdf->writeHTMLCell(0, 0, 10, (count($reportHeaderList)+1)*12,  $tblHTML , '', 1, 1, false, 'L', true);
		////$pdf->writeHTMLCell(0, 0, 10, 10500,  $tblHTML , '', 1, 1, false, 'L', true);		
		////$reportName = str_replace(' ','_',$reportHeaderList[1]).'_'.date('Y_m_d_H_i_s').'.pdf';		
		////$reportSaveNameUTF8 = iconv("UTF-8", "ISO-8859-9//TRANSLIT", $reportSaveName); // For french
    	////$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/'.$reportSaveNameUTF8.'.pdf';

    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/'.$reportSaveName.'.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);	
    	}
        
    $pdf->Output('pdfslice/'.$reportSaveName.'.pdf', 'F');      
//	   $pdf->Output($reportSaveName.'.pdf', 'I');      
		echo $reportSaveName.'.pdf';
		
		
		
?>