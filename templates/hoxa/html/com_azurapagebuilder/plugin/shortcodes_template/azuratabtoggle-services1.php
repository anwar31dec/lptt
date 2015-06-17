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

$classes = 'azp_tabs';
if ($class) {
	$classes .= ' '.$class;
}
$classes = 'class="'.$classes.'"';

?>

<?php 
	$tabTab = array();
	if(count($azuraTabToggleArray)){
		foreach ($azuraTabToggleArray as $key=>$tab){
			$aniData = '';
			$cls = '';
			if($tab['animationargs']['animation'] == '1'){
				$cls .= $tab['animationargs']['trigger'];
				if(!empty($tab['animationargs']['animationtype'])){
					$aniData .= 'data-anim-type="'.$tab['animationargs']['animationtype'].'"';
				}
				if(!empty($tab['animationargs']['animationdelay'])){
					$aniData .= ' data-anim-delay="'.$tab['animationargs']['animationdelay'].'"';
				}
			}

			if(!empty($cls)){
				$cls = 'class="'.$cls.'"';
			}

			if(!empty($tab['iconclass'])){
				$tab['iconclass'] = '<i class="'.$tab['iconclass'].'"></i>';
			}

			$tabTab[] = '<li '.$cls.' '.$aniData.'><a href="#'.$tab['id'].'" target="_self">'.$tab['iconclass'].' '.$tab['title'].'</a></li>';
		
		}
	}
?>
<ul class="tabs2" >
	<?php echo implode("\n", $tabTab);?>
</ul>

<div class="tabs-content2 fullw">
	<?php echo do_shortcode($content); ?>
</div>

