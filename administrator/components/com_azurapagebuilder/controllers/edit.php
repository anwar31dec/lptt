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

require_once JPATH_COMPONENT_ADMINISTRATOR.'/controllers/page.php';

class AzuraPagebuilderControllerEdit extends AzuraPagebuilderControllerPage
{
	public function getEditor(){
	   //$data = $this->input->get('data','','raw');
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doc->addStyleSheet(JURI::base(true).'/components/com_azurapagebuilder/assets/font-awesome/css/font-awesome.min.css');
		$defaultvalue = new stdClass;
		$defaultvalue->content = rawurlencode('Lorem Ipsum passages, and more recently with desktop publishing software over the years.');
		$defaultvalue = json_encode($defaultvalue);
		$defaultvalue = rawurlencode($defaultvalue);
		$data = $app->getUserState('com_azurapagebuilder.element.html.data',$defaultvalue);
	   //echo'<pre>';var_dump($data);exit;
       	$data = json_decode(urldecode($data));
		$editor = JFactory::getEditor();
		$params = array( 'smilies'=> '0' ,
	                 'style'  => '1' ,  
	                 'layer'  => '0' , 
	                 'table'  => '0' ,
	                 'clear_entities'=>'0'
	                 );
		echo $editor->display( 'AzuraTextEditor', rawurldecode($data->content), '100%', '300', '20', '20', false, null, null, null, $params );
	}
	
}