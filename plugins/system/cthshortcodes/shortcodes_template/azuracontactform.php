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

$classes = 'azp_contactform azp_font_edit';

$animationData = '';
if($animationArgs['animation'] == '1'){
    if($animationArgs['trigger'] == 'animate-in'){
        $classes .= ' '.$animationArgs['trigger'];
        $animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
    }else{
        $classes .= ' '.$animationArgs['trigger'].'-'.$animationArgs['hoveranimationtype'];
        if($animationArgs['infinite'] != '0'){
            $classes .= ' infinite';
        }
    }
    
    
}

$classes = 'class="'.$classes.'"';

?>
<!--contact form -->

<div class="row text-center" <?php echo $classes.' '.$contactformstyle.' '.$animationData;?>>

    <div id="result"></div>

    <form class="azp_contact-form" method="post" action="<?php echo JURI::root();?>" name="contactform" id="contactform">

            <input type="text" name="name" class="form-control1" id="name" placeholder="<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_NAME_TEXT');?>">
            <input type="email" name="email" class="form-control1" id="email" placeholder="<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_EMAIL_TEXT');?>">
            <!-- <input type="text" name="website" class="form-control" id="phone" placeholder="<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_WEBSITE_TEXT');?>"> -->
            <textarea name="message" id="comments" class="form-control1" placeholder="<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_MESSAGE_TEXT');?>"></textarea>



            <?php if($sendascopy == '1') :?>
				<label ><?php echo JText::_('MOD_AZURAPAGEBUILDER_SEND_AS_A_COPY_TEXT');?></label>
				<input type="checkbox" value="1"  class="form-control" name="sendAsCopy">
			<div class="clear"></div>
			<?php endif;?>

            <input type="submit"  class="btn btn-default" id="submit" value="<?php echo JText::_('COM_AZURAPAGEBUILDER_CONTACT_SEND_TEXT');?>">

            <input type="hidden" name="receiveEmail" value="<?php echo $receiveemail;?>">
			<input type="hidden" name="subject" value="<?php echo $emailsubject;?>">
			<input type="hidden" name="thanks" value="<?php echo $thanksmessage;?>">
			<input type="hidden" name="option" value="com_azurapagebuilder">
			<input type="hidden" name="task" value="contact.sendemail">
			<?php echo JHtml::_('form.token'); ?>


    </form>
</div>
	

<script type="text/javascript">
jQuery(document).ready(function($){

	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
    /* contact form init  */
    /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    $('#contactform').submit(function(){
        var action = $(this).attr('action');
        $("#result").slideUp(300,function() {
            $('#result').hide();
            $('#submit')
                .attr('disabled','disabled');
            $.post(
            	action,
            	$('#contactform').serialize(),
                function(data){
                	//console.log(data);
                    document.getElementById('result').innerHTML = data.msg;
                    $('#result').slideDown('slow');
                    $('#submit').removeAttr('disabled');
                    if(data.info == 'success'){
                    	$('#contactform').slideUp('slow');
                    }
                },
                
                'json'
            );

        });

        return false;

    });


	
});
</script>
