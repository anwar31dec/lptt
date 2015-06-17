<?php
/*------------------------------------------------------------------------
# mod_jcode - JCode
# ------------------------------------------------------------------------
# Iacopo Guarneri
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.the-html-tool.com
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );  
	
	if(!file_exists("components/com_jcode/source")){
		Mkdir("components/com_jcode/source",0775);
		
		$handle = fopen("components/com_jcode/source/index.html", 'w');
		fwrite($handle,"");
		fclose($handle);
	}
	$txt=stripslashes($_POST['txt']);
	$txt=str_replace("[e_commerciale]","&",$txt);
	$txt=str_replace("[piu]","+",$txt);
	
	$file = "components/com_jcode/source/".$_POST['title'];
	$handle = fopen($file, 'w');
	fwrite($handle,$txt);
	fclose($handle);
?>
