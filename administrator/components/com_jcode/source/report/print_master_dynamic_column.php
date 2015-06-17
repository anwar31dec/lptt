<?php
include("../define.inc");

/* Receive all parameter*/
$jBaseUrl = $_REQUEST['jBaseUrl'];
$lan = $_REQUEST['lan'];
$reportSaveName = $_REQUEST['reportSaveName'];
$reportHeaderList = json_decode($_REQUEST['reportHeaderList'], true );

if($lan == 'en-GB')
	array_unshift($reportHeaderList, SITETITLEENG);
else
	array_unshift($reportHeaderList,SITETITLEFRN);

//$htmlTable=$_REQUEST['reportHtmlTable'];
$chart = $_REQUEST['chart'];
//$chartName = $_REQUEST['chartName'];

//====================================Dynamic Design======================================

$reportHeaderListCount = count($reportHeaderList);
	//if ($totalRec>0){
	  
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
				
			    th, td {
					border: 1px solid #e4e4e4 !important;
				}
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
	
	 td{
	border: 1px solid #e4e4e4 !important;
	text-align: right !important;	
	}
	 th{
	border: 1px solid #e4e4e4 !important;
	
	}
		
	 td:nth-child(2) {
		text-align: left !important;
	}
	
	 td:nth-child(1) {
		text-align: center !important;
	}

	
	
	.productname{
	width : 300px !important;
	
	
	}
				
			</style>
			</head>
			<body>'; 
			//<h3>'.$reportHeaderName.'<h3>	
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
									//$s = file_get_contents("http://localhost/ospsante/administrator/components/com_jcode/source/report/print_pdf_excel_server.php", false, $context);
									////////echo file_get_contents($jBaseUrl."administrator/components/com_jcode/source/report/print_pdf_excel_dynamic_column_server.php", false, $context);
*/

		//$myfile = fopen($jBaseUrl."administrator/components/com_jcode/source/report/pdfslice/htmlTable.txt", "r") or die("Unable to open file!");
		//echo fread($myfile,filesize($jBaseUrl."administrator/components/com_jcode/source/report/pdfslice/htmlTable.txt"));
		//fclose($myfile);
		$myfile = fopen("media/htmlTable.txt", "r") or die("Unable to open file!");
		echo fread($myfile,filesize("media/htmlTable.txt"));
		fclose($myfile);

			//	for($i=0; $i< count($htmlTable); $i++)
			//echo $htmlTable;						
									
									
									
    echo'</div>
		 </div>  
         </div>';
    echo '</body></html>';	
	
   //  }else{
 //  	    echo 'No record found';
 //   }
	

?>