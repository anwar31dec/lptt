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
?>
<div class="login_form <?php echo $this->pageclass_sfx?>">		
	<form id="sky-form" method="post" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" class="sky-form">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<header><?php echo $this->escape($this->params->get('page_heading')); ?></header>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif; ?>

		<?php if ($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image') != '')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif; ?>	
		
		<fieldset>
			<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
				<?php if (!$field->hidden) : ?>
					<section>
						<div class="row">
							<label class="label col col-4"><?php echo $field->label; ?></label>
							<div class="col col-8">
								<label class="input">
									<!-- <i class="icon-append icon-user"></i> -->
									<?php echo $field->input; ?>
								</label>
							</div>
						</div>
					</section>

				<?php endif; ?>
			<?php endforeach; ?>

			<?php $tfa = JPluginHelper::getPlugin('twofactorauth'); ?>

			<?php if (!is_null($tfa) && $tfa != array()): ?>
				<section>
					<div class="row">
						<label class="label col col-4"><?php echo $this->form->getField('secretkey')->label; ?></label>
						<div class="col col-8">
							<label class="input">
								<!-- <i class="icon-append icon-user"></i> -->
								<?php echo $this->form->getField('secretkey')->input; ?>
							</label>
						</div>
					</div>
				</section>

			<?php endif; ?>
			
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<section>
					<div class="row">
						<div class="col col-4"></div>
						<div class="col col-8">
							<label class="checkbox"><input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"/><i></i><?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?></label>
						</div>
					</div>
				</section>

			<?php endif; ?>
			
			
		</fieldset>
		<footer>
			<div class="fright">
			<?php
			$usersConfig = JComponentHelper::getParams('com_users');
			if ($usersConfig->get('allowUserRegistration')) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>" class="button button-secondary"><?php echo JText::_('TPL_HOXA_REGISTER_TEXT');?></a>
			<?php endif; ?>

            
            <button type="submit" class="button"><?php echo JText::_('JLOGIN'); ?></button>
            </div>
			
		</footer>
		<ul class="nav ">
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
				<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
			</li>
			
		</ul>

		<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>			
</div>

<form action="demo-recovery.php" id="sky-form2" class="sky-form sky-form-modal">
	<header>Password recovery</header>
	
	<fieldset>					
		<section>
			<label class="label">E-mail</label>
			<label class="input">
				<i class="icon-append icon-user"></i>
				<input type="email" name="email" id="email">
			</label>
		</section>
	</fieldset>
	
	<footer>
		<button type="submit" name="submit" class="button">Submit</button>
		<a href="#" class="button button-secondary modal-closer">Close</a>
	</footer>
		
	<div class="message">
		<i class="icon-ok"></i>
		<p>Your request successfully sent!<br><a href="#" class="modal-closer">Close window</a></p>
	</div>
</form>
<div class="clearfix margin_top7"></div>
