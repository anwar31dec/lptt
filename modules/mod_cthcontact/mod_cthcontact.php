<?php

defined('_JEXEC') or die;

$title = $params->get('title','');
$introduction = $params->get('introduction','');

$email = $params->get('email','contact@cththemes.com');
$subject = $params->get('subject','Contact Subject');
$thanks = $params->get('thanks','Thanks for contacting with us');
$ascopy = $params->get('asCopy','0');
$showwebsite = $params->get('showWebsite','0');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));



require JModuleHelper::getLayoutPath('mod_cthcontact', $params->get('layout', 'default'));

