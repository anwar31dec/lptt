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

class AzuraPagebuilderModelPage extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $context = 'com_azurapagebuilder.page';


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('page.id', $pk);

		// Load the parameters.
		$params = $app->getParams();

		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_azurapagebuilder')) && (!$user->authorise('core.edit', 'com_azurapagebuilder')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$user	= JFactory::getUser();
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($this->getState('item.select', 'a.*'));

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('page.id');

		$query->from($db->quoteName('#__azurapagebuilder_pages').' AS a');

		// Join on user table.
		$query->select('u.name AS author')
				->join('LEFT', '#__users AS u on u.id = a.created_by');

		$query->where('a.id = '. (int) $pk);

		$db->setQuery($query);

		// convert page params to JRegistry object

		$result = $db->loadObject();

		if($result){

			$params = new JRegistry;

			$params->loadString($result->params);

			$result->params = $params;

			$metadata = new JRegistry;

			$metadata->loadString($result->metadata);

			$result->metadata = $metadata;

			return $result;
		}else{
			return JError::raiseError(404, JText::_('COM_AZURAPAGEBUILDER_ERROR_PAGE_NOT_FOUND'));
		}

		

		
	}

	public function getElements($pageID = null){
		$pageID = (!empty($pageID)) ? $pageID : (int) $this->getState('page.id');

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select('e.*');
		$query->from($db->quoteName('#__azurapagebuilder_elements') . ' AS e');
		$query->where('e.level = 0 AND e.pageID = ' .(int) $pageID);
		$query->where('e.published = 1 AND e.trash = 0');

		$query->order('e.elementOrder ASC');

		$db->setQuery($query,0,'All');

		$results = $db->loadObjectList();

		return $results;

	}

	public function getChildElements($id){

		$db = $this->getDbo();

		$pageId = $this->getState('page.id');

		$query = $db->getQuery(true);

		$query->select('e.*')//select('e.id,e.pageID,e.name,e.type,e.shortcode,e.content,e.attrs')
				->from($db->quoteName('#__azurapagebuilder_elements') . ' as e')
				->where('e.hasParentID = '.(int)$id.' AND e.pageID = '.(int) $pageId)
				->where('e.published = 1 AND e.trash = 0')
				->order('e.elementOrder asc');

		$db->setQuery($query);

		$elements = $db->loadObjectList();

		return $elements;
	}

	public function getAlt_layout($pk = null)
	{
		$user	= JFactory::getUser();
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($db->quoteName('a.alt_layout'));

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('page.id');

		$query->from($db->quoteName('#__azurapagebuilder_pages').' AS a');

		$query->where('a.id = '. (int) $pk);

		$db->setQuery($query);

		// convert page params to JRegistry object

		return $db->loadResult();

	}
	public function addComment(){

		$db = JFactory::getDbo();
		$userId = JFactory::getUser()->get('id');
		$input = JFactory::getApplication()->input;
		$comment = $input->getString('comment', '');

		$pk = (int) $this->getState('product.id');

		// variable for new comment to add
		$newComment = array();

		$newComment['userid'] = (int) $userId;
		$newComment['username'] = JFactory::getUser()->get('username');
		$newComment['comment'] = $comment;
		$newComment['created'] = JFactory::getDate()->toSql(true);


		$query = $db->getQuery(true);

			// Create the base select statement.
		$query->select('*')
			  ->from($db->quoteName('#__carts_products_comments'))
			  ->where($db->quoteName('product_id') . ' = ' . (int) $pk);

		// Set the query and load the result.
		$db->setQuery($query);
		$comments = $db->loadObject();

		$return = array();

		// Check for a database error.
		if ($db->getErrorNum()){
			$return['success'] = false;
			$return['error'] = $db->getErrorMsg();

			return $return;
		}

		if (!$comments) {
			// add comment for new product
			$commentExists = array();
			$commentExists[] = $newComment;

			$temp = new JRegistry;
			$temp->loadArray($commentExists);

			$commentExists = $temp->toString();

			$query = $db->getQuery(true);

				// Create the base insert statement.
				$query->insert($db->quoteName('#__carts_products_comments'))
					->columns(array($db->quoteName('product_id'), $db->quoteName('comments'), $db->quoteName('params')))
					->values((int) $pk . ', ' . $db->quote($commentExists) . ', "params goes here"');

				// Set the query and execute the insert.
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					JError::raiseWarning(500, $e->getMessage());

					$return['success'] = false;
					$return['error'] = $e->getMessage();

					return $return;
				}

			$return['success'] = true;
			$return['error'] = '';
			$return['comment'] = $newComment;

			return $return;
		}else{

			$commentExists = $comments->comments;

			$temp = new JRegistry;

			$temp->loadString($commentExists);

			$commentExists = $temp->toArray();

			array_unshift($commentExists, $newComment);

			$temp = new JRegistry;

			$temp->loadArray($commentExists);

			$commentExists = $temp->toString();

			//$return[] = $commentExists;

			//return $return;



			$query = $db->getQuery(true);

				// Create the base update statement.
				$query->update($db->quoteName('#__carts_products_comments').' ')
					->set($db->quoteName('comments') . ' = ' .$db->quote($commentExists))
					->where($db->quoteName('product_id') . ' = ' . (int) $pk);

				// Set the query and execute the update.
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					JError::raiseWarning(500, $e->getMessage());

					$return['success'] = false;
					$return['error'] = $e->getMessage();

					return $return;
				}

			$return['success'] = true;
			$return['error'] = '';
			$return['comment'] = $newComment;

			return $return;

		}

		$return['success'] = false;
		$return['error'] = 'Unknow error';

		return $return;
	}


	public function getComments(){

		$db = JFactory::getDbo();
		$input = JFactory::getApplication()->input;

		$pk = (int) $this->getState('product.id');

		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
			  ->from($db->quoteName('#__carts_products_comments'))
			  ->where($db->quoteName('product_id') . ' = ' . (int) $pk);

		// Set the query and load the result.
		$db->setQuery($query);
		$comment = $db->loadObject();

		$return = array();

		// Check for a database error.
		if ($db->getErrorNum()){

			JError::raiseWarning(500, $db->getErrorMsg());

			return false;
		}

		if(!$comment){
			return false;
		}
		else {
			$return = $comment->comments;

			$temp = new JRegistry;
			$temp->loadString($return);
			$return = $temp->toArray();

			$this->_comments_total = count($return);

			$limit = $this->getState('list.limit');

			$limitstart = $this->getState('list.start');

			$return = array_slice($return, $limitstart, $limit);

			return $return;
		}

	}

		/**
	 * Method to get a JPagination object for the comments data set.
	 *
	 * @return  JPagination  A JPagination object for the data set.
	 *
	 * @since   12.2
	 */
	public function getCommentsPagination()
	{
		// Get a storage key.
		$store = $this->getStoreId('getPagination');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Create the pagination object.
		$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
		$page = new JPagination($this->getTotal(), $this->getStart(), $limit);

		// Add the object to the internal cache.
		$this->cache[$store] = $page;

		return $this->cache[$store];
	}

	/**
	 * Method to get a store id based on the model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  An identifier string to generate the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   12.2
	 */
	protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id .= ':' . $this->getState('list.start');
		$id .= ':' . $this->getState('list.limit');

		return md5($this->context . ':' . $id);
	}

	/**
	 * Method to get the total number of items for the data set.
	 *
	 * @return  integer  The total number of items available in the data set.
	 *
	 * @since   12.2
	 */
	public function getTotal()
	{
		// Get a storage key.
		$store = $this->getStoreId('getTotal');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the total.
		
		// Add the total to the internal cache.
		$this->cache[$store] = $this->_comments_total;

		return $this->cache[$store];
	}

	/**
	 * Method to get the starting number of items for the data set.
	 *
	 * @return  integer  The starting number of items available in the data set.
	 *
	 * @since   12.2
	 */
	public function getStart()
	{
		$store = $this->getStoreId('getstart');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$start = $this->getState('list.start');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();

		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $start;

		return $this->cache[$store];
	}


	/**
	 * Increment the hit counter for the article.
	 *
	 * @param   integer  $pk  Optional primary key of the article to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 */
	public function hit($pk = 0)
	{
		$input = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('product.id');

			$table = JTable::getInstance('Product', 'CartsTable');
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}

	/**
	 * Save user vote on product
	 *
	 * @param   integer  $pk    Joomla product Id
	 * @param   integer  $rate  Voting rate
	 *
	 * @return  boolean          Return true on success
	 */
	public function storeVote($pk = 0, $rate = 0)
	{
		if ($rate >= 1 && $rate <= 5 && $pk > 0)
		{
			$userIP = $_SERVER['REMOTE_ADDR'];

			// Initialize variables.
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			// Create the base select statement.
			$query->select('*')
				->from($db->quoteName('#__carts_products_rating'))
				->where($db->quoteName('product_id') . ' = ' . (int) $pk);

			// Set the query and load the result.
			$db->setQuery($query);
			$rating = $db->loadObject();

			// Check for a database error.
			if ($db->getErrorNum())
			{
				JError::raiseWarning(500, $db->getErrorMsg());

				return false;
			}

			// There are no ratings yet, so lets insert our rating
			if (!$rating)
			{
				$query = $db->getQuery(true);

				// Create the base insert statement.
				$query->insert($db->quoteName('#__carts_products_rating'))
					->columns(array($db->quoteName('product_id'), $db->quoteName('lastip'), $db->quoteName('rating_sum'), $db->quoteName('rating_count')))
					->values((int) $pk . ', ' . $db->quote($userIP) . ',' . (int) $rate . ', 1');

				// Set the query and execute the insert.
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					JError::raiseWarning(500, $e->getMessage());

					return false;
				}
			}
			else
			{
				if ($userIP != ($rating->lastip))
				{
					$query = $db->getQuery(true);

					// Create the base update statement.
					$query->update($db->quoteName('#__carts_products_rating'))
						->set($db->quoteName('rating_count') . ' = rating_count + 1')
						->set($db->quoteName('rating_sum') . ' = rating_sum + ' . (int) $rate)
						->set($db->quoteName('lastip') . ' = ' . $db->quote($userIP))
						->where($db->quoteName('product_id') . ' = ' . (int) $pk);

					// Set the query and execute the update.
					$db->setQuery($query);

					try
					{
						$db->execute();
					}
					catch (RuntimeException $e)
					{
						JError::raiseWarning(500, $e->getMessage());

						return false;
					}
				}
				else
				{
					return false;
				}
			}

			return true;
		}

		JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('COM_CARTS_PRODUCT_INVALID_RATING', $rate), "JModelProduct::storeVote($rate)");

		return false;
	}
}
