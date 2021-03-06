<?php
/**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to pagination rendering.
 *
 * pagination_list_footer
 * 	Input variable $list is an array with offsets:
 * 		$list[limit]		: int
 * 		$list[limitstart]	: int
 * 		$list[total]		: int
 * 		$list[limitfield]	: string
 * 		$list[pagescounter]	: string
 * 		$list[pageslinks]	: string
 *
 * pagination_list_render
 * 	Input variable $list is an array with offsets:
 * 		$list[all]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[start]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[previous]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[next]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[end]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[pages]
 * 			[{PAGE}][data]		: string
 * 			[{PAGE}][active]	: boolean
 *
 * pagination_item_active
 * 	Input variable $item is an object with fields:
 * 		$item->base	: integer
 * 		$item->link	: string
 * 		$item->text	: string
 *
 * pagination_item_inactive
 * 	Input variable $item is an object with fields:
 * 		$item->base	: integer
 * 		$item->link	: string
 * 		$item->text	: string
 *
 * This gives template designers ultimate control over how pagination is rendered.
 *
 * NOTE: If you override pagination_item_active OR pagination_item_inactive you MUST override them both
 */

/**
 * Renders the pagination footer
 *
 * @param   array  $list  Array containing pagination footer
 *
 * @return  string  HTML markup for the full pagination footer
 *
 * @since   3.0
 */
function pagination_list_footer($list)
{
	$html = "<div class=\"pagination\">\n";
	$html .= $list['pageslinks'];
	$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart\" value=\"" . $list['limitstart'] . "\" />";
	$html .= "\n</div>";

	return $html;
}

/**
 * Renders the pagination list
 *
 * @param   array  $list  Array containing pagination information
 *
 * @return  string  HTML markup for the full pagination object
 *
 * @since   3.0
 */
function pagination_list_render($list)
{
	// Calculate to display range of pages
	$currentPage = 1;
	$range = 1;
	$step = 5;
	foreach ($list['pages'] as $k => $page)
	{
		if (!$page['active'])
		{
			$currentPage = $k;
		}
	}
	if ($currentPage >= $step)
	{
		if ($currentPage % $step == 0)
		{
			$range = ceil($currentPage / $step) + 1;
		}
		else
		{
			$range = ceil($currentPage / $step);
		}
	}

	//$html = '<div class="pagination">';
	//$html .= $list['start']['data'];
	$html = $list['previous']['data'];

	foreach ($list['pages'] as $k => $page)
	{
		if (in_array($k, range($range * $step - ($step + 1), $range * $step)))
		{
			if (($k % $step == 0 || $k == $range * $step - ($step + 1)) && $k != $currentPage && $k != $range * $step - $step)
			{
				$page['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $page['data']);
			}
		}

		$html .= $page['data'];
	}

	$html .= $list['next']['data'];
	//$html .= $list['end']['data'];

	//$html .= '</div>';
	return $html;
}

/**
 * Renders an active item in the pagination block
 *
 * @param   JPaginationObject  $item  The current pagination object
 *
 * @return  string  HTML markup for active item
 *
 * @since   3.0
 */
function pagination_item_active(&$item)
{
	// Check for "Start" item
	if ($item->text == JText::_('JLIB_HTML_START'))
	{
		$display = "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_START_TEXT')."</a>";
	}

	// Check for "Prev" item
	if ($item->text == JText::_('JPREV'))
	{
		$display = "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_PREV_TEXT')."</a>";
	}

	// Check for "Next" item
	if ($item->text == JText::_('JNEXT'))
	{
		$display = "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_NEXT_TEXT')."</a>";
	}

	// Check for "End" item
	if ($item->text == JText::_('JLIB_HTML_END'))
	{
		$display = "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_END_TEXT')."</a>";
	}

	// If the display object isn't set already, just render the item with its text
	if (!isset($display))
	{
		$display = "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".$item->text."</i></a>";
	}

	return $display;
}
/**
 * Renders an inactive item in the pagination block
 *
 * @param   JPaginationObject  $item  The current pagination object
 *
 * @return  string  HTML markup for inactive item
 *
 * @since   3.0
 */
function pagination_item_inactive(&$item)
{
	// Check for "Start" item
	if ($item->text == JText::_('JLIB_HTML_START'))
	{
		return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_START_TEXT')."</a>";
	}

	// Check for "Prev" item
	if ($item->text == JText::_('JPREV'))
	{
		return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_PREV_TEXT')."</a>";
	}

	// Check for "Next" item
	if ($item->text == JText::_('JNEXT'))
	{
		return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_NEXT_TEXT')."</a>";
	}

	// Check for "End" item
	if ($item->text == JText::_('JLIB_HTML_END'))
	{
		return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".JText::_('TPL_HOXA_END_TEXT')."</a>";
	}

	// Check if the item is the active page
	if (isset($item->active) && ($item->active))
	{
		return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks current\" >".$item->text."</i></a>";
	}

	// Doesn't match any other condition, render a normal item
	return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"navlinks\" >".$item->text."</i></a>";
}
