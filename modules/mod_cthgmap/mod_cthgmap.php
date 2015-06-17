<?php
defined('_JEXEC') or die;

$gmaplat = $params->get('gmapLat','41.8744661');
$gmaplog = $params->get('gmapLog','-87.6614312');

$gmappancontrol = $params->get('gmapPancontrol','1');
$gmapzoomcontrol = $params->get('gmapZoomcontrol','1');
$gmaptypecontrol = $params->get('gmapTypecontrol','1');
$gmapstreetviewcontrol = $params->get('gmapStreetviewcontrol','1');
$gmapscrollwheel = $params->get('gmapScrollwheel','1');
$gmapzoom = $params->get('gmapZoom','15');
$gmaptypeid = $params->get('gmapTypeId','ROADMAP');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));



require JModuleHelper::getLayoutPath('mod_cthgmap', $params->get('layout', 'default'));

