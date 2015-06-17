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

class AzuraPagebuilderModelElement extends JModelAdmin
{

	public function getForm($data = array(), $loadData = true)
	{

	}

	
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		
		$db = $this->getDbo();

		$query = $db->getQuery(true);

		$query->select('e.*')
				->from($db->quoteName('#__azurapagebuilder_elements') . ' as e')
				->where('e.id = '.(int) $pk);

		$db->setQuery($query);

		$item = $db->loadObject();

		return $item;
	}

	public function updateElement($data){
		$id = $data->id;
		if((int) $id > 0){
			// $attrsText = '';
			// foreach ($data->attrs as $attr => $value) {
			// 	$attrsText .= ' '.$attr .'="'.$value.'" ';
			// }
			if(!isset($data->content)){
				$data->content = '';
			}
			$data->shortcode = '';// '['.$data->type.$attrsText.']'.$data->content.'[/'.$data->type.']';
			$data->attrs = json_encode($data->attrs);
			$db = $this->getDbo();
			$result = $db->updateObject('#__azurapagebuilder_elements', $data, 'id');

			if((int)$result > 0) {
				//return $data->shortcode;
				return true;
			}
		}

		return false;
	}

	public function deleteElement($id){
		$result = false;
		if((int)$id > 0){
			$db = $this->getDbo();
 
			$query = $db->getQuery(true);
			 
			// delete all custom keys for user 1001.
			$conditions = array(
			    $db->quoteName('id') . '='.(int)$id
			);
			 
			$query->delete($db->quoteName('#__azurapagebuilder_elements'));
			$query->where($conditions);
			 
			$db->setQuery($query);
			 
			 
			$result = $db->query();
		}

		return $result;
	}

	
}
