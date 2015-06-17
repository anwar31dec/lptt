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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');


$doc = JFactory::getDocument();

$scr = 'var adComBaseUrl ="'. JURI::base().'";';

$doc->addScriptDeclaration($scr);

//$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/bootstrap/css/bootstrap.min.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/grid/grid.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/fancybox/jquery.fancybox.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/style.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/css/jquery-ui.min.css');

//$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/font-awesome/css/font-awesome.min.css');

$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery.min.js');
$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery-ui.min.js');
//$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/elements-ui.js');
$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery.mousewheel-3.0.6.pack.js');
$doc->addScript(JURI::base(true).'/components/com_azurapagebuilder/assets/fancybox/jquery.fancybox.pack.js');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
//JHtml::_('formbehavior.chosen', 'select');



?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'page.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php //echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
		var PageShortcodeArrayObjects = '';
		if(task == 'page.apply' || task == 'page.save'){
			jQuery('#azura-sortable .azura-element-block').each(function(index, val) {
				 /* iterate through array or object */
				 var AzuraElementDatas =  jQuery(this).find('.azura-element-settings-saved').val();

				 PageShortcodeArrayObjects += AzuraElementDatas;
			});

			jQuery('#jform_shortcode').val(PageShortcodeArrayObjects);
		}


}

</script>
<script src="<?php echo JURI::base(true).'/components/com_azurapagebuilder/assets/js/jquery-ui.min.js';?>" type="text/javascript"></script>
<script src="<?php echo JURI::base(true).'/components/com_azurapagebuilder/assets/fancybox/jquery.fancybox.js';?>" type="text/javascript"></script>
<script src="<?php echo JURI::base(true).'/components/com_azurapagebuilder/assets/fancybox/helpers/jquery.fancybox-media.js';?>" type="text/javascript"></script>
<script src="<?php echo JURI::base(true).'/components/com_azurapagebuilder/assets/js/outerHTML-2.1.0-min.js';?>" type="text/javascript"></script>
<script src="<?php echo JURI::base(true).'/components/com_azurapagebuilder/assets/js/core.js';?>" type="text/javascript"></script>


<script>





</script>

<form action="<?php echo JRoute::_('index.php?option=com_azurapagebuilder&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_AZURAPAGEBUILDER_NEW_PAGE', true) : JText::sprintf('COM_AZURAPAGEBUILDER_EDIT_PAGE', $this->item->id, true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="form-vertical">
					<div class="row-fluid">
						
						<div class="span9">
							<div class="azuraAddElementPageWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElementPage"  style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 32px; cursor: pointer;"></i></div>
							<div class="row-fluid azura-sortable azura-elements-page" id="azura-sortable" style="padding:15px;">
								<?php 
									if(isset($this->elements) && count($this->elements)) {
										foreach ($this->elements as $element) {

											$this->parseElement($element);

										}
									} 
								?>
							</div>
						</div>
						<div class="span2 azp_col-md-offset-1">
							<div>
								<a href="<?php echo JURI::root().'index.php?option=com_azurapagebuilder&view=page&id='.$this->item->id;?>" class="btn btn-default" target="_blank">Preview</a>
								<a href="<?php echo JURI::root().'index.php?option=com_azurapagebuilder&view=edit&id='.$this->item->id;?>" class="btn btn-primary"  target="_blank">Frontend Edit</a>


							</div>
							<br>
							<div class="form-vertical" >
								<?php echo $this->form->getControlGroup('alt_layout'); ?>
								<?php echo $this->form->getControlGroup('customCssLinks'); ?>
								<?php echo $this->form->getControlGroup('customJsLinks'); ?>
								<?php echo $this->form->getControlGroup('customJsButtonLinks'); ?>
								<?php echo $this->form->getControlGroup('customJsBottomScript'); ?>
								<?php echo $this->form->getControlGroup('jQueryLinkType'); ?>
								<?php echo $this->form->getControlGroup('noConflict'); ?>
							</div>


							
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>


		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
				<hr>
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_AZURAPAGEBUILDER_FIELDSET_PERMISSIONS', true)); ?>
				<?php echo $this->form->getInput('rules'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	</div>

	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('shortcode'); ?>
	<?php echo $this->form->getInput('elementsArray'); ?>
	<?php echo $this->form->getInput('elementsSettingArray'); ?>
	<?php echo $this->form->getInput('elementsShortcodeArray');?>
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="copyright">
	<p><small style="float:left;">Azura Page Builder &copy; 2014 by <a href="http://cththemes.com" title="Cththemes.com">Cththemes</a></small><small style="float:right;">Version 1.2.0</small></p>
</div>
