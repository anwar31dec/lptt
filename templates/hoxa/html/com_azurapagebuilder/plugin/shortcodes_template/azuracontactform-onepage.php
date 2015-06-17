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
        
<div class="cforms animate" data-anim-type="fadeInUp" data-anim-delay="200">
        
    <form action="<?php echo JURI::root();?>" method="post" id="sky-form" class="sky-form">
          
              <fieldset>
                <div class="row">
                  <div class="col col-6">
                    <label class="label"><?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_NAME_TEXT');?></label>
                    <label class="input"> <i class="icon-append icon-user"></i>
                      <input type="text" name="name" id="name">
                    </label>
                  </div>
                  <div class="col col-6">
                    <label class="label"><?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_EMAIL_TEXT');?></label>
                    <label class="input"> <i class="icon-append icon-envelope-alt"></i>
                      <input type="email" name="email" id="email">
                    </label>
                  </div>
                </div>
                <div>
                  <label class="label"><?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_SUBJECT_TEXT');?></label>
                  <label class="input"> <i class="icon-append icon-tag"></i>
                    <input type="text" name="subject" id="subject">
                  </label>
                </div>
                <div>
                  <label class="label"><?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_MESSAGE_TEXT');?></label>
                  <label class="textarea"> <i class="icon-append icon-comment"></i>
                    <textarea rows="4" name="message" id="message"></textarea>
                  </label>
                </div>
                
                
                <?php if($sendascopy == '1') :?>
                <div>
                  <label class="checkbox">
                    <input type="checkbox" name="copy" id="copy">
                    <?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_SEND_AS_COPY');?></label>
                </div>
                <?php endif;?>
              </fieldset>
          <footer>
            <button type="submit" class="button"><?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_SEND_MESSAGE_TEXT');?></button>
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

