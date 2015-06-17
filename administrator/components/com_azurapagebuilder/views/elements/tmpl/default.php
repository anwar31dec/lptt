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

$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/font-awesome/css/font-awesome.min.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/elements.css');

$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery.min.js');

?>
<script src="<?php echo JURI::base(true).'/components/com_azurapagebuilder/assets/js/outerHTML-2.1.0-min.js';?>" type="text/javascript"></script>
<div class="azura-elements">
<?php foreach ($this->form->getGroup('azura_elements') as $field) : ?>
	<?php echo $field->getInput(); ?>
<?php endforeach; ?>
</div>

<script type="text/javascript">
	jQuery('body').on('click','.azura-element-block', function(event){
		event.preventDefault();
		var data = jQuery(this).outerHTML();

		window.parent.azuraAddElement(encodeURIComponent(data),'<?php echo $this->state->get('topage');?>');

		//window.parent.azuraFrontAddElement(jQuery(this).data('typename'),'<?php echo $this->state->get('topage');?>');

		
	});
</script>