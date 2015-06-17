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

class AzuraPagebuilderModelPages extends JModelList {

	public function __construct($config = array()){
		if(empty($config['filter_fields'])){
			$config['filter_fields'] = array(
				'id','p.id',
				'title','p.title',
				'alias','p.alias',
				'hits','p.hits',
				'created_by','p.created_by',
				'state','p.state',
				'created','p.created',
				'access','p.access'

				);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null){

		$search = $this->getUserStateFromRequest($this->context .'.'.'filter.search','filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context.'.'.'filter.access','filter_access',null, 'int');
		$this->setState('filter.access', $access);

		$published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);

		$params = JComponentHelper::getParams('com_azurapagebuilder');
		$this->setState('params',$params);

		parent::populateState('p.title','asc');
	}

	protected function getStoreId($id = ''){

		$id .= ':'.$this->getState('filter.search');
		$id .= ':'.$this->getState('filter.access');
		$id .= ':'.$this->getState('filter.state');

		return parent::getStoreId($id);
	}

	protected function getListQuery(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'p.id, p.title, p.alias, p.checked_out, p.checked_out_time,' .
					'p.hits,' .
					'p.state, p.access, p.ordering,' .
					'p.language, p.publish_up, p.publish_down'
			)
		);
		$query->from($db->quoteName('#__azurapagebuilder_pages') . ' AS p');

		// Join over the language
		$query->select('l.title AS language_title')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = p.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=p.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = p.access');


		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('p.access = ' . (int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('p.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('p.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(p.state IN (0, 1))');
		}


		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('p.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(p.title LIKE ' . $search . ' OR p.alias LIKE ' . $search . ')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('p.language = ' . $db->quote($language));
		}

		$tagId = $this->getState('filter.tag');
		// Filter by a single tag.
		if (is_numeric($tagId))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId)
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('p.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_azurapagebuilder.page')
				);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));


		return $query;
	}
}