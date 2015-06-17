<?php

defined('_JEXEC') or die;



// Include the syndicate functions only once

require_once __DIR__ . '/helper.php';



$id = $params->get('flickr-id');
$limit = $params->get('flickr-limit');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));



require JModuleHelper::getLayoutPath('mod_cththemesflickr', $params->get('layout', 'default'));

