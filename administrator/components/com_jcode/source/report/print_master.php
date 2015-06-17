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

//$reportHeaderList = $reportHeaderList;
$dataType = json_decode($_REQUEST['dataType'], true );
$groupBySqlIndex = $_REQUEST['groupBySqlIndex'];//($_REQUEST['groupBySqlIndex'] == '')? -1 : $_REQUEST['groupBySqlIndex'];
$alignment = array("date" => "center","numeric" => "right", "string" => "left", "" => "center", "0" => "left", "html" => "left");
$tableHeaderList = json_decode($_REQUEST['tableHeaderList'], true );
$tableHeaderWidth = json_decode($_REQUEST['tableHeaderWidth'], true );
$sqlParameterList = json_decode($_REQUEST['sqlParameterList'], true );
$colorCodeIndex = json_decode($_REQUEST['colorCodeIndex'], true );
$checkBoxIndex = json_decode($_REQUEST['checkBoxIndex'], true );
$reportType = $_REQUEST['reportType'];
$chart = @$_REQUEST['chart'];

//====================================Dynamic Design======================================
$reportHeaderListCount = count($reportHeaderList);
	  
		echo '<!DOCTYPE html>
			 <html>
			 <head>
			  <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
			  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
			  <meta name="generator" content="Joomla! - Open Source Content Management" /
			  <link rel="stylesheet" href="tcpdf/css/template.css" type="text/css" /> 
			  <link href="tcpdf/css/bootstrap.min.css" rel="stylesheet">
			  <link href="tcpdf/css/font-awesome.min.css" rel="stylesheet">
			  <link href="tcpdf/css/pace.css" rel="stylesheet">	
			  <link href="tcpdf/css/colorbox/colorbox.css" rel="stylesheet">
			  <link href="tcpdf/css/morris.css" rel="stylesheet"/> 	
              <link href="tcpdf/css/endless.min.css" rel="stylesheet"> 
	          <link href="tcpdf/css/endless-skin.css" rel="stylesheet">
			  <link href="tcpdf/css/custom.css" rel="stylesheet"/>
			
			<style>
				table.display tr.even.row_selected td {
    				background-color: #4DD4FD;
			    }    
			    table.display tr.odd.row_selected td {
			    	background-color: #4DD4FD;
			    }
			    .SL{
			        text-align: center !important;
			    }
			    td.Countries{
			        cursor: pointer;
			    }   
			</style>
			</head>
			<body>'; 
			echo '<div class="row"> 
          	<div class="panel panel-default table-responsive" id="grid_main">
           	<div class="padding-md clearfix">
           	<div class="panel-heading" style="text-align:center;">';
			
			//Report Header
			for($i=0;$i<$reportHeaderListCount;$i++){
				if($i==0)
					echo '<h2>'.$reportHeaderList[$i].'<h2>';
				else if($i==1)
					echo '<h3>'.$reportHeaderList[$i].'<h3>';
				else
					echo '<h4>'.$reportHeaderList[$i].'<h4>';
			}
            echo '</div>';
			
			if($chart==1){			
			$svgfilePath='pdfslice/'.$reportSaveName.'.svg';
			echo '<div class="panel panel-default">
						<div class="panel-body">
							<div id="barchart-container">
							
								<div id="bar-chart" width="100%">
								<img src="'.$svgfilePath.'" width="100%" height="auto">								
								</div>
							</div>
						</div>           
					</div>';
			}
			
			include('print_pdf_excel_server.php');
			/*$postdata = http_build_query(
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
									//$s = file_get_contents("http://localhost/ospsante/administrator/components/com_jcode/source/report/print_pdf_excel_server.php", false, $context);
									echo file_get_contents($jBaseUrl."administrator/components/com_jcode/source/report/print_pdf_excel_server.php", false, $context);
			*/
    echo'</div>
		 </div>  
         </div>';
    echo '</body></html>';	
		
?>