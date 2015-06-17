<?php

$vLang = isset($_REQUEST['lan']) ? $_REQUEST['lan'] : '';

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