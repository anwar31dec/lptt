<?php

include_once ('../database_conn.php');
include_once ("../function_lib.php");

/* Receive all parameter*/
$jBaseUrl = $_REQUEST['jBaseUrl'];
$lan = $_REQUEST['lan'];
$reportSaveName = $_REQUEST['reportSaveName'];
$reportHeaderList = json_decode($_REQUEST['reportHeaderList'], true );

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


include('print_pdf_excel_server.php');

		
?>