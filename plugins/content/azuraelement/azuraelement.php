<?php

defined('_JEXEC') or die;

class PlgContentAzuraElement extends JPlugin
{

	public function onContentPrepareForm($form, $data)
	{
		$formName = $form->getName();
		if($formName == 'com_azurapagebuilder.elements'){
			$xmlFile = dirname(__FILE__) . '/element';
			JForm::addFormPath($xmlFile);
			$form->loadFile('elements', false);
		}

		return true;
	}

}
