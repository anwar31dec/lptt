<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[carousel]
if(!function_exists('contentslider_sc')) {
	$contentsliderArray = array();
	function contentslider_sc( $atts, $content="" ){
		global $contentsliderArray;
		
		$params = shortcode_atts(array(
			  'id' => 'contentsliderID'
		 ), $atts);

		$id = uniqid($params['id']);

		
		do_shortcode( $content );

		$html = '<!-- Content slider -->';

		$html .= '<ul id="'.$id.'" class="home-c-slider">';
			//contentsliders
		foreach ($contentsliderArray as $contentslider) {
				$html .='<!-- Slide -->';
				$html .='<li class="header large">';
					$html .='<div class="header-inner">';
						$html .= do_shortcode( $contentslider['content'] );
					$html .='</div>';
				$html .='</li>';

			}
		$html .= '</ul>';

		$contentsliderArray = array();

		ob_start();

		echo $html; ?>
		
		<script>
			/* ==================== 03. Home content slider ==================== */
			jQuery('#<?php echo $id;?>').bxSlider({
				mode: 'horizontal',
				pager: false,
				controls: true,
				nextText: '<i class="bs-right fa fa-angle-right"></i>',
				prevText: '<i class="bs-left fa fa-angle-left"></i>'
			});
		</script>

<?php
		$data = ob_get_clean();
			
		return $data;
	}
	
	add_shortcode( 'contentslider', 'contentslider_sc' );
		
	//contentslider Items
	function contentslider_item_sc( $atts, $content="" ){
		global $contentsliderArray;
		$contentsliderArray[] = array('content'=>$content);
	}

	add_shortcode( 'contentslider_item', 'contentslider_item_sc' );			
}