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

//JHtml::_('behavior.keepalive');
//JHtml::_('behavior.formvalidation');
//JHtml::_('formbehavior.chosen', 'select');

// Squeeze box
JHTML::_('behavior.modal');

$doc = JFactory::getDocument();

$scr = 'var adComBaseUrl ="'. JURI::base().'/administrator/";';
$scr .= 'var pageID = "'.$this->item->id.'";';

$doc->addScriptDeclaration($scr);

//$doc->addStyleSheet(JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/css/style.css');

$doc->addStyleSheet(JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/fancybox/jquery.fancybox.css');
//$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/style.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/template.css');
//$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/front-grid.css');
//$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/base-style.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/azp-framework.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/front-style.css');
$doc->addStyleSheet(JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/css/jquery-ui.min.css');

//$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');

// $doc->addScript(JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/js/jquery.min.js');
// //$doc->addScript(JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/js/jquery-ui.min.js');
// //$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/elements-ui.js');
// $doc->addScript(JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/js/jquery.mousewheel-3.0.6.pack.js');
// $doc->addScript(JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/fancybox/jquery.fancybox.pack.js');

// Create shortcut to parameters.
$params = $this->state->get('params');

?>
<!--<script src="<?php echo JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/js/jquery.min.js';?>" type="text/javascript"></script>-->
<script src="<?php echo JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/js/jquery-ui.min.js';?>" type="text/javascript"></script>

<script src="<?php echo JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/fancybox/jquery.fancybox.js';?>" type="text/javascript"></script>
<script src="<?php echo JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/fancybox/helpers/jquery.fancybox-media.js';?>" type="text/javascript"></script>
<script src="<?php echo JURI::base(true).'/administrator/components/com_azurapagebuilder/assets/js/outerHTML-2.1.0-min.js';?>" type="text/javascript"></script>
<script src="<?php echo JURI::base(true).'/components/com_azurapagebuilder/assets/js/core.js';?>" type="text/javascript"></script>
<div class="azura-elements-page azura-element-content azp_container-fluid" style="display: block;<?php if($params->get('usefullwidth') == '1') echo 'padding:0px;';?>">
<?php if(isset($this->elements) && count($this->elements)){
	foreach ($this->elements as $key => $element) {
		echo do_shortcode($this->parseElement($element));
	}
}
?>
</div>
<div class="azuraAddElementPageWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElementPage"  style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 32px; cursor: pointer;"></i></div>

