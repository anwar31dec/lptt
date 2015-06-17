<?php

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

/**
* Make string to slug
* 
* @param mixed $text
* @return string
*/

//[Quote]
if(!function_exists('quote_sc')) {
	$quoteArray = array();
	function quote_sc( $atts, $content="" ){
		global $quoteArray;
		
		$params = shortcode_atts(array(
			  'id' => 'quote-slider',
		 ), $atts);

		$params['id'] = uniqid($params['id']);
		
		do_shortcode( $content );

		$html = '<!-- Quote slider -->';
		$html .= '<div class="row">';
			$html .= '<div class="twelve col text-center qs-wrap">';
				$html .= '<div class="bg-white bg-padding">';
					$html .= '<div class="row">';
						$html .= '<div class="eight col offset-by-two">';

						if(count($quoteArray)){

							$html .= '<ul id="' . $params['id'] . '"  class="quote-slider">';

								
									//quotes
									foreach ($quoteArray as $key=>$quote) {

										$html .= '<!-- Quote -->';
										$html .= '<li>';
											$html .= do_shortcode( $quote['content'] );
											$html .= '<p>'.$quote['name'].' - <em>'.$quote['job'].' '.JText::_('TPL_MOMENTUM_AT_TEXT').' '.$quote['company'].'</em></p>';
										$html .= '</li>';
										
									}
								
								

							$html .= '</ul>';
						}else{
							$html .= do_shortcode( $content );
						}
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';

		if(!count($quoteArray)){
			return $html;
		}
	
		$quoteArray = array();	

		ob_start();

		echo $html;  ?>
		
		<script>
			/* ==================== 06. Quote slider ==================== */
			jQuery("#<?php echo $params['id'];?>").bxSlider({
				mode: 'horizontal',
				controls: false,
				adaptiveHeight: true
			});
		</script>
<?php
		$data = ob_get_clean();

		return $data;
	}
	
	add_shortcode( 'quote', 'quote_sc' );
		
	//Quote Items
	function quote_item_sc( $atts, $content="" ){
		global $quoteArray;
		$quoteArray[] = array('name'=>$atts['name'], 'job'=>$atts['job'], 'company'=>$atts['company'], 'content'=>$content);
	}

	add_shortcode( 'quote_item', 'quote_item_sc' );			
}