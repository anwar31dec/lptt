<?php
defined('_JEXEC') or die;

?>

<div id="flickr_badge_wrapper" class="cththemes-flickr">
            
</div>


<?php
$doc = JFactory::getDocument();
//$doc->addScript(JURI::base(true) . '/modules/mod_cththemesflickr/assets/js/jflickrfeed.min.js');
$js = array();
$js[] = 'jQuery(document).ready(function($){';
	$js[] = '$(\'#cththemes-flickr\').jflickrfeed({';
		$js[] = 'limit: '.$limit.',';
		$js[] = 'qstrings: {';
			$js[] = 'id: \''.$id.'\'';
		$js[] = '},';
		$js[] = 'useTemplate : false,';
        $js[] = 'itemCallback: function(item){';
            $js[] = "$(this).append('<li><a href='+item.image_b+'><img alt=\"'+item.title+'\" width=\"37px\" height=\"37px\" title=\"'+item.title+'\" src='+item.image_s+'></a></li>');";
        $js[] = '}';
		//$js[] = 'itemTemplate: \'<li><a href="{{image_b}}"><img src="{{image_s}}" alt="{{title}}" /></a></li>\'';
	$js[] = '});';
$js[] = '});';
//$doc->addScriptDeclaration(implode("\n", $js));

?>
<script src="<?php echo JURI::base(true) . '/modules/mod_cththemesflickr/assets/js/jflickrfeed.min.js';?>" type="text/javascript"></script>
<script>
jQuery(document).ready(function($){
	$('#flickr_badge_wrapper').jflickrfeed({
		limit: <?php echo $limit;?>,
		qstrings: {
			id: '<?php echo $id;?>'
		},
		useTemplate : false,
		itemCallback: function(item){
			$(this).append('<a href='+item.image_b+'><img alt="'+item.title+'" width="70px" height="70px" title="'+item.title+'" src='+item.image_s+'></a>');
		}
	});
});

</script>