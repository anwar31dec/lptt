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

$classes = 'peosec';

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

if($review == '5'){
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
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>';
}elseif($review == '2'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>';
}elseif($review == '1.5'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star-half-o"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>
					<i class="fa fa-star"></i>';
}elseif($review == '1'){
	$reviewstars = '<i class="fa fa-star"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>
					<i class="fa fa-star-o"></i>';
}

?>
<div <?php echo $id.' '.$classes.' '.$animationData.' '.$testimonialstyle;?>>
<?php if(!empty($avatar)):?>
	<img src="<?php echo JURI::root(true).'/'.$avatar;?>" alt="<?php echo $name;?>" />
	<?php elseif(!empty($email)): ?>
	<img src="http://www.gravatar.com/avatar/<?php echo md5($email);?>?s=240&amp;d=<?php echo urlencode( JURI::root().'templates/'.JFactory::getApplication()->getTemplate().'/images/placeholder/user.png');?>" />
	<?php endif;?> <strong>- <?php echo $name;?> <i> &nbsp;&nbsp; <?php echo $position;?></i></strong></div>
<p><?php echo nl2br(do_shortcode($content)); ?></p>
