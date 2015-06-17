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

if($id){
	$id = 'id="'.$id.'"';
}

$classes = 'azp_tabs azp_font_edit';

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

if ($class) {
	$classes .= ' '.$class;
}
$classes = 'class="'.$classes.'"';

?>

<?php 
	$tabTab = array();
	if(count($azuraTabToggleArray)){
		foreach ($azuraTabToggleArray as $key=>$tab){
			if(!empty($tab['iconclass'])){
				$tab['iconclass'] = '<i class="'.$tab['iconclass'].'"></i>';
			}
			$tabTab[] = '<li ><a href="#'.$tab['id'].'" ><h4 class="serif">'.$tab['title'].' '.$tab['iconclass'].'</h4></a></li>';
		
		}
	}
?>

<div <?php echo $id.' '.$classes.' '.$tabtogglestyle.' '.$animationData;?>>
	<ul class="nav nav-tabs" role="tablist">
		<?php echo implode("\n", $tabTab);?>
	</ul>

	<!-- <div class="tab-content"> -->
		<?php echo do_shortcode($content); ?>
	<!-- </div> -->
</div>


