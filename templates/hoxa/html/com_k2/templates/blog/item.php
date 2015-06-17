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
//echo'<pre>';var_dump($this->item);die;
$extraFields = null;
if(!empty($this->item->extra_fields)){
  $extraFields = json_decode($this->item->extra_fields);
  
}
$postType = $extraFields[0]->value;
$postLink = $extraFields[1]->value;

// $postType = $extraFields[0]->value;
// $postLink = substr(JUtility::parseAttributes($extraFields[1]->value)['src'], strlen(JURI::base(true)));
?>

<link rel="stylesheet" type="text/css" href="<?php echo JURI::root(true);?>/templates/hoxa/js/tabs/tabwidget/tabwidget.css" />

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
        <?php if($this->item->params->get('itemAuthor')): ?>
          <li class="post_by"><i><?php echo JText::_('TPL_HOXA_POSTED_BY_TEXT');?></i> 
          <?php if(isset($this->item->author->link) && $this->item->author->link): ?>
            <a href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
          <?php else: ?>
            <?php echo $this->item->author->name; ?>
          <?php endif; ?>
          </li>
        <?php endif; ?>
      
      <!-- post category -->
      <?php if($this->item->params->get('itemCategory')): ?>
        <li class="post_categoty"><i><?php echo JText::_('TPL_HOXA_POSTED_IN_TEXT');?></i> <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name;?></a></li>
      <?php endif; ?>
            
            <!-- post comment -->
            <?php if($this->item->params->get('itemCommentsAnchor') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1')) ): ?>
      
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

      <?php if(!empty($this->item->fulltext)): ?>
        <?php if($this->item->params->get('itemIntroText')): ?>
        <!-- Item introtext -->
          <?php echo $this->item->introtext; ?>
        <?php endif; ?>
        <?php if($this->item->params->get('itemFullText')): ?>
        <!-- Item fulltext -->
          <?php echo $this->item->fulltext; ?>
        <?php endif; ?>
      <?php else: ?>
          <?php if($this->item->params->get('itemIntroText')): ?>
          <!-- Item introtext -->
            <?php echo $this->item->introtext; ?>
          <?php endif; ?>
      <?php endif; ?>


    </div>
</div><!-- /# end post -->
    
<div class="clearfix divider_dashed9"></div>


<!-- social share buttons -->
<?php if($this->item->params->get('itemTwitterButton',1) || $this->item->params->get('itemFacebookButton',1) || $this->item->params->get('itemGooglePlusOneButton',1)): ?>
<!-- Socials section -->
<div class="sharepost">
  <h4><?php echo JText::_('TPL_HOXA_SHARE_THIS_POST_TEXT');?></h4>
  <ul>
    <!-- facebook share button -->
    <?php if($this->item->params->get('itemFacebookButton',1)): ?>
    <!-- Facebook Button -->
    <li class="itemFacebookButton">
        <div id="fb-root"></div>
        <script type="text/javascript">
            (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div class="fb-like" data-send="false" data-width="200" data-show-faces="true"></div>
    </li>
    <?php endif; ?>

    <!-- twitter share button -->
    <?php if($this->item->params->get('itemTwitterButton',1)): ?>
    <!-- Twitter Button -->
    <li class="itemTwitterButton">
        <a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal"<?php if($this->item->params->get('twitterUsername')): ?> data-via="<?php echo $this->item->params->get('twitterUsername'); ?>"<?php endif; ?>>
            <?php echo JText::_('K2_TWEET'); ?>
        </a>
        <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
    </li>
    <?php endif; ?>
    <!-- google plus share button -->

    <?php if($this->item->params->get('itemGooglePlusOneButton',1)): ?>
    <!-- Google +1 Button -->
    <li class="itemGooglePlusOneButton">
        <g:plusone annotation="inline" width="120"></g:plusone>
        <script type="text/javascript">
          (function() {
            window.___gcfg = {lang: 'en'}; // Define button default language here
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/plusone.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
          })();
        </script>
    </li>
    <?php endif; ?>

  </ul>

</div><!-- end share post links -->
<div class="clearfix"></div>
<?php endif;?> 

<!-- author block -->
<?php if($this->item->params->get('itemAuthorBlock') && empty($this->item->created_by_alias)): ?>
  <h4><?php echo JText::_('TPL_HOXA_ABOUT_THE_AUTHOR_TEXT');?></h4>
  <div class="about_author">

      <?php if($this->item->params->get('itemAuthorImage') && !empty($this->item->author->avatar)): ?>
        <img src="<?php echo $this->item->author->avatar; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($this->item->author->name); ?>" />
      <?php endif; ?>
      
      <a target="_blank" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a> <br\>
      
      <?php if($this->item->params->get('itemAuthorDescription') && !empty($this->item->author->profile->description)): ?>
        <p><?php echo $this->item->author->profile->description; ?></p>
      <?php endif; ?>
      
      <!--
      <?php if($this->item->params->get('itemAuthorURL') && !empty($this->item->author->profile->url)): ?>
      <span class="itemAuthorUrl"><?php echo JText::_('K2_WEBSITE'); ?> <a rel="me" href="<?php echo $this->item->author->profile->url; ?>" target="_blank"><?php echo str_replace('http://','',$this->item->author->profile->url); ?></a></span>
      <?php endif; ?>

      <?php if($this->item->params->get('itemAuthorEmail')): ?>
      <span class="itemAuthorEmail"><?php echo JText::_('K2_EMAIL'); ?> <?php echo JHTML::_('Email.cloak', $this->item->author->email); ?></span>
      <?php endif; ?>
      -->

      <!-- K2 Plugins: K2UserDisplay -->
      <?php echo $this->item->event->K2UserDisplay; ?>

  </div><!-- end about author -->
  <div class="clearfix margin_top5"></div>

<?php endif; ?>

<?php if(($this->item->params->get('itemAuthorLatest') && empty($this->item->created_by_alias) && isset($this->authorLatestItems)) || ($this->item->params->get('itemRelated') && isset($this->relatedItems))) : ?>
<!-- Latest items from author -->
<div class="one_half"> 
<?php if($this->item->params->get('itemAuthorLatest') && empty($this->item->created_by_alias) && isset($this->authorLatestItems)): ?>
  
    <div class="popular-posts-area">
        <h4><?php echo JText::_('K2_LATEST_FROM'); ?> <?php echo $this->item->author->name; ?></h4>
        <ul class="recent_posts_list">
          <?php foreach($this->authorLatestItems as $key=>$item): //echo'<pre>';var_dump($item);die;?>
            <li>
              <span><a href="#"><img style="width:50px;height:50px;" src="<?php echo $item->imageXSmall; ?>" alt="<?php echo $item->title; ?>" /></a></span>
              <a href="<?php echo $item->link ?>"><?php echo $item->title; ?></a>
               <i><?php echo JHtml::_('date',$item->created, 'F d, Y');?></i> 
            </li>
          <?php endforeach; ?>
        </ul>
        
    </div>

  <?php endif; ?>
</div><!-- end recent posts -->
<!-- Related items by tag -->
<div class="one_half last"> 
<?php if($this->item->params->get('itemRelated') && isset($this->relatedItems)): ?>

    <div class="popular-posts-area">
      <h4><?php echo JText::_("K2_RELATED_ITEMS_BY_TAG"); ?></h4>
        <ul class="recent_posts_list">
        <?php foreach($this->relatedItems as $key=>$item): ?>
          <li>
          <?php if($this->item->params->get('itemRelatedImageSize')): ?>
          <span><a href="<?php echo $item->link ?>"><img style="width:50px;height:50px;" src="<?php echo $item->imageXSmall; ?>" alt="<?php K2HelperUtilities::cleanHtml($item->title); ?>" /></a></span>
          <?php endif; ?>
          
          <?php if($this->item->params->get('itemRelatedTitle', 1)): ?>
          <a  href="<?php echo $item->link ?>"><?php echo $item->title; ?></a>
        <?php endif; ?>

          <?php if($this->item->params->get('itemRelatedIntrotext')): ?>
        <div class="itemRelIntrotext"><?php echo $item->introtext; ?></div>
        <?php endif; ?>

        <?php if($this->item->params->get('itemRelatedFulltext')): ?>
        <div class="itemRelFulltext"><?php echo $item->fulltext; ?></div>
        <?php endif; ?>

             <i><?php echo JHtml::_('date',$item->created, 'F d, Y');?></i> 
          </li>
        <?php endforeach;?>   
        </ul>
        
      </div>
    
  <?php endif; ?>
</div>
<div class="clearfix"></div>
<?php endif;?>

<!-- Item navigation -->
<?php if($this->item->params->get('itemNavigation') && !JRequest::getCmd('print') && (isset($this->item->nextLink) || isset($this->item->previousLink))): ?>
  <h4><?php echo JText::_('K2_MORE_IN_THIS_CATEGORY'); ?></h4>
  <div class="one_half"> 
    
    <?php if(isset($this->item->previousLink)): ?>
    <a class="itemPrevious" href="<?php echo $this->item->previousLink; ?>">
      &laquo; <?php echo $this->item->previousTitle; ?>
    </a>
    <?php endif; ?>
  </div>
  
  <div class="one_half last" style="text-align:right;">
    <?php if(isset($this->item->nextLink)): ?>
    <a class="itemNext" href="<?php echo $this->item->nextLink; ?>">
      <?php echo $this->item->nextTitle; ?> &raquo;
    </a>
    <?php endif; ?>
  </div>

   <div class="clearfix margin_top3"></div>
  <?php endif; ?>

<div class="clearfix divider_dashed9"></div>

<!--comment section-->

  <?php if($this->item->params->get('commentsFormPosition')=='above' && $this->item->params->get('itemComments') && !JRequest::getInt('print') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2' && K2HelperPermissions::canAddComment($this->item->catid)))): ?>
  <!-- Item comments form -->
    <?php echo $this->loadTemplate('comments_form'); ?>

  <?php endif; ?>  

  <!-- Plugins: AfterDisplay -->
  <?php echo $this->item->event->AfterDisplay; ?>

  <!-- K2 Plugins: K2AfterDisplay -->
  <?php echo $this->item->event->K2AfterDisplay; ?>

  <?php if($this->item->params->get('itemComments') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1'))): ?>
  <!-- K2 Plugins: K2CommentsBlock -->
  <?php echo $this->item->event->K2CommentsBlock; ?>
  <?php endif; ?>

  <?php if($this->item->params->get('itemComments') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2')) && empty($this->item->event->K2CommentsBlock)): ?>
    <a name="itemCommentsAnchor" id="itemCommentsAnchor"></a>
    
    <?php if($this->item->numOfComments>0 && $this->item->params->get('itemComments') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2'))): ?>
      <h4><?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?>  <span>(<?php echo $this->item->numOfComments; ?>)</span></h4>
      <div class="mar_top_bottom_lines_small3"></div>
      <?php foreach ($this->item->comments as $key=>$comment): ?>
        <div class="comment_wrap">
          <div class="gravatar"><img alt='<?php echo JFilterOutput::cleanText($comment->userName); ?>' src="<?php echo $comment->userImage; ?>" height="60" width="60"></div>
            <div class="comment_content">
              <div class="comment_meta">

                <div class="comment_author"><?php echo $comment->userName; ?> - <i><?php echo JHTML::_('date', $comment->commentDate, 'F d, Y'); ?></i></div>                   
                          
              </div>
              <div class="comment_text">
                <p><?php echo $comment->commentText; ?></p>
                
              </div>
          </div>
        </div><!-- end section -->
      <?php endforeach;?>


    <div class="itemCommentsPagination">
      <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
    <?php endif;?>
  <?php endif;?>
  
  <?php if($this->item->params->get('commentsFormPosition')=='below' && $this->item->params->get('itemComments') && !JRequest::getInt('print') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2' && K2HelperPermissions::canAddComment($this->item->catid)))): ?>
    <!-- Item comments form -->
    <?php echo $this->loadTemplate('comments_form'); ?>
  <?php endif; ?>

  <?php $user = JFactory::getUser(); if ($this->item->params->get('comments') == '2' && $user->guest): ?>
    <div><a href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>"><?php echo JText::_('K2_LOGIN_TO_POST_COMMENTS'); ?></a></div>
  <?php endif; ?>

