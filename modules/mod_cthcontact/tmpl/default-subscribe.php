<?php 

defined('_JEXEC') or die;

?>
<div class="left"><i class="fa fa-envelope"></i> <h5 class="white"><?php echo JText::_('MOD_CONTACT_SIGN_UP_NEWSLETTER_TEXT');?></h5></div>
        
<div class="right">
<form id="subscribe_form" method="post" action="<?php echo JURI::root();?>" enctype="multipart/form-data">
<input class="enter_email_input" name="e-mail" id="samplees" value="<?php echo JText::_('MOD_CTHCONTACT_ENTER_EMAIL_ADDRESS_TEXT');?>" onFocus="if(this.value == '<?php echo JText::_('MOD_CTHCONTACT_ENTER_EMAIL_ADDRESS_TEXT');?>') {this.value = '';}" onBlur="if (this.value == '') {this.value = '<?php echo JText::_('MOD_CTHCONTACT_ENTER_EMAIL_ADDRESS_TEXT');?>';}" type="text">
    <div class="clearfix"></div>
    <input value="<?php echo JText::_('MOD_CTHCONTACT_SUBSCRIBE_NOW_TEXT');?>" class="input_submit" id="subscribe_submit" type="submit">
    <input type="hidden" name="receiveEmail" value="<?php echo $email;?>">
	<input type="hidden" name="subject" value="<?php echo $subject;?>">
	<input type="hidden" name="thanks" value="<?php echo $thanks;?>">
	<input type="hidden" name="option" value="com_azurapagebuilder">
	<input type="hidden" name="task" value="contact.subscribe">
	<?php echo JHtml::_('form.token'); ?>
	<div class="clearfix"></div>
	<div id="subscribe_result"></div>

</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){

	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
    /* subscribe form init  */
    /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    $('#subscribe_form').submit(function(){
        var action = $(this).attr('action');
        $("#subscribe_result").slideUp(300,function() {
            $('#subscribe_result').hide();
            $('#subscribe_submit')
                .attr('disabled','disabled');
            $.post(
            	action,
            	$('#subscribe_form').serialize(),
                function(data){
                	//console.log($('input[name="e-mail"]'));
                    document.getElementById('subscribe_result').innerHTML = data.msg;
                    $('#subscribe_result').slideDown('slow');
                    $('#subscribe_submit').removeAttr('disabled');
                    if(data.info == 'success'){
                    	$('input[name="e-mail"]').val('<?php echo JText::_('MOD_CTHCONTACT_ENTER_EMAIL_ADDRESS_TEXT');?>');//.slideUp('slow');
                    }
                },
                
                'json'
            );

        });

        return false;

    });


	
});
</script>