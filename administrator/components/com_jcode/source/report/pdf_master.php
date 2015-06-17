<?php

include_once ('../database_conn.php');
include_once ("../function_lib.php");

/* Receive all parameter*/
$jBaseUrl = $_REQUEST['jBaseUrl'];
$lan = $_REQUEST['lan'];
$reportSaveName = $_REQUEST['reportSaveName'];
$reportHeaderList = json_decode($_REQUEST['reportHeaderList'], true );
//array_unshift($reportHeaderList,'Health Commodity Dashboard');
if($lan == 'en-GB')
	array_unshift($reportHeaderList, SITETITLEENG);
else
	array_unshift($reportHeaderList,SITETITLEFRN);

$dataType = json_decode($_REQUEST['dataType'], true );
$groupBySqlIndex = $_REQUEST['groupBySqlIndex'];//($_REQUEST['groupBySqlIndex'] == '')? -1 : $_REQUEST['groupBySqlIndex'];
$alignment = array("date" => "center","numeric"=>"right","string"=>"left",""=>"center","0"=>"left","html"=>"left");
$tableHeaderList = json_decode($_REQUEST['tableHeaderList'], true );
$tableHeaderWidth = json_decode($_REQUEST['tableHeaderWidth'], true );
$sqlParameterList = json_decode($_REQUEST['sqlParameterList'], true );
$colorCodeIndex = json_decode($_REQUEST['colorCodeIndex'], true );
$checkBoxIndex = json_decode($_REQUEST['checkBoxIndex'], true );
$reportType = $_REQUEST['reportType'];
$chart = @$_REQUEST['chart'];

//====================================Dynamic Design======================================
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
    $pdf->SetFillColor(255,255,255);

	
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
             td{
                 height: 6px;
                 line-height:3px;
             }
             th{
             height: 20;
             font-size:10px;
            }
            </style> ';
			//$reportSaveNameUTF8 = iconv("UTF-8", "ISO-8859-9//TRANSLIT", $reportSaveName); // For french
            if($chart==1){
				
				$svgfilePath='pdfslice/'.$reportSaveName.'.svg';
				$tblHTML.='<img src="'.$svgfilePath.'" width=auto height=auto />';
			}
			
			$tblHTML.='<body> ';
			
		/*	$postdata = http_build_query(
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
			$tblHTML.= file_get_contents($jBaseUrl."administrator/components/com_jcode/source/report/print_pdf_excel_server.php", false, $context);

			*/
			//
			ob_start();
			// or in our case
			include "print_pdf_excel_server.php";
			$result = ob_get_contents();
			ob_end_clean();
			$tblHTML.= $result;
			

			  $pdf->writeHTMLCell(0, 0, 10, (count($reportHeaderList)+1)*12,  $tblHTML , '', 1, 1, false, 'L', true);
			 
			
		
    	$filePath=SITEDOCUMENT.'administrator/components/com_jcode/source/report/pdfslice/'.$reportSaveName.'.pdf';
    	if (file_exists($filePath)) {
    		unlink($filePath);		
    	}
        
        $pdf->Output('pdfslice/'.$reportSaveName.'.pdf', 'F');
       	echo $reportSaveName.'.pdf';	



?>