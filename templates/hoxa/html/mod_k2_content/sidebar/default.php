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
            


	<?php if(count($items)): ?>
  <ul <?php echo ($params->get('moduleclass_sfx')? 'class="'.$params->get('moduleclass_sfx').'"': '');?>>
    <?php foreach ($items as $item): //echo'<pre>';var_dump($item);die;	?>
      <li>
      <?php if($params->get('itemImage')) : ?>
        <span><a href="<?php echo $item->link; ?>"><img src="<?php echo $item->image; ?>" width="50px" height="50px"  alt="<?php echo K2HelperUtilities::cleanHtml($item->title); ?>" /></a></span>
      <?php endif;?>
        <a href="<?php echo $item->link; ?>"><?php echo $item->title;?></a>
        <i><?php echo JHtml::_('date',$item->created,'F d, Y');?></i> 
      </li>

    <?php endforeach; ?>
  </ul>
  <?php endif;?>