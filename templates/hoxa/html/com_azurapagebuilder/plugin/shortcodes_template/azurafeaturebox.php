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

$classes =' azp_font_edit icon';

$animationData = '';
if($animationArgs['animation'] == '1'){
	$classes .= ' '.$animationArgs['trigger'];
	if(!empty($animationArgs['animationtype'])){
		$animationData .= 'data-anim-type="'.$animationArgs['animationtype'].'"';
	}
	if(!empty($animationArgs['animationdelay'])){
		$animationData .= ' data-anim-delay="'.$animationArgs['animationdelay'].'"';
	}
	
}


if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


?>

<div <?php echo $id.' '.$classes.' '.$featureboxstyle.' '.$animationData;?>><img src="<?php echo JURI::root(true).'/'.$image;?>" alt="<?php echo $title;?>" /></div>
    
<h4><?php echo $title;?></h4>

<p><?php echo nl2br(do_shortcode($content));?></p>
<br />
<a href="<?php echo $link;?>"><?php echo JText::_('TPL_HOXA_FEATURE_BOX_READ_MORE_TEXT');?></a>
