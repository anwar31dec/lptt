<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();

// Create shortcuts to some parameters.
$params  = $this->item->params;

$user    = JFactory::getUser();

//$azuraParams = JComponentHelper::getParams('com_azurapagebuilder');
// use awesome icons font
// if($azuraParams->get('useawesome') && $azuraParams->get('useawesome') == '1'){
// 	$doc->addStylesheet(JURI::root(true).'/components/com_azurapagebuilder/assets/awesomefonts/css/font-awesome.min.css');
// }
// use css framework for bootstrap grid
// if($azuraParams->get('useazuracss') && $azuraParams->get('useazuracss') == '1'){
// 	$doc->addStylesheet(JURI::root(true).'/components/com_azurapagebuilder/assets/css/azp-framework.css');
// }

//$this->addCustomCss($this->item->customCssLinks);

if($this->item->jQueryLinkType == '1'){
	$doc->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
}elseif($this->item->jQueryLinkType == '2') {
	$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery.min.js');
}
if($this->item->noConflict == '1') {
	$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery.noconflict.js');
}

//$this->addCustomJs($this->item->customJsLinks);
?>
<div class="container">
	<div class="content_fullwidth lessmar">
		<?php if(isset($this->elements) && count($this->elements)){
			foreach ($this->elements as $key => $element) {
				echo do_shortcode($this->parseElement($element));
			}
		} ?>
	</div>
</div>