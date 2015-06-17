<?php
/**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// no direct access
defined('_JEXEC') or die;

?>
<ul class="tags <?php echo $params->get('moduleclass_sfx');?>">
	<?php foreach ($tags as $tag): ?>
		<?php if(!empty($tag->tag)):
			$bold = rand(0,2);
		 ?>
		<li><a href="<?php echo $tag->link; ?>" title="<?php echo $tag->count.' '.JText::_('K2_ITEMS_TAGGED_WITH').' '.K2HelperUtilities::cleanHtml($tag->tag); ?>">
		<?php if($bold == 1) :?>
			<b><?php echo $tag->tag; ?></b>
		<?php else: ?>
			<?php echo $tag->tag; ?>
		<?php endif;?>
		</a></li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>

	
