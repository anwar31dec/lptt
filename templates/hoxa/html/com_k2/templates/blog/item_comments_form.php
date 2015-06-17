<?php
/**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// no direct access
defined('_JEXEC') or die;

?>
<!-- comment form -->
<div class="comment_form">
      
      <h4><?php echo JText::_('K2_LEAVE_A_COMMENT') ?></h4>
      
      <form action="<?php echo JURI::root(); ?>" method="post" id="comment-form" name="comment-form">
          <input type="text" name="userName" id="userName" class="comment_input_bg" />
          <label for="userName"><?php echo JText::_('TPL_HOXA_NAME_REQUIRED_TEXT');?></label>
          
          <input type="text" name="commentEmail" id="commentEmail" class="comment_input_bg" />
          <label for="commentEmail"><?php echo JText::_('TPL_HOXA_EMAIL_REQUIRED_TEXT');?></label>
          
          <input type="text" name="commentURL" id="commentURL" class="comment_input_bg" />
          <label for="commentURL"><?php echo JText::_('TPL_HOXA_WEBSITE_TEXT');?></label>
          
          <textarea name="commentText" id="commentText" class="comment_textarea_bg" rows="20" cols="7" ></textarea>
          <div class="clearfix"></div> 
          <?php if($this->params->get('recaptcha') && ($this->user->guest || $this->params->get('recaptchaForRegistered', 1))): ?>
          <label class="formRecaptcha"><?php echo JText::_('K2_ENTER_THE_TWO_WORDS_YOU_SEE_BELOW'); ?></label>
          <div id="recaptcha"></div>
          <div class="clearfix"></div> 
          <?php endif; ?>
          <input name="send" id="submitCommentButton" type="submit" value="<?php echo JText::_('TPL_HOXA_SUBMIT_COMMENT_TEXT') ?>" class="comment_submit"/>
          <p></p>
          <br/><br/><span id="formLog"></span><br/><br/>
          <!-- <p class="comment_checkbox"><input type="checkbox" name="check" /> Notify me of followup comments via e-mail</p> -->
        
        <input type="hidden" name="option" value="com_k2" />
        <input type="hidden" name="view" value="item" />
        <input type="hidden" name="task" value="comment" />
        <input type="hidden" name="itemID" value="<?php echo JRequest::getInt('id'); ?>" />
        <?php echo JHTML::_('form.token'); ?>
      </form>
      
</div><!-- end comment form -->
            
<div class="clearfix mar_top2"></div> 
