<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[carousel]
if(!function_exists('bgbgcontentslider_sc')) {
	$bgcontentsliderArray = array();
	function bgbgcontentslider_sc( $atts, $content="" ){
		global $bgcontentsliderArray;
		
		$params = shortcode_atts(array(
			  'id' => 'bgcontentsliderID'
		 ), $atts);

		$id = uniqid($params['id']);

		
		do_shortcode( $content );

		$html = '<!-- Content slider -->';

		$html .= '<ul id="'.$id.'" class="home-bgc-slider">';
			//bgcontentsliders
		foreach ($bgcontentsliderArray as $key=>$bgcontentslider) {
			$html .='<!-- Slide -->';
			$html .='<li>';
				$html .='<div class="header large bg-img background-<?php echo ($key+1);?>"  style="background-image: url(\''.JURI::root(true).'/'.$bgcontentslider["image"].'\');">';
					$html .='<div class="header-inner">';
						$html .= do_shortcode( $bgcontentslider['content'] );
					$html .='</div>';
				$html .='</div>';
			$html .='</li>';

			}
		$html .= '</ul>';

		$bgcontentsliderArray = array();

		ob_start();

		echo $html; ?>
		
		<script>
			/* ==================== 05. Home background and content slider ==================== */
			jQuery('#<?php echo $id;?>').bxSlider({
				mode: 'fade',
				pager: true,
				controls: true,
				nextText: '<i class="bs-right fa fa-angle-right"></i>',
				prevText: '<i class="bs-left fa fa-angle-left"></i>'
			});
		</script>

<?php
		$data = ob_get_clean();
			
		return $data;
	}
	
	add_shortcode( 'bgcontentslider', 'bgbgcontentslider_sc' );
		
	//bgcontentslider Items
	function bgcontentslider_item_sc( $atts, $content="" ){
		global $bgcontentsliderArray;
		$bgcontentsliderArray[] = array('image'=>$atts['bgimage'],'content'=>$content);
	}

	add_shortcode( 'bgcontentslider_item', 'bgcontentslider_item_sc' );			
}