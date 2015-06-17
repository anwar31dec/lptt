<?php
/**
 * @package Hoxa - Responsive MultiPurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 29-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$icon = false;
if($item->anchor_css){
	if(stripos($item->anchor_css, 'icon') !== false||stripos($item->anchor_css, 'iconsocial') !== false){
		$icon = true;
		$iconClass = str_replace('icon', "iconedited", $item->anchor_css);
		if(stripos($item->anchor_css, 'iconsocial') !== false){
			$item->title = '';
		}
		$item->anchor_css = '';
	}
}

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';

if ($item->menu_image)
	{
		$item->params->get('menu_text', 1) ?
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' .preg_replace('/--([^-]*)--/', '$1', $item->title) . '</span> ' :
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	if($icon === true){
		$linktype = '<i class="'.$iconClass.'"></i> '.preg_replace('/--([^-]*)--/', '$1', $item->title);
	}else{
		$linktype = preg_replace('/--([^-]*)--/', '$1', $item->title);
	}
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class; ?> href="<?php echo $flink; ?>" <?php echo $title; ?>> <?php echo $linktype; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?> href="<?php echo $flink; ?>" target="_blank" <?php echo $title; ?>> <?php echo $linktype; ?></a><?php
		break;
	case 2:
		// window.open
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$params->get('window_open');
			?><a <?php echo $class; ?> href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;" <?php echo $title; ?>> <?php echo $linktype; ?></a><?php
		break;
endswitch;
