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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="reg_form <?php echo $this->pageclass_sfx?>">
        <form id="sky-form" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate sky-form" enctype="multipart/form-data">

        <?php if ($this->params->get('show_page_heading')) : ?>
        	<header><?php echo $this->escape($this->params->get('page_heading')); ?></header>
	
<?php endif; ?>

				
				
				

				<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($fieldset->name);?>
	<?php if (count($fields)):?>
		<fieldset>
		<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.
		?>
			<!-- <legend><?php echo JText::_($fieldset->label);?></legend> -->
		<?php endif;?>
		<?php foreach ($fields as $field) :// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else:?>

				
					<?php if (!$field->required && $field->type != 'Spacer') : ?>
						<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
					<?php endif; ?>


				<section>
					<label class="input">
						<?php echo $field->label; ?>
						<!-- <i class="icon-append icon-user"></i> -->
						<?php echo $field->input;?>
						<b class="tooltip tooltip-bottom-right"><?php echo $field->label; ?></b>
					</label>
				</section>

			<?php endif;?>
		<?php endforeach;?>
		</fieldset>
	<?php endif;?>
<?php endforeach;?>
	

				<footer>
					<a class="button button-secondary" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
					<button type="submit" class="button validate"><?php echo JText::_('JREGISTER');?></button>
					
				</footer>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="registration.register" />
			<?php echo JHtml::_('form.token');?>
			</form>			
		</div>

<div class="clearfix margin_top7"></div>