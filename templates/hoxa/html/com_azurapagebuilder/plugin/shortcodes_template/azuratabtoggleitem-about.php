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

?>

<div class="tab-pane fade<?php if($azuraTabItem == 0) echo ' in active';?>" id="<?php echo $id;?>" <?php echo $tabtoggleitemstyle;?>>
	<?php echo do_shortcode($content);?>
</div>