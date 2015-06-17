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

$classes = 'azp_testimonial azp_font_edit';

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
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


if(empty($review)){
	$reviewstars = '';
}elseif($review == '5'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>';
}elseif($review == '4.5'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star-half-o"></i>';
}elseif($review == '4'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star-o"></i>';
}elseif($review == '3.5'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star-half-o">
					</i><i class="fa fa-star-o"></i>';
}elseif($review == '3'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>';
}elseif($review == '2.5'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star-half-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>';
}elseif($review == '2'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>';
}elseif($review == '1.5'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star-half-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>';
}elseif($review == '1'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>';
}

?>

<div <?php echo $classes.' '.$testimonialstyle.' '.$animationData;?>>
<?php if(!empty($reviewstars)) :?>
	<p class="azp_testimonial_rate">
	    <?php echo $reviewstars;?>
	</p>
<?php endif;?>
	<?php if(!empty($avatar)):?>
		<img class="azp_testimonial_avatar" src="<?php echo JURI::root(true).'/'.$avatar;?>" width="60px" height="60px" alt="<?php echo $name;?>" />
	<?php elseif(!empty($email)): ?>
		<img class="azp_testimonial_avatar" src="http://www.gravatar.com/avatar/<?php echo md5($email);?>?s=60&amp;d=<?php echo urlencode( JURI::root().'templates/'.JFactory::getApplication()->getTemplate().'/images/placeholder/user.png');?>" width="60px" height="60px" />
	<?php endif;?>

	<h3 class="azp_testimonial_comment">
	    <?php echo nl2br(do_shortcode($content)); ?>
	</h3>
	<br>
	<h4 class="azp_testimonial_position"><span><?php echo JText::_('COM_AZURAPAGEBUILDER_TESTIMONIAL_BY_TEXT');?> </span><span><?php echo $name;?></span><smal>, <?php echo $position;?></smal></h4>
</div>
