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
$trigger_overlay_number = 0;

$class = 'azp_k2category row azp_font_edit';

$animationData = '';
if($animationArgs['animation'] == '1'){
	if($animationArgs['trigger'] == 'animate-in'){
		$classes .= ' '.$animationArgs['trigger'];
		$animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
	}else{
		$classes .= ' '.$animationArgs['trigger'].'-'.$animationArgs['hoveranimationtype'];
		if($animationArgs['infinite'] != '0'){
			$classes .= ' infinite';
		}
	}
	
	
}

if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';

?>
<?php if(count($items)) : ?>
<!-- portfolio filters -->

    <div <?php echo $classes.' '.$k2categorystyle.' '.$animationData;?>>
		
		<?php foreach ($items as $key => $item) : 

			//$extraFields = json_decode($item->extra_fields);

		?>

		<div class="col-md-4 col-sm-6 col-xs-12 text-center k2categoryitemWrapper">
            <div class="k2categoryItem">
				<h3><?php echo $item->title;?></h3>
                <?php if(!empty($item->fulltext)):?>
                	<?php echo $item->fulltext;?>
                <?php else:?>
                	<?php echo $item->introtext;?>
                <?php endif;?>
            </div>
        </div>
			
		<?php endforeach;?>

    </div>


<?php endif;?>