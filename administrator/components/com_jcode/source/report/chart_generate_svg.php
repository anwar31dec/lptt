<?php
	$baseUrl = $_POST['baseUrl'];
	//$svgName = $_POST['alavel'];
	$svgName = $_POST['svgName'];
	$htmlTable = $_POST['htmlTable'];
	$chart = $_POST['chart'];
	//$chartName = $_POST['chartName'];
	
	
	//$htmlTable=$htmlTable.'<table><tbody><tr><td><div style="width:100%;background-color:#D7191C;">&nbsp;</div></td><td><div style="width:100%;background-color:#FE9929;">&nbsp;</div></td><td><div style="width:100%;background-color:#F0F403;">&nbsp;</div></td><td><div style="width:100%;background-color:#4DAC26;">&nbsp;</div></td><td><div style="width:100%;background-color:#50ABED;">&nbsp;</div></td></tr><tr><td>Stockout</td><td>High Risk</td><td>Medium Risk</td><td>Low Risk</td><td>Overstock</td></tr><tr><td> MOS: 0</td><td> MOS: 0.1 - 6</td><td> MOS: 6 - 12</td><td> MOS: 12 - 24</td><td> MOS: &gt; 24</td></tr></tbody></table>';
	
	
	
	
	
	if($chart==1){
		require_once('tcpdf/tcpdf.php');
		ini_set('magic_quotes_gpc', 'off');
		$html=htmlentities($_POST['html'], ENT_QUOTES, "UTF-8");
		$html=html_entity_decode($html, ENT_QUOTES, "UTF-8");

		$alavel=htmlentities($_POST['alavel'], ENT_QUOTES, "UTF-8");
		$alavel=html_entity_decode($alavel, ENT_QUOTES, "UTF-8");
		$htmlTable=$alavel.'<br>'.$htmlTable;
	 
		//$filePath = $baseUrl."/pdfslice/".$svgName.".svg"; 
		//$svgName = iconv("UTF-8", "ISO-8859-9//TRANSLIT", $svgName); // For french
		$filePath = "./pdfslice/".$svgName.".svg"; 
		if (file_exists($filePath)) {
			unlink($filePath);		
		}	
		$file = fopen($filePath,"w");
		fwrite($file, $html);
		
		fclose($file);
	}
	
	if($htmlTable){
		//$file = fopen($baseUrl."pdfslice/htmlTable.txt","w");
		$file = fopen("media/htmlTable.txt","w");
		echo fwrite($file,$htmlTable);
		fclose($file); 
	}
	//echo 'success';
?>