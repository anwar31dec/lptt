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
JToolBarHelper::title("JCode");

	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
	
	if(@$_GET['task']=="insert"){
		include("components/com_jcode/insert.php");
	}
	if(@$_GET['task']!="insert"):
?>
<script src="components/com_jcode/jquery.js"></script>
<script language="javascript" type="text/javascript">

function str_replace(str, str2, str3){
	return str3.split(str).join(str2);
}

function submitbutton() {
	var txt=Joomla.editors.instances['codice'].getCode();
	txt=str_replace("&","[e_commerciale]",txt);
	txt=str_replace("+","[piu]",txt);
	
	$.ajax({
		type: "POST",
		url: "index.php?option=com_jcode&task=insert",
		data: "txt="+txt+"&title="+$("#nome_file").val(),
		success: function(response){
			self.location.reload(true);
		}
	});
}
</script>

<h2>Files created</h2>
<div style="border-top:5px dotted #555;"></div>
<div style="width:100%; max-height:150px; overflow:auto; padding:15px 0;">
<?php

//modifica dei contenuti
function elencafiles($dirname){
	$arrayfiles=Array();
	if(file_exists($dirname)){
		$handle = opendir($dirname);
		while (false !== ($file = readdir($handle))) { 
			if(is_file($dirname.$file)){
				array_push($arrayfiles,$file);
			}
		}
		$handle = closedir($handle);
	}
	sort($arrayfiles);
	return $arrayfiles;
}
$arrayfile=array();
$arrayfile=elencafiles("components/com_jcode/source/");

foreach($arrayfile as $file){
	if($file!="index.html")
	echo "<div style='float:left; width:20%; margin-right:4%; height:20px; overflow:hidden;'><a href='index.php?option=com_jcode&file=".$file."'>".$file."</a></div>";
}
?>
</div>
<div style="border-top:5px dotted #555; margin-bottom:50px;"></div>

<?php

$contents="Insert code";
$contents_name="file_name.php";
if(@$_GET['file']!=""){
	$filename = "components/com_jcode/source/".$_GET['file'];
	$handle = fopen($filename, "r");
	$contents = stripslashes(fread($handle, filesize($filename)));
	$contents_name=$_GET['file'];
	fclose($handle);
}
echo 'administrator/components/com_jcode/source/<input type="text" id="nome_file" value="'.$contents_name.'">';

$editor = JFactory::getEditor("codemirror");
echo "<div style='border:1px solid #000;'>".$editor->display('codice', $contents, '100%', '768', '40', '5',false)."</div>";
echo JHTML::_( 'form.token' ); 
?>
<button type="button" onclick="submitbutton()"><?php echo JText::_('Save') ?></button>
<?php endif; ?>
