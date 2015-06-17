<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();

$classes = "";

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

if($lightboxtype == 'photo'){
	$lightboxlink = JURI::root(true).'/'.$lightboxlink;
}

?>

<li class="cbp-item identity logo" <?php echo $id.' '.$workstyle.' '.$animationData;?>>
    <a href="<?php echo $lightboxlink;?>" class="cbp-caption cbp-lightbox" data-title="<?php echo $title;?><br><?php echo $content;?>">
        <div class="cbp-caption-defaultWrap">
            <img src="<?php echo JURI::root(true).'/'.$thumb;?>" alt="<?php echo $title;?>" />
        </div>
        <div class="cbp-caption-activeWrap">
            <div class="cbp-l-caption-alignLeft">
                <div class="cbp-l-caption-body">
                    <div class="cbp-l-caption-title"><?php echo $title;?></div>
                    <div class="cbp-l-caption-desc"><?php echo do_shortcode($content);?></div>
                </div>
            </div>
        </div>
    </a>
</li>