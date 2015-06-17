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

class AzuraPagebuilderModelElements extends JModelAdmin
{

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_azurapagebuilder.elements';

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_AZURAPAGEBUILDER';

	protected $item = null;

	protected static $elementOrder = 0;

	public function getState($property = null, $default = null)
	{
		static $set;

		if (!$set)
		{
			$input = JFactory::getApplication()->input;

			$topage = $input->get('topage', '');
			$this->setState('topage', $topage);
			
			$set = true;
		}

		return parent::getState($property, $default);
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return;
			}

			$user = JFactory::getUser();

			if ($record->id)
			{
				return $user->authorise('core.delete', 'com_azurapagebuilder.page.'.(int) $record->id);
			}
			else
			{
				return parent::canDelete($record);
			}
		}
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_azurapagebuilder.page.'.(int) $record->id);
		}
		else
		{
			return parent::canEditState($record);
		}
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Page', $prefix = 'AzuraPagebuilderTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	* Get table for element
	*
	*/

	public function getTableElement($type = 'Element', $prefix = 'AzuraPagebuilderTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Abstract method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_azurapagebuilder.elements', 'elements', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}


		// Modify the form based on access controls.
		// if (!$this->canEditState((object) $data))
		// {
		// 	// Disable fields for display.
		// 	$form->setFieldAttribute('ordering', 'disabled', 'true');
		// 	$form->setFieldAttribute('state', 'disabled', 'true');
		// 	$form->setFieldAttribute('publish_up', 'disabled', 'true');
		// 	$form->setFieldAttribute('publish_down', 'disabled', 'true');

		// 	// Disable fields while saving.
		// 	// The controller has already verified this is a record you can edit.
		// 	$form->setFieldAttribute('ordering', 'filter', 'unset');
		// 	$form->setFieldAttribute('state', 'filter', 'unset');
		// 	$form->setFieldAttribute('publish_up', 'filter', 'unset');
		// 	$form->setFieldAttribute('publish_down', 'filter', 'unset');
		// }

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array  The default data is an empty array.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_azurapagebuilder.edit.page.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('page.id') == 0)
			{
				$app = JFactory::getApplication();
				
			}
		}

		$this->preprocessData('com_azurapagebuilder.page', $data);

		return $data;
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
		if ($item = parent::getItem($pk))
		{
			// Convert the metadata field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			$item->description = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_azurapagebuilder.page');
				$item->metadata['tags'] = $item->tags;
			}
		}

		$this->item = $item;

		return $item;
	}

	// get first level item
	public function getElements(){

		$db = $this->getDbo();

		$pageId = $this->getState($this->getName() . '.id');

		$query = $db->getQuery(true);

		$query->select('e.*')//select('e.id,e.pageID,e.name,e.type,e.shortcode,e.content,e.attrs')
				->from($db->quoteName('#__azurapagebuilder_elements') . ' as e')
				->where('e.level = 0 AND e.pageID = '.(int) $pageId)
				->order('e.elementOrder asc');

		$db->setQuery($query);

		$elements = $db->loadObjectList();

		return $elements;
	}

	public function getChildElements($id){

		$db = $this->getDbo();

		$pageId = $this->getState($this->getName() . '.id');

		$query = $db->getQuery(true);

		$query->select('e.*')//select('e.id,e.pageID,e.name,e.type,e.shortcode,e.content,e.attrs')
				->from($db->quoteName('#__azurapagebuilder_elements') . ' as e')
				->where('e.hasParentID = '.(int)$id.' AND e.pageID = '.(int) $pageId)
				->order('e.elementOrder asc');

		$db->setQuery($query);

		$elements = $db->loadObjectList();

		return $elements;
	}

	protected function getElement($id = 0){
		if((int)$id > 0){
			$table = $this->getTableElement();

			// Attempt to load the row.
			$return = $table->load((int)$id);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}


			// Convert to the JObject before adding other data.
			$properties = $table->getProperties(1);
			$item = JArrayHelper::toObject($properties, 'JObject');

			return $item;
		}

		return false;
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A reference to a JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias = JApplication::stringURLSafe($table->alias);

		if (empty($table->alias))
		{
			$table->alias = JApplication::stringURLSafe($table->title);
		}

		if (empty($table->id))
		{
			// Set the values

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__azurapagebuilder_pages');
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
			else
			{
				// Set the values
				$table->modified    = $date->toSql();
				$table->modified_by = $user->get('id');
			}
		}

		// Increment the product version number.
		$table->version++;
	}

	protected function prepareTableElement($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

		if (empty($table->id))
		{
			$pageId = $this->getState($this->getName() . '.id');

			$table->pageID = (int) $pageId;
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__azurapagebuilder_elements');
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
			else
			{
				// Set the values
				$table->modified    = $date->toSql();
				$table->modified_by = $user->get('id');
			}
		}

		$table->object = '  ';


		self::$elementOrder++;

		$table->elementOrder = self::$elementOrder;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  array  An array of conditions to add to ordering queries.
	 *
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();

		return $condition;
	}

	/* delete elements before save */

	protected function deleteElement($id){
		$db = $this->getDbo();
 
		$query = $db->getQuery(true);
		 
		// delete all custom keys for user 1001.
		$conditions = array(
		   $db->quoteName('pageID') . ' = ' . (int) $this->getState($this->getName() . '.id'),
		   $db->quoteName('id') . ' = ' . (int) $id
		);
		 
		$query->delete($db->quoteName('#__azurapagebuilder_elements'));
		$query->where($conditions);
		 
		$db->setQuery($query);
		 
		 
		$db->execute();
	}

	/* get elementsID */
	protected function getElementsID(){
		$db = $this->getDbo();

		$pageId = $this->getState($this->getName() . '.id');

		$query = $db->getQuery(true);

		$query->select('id')
				->from($db->quoteName('#__azurapagebuilder_elements'))
				->where('pageID = '.(int) $pageId);

		$db->setQuery($query);

		$elements = $db->loadRowList();

		return $elements;
	}
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since	3.1
	 */
	public function save($data)
	{

		$app = JFactory::getApplication();

		$dataShortcode = $data['shortcode'];


		$dataElementsArray = json_decode(rawurldecode($data['elementsArray']));


		$dataElementsSettingArray = json_decode(rawurldecode($data['elementsSettingArray']));

		$dataElementsShortcodeArray = json_decode(rawurldecode($data['elementsShortcodeArray']));

		$controllerTask = $app->input->get('task');
		// Alter the title for save as copy
		if ($controllerTask == 'save2copy')
		{
			list($name, $alias) = $this->generateNewTitle('', $data['alias'], $data['title']);
			$data['title']	= $name;
			$data['alias']	= $alias;
			$data['state']	= 0;
		}

		if(parent::save($data)){

			$elements = array();
			$elementsID = array();

			foreach ($dataElementsSettingArray as $key => $element) {

				$attrs = json_decode($element)->attrs;

				$element = get_object_vars(json_decode($element));
				
				$element['attrs'] = json_encode($attrs);

				$element['shortcode'] = rawurldecode($dataElementsShortcodeArray[$key]);

				if ($controllerTask == 'save2copy'){
					$element['id'] = 0;
				}else{
					$elementsID[] = $element['id'];
				}

				$elements[] = $element;
				

			}

			if(count($elementsID)){
				$elementsDatabase = $this->getElementsID();

				foreach ($elementsDatabase as $elIDArr) {
					if(!in_array($elIDArr[0], $elementsID)){
						$this->deleteElement($elIDArr[0]);
					}
				}
			}

			foreach ($elements as $el) {
				$this->savePageElement($el);
			}

			return true;
		}

		return false;

	}

	public function savePageElement($elementObject){

		$table = $this->getTableElement();

		$isNew = true;

		// Allow an exception to be thrown.
		try
		{
			// Load the row if saving an existing record.
			if ((int)$elementObject['id'] > 0)
			{
				
				$table->load((int)$elementObject['id']);
				$isNew = false;
			}
			
			// Bind the data.
			if (!$table->bind($elementObject))
			{

				$this->setError($table->getError());

				return false;
			}

			// Prepare the row for saving
			$this->prepareTableElement($table);

			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}
			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}
			// Clean the cache.
			//$this->cleanCache();
			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer  $category_id  The id of the parent.
	 * @param   string   $alias        The alias.
	 * @param   string   $name         The title.
	 *
	 * @return  array  Contains the modified title and alias.
	 *
	 * @since   3.1
	 */
	protected function generateNewTitle($category_id, $alias, $name)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
			if ($name == $table->title)
			{
				$name = JString::increment($name);
			}

			$alias = JString::increment($alias, 'dash');
		}

		return array($name, $alias);
	}
}
