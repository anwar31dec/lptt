<?php
/**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



unset (JFactory::getDocument()->_scripts['/lptt/media/system/js/mootools-more.js']);

// print_r(JFactory::getDocument()->_scripts);
// exit;

// Getting params from template
$params = &JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$title = $doc->getTitle();
$this->language = $doc->language;

// TPL_NAME constant template name add by Anwar dated on 2015-04-05
define('TPL_NAME', $this->template);

$hideComponentErea = $params->get('hideComponentErea','0');

$input = $app->input;

// unset($doc->_scripts[JURI::root(true)."/media/system/js/mootools-core.js"]);
// unset($doc->_scripts[JURI::root(true)."/media/system/js/core.js"]);
// unset($doc->_scripts[JURI::root(true)."/media/system/js/mootools-more.js"]);
// unset($doc->_scripts[JURI::root(true)."/media/jui/js/jquery.min.js"]);
// unset($doc->_scripts[JURI::root(true)."/media/jui/js/jquery-noconflict.js"]);
// unset($doc->_scripts[JURI::root(true)."/media/jui/js/jquery-migrate.min.js"]);
// unset($doc->_scripts[JURI::root(true)."/components/com_k2/js/k2.js?v2.6.7&amp;sitepath=".JURI::base(true)."/"]);
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

// // Adjusting content width
// if (($this->countModules('left-sidebar')|| $this->countModules('position-8')) && ($this->countModules('right-sidebar')|| $this->countModules('position-7')))
// {
// 	$col = "grid-2";
// }
// elseif (($this->countModules('left-sidebar')|| $this->countModules('position-8')) && !$this->countModules('right-sidebar') && !$this->countModules('position-7'))
// {
// 	$col = "grid-4";
// }
// elseif (!$this->countModules('left-sidebar') && !$this->countModules('position-8') && ($this->countModules('right-sidebar')|| $this->countModules('position-7')))
// {
// 	$col = "grid-4";
// }
// else
// {
// 	$col = "grid-full";
// }

$templateprofile = $params->get('templateprofile','profile1');
// Site layout variation
$layoutsite = $params->get('layoutsite','wide');
// Background pattern
$pattern = $params->get('pattern','default');
// Content layout variation
$layoutstyle = $params->get('layoutstyle','rightsidebar');
// $headerstyle = $params->get('headerstyle','style1');
// $footerstyle = $params->get('footerstyle','style1');

// Google fonts
$bodyfont = $params->get('bodyfont','Open+Sans');
$bodyfontvariants = $params->get('bodyfontvariants',array());
$bodyfontfamily = $params->get('bodyfontfamily',"'Open Sans', sans-serif;");

$headingfont = $params->get('headingfont','Raleway');
$headingfontvariants = $params->get('headingfontvariants',array());
$headingfontfamily = $params->get('headingfontfamily',"'Raleway', sans-serif;");

$highlightfont = $params->get('highlightfont','Raleway');
$highlightfontvariants = $params->get('highlightfontvariants',array());
$highlightfontfamily = $params->get('highlightfontfamily',"'Raleway', sans-serif;");


if(count($bodyfontvariants)){
    $bodyfont .= ':'.implode(",", $bodyfontvariants);
}

if(count($headingfontvariants)){
    $headingfont .= ':'.implode(",", $headingfontvariants);
}

if(count($highlightfontvariants)){
    $highlightfont .= ':'.implode(",", $highlightfontvariants);
}

$favicon = $params->get('favicon');
$logoImage = $params->get('logoImage');
$logoText = $params->get('logoText');


// Color Presets				
$preset = $params->get('preset','default');
$overrideColor = $params->get('overrideColor','0');
$bC = substr($params->get('baseColor', '#13AFEB'),1);

// Animation Turn Off
$animationTurnOff = $params->get('animationTurnOff','0');

$template_folder = JURI::root(true).'/templates/'.$this->template;

// custom style and script
$customStyleLinks = array();
$customScriptLinks = array();

$themePath = JPATH_THEMES.'/'.$this->template;
$themeLink = JURI::root(true).'/templates/'.$this->template;

$customCssLinks = array();

$cusCssLinks = $params->get('customcsslinks');
if(!empty($cusCssLinks)){

    $customCssLinks = explode(",", $cusCssLinks);

}


if(!empty($customCssLinks)){

    foreach ($customCssLinks as $css) {
        if(file_exists($themePath.$css)){
            $customStyleLinks[] = $themeLink.$css;
        }elseif(file_exists($themePath.'/css/'.$css)){
            $customStyleLinks[] = $themeLink.'/css/'.$css;
        }elseif(file_exists($themePath.'/stylesheet/'.$css)){
            $customStyleLinks[] = $themeLink.'/stylesheet/'.$css;
        }
        
    }
}

$customJsLinks = array();

$cusJsLinks = $params->get('customjslinks');
if(!empty($cusJsLinks)){

    $customJsLinks = explode(",", $cusJsLinks);

}


if(!empty($customJsLinks)){

    foreach ($customJsLinks as $js) {
        if(file_exists($themePath.$js)){
            $customScriptLinks[] = $themeLink.$js;
        }elseif(file_exists($themePath.'/js/'.$js)){
            $customScriptLinks[] = $themeLink.'/js/'.$js;
        }elseif(file_exists($themePath.'/script/'.$js)){
            $customScriptLinks[] = $themeLink.'/script/'.$js;
        }
        
    }
}

?>

<?php require_once dirname(__FILE__).'/layout/header.php'; ?>



<?php if($this->countModules('breadcrumbs')) : ?>
	<!-- Breadcrumbs -->
	<jdoc:include type="modules" name="breadcrumbs"  style="none" />
<?php endif;?>



<?php if ($this->countModules('position-4')) : ?>
		<jdoc:include type="modules" name="position-4" style="none" />
<?php endif;?>

<?php if ($this->countModules('position-5')) : ?>
		<jdoc:include type="modules" name="position-5" style="none" />
<?php endif;?>

<?php if ($this->countModules('position-6')) : ?>
		<jdoc:include type="modules" name="position-6" style="none" />
<?php endif;?>


<?php if($hideComponentErea !=='1') {
	require_once dirname(__FILE__).'/layout/component.php';

} ?>
<!-- Component erea -->



<?php if ($this->countModules('position-9')) : ?>
		<jdoc:include type="modules" name="position-9" style="none" />
<?php endif;?>


<?php require_once dirname(__FILE__).'/layout/footer.php'; ?>