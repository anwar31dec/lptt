<?php
/**
 * @package Hoxa - Responsive MultiPurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 30-09-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// no direct access
defined('_JEXEC') or die;

?>



<?php if(!empty($this->item->fulltext)): ?>
	<?php if($this->item->params->get('itemIntroText')): ?>
	<!-- Item introtext -->
	
		<?php echo $this->item->introtext; ?>
	
	<?php endif; ?>
	<?php if($this->item->params->get('itemFullText')): ?>
	<!-- Item fulltext -->

		<?php echo $this->item->fulltext; ?>

	<?php endif; ?>
<?php else: ?>
	<!-- Item text -->

		<?php echo $this->item->introtext; ?>

<?php endif; ?>


<div class="clearfix margin_top7"></div>

