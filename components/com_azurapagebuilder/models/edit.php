<?php

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/page.php';

class AzuraPagebuilderModelEdit extends AzuraPagebuilderModelPage
{

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_azurapagebuilder.page';

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_AZURAPAGEBUILDER';


		public function getForm($data = array(), $loadData = true)
		{
		
		// Get the form.
		$form = $this->loadForm('com_azurapagebuilder.edit', 'edit', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$form->setFieldAttribute('id', 'type', 'hidden');
		$form->setFieldAttribute('title', 'type', 'hidden');
		$form->setFieldAttribute('alias', 'type', 'hidden');


		return $form;
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
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();

		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');

		if (property_exists($item, 'params'))
		{
			$registry = new JRegistry;
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}
		if (property_exists($item, 'order_params'))
		{
			$registry = new JRegistry;
			$registry->loadString($item->order_params);
			$item->order_params = $registry->toArray();
		}

		return $item;
	}

	public function getAlt_layout($pk = null)
	{
		$user	= JFactory::getUser();
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($db->quoteName('a.alt_layout'));

		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');

		$query->from($db->quoteName('#__azurapagebuilder_pages').' AS a');

		$query->where('a.id = '. (int) $pk);

		$db->setQuery($query);

		// convert page params to JRegistry object

		return $db->loadResult();

	}


	public function getReturnPage(){
		$url = JURI::getInstance()->root();
		$url = base64_encode($url);

		return $url;
	}
}
