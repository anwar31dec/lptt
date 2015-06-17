<?php

	defined('_JEXEC') or die;
?>

<?php if(count($data) ): ?>
	<div class="left"><i class="fa fa-twitter"></i> <h5 class="white"><?php echo JText::_('MOD_TWITTER_TWITTER_FEEDS_TEXT');?></h5></div>
            
	<div class="right">
		<?php foreach($data as $key=>$value) : ?>

			<?php echo $twitter->prepareTweet($value['text'])?>
			<em><?php echo  $twitter->timeago($value['created_at']).' '.JText::_('MOD_TWITTER_AGO_TEXT');//$twitter->getLC3Date($value['created_at']); ?> <!-- <a href="#">reply</a> .<a href="#">retweet</a> .<a href="#">favorite</a> --></em>
		<?php endforeach;?>
	</div>
<?php endif;?>