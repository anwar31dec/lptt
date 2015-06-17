<?php
$user = JFactory::getUser();
$lang = JFactory::getLanguage();

$vLang = $lang->getTag();

$TEXT = '';

switch ($vLang) {
	case 'en-GB' :
		$TEXT = $TEXT_EN;
		break;
	case 'fr-FR' :
		$TEXT = $TEXT_FR;
		break;
            
}
?>