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
<div class="page_title <?php echo $moduleclass_sfx; ?>">
	<div class="container">
	    <h1><?php echo preg_replace('/--([^-]*)--/', '<strong>$1</strong>', $list[$last_item_key]->name);?></h1>   
	</div>
</div><!-- end page title --> 

<div class="clearfix"></div>

