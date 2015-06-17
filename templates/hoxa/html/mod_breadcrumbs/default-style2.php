<?php
/**
 * @package Hoxa - Responsive MultiPurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 29-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

//$separator = '<i class="breadcrumbs-arrow icon-right-dir"></i>';

// Find last and penultimate items in breadcrumbs list
end($list);
$last_item_key = key($list);
prev($list);
$penult_item_key = key($list);



?>
<div class="page_title2">
	<div class="container">

	    <div class="title"><h1><?php echo strip_tags($list[$last_item_key]->name);?></h1></div>

			<div class="pagenation <?php echo $moduleclass_sfx; ?>">	    

				<?php
				if ($params->get('showHere', 1))
				{
					echo '<a href="#">' . JText::_('MOD_BREADCRUMBS_HERE') . '&#160;</a>';
				}
				else
				{
					echo '&nbsp;';
				}

				// Get rid of duplicated entries on trail including home page when using multilanguage
				for ($i = 0; $i < $count; $i++)
				{
					if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link)
					{
						unset($list[$i]);
					}
				}

				// Find last and penultimate items in breadcrumbs list
				end($list);
				$last_item_key = key($list);
				prev($list);
				$penult_item_key = key($list);

				// Make a link if not the last item in the breadcrumbs
				$show_last = $params->get('showLast', 1);

				// Generate the trail
				foreach ($list as $key => $item) :
				if ($key != $last_item_key)
				{
					// Render all but last item - along with separator
					//echo '<li>';
					if (!empty($item->link))
					{
						echo '<a href="' . $item->link . '">' . preg_replace('/--([^-]*)--/', '$1', $item->name) . '</a>';
					}
					else
					{
						echo '<a href="#">' . preg_replace('/--([^-]*)--/', '$1', $item->name) . '</a>';
					}

					if (($key != $penult_item_key) || $show_last)
					{
						echo ' <i>' . $separator . '</i> ';
					}

					//echo '</li>';
				}
				elseif ($show_last)
				{
					// Render last item if reqd.
					echo ' '.preg_replace('/--([^-]*)--/', '$1', $item->name);
				}
				endforeach; ?>
			</div>
	    
	</div>
</div><!-- end page title -->  

<div class="clearfix"></div>

