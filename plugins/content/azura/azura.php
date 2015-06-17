<?php

defined('_JEXEC') or die;

class PlgContentAzura extends JPlugin
{

	public function onContentBeforeDelete($context, $data)
	{
		if($context == 'com_azurapagebuilder.page'){

			if((int)$data->id > 0){
				$db = JFactory::getDbo();
 
				$query = $db->getQuery(true);
				 
				// delete all elements for page id.
				$conditions = array( 
				    $db->quoteName('pageID') . ' = ' . (int)$data->id
				);
				 
				$query->delete($db->quoteName('#__azurapagebuilder_elements'));
				$query->where($conditions);
				 
				$db->setQuery($query);
				 
				$result = $db->execute();

				if($result !== false){
					return true;
				}
			}

			return false;
		}

		return true;
	}

}
