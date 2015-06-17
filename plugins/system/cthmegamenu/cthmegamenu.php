<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class plgSystemCthMegaMenu extends JPlugin
{
	/*var $plugin = null;
    var $plgParams = null;
    var $time = 0;*/

    function __construct(&$subject, $config)
    {
            parent::__construct($subject, $config);
            $lang = JFactory::getLanguage();
            $lang->load('plg_system_cthmegamenu', JPATH_ADMINISTRATOR);
    }
	

	function onContentPrepareForm($form, $data)
	{
			if ($form->getName() == 'com_menus.item')
			{
					$xmlFile = dirname(__FILE__) . '/params';
					JForm::addFormPath($xmlFile);
					$form->loadFile('params', false);
			}
	}

}
