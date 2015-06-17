<?php

defined('_JEXEC') or die;

class PlgAzuraAzura extends JPlugin
{

	public function onAzuraPrepareElementForm($eleForm, $eleObj)
	{
		$formName = $eleForm->getName();
		if (preg_match('/^com_azurapagebuilder\.element\.formoption(.*)$/', $formName)) {
			$xmlFile = dirname(__FILE__) . '/options';
			JForm::addFormPath($xmlFile);
			$eleForm->loadFile('overrideoption', true);
		}

		/*
		if (preg_match('/^com_azurapagebuilder\.element\.(.*)$/', $formName,$matches)) {
			//echo'<pre>';var_dump($matches);die;
			if(!empty($matches[1])){
				$xmlFile = dirname(__FILE__) . '/options';
				JForm::addFormPath($xmlFile);
				$eleForm->loadFile($matches[1], true);
			}

			
		}
		*/

		return true;
	}

	public function onAzuraBeforePrepareElementForm(){
		$xmlFile = dirname(__FILE__) . '/options';
		JForm::addFormPath($xmlFile);
	}

}
