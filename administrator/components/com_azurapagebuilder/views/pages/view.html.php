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

class AzuraPagebuilderViewPages extends JViewLegacy
{
	protected $items;

	protected $state;

	protected $pagination;

	public function display($tpl = null){

		$this->state = $this->get('State');

		$this->items = $this->get('Items');

		$this->pagination = $this->get('Pagination');


		//CartsHelper::addSubmenu('products');

		if(count($errors = $this->get('Errors'))){
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();

		parent::display();
	}

	protected function addToolbar(){

		require_once JPATH_COMPONENT . '/helpers/pages.php';

		$state	= $this->get('State');
		$canDo	= PagesHelper::getActions('com_azurapagebuilder','', '');
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('Pages Manager: Pages'), '');

		if (count($user->authorise('com_azurapagebuilder', 'core.create')) > 0)
		{
			JToolbarHelper::addNew('page.add');
		}
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('page.edit');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::publish('pages.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('pages.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			//JToolbarHelper::archiveList('pages.archive');
			JToolbarHelper::checkin('pages.checkin');
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'pages.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('pages.trash');
		}

		if ($user->authorise('core.admin', 'com_azurapagebuilder'))
		{
			JToolbarHelper::preferences('com_azurapagebuilder');
		}

		JToolbarHelper::help('LINK');

		JHtmlSidebar::setAction('index.php?option=com_azurapagebuilder&view=pages');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);


		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);

		JHtmlSidebar::addFilter(
		JText::_('JOPTION_SELECT_TAG'),
		'filter_tag',
		JHtml::_('select.options', JHtml::_('tag.options', true, true), 'value', 'text', $this->state->get('filter.tag'))
		);

	}


	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.hits' => JText::_('JGLOBAL_HITS'),
			//'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}