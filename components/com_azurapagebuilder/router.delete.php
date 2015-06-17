<?php

defined('_JEXEC') or die;

function AzuraPagebuilderBuildRoute(&$query)
{
	$segments = array();

	// get a menu item based on Itemid or currently active
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$params = JComponentHelper::getParams('com_azurapagebuilder');
	$advanced = $params->get('sef_advanced_link', 1);

	// we need a menu item.  Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
	}
	else
	{
		$menuItem = $menu->getItem($query['Itemid']);
	}

	$mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

	if (isset($query['view']))
	{
		$view = $query['view'];

		if (empty($query['Itemid']) || empty($menuItem) || $menuItem->component != 'com_azurapagebuilder')
		{
			$segments[] = $query['view'];
		}

		// We need to keep the view for forms since they never have their own menu item
		if ($view != 'form')
		{
			unset($query['view']);
		}
	}

	// are we dealing with an page that is attached to a menu item?
	if (isset($query['view']) && ($mView == $query['view']) and (isset($query['id'])) and ($mId == (int) $query['id']))
	{
		unset($query['view']);
		unset($query['catid']);
		unset($query['id']);

		return $segments;
	}

	if (isset($view) and ($view == 'page' or $view == 'edit'))
	{
		if ($mId != (int) $query['id'] || $mView != $view)
		{
			

			if ($view == 'page'|| $view == 'edit')
			{
				if ($advanced)
				{
					list($tmp, $id) = explode(':', $query['id'], 2);
				}
				else
				{
					$id = $query['id'];
				}

				$segments[] = $id;
			}
		}

		unset($query['id']);
		//unset($query['catid']);
	}

	if (isset($query['layout']))
	{
		if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
		{
			if ($query['layout'] == $menuItem->query['layout'])
			{
				unset($query['layout']);
			}
		}
		else
		{
			if ($query['layout'] == 'default')
			{
				unset($query['layout']);
			}
		}
	}

	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * @return  array  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 */
function AzuraPagebuilderParseRoute($segments)
{
	$vars = array();

	//Get the active menu item.
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$item = $menu->getActive();
	$params = JComponentHelper::getParams('com_azurapagebuilder');
	$advanced = $params->get('sef_advanced_link', 1);

	// Count route segments
	$count = count($segments);

	// Standard routing for page.
	if (!isset($item))
	{
		$vars['view'] = $segments[0];
		$vars['id'] = $segments[$count - 1];
		return $vars;
	}

	// From the categories view, we can only jump to a category.
	$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';

	$found = 0;

	foreach ($segments as $segment)
	{

		if ($found == 0)
		{
			if ($advanced)
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select($db->quoteName('id'))
					->from('#__azurapagebuilder_pages')
					->where($db->quoteName('alias') . ' = ' . $db->quote($db->quote(str_replace(':', '-', $segment))));
				$db->setQuery($query);
				$id = $db->loadResult();
			}
			else
			{
				$id = $segment;
			}

			$vars['id'] = $id;
			$vars['view'] = 'page';

			break;
		}

		$found = 0;
	}

	return $vars;
}
