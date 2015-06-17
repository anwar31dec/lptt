<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();

class AzuraPagebuilderControllerContact extends JControllerLegacy {

	public function sendemail(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$input = $app->input;

		$mailfrom	= $app->getCfg('mailfrom');
		$fromname	= $app->getCfg('fromname');

		$name		= $input->getString('name');
		$website		= $input->getString('website','');
		$email		= JStringPunycode::emailToPunycode($input->getString('email'));

		// Set up regular expression strings to evaluate the value of email variable against
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
		// Run the preg_match() function on regex against the email address
		if (!preg_match($regex, $email)) {
		    echo json_encode(array("info"=>'error',"msg"=>JText::_('COM_AZURAPAGEBUILDER_INVALID_EMAIL_MESSSAGE')));
		    exit();
		}
		
		$body		= $input->getString('message');

		$receiveEmail = JStringPunycode::emailToPunycode($input->getString('receiveEmail'));
		$subject	= $input->getString('subject');
		$thanks	= $input->getString('thanks');

		// Prepare email body
		$prefix = JText::sprintf('COM_AZURAPAGEBUILDER_ENQUIRY_TEXT', JUri::base());
		$body	= $prefix."\n".$name.' <'.$email.'>'. ' '. $website. "\r\n\r\n".stripslashes($body);

		$mail = JFactory::getMailer();

		$mail->addRecipient($receiveEmail);
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($subject);
		$mail->setBody($body);
		$sent = $mail->Send();

		//If we are supposed to copy the sender, do so.

		// check whether email copy function activated
		if ($input->getInt('sendAsCopy') == '1')
		{
			$copytext		= JText::sprintf('COM_AZURAPAGEBUILDER_COPYTEXT_OF', $receiveEmail, $sitename);
			$copytext		.= "\r\n\r\n".$body;
			$copysubject	= JText::sprintf('COM_AZURAPAGEBUILDER_COPYSUBJECT_OF', $subject);

			$mail = JFactory::getMailer();
			$mail->addRecipient($email);
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($copysubject);
			$mail->setBody($copytext);
			$sent = $mail->Send();
		}

		$mes = (string)$sent;
		if (!($sent instanceof Exception)){
			echo json_encode(array("info"=>'success',"msg"=>$thanks));
		}else{
			echo json_encode(array("info"=>'error',"msg"=>$mes));
		}
		exit();
	}

	public function subscribe(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$input = $app->input;

		$mailfrom	= $app->getCfg('mailfrom');
		$fromname	= $app->getCfg('fromname');

		$name		= $input->getString('name');
		$email		= JStringPunycode::emailToPunycode($input->getString('e-mail'));

		// Set up regular expression strings to evaluate the value of email variable against
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
		// Run the preg_match() function on regex against the email address
		if (!preg_match($regex, $email)) {
		    echo json_encode(array("info"=>'error',"msg"=>JText::_('COM_AZURAPAGEBUILDER_INVALID_EMAIL_MESSSAGE')));
		    exit();
		}

		$receiveEmail = JStringPunycode::emailToPunycode($input->getString('receiveEmail'));
		$subject	= $input->getString('subject');
		$thanks	= $input->getString('thanks');

		// Prepare email body
		$prefix = JText::sprintf('COM_AZURAPAGEBUILDER_ENQUIRY_TEXT', JUri::base());
		$body	= $prefix."\n".$name.' <'.$email.'>'. "\r\n\r\n".stripslashes($subject);

		$mail = JFactory::getMailer();

		$mail->addRecipient($receiveEmail);
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($subject);
		$mail->setBody($body);
		$sent = $mail->Send();

		//If we are supposed to copy the sender, do so.

		// check whether email copy function activated
		if ($input->getInt('sendAsCopy') == '1')
		{
			$copytext		= JText::sprintf('COM_AZURAPAGEBUILDER_COPYTEXT_OF', $receiveEmail, $sitename);
			$copytext		.= "\r\n\r\n".$body;
			$copysubject	= JText::sprintf('COM_AZURAPAGEBUILDER_COPYSUBJECT_OF', $subject);

			$mail = JFactory::getMailer();
			$mail->addRecipient($email);
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($copysubject);
			$mail->setBody($copytext);
			$sent = $mail->Send();
		}

		$mes = (string)$sent;
		if (!($sent instanceof Exception)){
			echo json_encode(array("info"=>'success',"msg"=>$thanks));
		}else{
			echo json_encode(array("info"=>'error',"msg"=>$mes));
		}
		exit();
	}
}