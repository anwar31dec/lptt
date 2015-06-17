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
  <div <?php echo ($params->get('moduleclass_sfx')? 'class="'.$params->get('moduleclass_sfx').'"': '');?>>     
    <div>
      <section class="slider nosidearrows">
        <div class="flexslider carousel">
        
          <ul class="slides">

          <?php foreach ($items as $item): //echo'<pre>';var_dump($item);die;	
            $extraFields = json_decode($item->extra_fields);
          ?>
            <li>
                        
              <div class="grid-container cbp-l-grid-fullScreen sidebar">
              <ul>
                <li class="cbp-item identity logo"> 
                <?php if($extraFields[2]->value == '1') :?>
                  <a href="<?php echo JURI::root(true).$extraFields[3]->value;?>" class="cbp-caption cbp-lightbox" data-title="<?php echo $item->title;?><br>by <?php echo $item->created_by_alias;?>">
                <?php elseif($extraFields[2]->value == '2'||$extraFields[2]->value == '3'):?>
                  <a href="<?php echo $extraFields[3]->value;?>" class="cbp-caption cbp-lightbox" data-title="<?php echo $item->title;?><br>by <?php echo $item->created_by_alias;?>">
                <?php endif;?>
                  <div class="cbp-caption-defaultWrap"> <img src="<?php echo JURI::root(true).$extraFields[0]->value;?>" alt="<?php echo $item->title;?>" /> </div>
                  <div class="cbp-caption-activeWrap">
                    <div class="cbp-l-caption-alignLeft">
                      <div class="cbp-l-caption-body">
                        <div class="margin_top7"></div>
                        <div class="cbp-l-caption-title"><?php echo $item->title;?></div>
                        <div class="cbp-l-caption-desc"><?php echo $extraFields[1]->value;?></div>
                        </div>
                    </div>
                  </div>
                  </a>
                  </li>
              </ul>
              </div>
              
          </li>

          <?php endforeach; ?>
          </ul>
        </div>
      </section>
    </div>
  </div>
  <?php endif;?>