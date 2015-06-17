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

<div class="container">

	<div class="content_fullwidth lessmar">

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

	</div>
</div>
<div class="clearfix margin_top7"></div>

