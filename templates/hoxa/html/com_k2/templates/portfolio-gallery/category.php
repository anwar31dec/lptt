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
<?php if((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>

<div class="container">
	<br />

	<div id="grid-container" class="cbp-l-grid-fullScreen">

        
        <ul>

		<?php if(isset($this->leading) && count($this->leading)): ?>
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

		</ul>
	</div>
	<div class="cbp-l-loadMore-text">
    	<div data-href="#" class="cbp-l-loadMore-text-link"></div>
  	</div>
	<!-- Pagination -->
	<?php if($this->pagination->getPagesLinks()): ?>
	<div class="pagination center">
		<?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>

</div>

<?php endif; ?>
