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

// Define default image size (do not change)
//K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);

$extraFields = json_decode($this->item->extra_fields);
$postType = $extraFields[0]->value;
$postLink = $extraFields[1]->value;

// $filter = getItemTagsFilter($this->item);
?>

<div class="blog_post">	
    <div class="blog_postcontent">
    	<!-- Post type -->
    	<?php if($postType == '1') : ?>
    		<div class="image_frame"><a href="<?php echo $this->item->link; ?>"><img src="<?php echo $this->item->imageXLarge;//JURI::root(true).$postLink;?>" alt="<?php echo $this->item->title;?>" /></a></div>
		<?php elseif($postType == '4') : ?>
			<div class="image_frame">
				<ul class="slides">
		<?php
	    	foreach ($extraFields as $key => $field) :
	    	if($key > 0 && trim($field->value) !='') :
	    ?>
				<li>
					<img alt="<?php echo $this->item->title. '-image-'.($key+1);?>" src="<?php echo JURI::root(true).$field->value;?>" >
				</li>
		<?php endif; endforeach; ?>
				</ul>
			</div>
		<?php elseif($postType == '2') : 
			$id = array();
			// get youtube video id from link
			preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $postLink, $id);
	        //support embed link pasted at link
	        if(empty($id) || !is_array($id)){
	            preg_match('/embed[\/]([^\\?\\&]+)[\\?]/', $postLink, $id);
	        }
	    	if(!empty($id[1])) :
		?>
			<div class="video_frame">
				<!-- youtube -->
				<iframe src="http://www.youtube.com/embed/<?php echo $id[1];?>?" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<!-- End youtube -->
			</div>
			<?php endif;?>
		<?php elseif($postType == '3') :

		$id = array();
		// get vimeo video id from link
		preg_match('/http:\/\/vimeo.com\/(\d+)$/', $postLink, $id);

		if(!empty($id[1])) :

		?>
			<div class="video_frame">
				<!-- Vimeo -->
				<iframe  src="http://player.vimeo.com/video/<?php echo $id[1];?>?title=0&amp;byline=0&amp;portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<!-- End Vimeo -->
			</div>
		<?php endif;?>
		
		<?php elseif($postType == '5') : 
			$url = str_replace(":", "%3A", $postLink);
		?>
			<div class="video_frame">
				<iframe src="https://w.soundcloud.com/player/?url=<?php echo $url;?>&amp;auto_play=false&amp;hide_related=false&amp;visual=true" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			</div>
		<?php endif;?>

        <!-- Post title -->
		<h3><a href="<?php echo $this->item->link; ?>"><?php echo $this->item->title;?></a></h3>

        <ul class="post_meta_links">
        <!-- post author -->
        	<li><a href="#" class="date"><?php echo JHTML::_('date', $this->item->created, 'd F Y'); ?></a></li>
            <?php if($this->item->params->get('catItemAuthor')): ?>
            	<li class="post_by"><i><?php echo JText::_('TPL_HOXA_POSTED_BY_TEXT');?></i> 
				<?php if(isset($this->item->author->link) && $this->item->author->link): ?>
				<a href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
				<?php else: ?>
				<?php echo $this->item->author->name; ?>
				<?php endif; ?>
				</li>
			<?php endif; ?>
			
			<!-- post category -->
			<?php if($this->item->params->get('catItemCategory')): ?>
				<li class="post_categoty"><i><?php echo JText::_('TPL_HOXA_POSTED_IN_TEXT');?></i> <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name;?></a></li>
			<?php endif; ?>
            
            <!-- post comment -->
            <?php if($this->item->params->get('catItemCommentsAnchor') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1')) ): ?>
			
				<li class="post_comments"><i><?php echo JText::_('TPL_HOXA_POSTED_NOTE_TEXT');?></i>

				<?php if(!empty($this->item->event->K2CommentsCounter)): ?>
					<!-- K2 Plugins: K2CommentsCounter -->
					<?php echo $this->item->event->K2CommentsCounter; ?>

				<?php else: ?>
					<?php if($this->item->numOfComments > 0): ?>
					<a href="<?php echo $this->item->link; ?>#itemCommentsAnchor">
						<?php echo $this->item->numOfComments; ?> <?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?>
						</a>
					<?php else: ?>
					<a href="<?php echo $this->item->link; ?>#itemCommentsAnchor">
						<?php echo JText::_('K2_BE_THE_FIRST_TO_COMMENT'); ?>
					</a>
					<?php endif; ?>
				<?php endif; ?>
				</li>
			<?php endif; ?>

            
        </ul>
        <div class="clearfix"></div>
        <div class="margin_top1"></div>

        <?php if($this->item->params->get('catItemIntroText')): ?>
			<!-- Item introtext -->
		  	<?php echo $this->item->introtext; ?>
		
		<?php endif; ?>

		<?php if ($this->item->params->get('catItemReadMore')): ?>
		<!-- Item "read more..." link -->

			<a href="<?php echo $this->item->link; ?>"><?php echo JText::_('TPL_HOXA_READ_MORE_TEXT'); ?></a>

		<?php endif; ?>

    </div>
</div><!-- /# end post -->
    
<div class="clearfix divider_dashed9"></div>
