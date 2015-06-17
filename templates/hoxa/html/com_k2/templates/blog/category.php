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
<link rel="stylesheet" type="text/css" href="<?php echo JURI::root(true);?>/templates/hoxa/js/tabs/tabwidget/tabwidget.css" />

<?php if(isset($this->leading) && count($this->leading)): 
$leadingCount = count($this->leading);
?>
<!-- Leading items -->
	<?php foreach($this->leading as $item): ?>

		<?php
			// Load category_item.php by default
			$this->item=$item;
			echo $this->loadTemplate('item');
		?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if(isset($this->primary) && count($this->primary)): ?>
<!-- Primary items -->
	<?php foreach($this->primary as $item): ?>

		<?php
			// Load category_item.php by default
			$this->item=$item;
			echo $this->loadTemplate('item');
		?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if(isset($this->secondary) && count($this->secondary)): ?>
<!-- Secondary items -->
	<?php foreach($this->secondary as $item): ?>
	
	
		<?php
			// Load category_item.php by default
			$this->item=$item;
			echo $this->loadTemplate('item');
		?>

	<?php endforeach; ?>
<?php endif; ?>
	
<!-- Pagination -->
<?php if($this->pagination->getPagesLinks()): ?>
	<div class="pagination center">
    <b><?php if($this->params->get('catPaginationResults')) echo $this->pagination->getPagesCounter(); ?></b>
    <?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
    </div><!-- /# end pagination -->
	
<?php endif; ?>
