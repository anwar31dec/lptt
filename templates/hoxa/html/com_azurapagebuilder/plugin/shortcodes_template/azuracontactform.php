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

?>
<!--contact form -->
<?php echo $introduction;?>
        
<div class="cforms">
        
    <form action="<?php echo JURI::root();?>" method="post" id="sky-form" class="sky-form">
          <header>{lang en}<?php echo $title;?>{/lang}{lang fr}Formulaire <strong>de contact</strong>{/lang}</header>
              <fieldset>
                    <div class="row">
                          <section class="col col-6">
                            <label class="label">{lang en}<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_NAME_TEXT');?>{/lang}{lang fr}nom{/lang}</label>
                            <label class="input"> <i class="icon-append icon-user"></i>
                              <input type="text" name="name" id="name">
                            </label>
                          </section>
                          <section class="col col-6">
                            <label class="label">{lang en}<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_EMAIL_TEXT');?>{/lang}{lang fr}Adresse e-mail{/lang}</label>
                            <label class="input"> <i class="icon-append icon-envelope-alt"></i>
                              <input type="email" name="email" id="email">
                            </label>
                          </section>
                    </div>
                <section>
                    <label class="label">{lang en}<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_SUBJECT_TEXT');?>{/lang}{lang fr}sujet{/lang}</label>
                    <label class="input"> <i class="icon-append icon-tag"></i>
                        <input type="text" name="subject" id="subject">
                    </label>
                </section>
                <section>
                  <label class="label">{lang en}<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_MESSAGE_TEXT');?>{/lang}{lang fr}message{/lang}</label>
                  <label class="textarea"> <i class="icon-append icon-comment"></i>
                    <textarea rows="4" name="message" id="message"></textarea>
                  </label>
                </section>
                <?php if($sendascopy == '1') :?>
                <section>
                  <label class="checkbox">
                    <input type="checkbox" name="copy" id="copy">
                    <i></i>{lang en}<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_SEND_AS_COPY');?>{/lang}{lang fr}Envoyer une copie à mon adresse e-mail{/lang}</label>
                </section>
				<section>
				<div class="g-recaptcha" data-sitekey="6LfvHQATAAAAAAM0Jyx5fXSzYxl5iqLGiETpbfIl"></div>
				</section>
                <?php endif;?>
              </fieldset>
          <footer>
            <button type="submit" class="button">{lang en}<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_SEND_MESSAGE_TEXT');?>{/lang}{lang fr}Envoyer un message{/lang}</button>
          </footer>
          <div class="message"> <i class="icon-ok"></i>
            <p><?php echo $thanksmessage;?></p>
          </div>
          <input type="hidden" name="receiveEmail" value="<?php echo $receiveemail;?>">
            
            <input type="hidden" name="thanks" value="<?php echo $thanksmessage;?>">
            <input type="hidden" name="option" value="com_azurapagebuilder">
            <input type="hidden" name="task" value="contact.sendemail">
            <?php echo JHtml::_('form.token'); ?>
    </form>
        
</div>

