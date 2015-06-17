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
	
	$app = JFactory::getApplication('site');
	$params = $app->getParams('com_jcode');
	$file = $params->get( 'file_name', 0);

	$dir=dirname(__FILE__);
	$dir=str_replace("components","administrator/components",$dir)."/source/".$file;
	if(file_exists($dir)){
		include($dir);
	}else{
		echo"not found: ".$dir;
	} 
?>

