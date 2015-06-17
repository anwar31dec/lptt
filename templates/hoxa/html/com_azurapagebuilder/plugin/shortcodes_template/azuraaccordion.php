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

$classes = 'azp_panel-group panel-group';
if(!empty($class)){
	//$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

$rel = '1';
if((int)$defaultactive){
	$rel = (int)$defaultactive;
}

?>
<div class="row features text-center" <?php echo $accordionstyle;?>>
	<?php if($acctype == 'accordion'): ?>
	<div <?php echo $classes;?> id="<?php echo $accordionGroupID;?>" rel="<?php echo $rel;?>">
	<?php endif;?>

		<?php echo do_shortcode($content);?>

	<?php if($acctype == 'accordion'): ?>
	</div>
	<?php endif;?>
</div>
<script>
	jQuery(document).ready(function($) {
		<?php if($acctype == 'accordion'): ?>
			$('.azp_panel-group').each(function(){
				var acc=$(this).attr("rel")*1;$(this).find('.azp_panel:nth-child('+ acc+') > .azp_panel-collapse').show();
				$(this).find('.azp_panel .azp_accordion-toggle').each(function(){
					if($(this).hasClass('active')){
						$(this).removeClass('active');
					}
				});
				$(this).find('.azp_panel:nth-child('+ acc+') .azp_accordion-toggle').addClass("active");
			});
			$('.azp_panel-group .azp_accordion-toggle').click( 
				function(){
					var azp_panel_collapse = $(this).closest('.azp_panel').children('.azp_panel-collapse');
					if(azp_panel_collapse.is(':hidden')){
						$(this).closest('.azp_panel-group').find('.azp_panel .azp_accordion-toggle').each(function(){
							if($(this).hasClass('active')){
								$(this).removeClass('active');
								$(this).closest('.azp_panel').children('.azp_panel-collapse').slideUp(200);
							}
						});
						$(this).toggleClass('active').closest('.azp_panel').children('.azp_panel-collapse').slideDown(200);
					}
					return false;
				}
			);
        <?php else :?>

        if($(".azp_panel .azp_accordion-toggle").hasClass('active')){
			$(".azp_panel .azp_accordion-toggle.active").closest('.azp_panel').find('.azp_panel-collapse').show();
		}
			
		$(".azp_panel .azp_accordion-toggle").click(function(){
			if($(this).hasClass('active')){
				$(this).removeClass("active").closest('.azp_panel').find('.azp_panel-collapse').slideUp(200);
			}
			else{
				$(this).addClass("active").closest('.azp_panel').find('.azp_panel-collapse').slideDown(200);
			}
			return false;
		});

		<?php endif;?>
	});
</script>