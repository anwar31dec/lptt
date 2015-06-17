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

if(empty($id)){
	$id = uniqid('tabID');
}


$classes = 'azp_tabs row';
if ($class) {
	$classes .= ' '.$class;
}
$classes = 'class="'.$classes.'"';

?>

<?php 
	$tabTab = array();
	if(count($azuraTabToggleArray)){
		foreach ($azuraTabToggleArray as $key=>$tab){

			$tabTabHtml = '<div '.(($key==0)? 'class="active"':'').'>';
		        $tabTabHtml .= '<a href="#'.$tab['id'].'" role="tab" data-toggle="tab">';
		            $tabTabHtml .= '<div class="col-md-3 col-sm-3 col-xs-6 aboutItem">';
		                $tabTabHtml .= '<div class="squareWrapper">';
		                    $tabTabHtml .= '<div class="squareBorder">';
		                        $tabTabHtml .= '<img class="img-responsive" src="'.JURI::root(true).'/images/about/square.png" alt="square">';
		                        $tabTabHtml .= '<i class="'.$tab['iconclass'].' squareIcon"></i>';
		                    $tabTabHtml .= '</div>';

		                $tabTabHtml .= '</div>';
		                $tabTabHtml .= '<div class="text-center">';
		                    $tabTabHtml .= '<h4 class="montserrat">'.preg_replace('/--([^-]*)--/', '<span class="serifItalic">$1</span>', $tab['title']).'</h4>';
		                $tabTabHtml .= '</div>';
		            $tabTabHtml .= '</div>';
		        $tabTabHtml .= '</a>';
		    $tabTabHtml .= '</div>';

			
			$tabTab[] = $tabTabHtml;
		
		}
	}
?>
<div class="row" id="<?php echo $id;?>" role="tablist" <?php echo $tabtogglestyle;?>>
    <?php echo implode("\n", $tabTab);?>

</div>

<div class="row">
	<div class="tab-content">
		<?php echo do_shortcode($content); ?>
	</div>
</div>

<script type="text/javascript">
	// jQuery(document).ready(function($){
	// 	$('#<?php echo $id;?> div').click(function (e) {
	//         e.preventDefault()
	//         $(this).tab('show')
	//     })

	//     $('#<?php echo $id;?> div').on('click', function(){
	//         $('#<?php echo $id;?> div.active').removeClass('active');
	//         $(this).addClass('active');
	//     });
	// });
</script>

<?php
	global $azuraTabItem;
 	$azuraTabItem = 0;
?>

