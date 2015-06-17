<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[carousel]
if(!function_exists('bgslider_sc')) {
	$bgsliderArray = array();
	function bgslider_sc( $atts, $content="" ){
		global $bgsliderArray;
		
		$params = shortcode_atts(array(
			  'id' => 'bgsliderID'
		 ), $atts);

		$id = uniqid($params['id']);

		
		do_shortcode( $content );

		$html = '<!-- Background slider -->';
		$html .= '<ul id="'.$id.'" class="home-bg-slider">';
			//bgsliders
			foreach ($bgsliderArray as $key=>$bgslider) {
				$html .='<li><div class="header large bg-img background-'.($key+1).'" style="background-image: url(\''.JURI::root(true).'/'.$bgslider["image"].'\');"></div></li>';
			}
		$html .= '</ul>';

		$bgsliderArray = array();

		ob_start();

		echo $html; ?>
		
		<script>
			/* ==================== 04. Home background slider ==================== */
			jQuery('#<?php echo $id;?>').bxSlider({
				mode: 'fade',
				auto: true,
				speed: 1000,
				pager: false,
				controls: false,
				nextText: '<i class="bs-right fa fa-angle-right"></i>',
				prevText: '<i class="bs-left fa fa-angle-left"></i>'
			});
		</script>

<?php
		$data = ob_get_clean();
			
		return $data;
	}
	
	add_shortcode( 'bgslider', 'bgslider_sc' );
		
	//bgslider Items
	function bgslider_item_sc( $atts, $content="" ){
		global $bgsliderArray;
		$bgsliderArray[] = array('image'=>$atts['image'],'content'=>$content);
	}

	add_shortcode( 'bgslider_item', 'bgslider_item_sc' );			
}