<?php
defined('_JEXEC') or die;

// Include the syndicate functions only once

require_once __DIR__ . '/helper.php';

$twitter = new modAAAthemeTwitterHelper($params);

$data = $twitter->fetch();

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_aaathemetwitter', $params->get('layout', 'default'));

